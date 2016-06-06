//=require ../packages/uuid-js/lib/uuid.js
//=require ../packages/async/dist/async.min.js
//=require ../packages/moment/min/moment.min.js
//=require ../packages/angular/angular.min.js
//=require ../packages/angular-moment/angular-moment.min.js
//=require ../packages/angular-nl2br/angular-nl2br.min.js
//=require ../packages/socket.io-client/socket.io.js
//=require ../packages/castmydata-jsclient/dist/castmydata.min.js
//=require ../packages/castmydata-jsclient/dist/ng-castmydata.min.js

var app = angular.module('wall', ['angularMoment', 'nl2br', 'NgCastMyData'])


    .value('CastMyDataServer', 'https://www.castmydata.com/')


    .filter('trustedHtml', ['$sce', function($sce){
        return function(text) {
            return $sce.trustAsHtml(text);
        };
    }])

    .factory('Session', ['$interval', function($interval) {
        var last = localStorage.getItem('session');
        var session = {};
        return session;
    }])

    .run(['$rootScope', 'Session', function($rootScope, Session){
        $rootScope.Session = Session;
    }])


    .directive('enterSubmit', function () {
        return {
            restrict: 'A',
            link: function (scope, elem, attrs) {
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

    .controller('WallController', ['NgCastMyDataEndpoint', '$scope', 'User', 'Session', function(NgCastMyDataEndpoint, $scope, User, Session){

        var feeds = NgCastMyDataEndpoint(window.location.host + '-wall-feeds').bindToScope($scope, 'feeds');

        $scope.user = User;
        $scope.createFeed = function() {
            feeds.post({
                user: User.params(),
                comment: $scope.comment,
                replies: [],
                createdAt: Date.now(),
                updatedAt: Date.now(),
            });
            $scope.comment = '';
        }

        $scope.deleteFeed = function(feed) {
            if(confirm('Are you sure you want to delete this?')) {
                feed.delete()
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
            if(index > -1 && confirm('Are you sure you want to delete this?')) {
                feed.replies.splice(index, 1);
                feed.put({
                    replies: feed.replies,
                    updatedAt: Date.now()
                })
            }
        }

        $scope.doEditComment = function(feed, comment) {
            feed.put({
                comment: comment,
                updatedAt: Date.now()
            });
            Session.editComment = null;
        }

        $scope.doEditReply = function(feed, reply, comment) {
            var index = feed.replies.indexOf(reply);
            console.log(comment)
            if(index > -1) {
                reply.comment = comment;
                feed.put({
                    replies: feed.replies,
                    updatedAt: Date.now()
                })
            }
            Session.editReply = null;
        }
    }])

    .controller('SidebarController', ['$scope', '$http', function($scope, $http){
        $scope.entitlements = {};
        $http.get('/ajax/entitlement-balances')
            .then(function(response){
                $scope.entitlements = response.data;
            })
    }])

