<!DOCTYPE html>
<html>
<head>
<title>Registration Form </title>
 
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
    <div class="col-md-4 mt-4">
              <h3 class="login-heading mb-4">Bixgrow register!</h3>
               <form action="{{route('admin.create_acount')}}" method="POST" id="regForm">
                 {{ csrf_field() }}
                <div class="form-label-group">
                <label for="inputName">Name</label>
                  <input type="text" id="inputName" name="name"  value="{{ old('name') }}" class="form-control" placeholder="Full name" autofocus>
                  @if ($errors->has('name'))
                  <span class="error">{{ $errors->first('name') }}</span>
                  @endif       
 
                </div> 
                <div class="form-label-group">
                <label for="inputEmail">Email address</label>
                  <input type="email" name="email" id="inputEmail" value="{{ old('email') }}" class="form-control" placeholder="Email address" > 
                  @if ($errors->has('email'))
                  <span class="error">{{ $errors->first('email') }}</span>
                  @endif    
                </div> 
 
                <div class="form-label-group mb-2">
                <label for="inputPassword">Password</label>
                  <input type="password" name="password" id="inputPassword" value="{{ old('password') }}" class="form-control" placeholder="Password">
                  @if ($errors->has('password'))
                  <span class="error">{{ $errors->first('password') }}</span>
                  @endif  
                </div>
 
                <button class="btn btn-lg btn-primary btn-block btn-login text-uppercase font-weight-bold mb-2" type="submit">Register</button>
                <div class="text-center">If you have an account?
                  <a class="small" href="{{route('admin.login')}}">Sign In</a></div>
              </form>
  </div>
</div>
 
</body>
</html>