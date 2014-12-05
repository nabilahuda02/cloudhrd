@if(Session::get('NotifySuccess'))
  <div class="alert alert-success">{{ Session::get('NotifySuccess') }}</div>
@endif
@if(Session::get('NotifyWarning'))
  <div class="alert alert-warning">{{ Session::get('NotifyWarning') }}</div>
@endif
@if(Session::get('NotifyDanger'))
  <div class="alert alert-danger">{{ Session::get('NotifyDanger') }}</div>
@endif
@if(Session::get('NotifyInfo'))
  <div class="alert alert-info">{{ Session::get('NotifyInfo') }}</div>
@endif