<div class="ui menu asd borderless top-menu">
    <span>
        <img src="{{ asset('assets/images/logo.png') }}" title="{{ config('app.name', 'Laravel') }}" class="authentication-logo" />
    </span>
    <div class="right menu">
        <a href="{{ route('form.login') }}" class="ui item borderless">Login</a>
        <a href="{{ route('form.register') }}" class="ui item borderless">Register</a>
    </div>
</div>
