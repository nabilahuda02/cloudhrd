@extends('layouts.module')
@section('content')
<section id="wall">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <h2>Public Wall</h2>
            </div>
        </div>
        <div class="row">
            <!-- wall begin -->
            <div class="col-md-9 col-xs-12">
                <div class="wall-poster">
                    <textarea name="" id="" cols="" rows="6" placeholder="Write something then shift + enter to submit"></textarea>
                </div>
                <div class="wall-post">
                    <div class="row">
                        <div class="col-md-8 col-xs-6">
                            <div class="wall-post-profile-pic">
                                <img src="/assets/images/profile-post.png" alt=""><p>John Doe</p>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-6">
                            <div class="wall-post-timestamp">
                                <i class="fa fa-calendar-o"></i> <small>6 hours ago</small>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="wall-post-body">
                                <p>Few would argue that, despite the advancements of feminism over the past three decades, women still face a double standard when it comes to their behavior.</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="wall-post-footer wall-post-reply-btn">
                                <a href="#"><i class="fa fa-mail-reply"></i>Reply</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wall-post">
                    <div class="row">
                        <div class="col-md-8 col-xs-6">
                            <div class="wall-post-profile-pic">
                                <img src="/assets/images/profile-post.png" alt=""><p>John Doe</p>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-6">
                            <div class="wall-post-timestamp">
                                <i class="fa fa-calendar-o"></i> <small>6 hours ago</small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="wall-post-body">
                                <p>Few would argue that, despite the advancements of feminism over the past three decades, women still face a double standard when it comes to their behavior.</p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="wall-post-footer">
                                <div class="wall-post-recent-btn wall">
                                    <a href="#">More recents</a>
                                </div>
                            </div>
                            <div class="wall-post-footer">
                                <div class="wall-post-reply-timeline">
                                    <div class="wall-post-profile-pic">
                                        <img src="/assets/images/profile-post.png" alt=""><p>Lois Herrera</p>
                                    </div>
                                    <div class="wall-post-body">
                                        <p>Few would argue that, despite the advancements of feminism over the past three decades, women still face a double standard when it comes to their behavior.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="wall-post-footer">
                                <div class="wall-post-reply-timeline">
                                    <div class="wall-post-profile-pic">
                                        <img src="/assets/images/profile-post.png" alt=""><p>Lois Herrera</p>
                                    </div>
                                    <div class="wall-post-body">
                                        <p>Few would argue that, despite the advancements of feminism over the past three decades, women still face a double standard when it comes to their behavior.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="wall-post-footer wall-post-holder">
                                <div class="row">
                                    <div class="col-sm-1 hidden-xs wall-post-profile-pic">
                                        <img src="/assets/images/profile-post.png" alt="">
                                    </div>
                                    <div class="col-sm-11 col-xs-12 wall-post-holder-poster">
                                        <textarea name="" id="" cols="" rows="2" placeholder="Shift + Enter to Submit"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- wall end -->
            <!-- sidebar begin -->
            <div class="col-md-3">
                <div class="row">
                    <!-- task notification begin -->
                    <div class="col-md-12">
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
                    <!-- task notification end -->
                    <!-- leave balance begin -->
                    <div class="col-md-12">
                        <h5>Leave Balance</h5>
                        <div class="wall-leave">
                            <div class="wall-leave-icon">
                                <i class="fa fa-calendar-o"></i>
                                <span class="wall-leave-inner-date">05</span>
                            </div>
                            <div class="wall-leave-category">
                                <p>Medical Leave</p>
                            </div>
                        </div>
                        <div class="wall-leave">
                            <div class="wall-leave-icon">
                                <i class="fa fa-calendar-o"></i>
                                <span class="wall-leave-inner-date">10</span>
                            </div>
                            <div class="wall-leave-category">
                                <p>Annual Leave</p>
                            </div>
                        </div>
                        <div class="wall-leave">
                            <div class="wall-leave-icon">
                                <i class="fa fa-calendar-o"></i>
                                <span class="wall-leave-inner-date">20</span>
                            </div>
                            <div class="wall-leave-category">
                                <p>Compasionate Leave</p>
                            </div>
                        </div>
                        <div class="wall-leave">
                            <div class="wall-leave-icon">
                                <i class="fa fa-calendar-o"></i>
                                <span class="wall-leave-inner-date">26</span>
                            </div>
                            <div class="wall-leave-category">
                                <p>Emergency Leave</p>
                            </div>
                        </div>
                    </div>
                    <!-- leave balance end -->
                </div>
            <!-- sidebar end -->
        </div>
    </div>
</section>

<script type="text/template" id="task-template"><li><img src="<%- (model.owner.profile.user_image + '').replace('original', 'avatar') || '/images/user.jpg' %>" class="feed-avatar hidden-sm hidden-xs">
    <%- model.description %> 
    <% model.tags.forEach(function(tag){ if(tag.category) { %> <span class="label label-<%-tag.label%>">
            <span title="<%- tag.category.name %>: <%- tag.name %>" data-toggle="tooltip" data-placement="top" class="has-tooltip"><%- tag.name.charAt(0) %></span></span><% }}); %>
    <div class="clearfix"></div></li></script>
<script type="text/template" id="feeds-template">
<% _.each(shares, function(item){ %>
<div class="feed">
    <img src="<%= ((item.user.profile.user_image + '').replace('original', 'avatar') || '/images/user.jpg') %>" class="feed-avatar hidden-sm hidden-xs">
    <div class="feed-wrap">
        <div class="title">
            <span class="pull-right text-muted">
                <i class="fa fa-clock-o"></i>
                <%= moment(item.created_at).fromNow() %>
                <% if(item.user_id === <?=$user->id?> || <?=$user->is_admin ? 'true' : 'false'?>){ %><a data-shareid="<%= item.id %>" class="remove-share"> | <span class="fa fa-trash"></span></a><% } %>
                <a class="pin-share">| <i data-shareid="<%= item.id %>" class="fa fa-thumb-tack <% if(_.pluck(item.pins, 'user_id').indexOf(<?=$user->id?>) > -1){ %>active<% } %>"></i></a>
            </span>
            <%= (item.user.profile.first_name + (item.user.profile.last_name ?  ' ' + item.user.profile.last_name : '')) %> <% if(item.user.is_admin){ %><span class="label label-danger">Admin</span><% } %>
        </div>
        <div class="body"><%= nl2br(item.content) %></div>
    </div>
    <div class="comments less-than-three-comments">
        <div class="comments-view" data-shareid="<%= item.id %>">
        </div>
        <div class="comment new-comment">
            <img src="<?=$user_image ?>" class="feed-avatar hidden-sm hidden-xs">
            <textarea class="new-comment" data-feedid="<%= item.id %>" placeholder="Write a reply and shift + enter to submit..."></textarea>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
<% }); %>
<div class="text-center muted">
    <button data-loading-text="Loading..." class="btn btn-primary btn-sm" id="load-older">Load Older</button>
</div>
</script>
<script type="text/template" id="comments-template">
    <% if(!opened) { %>
    <div class="toggle-more">
        View <%= comments.length - 2 %> more comment<% if(comments.length - 2 > 1) { %>s<%}%>
    </div>
    <% } %>
    <% _.each(comments.reverse(), function(comment){ %>
        <div class="comment">
            <img src="<%= (comment.user.profile.user_image + '').replace('original', 'avatar') || '/images/user.jpg' %>" class="feed-avatar hidden-sm hidden-xs">
            <div class="body">
                <b><%= (comment.user.profile.first_name + (comment.user.profile.last_name ?  ' ' + comment.user.profile.last_name : '')) %></b> 
                <%= nl2br(comment.comment) %>
            </div>
            <div class="footer">
                <span class="text-muted">
                    <i class="fa fa-clock-o"></i> <%= moment(comment.created_at).fromNow() %>
                </span>
                <% if(comment.user_id === <?=$user->id?>){ %><a data-commentid="<%= comment.id %>" class="remove-comment"> | <span class="fa fa-trash"></span></a><% } %>
            </div>
            <div class="clearfix"></div>
        </div>
    <% }); %>
</script>
@stop