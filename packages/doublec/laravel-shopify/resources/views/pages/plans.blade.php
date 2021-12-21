@extends("laravel-shopify::layouts.double-c-layout")
@section('title', 'Plan subscription')
@section('header')
    <link rel="stylesheet" href="{{ asset('vendors/double-c/css/pricing.css') }}">
@endsection
@section("main-content")
    <div class="container">
        <div class="row justify-content-center mb-5">
            @foreach($plans as $plan)
                <div class="col-md-4">
                    <div class="card mt-5">
                        <div class="card-header">
                            <h2> {{ $plan->name }} </h2>
                        </div>
                        <div class="card-header">
                            <div class="price">
                                <span class="currency">$</span>
                                <span class="value"> {{ $plan->price }} </span>
                                <span class="duration">
                                    @if($plan->type === 'recurring')
                                        MO
                                    @elseif($plan->type === 'one-time')
                                        ONE TIME
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="features d-flex justify-content-center">
                                <ul>
                                    @foreach($plan->features as $feature)
                                        @if($plan->name)
                                            <li>
                                                <i class="fas fa-check-circle"></i>
                                                <strong> {{ $feature->value  }} </strong>
                                                <span> {{ $feature->name }} </span>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                            <form action="{{ route('dc.subscribe-plan') }}" method="post">
                                @csrf
                                <input type="hidden" name="plan_id" value="{{$plan->id}}">
                                <button type="submit" class="subscription-btn btn btn-outline-success">
                                    SIGN UP
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
