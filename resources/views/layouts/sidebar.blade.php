@if(Auth::user()->type == 'admin' || Auth::user()->type == 'superadmin')
    @include('layouts.sidebar.admin')
@else
@include('layouts.sidebar.user')
@endif
