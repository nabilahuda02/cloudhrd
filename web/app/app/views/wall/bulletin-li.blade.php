<li data-id="{{$bulletin->id}}" id="bulletin_{{$bulletin->id}}">
    @if($bulletin->type === 'event')
    <i class="list-icon fa fa-calendar"></i>
    @endif
    @if($bulletin->type === 'bulletin')
    <i class="list-icon fa fa-user"></i>
    @endif
    @if($bulletin->type === 'image')
    <i class="list-icon fa fa-camera"></i>
    @endif
    <div class="block">
        <div class="caret"></div>
        <div class="box-generic">
            <div class="timeline-top-info">
                <img class="user_avatar" src="{{ $bulletin->user->avatar() }}" alt="">
                <span class="text-primary">{{ $bulletin->user->profile->first_name }}</span> posted in bulletin
                @if($bulletin->isMine())
                <a href="/wall/delete-bulletin/{{ $bulletin->id }}" class="confirmdelete text-primary"><i class="fa fa-trash-o"></i></a>
                @endif
            </div>
            <div class="media margin-none">
                <div class="row innerLR innerB">
                    <div class="col-sm-12 col-lg-12">
                        <div class="innerT">
                            @if($bulletin->root_path)
                            <a rel="gallery-01" class="fancybox-link" title="{{ $bulletin->content }}" href="{{$bulletin->root_path}}/medium.{{$bulletin->extension}}">
                                <img src="{{$bulletin->root_path}}/thumbnail.{{$bulletin->extension}}" alt="">
                                <span class="clearfix"></span>
                            </a>
                            @endif
                            @if($bulletin->type==='event')
                            <div class="event-box">
                                <h1>{{ date('d', strtotime($bulletin->event_date)) }}</h1>
                                <p>{{ date('M', strtotime($bulletin->event_date)) }}</p>
                            </div>
                            @endif
                            <b>{{$bulletin->title}}</b><br>
                            {{ $bulletin->content }}
                            <div class="clearfix"></div>
                        </div>
                        <div class="timeline-bottom innerT half">
                            <a href="javascript:;" class="comment-counter">
                                <span class="comments-count-num">{{ $bulletin->comments->count() }}</span> <i class="fa fa-comment"></i>
                            </a> |
                            @if(Auth::user()->is_admin)
                            <a href="javascript:;" data-id="{{$bulletin->id}}" class="share-pinned
                                @if($bulletin->pinned)
                                active
                                @endif">
                                <i class="fa fa-thumb-tack"></i>
                            </a> |
                            <a href="javascript:;" data-id="{{$bulletin->id}}" class="send-email">
                                <i class="fa fa-envelope"></i>
                            </a> |
                            @endif
                            <span><i class="fa fa-calendar fa-fw"></i> {{ Helper::Timeago($bulletin->updated_at) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bulletin-comments media margin-none">
            <div style="padding:8px">
                <div>
                    <input data-id="{{$bulletin->id}}" class="comment-input" type="text" placeholder="Press enter to add a comment...">
                </div>
                <dl id="comments_{{$bulletin->id}}">
                    @foreach ($bulletin->comments as $comment)
                    @include('wall.comment')
                    @endforeach
                </dl>
            </div>
        </div>
    </div>
</li>