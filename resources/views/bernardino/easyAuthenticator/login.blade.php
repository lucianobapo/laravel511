@extends('easyAuthenticator::auth')

@section('content')
<div class="container">
    <div class="col-md-12">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">Login</div>
                <div class="panel-body">
                    @if (Session::get('session'))
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                            <ul>
                                    <li>{{ Session::get('session') }}</li>
                            </ul>
                        </div>
                    @endif
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('auth/login') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group">
                            <label class="col-md-4 control-label">E-Mail Address</label>
                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Password</label>
                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> Remember Me
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">Login</button>
                                <a class="btn btn-link" href="{{ url('/password/email') }}">Forgot Your Password?</a>
                                <a class="btn btn-link" href="{{ url('/auth/register') }}">Register an account</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6" style="margin-bottom: 20px">
            <a href="/easyAuth/twitter" class="btn-lg btn btn-success col-md-12">Log in with Twitter</a>
        </div>
        <div class="col-md-6" style="margin-bottom: 20px">
            <a href="/easyAuth/facebook" class="btn-lg btn btn-success col-md-12">Log in with Facebook</a>
        </div>
        <div class="col-md-6" style="margin-bottom: 20px">
            <a href="/easyAuth/google" class="btn-lg btn btn-success col-md-12">Log in with Google</a>
        </div>
        <div class="col-md-6" style="margin-bottom: 20px">
            <a href="/easyAuth/github" class="btn-lg btn btn-success col-md-12">Log in with Github</a>
        </div>
    </div>
</div>
@stop