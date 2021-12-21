@extends("laravel-shopify::layouts.double-c-layout")
@section("title", "Admin sudo login ". config("app.name"))
@section("main-content")
    <div class="install-container fadeInDown">
        <form action="{{ route("dc.admin.sudo") }}" method="post">
            @csrf
            <label for="shop"></label>
            <input type="text" name="shop" placeholder="Shopify domain" id="shop">
            <button type="submit">Login</button>
        </form>
    </div>
@endsection
