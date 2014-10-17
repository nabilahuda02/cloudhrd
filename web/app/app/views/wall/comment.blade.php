<div data-id="{{$comment->id}}" class="comment-wrapper">
  <dt>
    <img class="user_avatar" src="{{ $comment->user->avatar() }}" alt="">
    <span class="text-primary">{{Helper::userName($comment->user->id)}}</span> wrote:
  </dt>
  <dd>
    {{$comment->comment}}
    <div class="comment-footer">
      @if($comment->isMine())
        <a href="javascript:;" data-parent="{{$comment->share_id}}" data-id="{{$comment->id}}" class="delete_comment"><i class="fa fa-trash-o"></i></a> 
      @endif
      | <i class="fa fa-calendar fa-fw"></i> {{ Helper::Timeago($comment->updated_at) }}
    </div> 
  </dd>
</div>