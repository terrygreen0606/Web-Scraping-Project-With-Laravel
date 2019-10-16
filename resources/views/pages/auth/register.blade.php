@extends('masters.auth')

@section('form')
    <form class="ui form" id="form-user-register" method="POST" action="{{ route('user.register') }}">
        <h4 class="ui dividing header">{{ __('Register') }}</h4>

        <div class="field">
            <label>Name</label>
            <div class="two fields">
                <div class="field">
                    <div class="ui left icon input">
                        <input type="text" id="name" name="name" placeholder="First Name">
                        <i class="icon user"></i>
                    </div>
                </div>
                <div class="field">
                    <input type="text" id="family" name="family" placeholder="Last Name">
                </div>
            </div>
        </div>

        <div class="field">
            <label>Email Address</label>
            <div class="ui left icon icon input">
                <input type="email" id="email" name="email" placeholder="Email Address">
                <i class="envelope outline icon"></i>
            </div>
        </div>

        <div class="field">
            <label>Password</label>
            <div class="two fields">
                <div class="field">
                    <div class="ui left icon input">
                        <input type="password" id="password"name="password" placeholder="Password">
                        <i class="icon lock"></i>
                    </div>
                </div>
                <div class="field">
                    <div class="ui left icon input">
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password">
                        <i class="icon lock"></i>
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="ui button green labled icon">
            <i class="icon lock"></i> Register
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
    <script type="text/javascript" src="{{ asset('assets/js/auth/register.js') }}"></script>
@endsection