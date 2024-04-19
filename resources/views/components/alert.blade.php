@if(session()->has('success'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif
@if(session()->has('danger'))
    <div class="alert alert-danger">
        {{ session()->get('message') }}
    </div>
@endif
@if(session()->has('info'))
    <div class="alert alert-info">
        {{ session()->get('message') }}
    </div>
@endif
@if(session()->has('warning'))
    <div class="alert alert-warning">
        {{ session()->get('message') }}
    </div>
@endif