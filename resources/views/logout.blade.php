@extends('masters.auth')

@section('content')
<div class="ui text container">
 
    <h4 class="ui dividing header">{{ __('Logout') }}</h4>

    <button class="ui button green labled icon logout" data-logout-url="{{ route('logout') }}">
        <i class="icon unlock"></i> {{ __('Logout') }}
    </button>

    <div class="ui hidden message logout">
        <i class="close icon"></i>
        <div class="header"></div>
        <ul class="list"></ul>
    </div>

</div>
@endsection

@section('ExtraJavascript')
<script type="text/javascript" src="{{ asset('assets/js/auth/logout.js') }}"></script>
@endsection