@extends('admin.partials.layout')
@section('content')
<div class="alert alert-info" role="alert">
 Note: Thêm từng ngôn ngữ vào cơ sở dữ liệu.
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
<form action="/admin/dashboard/translations-one" method="POST">
        @csrf
        <div class="form-group">
            <label for="exampleSelect1"><b>Language</b> </label>
            <select class="form-control" name="locale" id="exampleSelect1">
                <option value="en">English</option>
                <option value="es">Spanish</option>
                <option value="zh">Chinese</option>
                <option value="de">German</option>
                <option value="fr">French</option>
                <option value="pt">Portuguese</option>
                <option value="it">Italian</option>
            </select>
        </div>
        <label for="inputKey"><b>Key</b> </label>
        <input type="text" name="key" id="inputKey" class="form-control" placeholder="Key">
        <label for="inputText" class="mt-2"> <b>Text</b> </label>
        <input type="text" name="text" id="inputText" class="form-control" placeholder="Text">
        <button class="btn btn-primary mt-2 btn-sm" type="submit">Add Or Update</button>
</form>
<div class="form-group mt-2" style="color: #7e808c;font-size: 16px;">
  Bạn muốn dịch tiếng anh sang đa ngôn ngữ  <a href="{{route('admin.dashboard')}}">Click here.</a>
</div>
@endsection