@extends('masters.auth')

@section('form')
    <form class="ui form" id="form-user-login" method="POST" action="{{ route('user.login') }}">
        <h4 class="ui dividing header">{{ __('Login') }}</h4>

        <div class="field">
            <label>Email Adress</label>
            <div class="ui left icon icon input">
                <input type="email" id="email" name="email" placeholder="Email Address">
                <i class="envelope outline icon"></i>
            </div>
        </div>

        <div class="field">
            <label>Password</label>
            <div class="ui left icon input">
                <input type="password" id="password" name="password" placeholder="Password">
                <i class="icon lock"></i>
            </div>
        </div>

        <div class="inline field">
            <div class="ui toggle checkbox">
                <input type="checkbox" name="remember" id="remember" class="hidden">
                <label for="remember">{{ __('Remember Me') }}</label>
            </div>
        </div>

        <div class="inline field">
            <a href="{{ route('form.forgotPassword') }}">Forgot password?</a>
        </div>

        <button type="submit" class="ui button green labled icon">
            <i class="icon unlock"></i> {{ __('Login') }}
        </button>

        <div class="ui hidden message">
            <i class="close icon"></i>
            <div class="header"></div>
            <ul class="list"></ul>
        </div>

        @csrf
    </form>
@endsection

@section('ExtraJavascript')
<script type="text/javascript" src="{{ asset('assets/js/auth/login.js') }}"></script>
@endsection