// Requires GunDB, asyncjs

angular.module('angularGunDB', [])

    .value('GunDBEndpoint', '')

    .factory('GunHasManyRelationship', function($injector, $q){
        var Relationship = function(model, RepositoryName, key) {
            this.model = model;
            this.repository = $injector.get(RepositoryName);
            this.key = key;
        }

        Relationship.prototype = {
            get: function() {
                console.log(this.key, this.model.id)
                return this.repository.where(this.key, this.model.id).get();
            },
            set: function() {
                var deferred = $q.defer();
                return deferred.promise;
            },
        }
        return Relationship;
    })

    .factory('GunBelongsToRelationship', function($injector, $q){
        var Relationship = function(model, RepositoryName, key) {
            this.model = model;
            this.repository = $injector.get(RepositoryName);
            this.key = key;
        }

        Relationship.prototype = {
            get: function() {
                return this.repository.find(this.model[this.key]);
            },
            set: function(related) {
                var deferred = $q.defer();
                var relationData = {};
                relationData[this.key] = related.id;
                this.model.set(relationData).then(deferred.resolve, deferred.reject);
                return deferred.promise;
            },
        }
        return Relationship;
    })

    .factory('GunHasOneRelationship', function($injector, $q){
        var Relationship = function(model, RepositoryName, key) {
            this.model = model;
            this.repository = $injector.get(RepositoryName);
            this.key = key;
        }

        Relationship.prototype = {
            get: function() {
                return this.repository.where(this.key, this.model.id).first();
            },
            set: function(related) {
                var deferred = $q.defer();
                var relationData = {};
                relationData[this.key] = related.id;
                this.model.set(relationData).then(deferred.resolve, deferred.reject);
                return deferred.promise;
            },
        }
        return Relationship;
    })

    .factory('GunModel', function($q, $timeout, GunHasManyRelationship, GunBelongsToRelationship, GunHasOneRelationship) {
        var GunModel = function(parent, data) {
            var that = this;

            // set defaults
            if(parent.configs.defaults) {
                angular.forEach(parent.configs.defaults, function(value, key) {
                    if (parent.configs.casts[key]) {
                        value = parent.configs.casts[key].get(value);
                    }
                    that[key] = value;
                });
            }

            angular.forEach(data, function(value, key) {
                if (parent.configs.casts[key]) {
                    value = parent.configs.casts[key].get(value);
                }
                that[key] = value;
            });

            // so that _parent is not overwritten from top function
            this._parent = parent;

            // watch changes and resolve deferreds
            parent.context.path(this.id).on(function(params) {
                angular.forEach(params, function(value, key) {
                    if (key.charAt(0) == '_')
                        return;
                    if (parent.configs.casts[key]) {
                        value = parent.configs.casts[key].get(value);
                    }
                    try {
                        if (JSON.stringify(that[key]) != JSON.stringify(value)) {
                            that[key] = value;
                        }
                    } catch(e) {
                        that[key] = value;
                    }
                });
            });

            // boot relationships
            this._relationships = {};
            if(parent.configs.relationships) {
                angular.forEach(parent.configs.relationships, function(fn, key) {
                    that._relationships[key] = fn.call(that);
                    that[key] = {};
                    that._relationships[key].get().then(function(data){
                        that[key] = data;
                    });
                });
            }
        };

        GunModel.prototype = {
            belongsTo: function(RepositoryName, key) {
                return new GunBelongsToRelationship(this, RepositoryName, key);
            },
            hasMany: function(RepositoryName, key) {
                return new GunHasManyRelationship(this, RepositoryName, key);
            },
            hasOne: function(RepositoryName, key) {
                return new GunHasOneRelationship(this, RepositoryName, key);
            },
            touch: function() {
                return this.set({});
            },
            set: function(data) {
                var data = data || {};
                var deferred = $q.defer();
                var that = this;
                data.updatedAt = new Date();

                data = Object.keys(data).reduce(function(obj, key) {
                    if (that._parent.configs.casts[key]) {
                        obj[key] = that._parent.configs.casts[key].set(data[key]);
                    } else {
                        obj[key] = data[key];
                    }
                    return obj;
                }, {});

                this._parent.context.path(this.id).put(data, function() {
                    deferred.resolve(that._parent.find(that.id));
                });
                return deferred.promise;
            },
            remove: function() {
                var deferred = $q.defer();
                var that = this;
                this._parent.context.path(this.id).put(null, function() {
                    deferred.resolve();
                });
                return deferred.promise;
            },
            bindToScope: function($scope, key) {
                var that = this;
                $scope[key] = this;

                angular.forEach(this, function(val, _key) {
                    if (_key.charAt(0) == '_')
                        return;

                    var ignores = [
                        'createdAt',
                        'updatedAt'
                    ].concat(Object.keys(that._relationships));

                    if (ignores.indexOf(_key) > -1)
                        return;
                    $scope.$watch(key + '.' + _key, function(newData, oldData) {
                        try {
                            if (JSON.stringify(oldData) !== JSON.stringify(newData)) {
                                var changed = {};
                                changed[_key] = newData;
                                that.set(changed);
                            }
                        } catch (e) {
                            console.error(e)
                        }
                    });
                })
            }
        }

        return GunModel;
    })

    .factory('GunRepository', function($q, GunModel, GunDBEndpoint, $timeout) {

        var instance = Gun(GunDBEndpoint);

        var GunRepository = function(path, configs) {
            var that = this;
            this.configs = angular.merge({
                casts: {
                    createdAt: {
                        set: function(date) {
                            return date.toJSON();
                        },
                        get: function(date) {
                            return new Date(date);
                        }
                    },
                    updatedAt: {
                        set: function(date) {
                            return date.toJSON();
                        },
                        get: function(date) {
                            return new Date(date);
                        }
                    }
                }
            }, configs);

            this.context = instance.get(path);
            this.wheres = [];
            this.models = {};

            // if(this.configs.scopes) {
            //     angular.forEach(this.configs.scopes, function(fn, key) {
            //         that[key] = fn;
            //     })
            // }
        }

        GunRepository.prototype = {
            bindToScope: function($scope, key, config) {
                var that = this;
                var deferred = $q.defer();

                this.context.on(function(data) {
                    if(config.filter) {
                        that.where('*', 'fn', config.filter);
                    }
                    if(config.sort) {
                        if(angular.isFunction(config.sort)) {
                            that.sort(config.sort);
                        } else {
                            that.sort(config.key, config.desc);
                        }
                    }
                    that.get().then(function(data) {
                        $scope[key] = data;
                        $timeout(function(){
                            $scope.$apply();
                        })
                    })
                });
                return deferred.promise;
            },
            first: function() {
                var deferred = $q.defer();
                var that = this;

                this.get().then(function(models) {
                    deferred.resolve(models.shift() || null);
                }, deferred.reject);
                return deferred.promise;
            },
            last: function() {
                var deferred = $q.defer();
                var that = this;

                this.get().then(function(models) {
                    deferred.resolve(models.pop());
                }, deferred.reject);
                return deferred.promise;
            },
            find: function(key) {
                var deferred = $q.defer();
                var that = this;
                this.context.not(function() {
                    deferred.reject();
                })
                this.context.path(key).not(function(data) {
                    deferred.reject();
                })
                this.context.path(key).val(function(data) {
                    if (!data) {
                        return deferred.reject();
                    }
                    if (!that.models[key]) {
                        that.models[key] = new GunModel(that, data);
                    }
                    deferred.resolve(that.models[key]);
                })
                return deferred.promise;
            },
            where: function(key, oper, value) {
                if (!value) {
                    value = oper;
                    oper = '=';
                }
                switch (oper) {
                    case '<':
                    case '>':
                    case '=':
                    case 'fn':
                    case 're':
                        this.wheres.push({
                            key: key,
                            oper: oper,
                            value: value,
                        });
                        break;
                    default:
                        throw 'Invalid operation. Must be one of: <, >, =, r, fn';
                }
                return this;
            },
            sort: function(sortKey, sortDesc) {
                this.sortKey = sortKey;
                this.sortDesc = sortDesc || false;
                return this;
            },
            take: function(takeItems, offset) {
                this.takeItems = takeItems;
                return this;
            },
            skip: function(skipItems, offset) {
                this.skipItems = skipItems;
                return this;
            },
            get: function() {
                var deferred = $q.defer();
                var that = this;
                this.context.not(function() {
                    deferred.resolve([]);
                })
                this.context.val(function(objects) {
                    async.reduce(Object.keys(objects), [], function(models, key, cb) {
                        if (key == '_')
                            return cb(null, models);

                        that.find(key).then(function(model) {
                            models.push(model);
                            cb(null, models);
                        }, function() {
                            cb(null, models);
                        });
                    }, function getAsyncCallback(err, models) {
                        if (err)
                            return deferred.reject(err);

                        if (that.wheres.length) {
                            models = that.wheres.reduce(function(models, where) {
                                switch (where.oper) {
                                    case '<':
                                        models = models.filter(function(model) {
                                            return model[where.key] < where.value;
                                        });
                                        break;
                                    case '>':
                                        models = models.filter(function(model) {
                                            return model[where.key] > where.value;
                                        });
                                        break;
                                    case '=':
                                        models = models.filter(function(model) {
                                            return model[where.key] == where.value;
                                        });
                                        break;
                                    case 're':
                                        models = models.filter(function(model) {
                                            return where.value.test(model[where.key]);
                                        });
                                        break;
                                    case 'fn':
                                        models = models.filter(where.value);
                                        break;
                                }
                                return models;
                            }, models);
                            that.wheres = [];
                        }

                        if (that.sortKey) {
                            if(angular.isFunction(that.sortKey)) {
                                models = models.sort(that.sortKey);
                            } else {
                                models = models.sort(function(a, b) {
                                    if (a[that.sortKey] == b[that.sortKey])
                                        return 0;
                                    if (that.sortDesc) {
                                        return (a[that.sortKey] > b[that.sortKey]) ? -1 : 1;
                                    } else {
                                        return (a[that.sortKey] < b[that.sortKey]) ? -1 : 1;
                                    }
                                });
                            }
                        }

                        if (that.skipItems) {
                            models.splice(0, that.skipItems);
                            that.skipItems = null;
                        }

                        if (that.takeItems) {
                            models = models.splice(0, that.takeItems);
                            that.takeItems = null;
                        }

                        deferred.resolve(models || []);
                    });
                })
                return deferred.promise;
            },
            remove: function() {
                var d = $q.defer();
                this.get().then(function(models){
                    async.forEach(models, function(model, callback){
                        model.remove().then(callback);
                    }, function(){
                        d.resolve();
                    });
                });
                return d.promise;
            },
            upsert: function(data) {
                var that = this;
                var deferred = $q.defer();
                data.id = data.id || UUIDjs.create().toString();
                this.find(data.id).then(function(model) {
                    // update if found
                    if (!model.createdAt)
                        data.createdAt = new Date();

                    model.set(data)
                        .then(function(model) {
                            deferred.resolve(model);
                        }, function() {
                            deferred.reject(err);
                        });

                }, function() {
                    var model = new GunModel(that, {
                        id: data.id
                    });
                    data.updatedAt = new Date();
                    data.createdAt = new Date();

                    model.set(data)
                        .then(function(model) {
                            that.models[data.id] = model;
                            deferred.resolve(model);
                        }, function(err) {
                            deferred.reject(err);
                        });
                });
                return deferred.promise;
            }
        }

        return GunRepository;
    });