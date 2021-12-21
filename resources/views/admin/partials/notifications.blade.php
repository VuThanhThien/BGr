@extends('admin.partials.layout')
@section('content')
<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.tiny.cloud/1/kjfo1e5r0moyxxuz65a0cn7v5uscp3f7qjwmn3rhroa31rfl/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
  <script type="text/javascript">
  tinymce.init({
    selector: 'textarea',
    plugins: [
            'advlist autolink lists link image charmap print preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table paste code help wordcount emoticons'
            ],
    toolbar: 'undo redo | formatselect | bold italic forecolor backcolor | code \
            alignleft aligncenter alignright alignjustify | image |charmap emoticons\
            bullist numlist outdent indent | removeformat',
    height:500,
    menubar: true,
  });
  </script>
</head>
<style>
.tox .tox-notification--in {
    display: none !important;
    opacity: 0 !important;
}
.tox .tox-notification--warn, .tox .tox-notification--warning{
    display: none !important;
}
.tox-statusbar__branding{
    display: none !important;
}
</style>
<body>
<h1>Insert notification</h1>
@if(isset($success))
<div class="alert alert-info" role="alert">
 {{$success}}
</div>
@endif
<form action="/admin/dashboard/notifications" method="POST">
        @csrf
        <label for="header"><b>Title thông báo</b> </label>
        <input type="text" name="header" id="header" class="form-control" placeholder="Header">

        <label for="reflink" class="mt-2"> <b>Content thông báo</b> </label>
        <textarea name="content" id="mytextarea"></textarea>

        <label for="label" class="mt-2"> <b>Text cần gắn link ở bottom</b> </label>
        <input type="text" name="label" id="reflink" class="form-control" placeholder="Text footer">

        <label for="link" class="mt-2"> <b>Link của text bên trên</b> </label>
        <input type="text" name="link" id="link" class="form-control" placeholder="Link">

        <button class="btn btn-primary mt-2 btn-sm" type="submit">Save</button>
</form>
</body>
</html>

@endsection
