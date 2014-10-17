@extends('layouts.default')
@section('content')
    <h2>Subscription Config</h2>
    <hr>
    {{Former::open(action('admin.subscription-config.update', [1]))}}
        {{Former::populate($config)}}
        {{ Former::hidden('_method', 'PUT') }}
        {{ Former::text('subscription_duration')
            ->label('Subscription Duration')
            ->required() }}
        {{ Former::number('per_user')
            ->label('Charge Per User Per Month')
            ->required() }}
        {{ Former::text('currency')
            ->label('Currency')
            ->required() }}
        {{ Former::text('minimum_user')
            ->label('Minimum Users')
            ->required() }}
        {{ Former::text('trial_without_reseller_code')
            ->label('Trial Period Without Reseller Code')
            ->required() }}
        {{ Former::text('trial_with_reseller_code')
            ->label('Trial Period With Reseller Code')
            ->required() }}
        <div class="well">
            <button class="btn btn-primary">Submit</button>
        </div>
    {{Former::close()}}
@stop