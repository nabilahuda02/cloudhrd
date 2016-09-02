angular.module('app')
    .factory('collection',
        function($q, $http) {

            function Collection(path) {
                this.path = path;
            }

            Collection.prototype.fetch = function(params) {
                return $http.get('/wall/feeds').then(function(response) {
                    return response.data;
                });
            };

            Collection.prototype.get = function(id) {
                return $http.get('/wall/feed/' + id).then(function(response) {
                    return response.data;
                });
            };

            Collection.prototype.create = function(data) {
                data.type = 'text';
                return $http.post('/wall/feed', data).then(function(response) {
                    return response.data;
                });
            };

            Collection.prototype.update = function(id, data) {
                var self = this;
                return this.get(id)
                    .then(function(_data) {
                        _data = angular.merge(_data, data);
                        return $http.put('/wall/feed/' + _data.id, _data);
                    })
                    .then(function(response) {
                        return response.data;
                    });
            };

            Collection.prototype.delete = function(id) {
                return $http.delete('/wall/feed/' + id);
            };

            var cached = {};

            return function(collection) {
                var collectionName = (window.location.hostname + '-' + collection).replace(/[\W_]+/g, '_');
                if (!cached[collectionName]) {
                    cached[collectionName] = new Collection(collectionName);
                }
                return cached[collectionName];
            }
        }
    )

    .factory('WallFeeds', function(collection) {
        return collection('wall-feeds');
    })

    .filter('trustedHtml', ['$sce',
        function($sce) {
            return function(text) {
                return $sce.trustAsHtml(text);
            };
        }
    ])

    .factory('Session', ['$interval',
        function($interval) {
            var last = localStorage.getItem('session');
            var session = {};
            return session;
        }
    ])

    .run(['$rootScope', 'Session',
        function($rootScope, Session) {
            $rootScope.Session = Session;
        }
    ])


    .directive('enterSubmit', function() {
        return {
            restrict: 'A',
            link: function(scope, elem, attrs) {
                elem.bind('keydown', function(event) {
                    var code = event.keyCode || event.which;
                    if (code === 13) {
                        if (event.shiftKey) {
                            event.preventDefault();
                            scope.$apply(attrs.enterSubmit);
                        }
                    }
                });
            }
        }
    })

    .factory('User', function() {
        user.params = function() {
            return {
                name: user.profile.first_name + ' ' + user.profile.last_name,
                id: user.id,
                avatar: user.profile.user_image
            }
        }
        return user;
    })

    .controller('WallController',
        function(WallFeeds, $scope, User, Session, $timeout) {

            Pusher.logToConsole = false;

            var pusher = new Pusher('8ae2990cab38227cd212', {
                cluster: 'ap1',
                encrypted: true
            });

            var channel = pusher.subscribe(window.location.host);
            channel.bind('wall', function(payload) {
                switch (payload.action) {
                    case 'created':
                        $scope.feeds.unshift(payload.data);
                        break;
                    case 'updated':
                        var existing = $scope.feeds.filter(function(feed) {
                            return feed.id == payload.data.id;
                        }).pop();
                        if (existing) {
                            $scope.feeds.splice($scope.feeds.indexOf(existing), 1, payload.data);
                        } else {
                            $scope.feeds.unshift(payload.data);
                        }
                        break;
                    case 'deleted':
                        var existing = $scope.feeds.filter(function(feed) {
                            return feed.id == payload.id;
                        }).pop();
                        if (existing) {
                            $scope.feeds.splice($scope.feeds.indexOf(existing), 1);
                        }
                        break;
                }
                $timeout(function() {
                    $scope.$digest();
                })
                console.log(payload);
            });

            channel.bind('wall-comments', function(payload) {
                console.log(payload);
            });

            $scope.feeds = [];
            $scope.length = 10;
            WallFeeds.fetch($scope.length).then(function(datas) {
                datas.forEach(function(data) {
                    $scope.feeds.push(data);
                });
            });
            $scope.user = User;
            $scope.createFeed = function() {
                WallFeeds.create({
                    user: User.params(),
                    content: $scope.comment,
                    replies: [],
                }).then(function(data) {
                    $scope.comment = '';
                });
            }

            $scope.doEditFeed = function(feed, comment) {
                WallFeeds.update(feed.id, {
                    content: comment,
                });
                Session.editComment = null;
            }

            $scope.deleteFeed = function(feed) {
                if (confirm('Are you sure you want to delete this?')) {
                    WallFeeds.delete(feed.id);
                }
            }

            $scope.createReply = function(feed, comment) {
                var replies = feed.replies;
                replies.push({
                    user: User.params(),
                    comment: comment,
                    createdAt: Date.now(),
                    updatedAt: Date.now(),
                })
                feed.put({
                    replies: replies,
                    updatedAt: Date.now()
                })
                $scope.reply = '';
            }

            $scope.deleteReply = function(feed, reply) {
                var index = feed.replies.indexOf(reply);
                if (index > -1 && confirm('Are you sure you want to delete this?')) {
                    feed.replies.splice(index, 1);
                    feed.put({
                        replies: feed.replies,
                        updatedAt: Date.now()
                    });
                }
            }

            $scope.doEditReply = function(feed, reply, comment) {
                var index = feed.replies.indexOf(reply);
                if (index > -1) {
                    reply.comment = comment;
                    feed.put({
                        replies: feed.replies,
                        updatedAt: Date.now()
                    })
                }
                Session.editReply = null;
            }
        }
    )

    .controller('SidebarController', ['$scope', '$http',
        function($scope, $http) {
            $scope.entitlements = {};
            $http.get('/ajax/entitlement-balances')
                .then(function(response) {
                    $scope.entitlements = response.data;
                })
        }
    ]);