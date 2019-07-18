<div class="row">
    <div class="col-12 text-center">
        @if(session('errors'))
        <div class="alert alert-danger">{{ session('errors')->first() }}</div>
        @endif @if(session('notice'))
        <div class="alert alert-warning">{{ session('notice') }}</div>
        @endif @if(session('message'))
        <div class="alert alert-info">{{ session('message') }}</div>
        @endif @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
        @endif
    </div>
</div>
