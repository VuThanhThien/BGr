<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--Bootsrap 4 CDN-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
    <div class="container-fluid">
        <div class="row no-gutter d-flex justify-content-center align-items-center">
            <div class="col-md-10 ">
                <h3 class="login-heading mb-4">Bixgrow tools!</h3>
                <div class="card">
                    @include('admin.partials.header')
                    @include('admin.partials.menu')
                    <div class="card-body">
                     @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>