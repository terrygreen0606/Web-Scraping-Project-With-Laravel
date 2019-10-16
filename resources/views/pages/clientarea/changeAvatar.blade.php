@extends('masters.clientarea')

@section('ExtraStylesheets')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/cropper.css') }}">
@endsection

@section('content')
<div class="ui middle aligned tow column centered grid">
    <div class="row">
        <div class="column">
            <div class="ui text container segment">
                <form class="ui form" id="form-user-change-avatar" method="POST" action="{{ route('do.changeAvatar') }}">
                    <h4 class="ui dividing header">{{ __('Upload Avatar') }}</h4>

                    <div class="field">
                        <button class="ui left attached button negative labled icon select-avatart-picture">
                            <i class="icon image"></i> {{ __('Choose Picture') }}
                        </button>
                        <button type="submit" class="ui right attached button positive labled icon disabled submit-avatar">
                            <i class="icon save"></i> {{ __('Upload Avatar') }}
                        </button>
                        <input type="file" id="avatar-file" name="avatar-file" >
                    </div>

                    <div class="field">
                        <img class="ui centered change-avatar image hidden" id="cropper-stage">
                        <div class="ui icon yellow message change-avatar-place-holder">
                            <i class="inbox icon"></i>
                            <div class="content">
                                <div class="header">
                                    Choose your avatar
                                </div>
                                <p>
                                    <span>You can select and upload picture for your profile avatar by click </span>
                                    <button class="ui button mini red select-avatart-picture">here</button>
                                </p>
                            </div>
                        </div>
                    </div>

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
    <script type="text/javascript" src="{{ asset('assets/js/cropper.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/jquery-cropper.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/change-avatar.js') }}"></script>
@endsection