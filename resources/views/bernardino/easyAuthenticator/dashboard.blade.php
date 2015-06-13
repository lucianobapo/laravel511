@extends('easyAuthenticator::auth')

@section('content')
<div class="container">
    <div class="col-md-12">
        <h1>Welcome {{$user->name}}</h1>
        <p>This is where authenticated users come after logging in.
            Feel free to use the templates provided by Easy Authenticator to customise the look and feel.</p>
           <p> Everything is fully customisable :-)</p>
    </div>
</div>
@stop