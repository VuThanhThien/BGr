@extends('admin.partials.layout')
@section('content')
<div class="alert alert-info" role="alert">
 Note: Thêm và dịch key Tiếng anh sang 6 ngôn ngữ: Spanish, Chinese, German, French, Portuguese, Italian và thêm vào Cơ sở dữ liệu.
</div>
@if(isset($success))
<div class="alert alert-info" role="alert">
 {{$success}}
</div>
@endif
@if (isset($error))
<div class="alert alert-warning" role="alert">
  {{$error}}
 </div>
@endif
@if ($errors->any())
<div class="alert alert-danger" role="alert">
  @foreach ($errors->all() as $error)
       <div>{{$error}}</div>
  @endforeach
 </div>
@endif
<form action="/admin/dashboard" method="POST">
        @csrf
        <h6>Language : <b>English</b>  </h6>
        <label for="inputKey"><b>Key</b> </label>
        <input type="text" name="key" id="inputKey" class="form-control" placeholder="Key">
        <label for="inputText" class="mt-2"> <b>Text</b> </label>
        <input type="text" name="text" id="inputText" class="form-control" placeholder="Text">
        <button class="btn btn-primary mt-2 btn-sm" type="submit">Add Or Update</button>
</form>
<div class="form-group mt-2" style="color: #7e808c;font-size: 16px;">
  Bạn muốn thêm từng ngôn ngữ riêng <a href="/admin/dashboard/translations-one">Click here.</a>
</div>
@endsection
