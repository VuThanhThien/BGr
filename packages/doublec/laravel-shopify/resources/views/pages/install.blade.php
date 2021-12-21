@extends("laravel-shopify::layouts.double-c-layout")
@section("title", "Install ". config("app.name"))
@section("main-content")
    <div class="install-container fadeInDown">
        <form action="{{ route("dc.install-app") }}" method="post">
            @csrf
            <label for="shop"></label>
            <input type="text" name="shop" placeholder="Shopify domain" id="shop">
            <button type="submit">Install on your store</button>
        </form>
    </div>
@endsection
