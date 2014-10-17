<div class="well">
    @if(isset($has_submit))
        <button class="btn btn-primary">Submit</button>
    @endif
    @if(!isset($is_list) && Subscription::canList())
        <a href="{{route('subscriptions.index')}}" class="btn btn-default">List</a>  
    @endif
    @if(Subscription::canCreate())
        <a href="{{route('subscriptions.create')}}" class="btn btn-default">Create</a>
    @endif
    {{Former::close()}}
    @if(isset($subscription))
        @if($subscription->canShow())
          <a href="{{ action('subscriptions.show', $subscription->id) }}" class="btn btn-default">Details</a>
        @endif
        @if($subscription->canUpdate())
          <a href="{{ action('subscriptions.edit', $subscription->id) }}" class="btn btn-default">Edit</a>
        @endif
        @if($subscription->canDelete())
          {{Former::open(action('subscriptions.destroy', $subscription->id))->class('form-inline')}}
            {{Former::hidden('_method', 'DELETE')}}
            <button type="button" class="btn btn-default confirm-delete">Delete</button>
          {{Former::close()}}
        @endif
    @endif
</div>