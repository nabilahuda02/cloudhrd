@if(Session::get('NotifySuccess'))
  <div class="text-center alert alert-success">{{ Session::get('NotifySuccess') }}</div>
@endif
@if(Session::get('NotifyWarning'))
  <div class="text-center alert alert-warning">{{ Session::get('NotifyWarning') }}</div>
@endif
@if(Session::get('NotifyDanger'))
  <div class="text-center alert alert-danger">{{ Session::get('NotifyDanger') }}</div>
@endif
@if(Session::get('NotifyInfo'))
  <div class="text-center alert alert-info">{{ Session::get('NotifyInfo') }}</div>
@endif