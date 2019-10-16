@extends('masters.auth')

@section('form')
    @if(count($errors) > 0)

        {{-- begin display error --}}
        <div class="ui negative message">
            <div class="header">There is an issue</div>
            <ul class="list">
                @foreach ($errors as $error)
                    <li>$error</li>
                @endforeach
            </ul>
        </div>
        {{-- end display error --}}

        <div>
            <a href="{{ route('dashboard') }}">Continue</a>
        </div>

    @else

        <div class="ui positive message">
            <div class="header">Your Email Verified</div>
            <p>
                <span>Your email has been verified successfully</span> 
                <a href="{{ route('dashboard.index') }}">Continue</a>
            </p>
        </div>

    @endif
@endsection