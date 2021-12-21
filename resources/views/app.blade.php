<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <meta name="csrf-token" value="{{ csrf_token() }}"/>
   <title>Affiliate BixGrow</title>
   <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:300,400,500,600,700|Material+Icons" rel="stylesheet">
   <link href="{{ mix('css/app.css') }}" type="text/css" rel="stylesheet"/>
   <link rel="icon" type="image/png" href="media/logos/favicon.png"/>
   <script type = "text/javascript"> window.$crisp = []; window.CRISP_WEBSITE_ID = "dfa718e4-6ddf-45c3-8f9b-7a1ffb0d840b"; (function () {d = document; s = d.createElement ("script"); s.src =" https://client.crisp.chat/l.js "; s.async = 1; d.getElementsByTagName ("head") [0] .appendChild (s);}) ( ); </script>

</head>
<body>
   <div id="app">
   </div>
    @if(isset($token))
    <script>

        var token = '{{$token}}';
        window.localStorage.setItem('id_token', token);
        @if($isSkipStarted)
         window.location.href = '/#/dashboard';
         @else
         window.location.href = '/#/install-success';
         @endif
    </script>
    @endif

   <script src="{{ mix('js/app.js') }}" type="text/javascript"></script>

</body>
</html>
