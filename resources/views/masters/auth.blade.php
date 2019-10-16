@extends('masters.master')

@section('ExtraStylesheets')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/authentication.css') }}" />
@endsection

@section('body')
    <div class="pusher">
        @component('components.authentication.header')@endcomponent
        
        <div class="ui middle aligned tow column centered grid authentication">
            <div class="row">
                <div class="column">
                    <div class="ui text container segment">
                        @yield('form')
                    </div>
                </div>
            </div>
        </div>

        @component('components.authentication.footer')@endcomponent
    </div>
@endsection