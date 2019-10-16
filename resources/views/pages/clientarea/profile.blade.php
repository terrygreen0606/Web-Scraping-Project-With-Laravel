@extends('masters.clientarea')

@section('content')
    <div class="ui middle aligned tow column centered grid authentication">
        <div class="row">
            <div class="column">
                <div class="ui text container segment">
                    <form class="ui form" id="form-user-profile" method="POST" action="{{ route('user.profile') }}">
                        <h4 class="ui dividing header">{{ __('Profile') }}</h4>
                    
                        <div class="field">
                            <label>Email Address</label>
                            <div class="ui left icon icon input disabled">
                                <input type="email" id="email" name="email" placeholder="Email Address" value="{{ $user->email }}">
                                <i class="envelope outline icon"></i>
                            </div>
                        </div>
                        
                        <div class="two fields">
                            <div class="field">
                                <label>First name</label>
                                <div class="ui left icon input">
                                    <input type="text" id="name" name="name" placeholder="First Name" value="{{ $user->name }}">
                                    <i class="icon user"></i>
                                </div>
                            </div>
                            <div class="field">
                                <label>Last name</label>
                                <div class="ui input">
                                    <input type="text" id="family" name="family" placeholder="Last Name" value="{{ $user->family }}">
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="ui button green labled icon">
                            <i class="icon save"></i> {{ __('Update') }}
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
    <script type="text/javascript" src="{{ asset('assets/js/update-profile.js') }}"></script>
@endsection