<?php $subscription = Subscription::find($id); ?>
<a href="{{ URL::route( 'subscriptions.show', array($id)) }}" class="btn btn-default">View</a>
@if($subscription->canUpdate())
    <a href="{{ URL::route( 'subscriptions.edit', array($id)) }}" class="btn btn-default">Edit</a>
@endif
@if($subscription->canDelete())
    {{Former::open(action('subscriptions.destroy', $subscription->id))->class('form-inline')}}
        {{Former::hidden('_method', 'DELETE')}}
        <button type="button" class="btn btn-default confirm-delete">Delete</button>
    {{Former::close()}}
@endif
