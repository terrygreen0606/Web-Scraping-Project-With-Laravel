<div class="ui menu asd borderless top-menu">

    {{-- <a class="ui item borderless toggle-sidebar">
        <i class="icon bars"></i>
    </a> --}}

    <span class="item borderless">
        {{ env('APP_NAME') }}
    </span>

    <div class="right menu">
         @component('components.top_usermenu', ['user' => $user])@endcomponent
    </div>
</div>
