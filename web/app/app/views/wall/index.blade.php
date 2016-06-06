@extends('layouts.module')

@section('content')
<script>
    var user = {{json_encode($user)}};
</script>
<section id="wall" ng-app="wall">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <h2>Public Wall</h2>
            </div>
        </div>
        <div class="row" ng-cloak>
            <div class="col-md-9 col-xs-12" ng-controller="WallController">
                <div class="wall-poster">
                    <textarea ng-model="comment" enter-submit="createFeed()" name="" id="" cols="" rows="4" placeholder="Write something then shift + enter to submit"></textarea>
                </div>
                <div class="wall-post" ng-repeat="feed in feeds | orderBy: '-updatedAt' track by $index">
                    <div class="row">
                        <div class="col-md-8 col-xs-6">
                            <div class="wall-post-profile-pic">
                                <img width="33px" ng-src="@{{feed.user.avatar}}" alt="">
                                <p>
                                    <span ng-bind="feed.user.name"></span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-6">
                            <div class="wall-post-timestamp">
                                <i class="fa fa-pencil" ng-show="feed.user.id == user.id" ng-click="Session.editComment = feed.id"></i>
                                <i class="fa fa-trash" ng-show="feed.user.id == user.id || user.is_admin" ng-click="deleteFeed(feed)"></i>
                                <i class="fa fa-calendar-o"></i> <small am-time-ago="feed.createdAt"></small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="wall-post-body">
                                <p ng-hide="Session.editComment == feed.id" ng-bind-html="feed.comment | nl2br | trustedHtml"></p>
                                <textarea class="editingComment" ng-show="Session.editComment == feed.id" ng-focus="Session.editComment == feed.id" ng-model="feed.comment" enter-submit="doEditComment(feed, feed.comment)" name="" id="" cols="" rows="2" placeholder="Shift + Enter to Submit"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" ng-init="limit=-2">
                            <div class="wall-post-footer" ng-hide="limit == undefined || feed.replies.length <= 2">
                                <div class="wall-post-recent-btn wall">
                                    <button class="btn btn-link" ng-click="limit = undefined">Older</button>
                                </div>
                            </div>
                            <div class="wall-post-footer" ng-repeat="reply in feed.replies | limitTo:limit track by $index">
                                <div class="wall-post-reply-timeline">
                                    <div class="row">
                                        <div class="col-md-8 col-xs-6">
                                            <div class="wall-post-profile-pic">
                                                <img width="33px" ng-src="@{{reply.user.avatar}}" alt="">
                                                <p>
                                                    <span ng-bind="reply.user.name"></span>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-xs-6">
                                            <div class="wall-post-timestamp">
                                                <i class="fa fa-pencil" ng-show="reply.user.id == user.id" ng-click="Session.editReply = reply"></i>
                                                <i class="fa fa-trash" ng-show="reply.user.id == user.id || user.is_admin" ng-click="deleteReply(feed, reply)"></i>
                                                <i class="fa fa-calendar-o"></i> <small am-time-ago="reply.createdAt"></small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="wall-post-body">
                                        <p ng-hide="Session.editReply == reply" ng-bind-html="reply.comment | nl2br | trustedHtml"></p>
                                        <textarea class="editingComment" ng-show="Session.editReply == reply" ng-focus="Session.editReply == reply" ng-model="reply.comment" enter-submit="doEditReply(feed, reply, reply.comment)" name="" id="" cols="" rows="2" placeholder="Shift + Enter to Submit"></textarea>

                                    </div>
                                </div>
                            </div>
                            <div ng-hide="Session.editing == feed.id" class="wall-post-footer wall-post-reply-btn">
                                <button ng-click="Session.editing = feed.id" class="btn btn-link"><i class="fa fa-mail-reply"></i>Reply</button>
                            </div>
                            <div class="wall-post-footer wall-post-holder" ng-show="Session.editing == feed.id">
                                <div class="row">
                                    <div class="col-sm-1 hidden-xs wall-post-profile-pic">
                                        <img width="33px" ng-src="@{{user.params().avatar}}" alt="">
                                    </div>
                                    <div class="col-sm-11 col-xs-12 wall-post-holder-poster">
                                        <textarea ng-focus="Session.editing == feed.id" ng-model="$parent.reply" enter-submit="createReply(feed, $parent.reply)" name="" id="" cols="" rows="2" placeholder="Shift + Enter to Submit"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3" ng-controller="SidebarController">
                <div class="row">
                    <div class="col-md-12 hidden">
                        <h5>Task Notifications</h5>
                        <div class="wall-task task-gray">
                            <div class="wall-task-img">
                                <img src="/assets/images/sidebar-profile.png" alt="">
                            </div>
                            <div class="wall-task-content">
                                <p>Web Testing</p>
                                <small>Development</small>
                            </div>
                        </div>
                        <div class="wall-task task-orange">
                            <div class="wall-task-img">
                                <img src="/assets/images/sidebar-profile.png" alt="">
                            </div>
                            <div class="wall-task-content">
                                <p>Web Testing</p>
                                <small>Development</small>
                            </div>
                        </div>
                        <div class="wall-task task-red">
                            <div class="wall-task-img">
                                <img src="/assets/images/sidebar-profile.png" alt="">
                            </div>
                            <div class="wall-task-content">
                                <p>Web Testing</p>
                                <small>Development</small>
                            </div>
                        </div>
                        <div class="wall-task task-green">
                            <div class="wall-task-img">
                                <img src="/assets/images/sidebar-profile.png" alt="">
                            </div>
                            <div class="wall-task-content">
                                <p>Web Testing</p>
                                <small>Development</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <h5>Leave Balance</h5>
                        <div ng-repeat="(name, balance) in entitlements.leave" class="wall-leave">
                            <div class="wall-leave-icon">
                                <span class="wall-leave-inner-date">@{{balance}}</span>
                            </div>
                            <div class="wall-leave-category">
                                <p>@{{name}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <h5>Medical Claims Balance</h5>
                        <div ng-repeat="(name, balance) in entitlements.medical" class="wall-leave">
                            <div class="wall-leave-icon">
                                <span class="wall-leave-inner-date">@{{balance}}</span>
                            </div>
                            <div class="wall-leave-category">
                                <p>@{{name}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@stop
