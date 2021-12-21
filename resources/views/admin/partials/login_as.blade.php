@extends('admin.partials.layout')
@section('content')
<div class="row">
<div class="col-md-4">
    <label for="inputKey"><b>Domain shoptify: </b> </label>
    <input type="text" name="shop" id="shop" class="form-control">
    <button class="btn btn-primary mt-2 btn-sm" id="login-as" type="button">Login</button>
</div>
</div>
   <script>
      $( document ).ready(function() {
        $.ajaxSetup({
             headers: {
             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#login-as').click(function(){
            let shop = $('#shop').val();
            $.ajax({
            type: 'post',
            url: '/admin/dashboard/login-as',
            data:  JSON.stringify({shop: shop}),
            contentType: "application/json; charset=utf-8",
            success: function (data) {
                if(data.status){
                    window.localStorage.setItem('id_token', data.data);
                    // window.location.href = '/';
                    window.open('/');
                }
                else
                {
                    alert(data.data);
                }
            }
        });
        })
    });
   </script>
@endsection