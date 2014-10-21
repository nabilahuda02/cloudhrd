<script type="text/template" id="feeds-view-template">
    <div class="feeds-wrapper-outlet"></div>
    <div class="text-center muted">
        <a>Load Older</a>
    </div>
</script>

<script type="text/template" id="feeds-wrapper-view-template">
    <div class="feed">
        <div class="feed-view-outlet"></div>
        <div class="comments-view-outlet"></div>
        <div class="clearfix"></div>
    </div>
</script>

<!-- share -->
<script type="text/template" id="feed-view-template">
    <img src="<%= share.user.profile.user_image %>" class="feed-avatar hidden-sm hidden-xs">
    <div class="feed-wrap">
        <div class="title">
            <span class="fa fa-clock-o pull-right text-muted">
                <%= moment(share.created_at).fromNow() %>
                <% if(share.user_id === <?=$user->id?>){ %><a class="remove-share"> | <span class="fa fa-trash"></span></a><% } %>
            </span>
            <%= (share.user.profile.first_name + (share.user.profile.last_name ?  ' ' + share.user.profile.last_name : '')) %> <% if(share.user.is_admin < 3){ %><span class="label label-danger">Admin</span><% } %>
        </div>
        <div class="body"><%= nl2br(share.content) %></div>
    </div>
</script>

<!-- id = share.id, length = comments.length -->
<script type="text/template" id="comments-view-template">
    <div class="comments <% if(length < 3){ %>less-than-three-comments<% } %>">
        <div class="toggle-more">
            View <%= length - 2 %> more comment<% if(length - 2 > 1) { %>s<%}%>
        </div>
        <% for(var i = 0; i < length; i++){ %>
            <div class="comment-view-template"></div>
        <% }; %>
        <div class="comment new-comment">
            <img src="<?=$user_image?>" class="feed-avatar hidden-sm hidden-xs">
            <textarea class="new-comment" data-feedid="<%= id %>" placeholder="Write a comment..."></textarea>
            <div class="clearfix"></div>
        </div>
    </div>
</script>

<!-- comment -->
<script type="text/template" id="comment-view-template">
    <div class="comment">
        <img src="<%= comment.user.profile.user_image %>" class="feed-avatar hidden-sm hidden-xs">
        <div class="body">
            <b><%= (comment.user.profile.first_name + (comment.user.profile.last_name ?  ' ' + comment.user.profile.last_name : '')) %></b> 
            <%= nl2br(comment.comment) %>
        </div>
        <div class="footer">
            <span class="fa fa-clock-o text-muted"> <%= moment(comment.created_at).fromNow() %></span>
            <% if(comment.user_id === <?=$user->id?>){ %><a data-commentid="<%= comment.id %>" class="remove-comment"> | <span class="fa fa-trash"></span></a><% } %>
        </div>
        <div class="clearfix"></div>
    </div>
</script>