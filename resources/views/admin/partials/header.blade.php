<div class="card-header">
    <div class="card-title">
        <h3 class="card-label">Hello: <b>{{ucfirst(auth('web')->user()->name)}}</b></h3>
        <a class="small btn btn-info" href="{{route('admin.logout')}}">Logout</a>
    </div>
</div>