<!DOCTYPE html>
<html lang="en">
    <head>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        @isset($user)
        <meta name="user" content="{{ $user->uuid }}">
        @endisset

        <title>{{ config('app.name', 'Laravel') }}</title>


        <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/semantic.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/fontawesome.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/app.css') }}">

        @yield('ExtraStylesheets')

    </head>
    <body>

        @yield('body')

        {{--
        @yield('sidebar')

        <div class="pusher">
            @component('components.header', ['user' => Auth::user()])@endcomponent
            @yield('content')
            @component('components.footer')@endcomponent
        </div>

        @component('components.document_parts.javascripts')@endcomponent
        @yield('ExtraJavascript')
        --}}

        <script src="{{ asset('assets/js/jquery-3.1.1.min.js') }}"></script>
        <script src="{{ asset('assets/js/semantic.min.js') }}"></script>
        <script src="{{ asset('assets/js/fontawesome.min.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
        <script src="{{ asset('assets/js/general.js') }}"></script>

        @yield('ExtraJavascript')
    </body>

</html>
