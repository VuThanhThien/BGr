<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <meta name="csrf-token" value="{{ csrf_token() }}"/>
   <title>Affiliate BixGrow</title>
   <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:300,400,500,600,700|Material+Icons" rel="stylesheet">
   <link href="{{ mix('css/affiliate.css') }}" type="text/css" rel="stylesheet"/>
</head>
<body>
   <div id="app">
   </div>
    @if(isset($token))
    <script>

        var token = '{{$token}}';
        window.localStorage.setItem('aff_id_token', token);
        window.location.href = '/';

    </script>
    @endif

   <script src="{{ mix('js/affiliate.js') }}" type="text/javascript"></script>
</body>
</html>
