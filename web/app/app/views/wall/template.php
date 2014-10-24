<script type="text/template" id="feeds-template">
<% _.each(shares, function(item){ %>
<div class="feed">
    <img src="<%= item.user.profile.user_image %>" class="feed-avatar hidden-sm hidden-xs">
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
            <img src="<?=$user_image?>" class="feed-avatar hidden-sm hidden-xs">
            <textarea class="new-comment" data-feedid="<%= item.id %>" placeholder="Write a comment..."></textarea>
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
            <img src="<%= comment.user.profile.user_image %>" class="feed-avatar hidden-sm hidden-xs">
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