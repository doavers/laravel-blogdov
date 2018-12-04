@extends('layouts.app')

@section('content')

<div class="login-box">
    <div class="login-logo">
        <a href="/"><b>My</b>BLOG</a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">{{ __('Login') }} to start your session</p>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} has-feedback">
                <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('E-Mail Address') }}" name="email" value="{{ old('email') }}">
                <span class="fa fa-envelope form-control-feedback"></span>

                @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
                @endif
            </div>
            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} has-feedback">
                <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{ __('Password') }}" name="password">
                <span class="fa fa-lock form-control-feedback"></span>

                @if ($errors->has('password'))
                <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
                @endif
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox" name="remember"
                            {{ old('remember') ? 'checked' : '' }}> {{ __('Remember Me') }}
                        </label>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">{{ __('Login') }}</button>
                </div>
                <!-- /.col -->
            </div>
        </form>

        <br>
        @if (Route::has('password.request'))
        <a class="btn btn-link" href="{{ route('password.request') }}">
            {{ __('Forgot Your Password?') }}
        </a>
        @endif
    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

@endsection
