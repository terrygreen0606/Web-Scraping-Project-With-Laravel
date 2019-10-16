@extends('masters.auth')

@section('form')
    @if (count($errors) == 0)
        {{-- begin set new password form --}}
        <form class="ui form" id="form-set-new-password" method="POST" action="{{ route('user.resetPassword') }}">
            <h4 class="ui dividing header">Set New Password</h4>

            <div class="field">
                <label>Password</label>
                <div class="ui left icon input">
                    <input type="password" id="password" name="password" placeholder="Password">
                    <i class="icon lock"></i>
                </div>
            </div>

            <div class="field">
                <label>Confirm Password</label>
                <div class="ui left icon input">
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Password">
                    <i class="icon lock"></i>
                </div>
            </div>

            <button type="submit" class="ui button green labled icon">
                <i class="icon unlock"></i> Set new password
            </button>

            <div class="ui hidden message">
                <i class="close icon"></i>
                <div class="header"></div>
                <ul class="list"></ul>
            </div>

            <input type="hidden" name="reset_password_token" value="{{ $token }}" />
            @csrf
        </form>
        {{-- end set new password form --}}
    @else
        {{-- begin display error --}}
        <div class="ui icon message yellow">
            <i class="lock icon"></i>
            <div class="content">
                <div class="header">
                    Invalid token
                </div>
                <p>Your reset password token is invalid. <a href="{{ route('form.forgotPassword') }}">Continue to forgot password</a></p>
            </div>
        </div>
        {{-- end display error --}}
    @endif
@endsection

@section('ExtraJavascript')
    <script type="text/javascript" src="{{ asset('assets/js/auth/reset-password.js') }}"></script>
@endsection