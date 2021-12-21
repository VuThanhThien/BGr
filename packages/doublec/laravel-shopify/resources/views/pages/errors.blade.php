@extends("laravel-shopify::layouts.double-c-layout")
@section('title', 'Error messages')
@section("main-content")
    <div class="error">
        @foreach($messages as $message)
            <div class="alert alert-danger">
                <p>{{$message}}</p>
            </div>
        @endforeach
    </div>
@endsection
