@extends('masters.auth')

@section('form')
    <div class="ui negative message">
        <div class="header">Your email is unverified !</div>
        <p>
            <span>Please check your email and verify it by click on the verification link</span>
            <a href="{{ route('user.verifyEmailSendToken') }}">Resend Email Verification Link</a>
        </p>
    </div>
@endsection