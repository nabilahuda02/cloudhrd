@if(count($leave) > 0)
<ul class="list-unstyled">
  <li class="heading">
    <i class="fa fa-plane"></i>
    Leaves
  </li>
  @foreach ($leave as $item)
    <li>
      <a href="{{$item['link']}}">
        <span class="status-{{$item['status']}}"></span> {{$item['title']}}
      </a>
    </li>
  @endforeach
</ul>
@endif
@if(count($medical) > 0)
<ul class="list-unstyled">
  <li class="heading">
    <i class="fa fa-plus-square"></i>
    Medical Claims
  </li>
  @foreach ($medical as $item)
    <li>
      <a href="{{$item['link']}}">
        <span class="status-{{$item['status']}}"></span> {{$item['title']}}
      </a>
    </li>
  @endforeach
</ul>
@endif
@if(count($claims) > 0)
<ul class="list-unstyled">
  <li class="heading">
    <i class="fa fa-file-text-o"></i>
    General Claims
  </li>
  @foreach ($claims as $item)
    <li>
      <a href="{{$item['link']}}">
        <span class="status-{{$item['status']}}"></span> {{$item['title']}}
      </a>
    </li>
  @endforeach
</ul>
@endif
<!-- <ul class="list-unstyled">
  <li class="heading">
    <i class="fa fa-bullhorn"></i>
    Shares
  </li>
  <li>LA-01</li>
  <li>LA-02</li>
</ul> -->