@extends('masters.auth')

@section('form')
    <form class="ui form" id="form-user-forgot-password" method="POST" action="{{ route('user.forgotPassword') }}">
        <h4 class="ui dividing header">{{ __('Reset Password') }}</h4>

        <div class="ui icon message yellow">
            <i class="lock icon"></i>
            <div class="content">
                <div class="header">
                    Start Password Reset
                </div>
                <p>Please enter your email address to start the reset password process.</p>
            </div>
        </div>

        <div class="field">
            <label>Email Adress</label>
            <div class="ui left icon input">
                <input type="email" id="email" name="email" placeholder="Email Address">
                <i class="envelope outline icon"></i>
            </div>
        </div>

        <button type="submit" class="ui button green labled icon">
            <i class="icon lock"></i> {{ __('Reset Password') }}
        </button>

        <div class="ui hidden message messages">
            <i class="close icon"></i>
            <div class="header"></div>
            <ul class="list"></ul>
        </div>

        @csrf
    </form>
@endsection

@section('ExtraJavascript')
<script type="text/javascript" src="{{ asset('assets/js/auth/forgot-password.js') }}"></script>
@endsection