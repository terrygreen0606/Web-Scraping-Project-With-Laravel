@extends('masters.master')

@section('ExtraStylesheets')
    
@endsection

@section('body')
    @component('components.clientarea.sidebar', ['user' => $user])@endcomponent
    <div class="pusher">
        @component('components.clientarea.header', ['user' => $user])@endcomponent
        @component('components.breadcrumb', ['breadcrumb' => $breadcrumb])@endcomponent

        <div class="container">
            @yield('content')
        </div>

        @component('components.clientarea.footer')@endcomponent
        @component('components.modal_csrf')@endcomponent
    </div>
@endsection

@section('ExtraJavascript')
    @yield('ExtraJavascript')
@endsection