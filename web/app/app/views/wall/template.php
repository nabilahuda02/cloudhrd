<% _.each(data, function(item){ %>
<div class="feed">
    <img src="<%= item.user.profile.user_image %>" class="feed-avatar hidden-sm hidden-xs">
    <div class="feed-wrap">
        <div class="title">
            <span class="fa fa-clock-o pull-right text-muted">
                <%= moment(item.created_at).fromNow() %>
                <% if(item.user_id === <?=$user->id?>){ %><a data-shareid="<%= item.id %>" class="remove-share"> | <span class="fa fa-trash"></span></a><% } %>
            </span>
            <%= (item.user.profile.first_name + (item.user.profile.last_name ?  ' ' + item.user.profile.last_name : '')) %> <% if(item.user.is_admin < 3){ %><span class="label label-danger">Admin</span><% } %>
        </div>
        <div class="body"><%= nl2br(item.content) %></div>
    </div>
    <div class="comments <% if(item.comments.length < 3){ %>less-than-three-comments<% } %>">
        <div class="toggle-more">
            View <%= item.comments.length - 2 %> more comment<% if(item.comments.length - 2 > 1) { %>s<%}%>
        </div>
        <% _.each(item.comments.reverse(), function(comment){ %>
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
        <% }); %>
        <div class="comment new-comment">
            <img src="<?=$user_image?>" class="feed-avatar hidden-sm hidden-xs">
            <textarea class="new-comment" data-feedid="<%= item.id %>" placeholder="Write a comment..."></textarea>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
<% }); %>
<!-- <div class="feed">
    <img src="/images/user.jpg" class="feed-avatar hidden-sm hidden-xs">
    <div class="feed-wrap">
        <div class="title">
            <span class="fa fa-clock-o pull-right text-muted"> Just Now</span>
            John Smith <span class="label label-danger">Admin</span>
        </div>
        <div class="body">
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Assumenda, praesentium ipsum aperiam, dignissimos inventore sunt asperiores velit excepturi dolor repellendus, recusandae eos deserunt illum eum debitis, laudantium voluptatum quos suscipit.
        </div>
    </div>
    <div class="comments">
        <div class="toggle-more">
            View 18 more comments
        </div>
        <div class="comment">
            <img src="/images/user.jpg" class="feed-avatar hidden-sm hidden-xs">
            <div class="body">
                <b>Abu</b> 2 Lorem ipsum dolor sit amet, consectetur adipisicing elit. Id asperiores a harum excepturi, obcaecati facere eos quasi maxime sapiente quibusdam dolore, vero hic mollitia repellendus fuga ipsa laborum dolorem magnam!
            </div>
            <div class="footer">
                <span class="fa fa-clock-o text-muted"> Just Now</span>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="comment">
            <img src="/images/user.jpg" class="feed-avatar hidden-sm hidden-xs">
            <div class="body">
                <b>Abu</b> 3 Lorem ipsum dolor sit amet, consectetur adipisicing elit. Id asperiores a harum excepturi, obcaecati facere eos quasi maxime sapiente quibusdam dolore, vero hic mollitia repellendus fuga ipsa laborum dolorem magnam!
            </div>
            <div class="footer">
                <span class="fa fa-clock-o text-muted"> Just Now</span>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="comment">
            <img src="/images/user.jpg" class="feed-avatar hidden-sm hidden-xs">
            <div class="body">
                <b>Abu</b> 4 Lorem ipsum dolor sit amet, consectetur adipisicing elit. Id asperiores a harum excepturi, obcaecati facere eos quasi maxime sapiente quibusdam dolore, vero hic mollitia repellendus fuga ipsa laborum dolorem magnam!
            </div>
            <div class="footer">
                <span class="fa fa-clock-o text-muted"> Just Now</span>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="comment new-comment">
            <img src="/images/user.jpg" class="feed-avatar hidden-sm hidden-xs">
            <textarea placeholder="Write a comment..."></textarea>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="clearfix"></div>
</div> -->
<div class="text-center muted">
    <a href="">Load Older</a>
</div>
