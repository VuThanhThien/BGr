<!DOCTYPE html>
<html>

<head>
    <title>Login Form </title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!--Bootsrap 4 CDN-->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <style>
     .error{
        color: red;
     }
 </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row no-gutter d-flex justify-content-center align-items-center">
            <div class="col-md-4 mt-4 border">
                <h3 class="login-heading mb-4">Bixgrow login!</h3>
                @if($message = session()->get('Errors'))
                <div class="alert alert-danger" role="alert">
                    {{$message}}
                </div>
                @endif
                <form action="{{route('admin.post_login')}}" method="POST" id="logForm">

                    {{ csrf_field() }}

                    <div class="form-label-group">
                        <label for="inputEmail">Email address</label>
                        <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email address">
                        @if ($errors->has('email'))
                        <span class="error">{{ $errors->first('email') }}</span>
                        @endif
                    </div>

                    <div class="form-label-group mb-2">
                        <label for="inputPassword">Password</label>
                        <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password">
                        @if ($errors->has('password'))
                        <span class="error">{{ $errors->first('password') }}</span>
                        @endif
                    </div>
                    <button class="btn btn-lg btn-primary btn-block btn-login text-uppercase font-weight-bold mb-2" type="submit">Sign In</button>
                    <!-- <div class="text-center">If you have an account?
                        <a class="small" href="{{route('admin.register')}}">Sign Up</a>
                    </div> -->
                </form>
            </div>
        </div>
    </div>

</body>

</html>