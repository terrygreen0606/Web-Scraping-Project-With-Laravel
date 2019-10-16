@extends('masters.clientarea')

@section('content')
<div class="ui middle aligned tow column centered grid authentication">
    <div class="row">
        <div class="column">
            <div class="ui text container segment">
                <form class="ui form" id="form-user-change-password" method="POST" action="{{ route('user.changePassword') }}">
                    <h4 class="ui dividing header">{{ __('Change Password') }}</h4>

                    <div class="two fields">
                        <div class="field">
                            <label>Current password</label>
                            <div class="ui left icon icon input">
                                <input type="password" id="old_password" name="old_password" placeholder="Current Password">
                                <i class="lock icon"></i>
                            </div>
                        </div>
                        <div class="field"></div>
                    </div>

                    <div class="two fields">
                        <div class="field">
                            <label>New password</label>
                            <div class="ui left icon input">
                                <input type="password" id="password" name="password" placeholder="New Password">
                                <i class="icon lock"></i>
                            </div>
                        </div>
                        <div class="field">
                            <label>New password confirm</label>
                            <div class="ui left icon input">
                                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="New Password Confirm">
                                <i class="icon lock"></i>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="ui button green labled icon">
                        <i class="icon save"></i> {{ __('Change Password') }}
                    </button>

                    <div class="ui hidden message">
                        <i class="close icon"></i>
                        <div class="header"></div>
                        <ul class="list"></ul>
                    </div>

                    @csrf

                    <input type="hidden" id="user_id" name="user_id" value="{{ $user->uuid }}">
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('ExtraJavascript')
<script type="text/javascript" src="{{ asset('assets/js/change-password.js') }}"></script>
@endsection