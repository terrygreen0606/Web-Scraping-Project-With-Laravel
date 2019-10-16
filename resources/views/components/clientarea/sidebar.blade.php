@if($user->left_sidebar_status == 'expanded')
    <div class="left sidebar ui uncover visible expanded">
@else
    <div class="left sidebar ui uncover visible very thin icon collapsed">
@endif


    {{-- Begin sidebar top logo --}}
    <div class="ui menu asd borderless top-menu">
        <span class="ui item logo-container">
            <img id="logo-large" src="{{ asset('assets/images/logo.png') }}" title="image processing system" class="logo ui" />
            <img id="logo-small" src="{{ asset('assets/images/favicon.png') }}" title="image processing system" class="logo ui" />
        </span>
    </div>
    {{-- End sidebar top logo --}}


    {{-- Begin user avatar --}}
    <div class="user-avatar">
        @if ($user->avatar == null)
        <img class="ui centered small circular image"
            src="https://www.gravatar.com/avatar/{{ md5($user->email) }}.jpg?s=192">
        @else
        <img class="ui centered medium circular image" src="{{ asset('storage/avatars/' . $user->avatar) }}">
        @endif
        <h5 class="ui header">{{ $user->name }} {{ $user->family }}</h5>
    </div>
    {{-- End user avatar --}}


    {{-- Begin accordion --}}
    <div class="accordion-menu">

        <a class="item title" href="/dashboard">
            <i class="icon braille"></i>
            Dashboard
        </a>

        <div class="ui accordion">

            @if (env('App_Group') == 'images')
            {{-- Begin templates menu --}}
            <div class="item title">
                <span>
                    <i class="icon paint brush"></i>
                    Templates
                </span>
                <i class="dropdown icon"></i>
            </div>
            <div class="content">
                <div>
                    <a class="item" href="inbox.html">Template List</a>
                    <a class="item" href="inbox.html">New Template</a>
                </div>
            </div>
            {{-- End templates menu --}}
            @endif


            @if (env('App_Group') == 'images')
            {{-- Begin images menu --}}
            <div class="item title">
                <span>
                    <i class="icon image outline"></i>
                    Images
                </span>
                <i class="dropdown icon"></i>
            </div>
            <div class="content">
                <div>
                    <a class="item" href="{{ route('form.imageList') }}">Image List</a>
                    <a class="item" href="{{ route('form.uploadImage') }}">Upload New Image</a>
                </div>
            </div>
            {{-- End images menu --}}
            @endif


            @if (env('App_Group') == 'links')
            {{-- Begin anchors menu --}}
            <div class="item title">
                <span>
                    <i class="icon chart line"></i>
                    Links
                </span>
                <i class="dropdown icon"></i>
            </div>
            <div class="content">
                <div>
                    <a class="item" href="{{ route('form.searchAnchors') }}">Process Anchors</a>
                </div>
            </div>
            {{-- End anchors menu --}}

            {{-- Begin monitorng menu --}}
            <div class="item title">
                <span>
                    <i class="icon desktop line"></i>
                    Link Monitor
                </span>
                <i class="dropdown icon"></i>
            </div>
            <div class="content">
                <div>
                    <a class="item" href="{{ route('form.clients') }}">Clients</a>
                    <a class="item" href="{{ route('form.providers') }}">Providers</a>
                    <a class="item" href="{{ route('form.tier1link') }}">Tier 1 Links</a>
                    <a class="item" href="{{ route('form.tier2link') }}">Tier 2 Links</a>
                </div>
            </div>
            {{-- End monitorng menu --}}
            @endif

        </div>


        @if (env('App_Group') == 'images')
        <a class="item title" href="{{ route('categoryList') }}">
            <i class="icon folder open outline"></i>
            Category
        </a>
        @endif

    </div>
    {{-- End accordion --}}


    {{-- Begin icon menu
    <div class="menu vertical icon-menu">

        <a class="item" href="/dashboard" data-inverted="" data-tooltip="Dashboard" data-position="top left">
            <i class="icon big braille"></i>
        </a>


        @if (env('App_Group') == 'images')
        {{-- Begin templates menu
        <div class="ui left pointing dropdown link item" data-inverted="" data-tooltip="Templates"
            data-position="top left">
            <i class="icon big paint brush"></i>
            <div class="menu">
                <a class="item">Template List</a>
                <a class="item">New Template</a>
            </div>
        </div>
        {{-- End templates menu
        @endif


        @if (env('App_Group') == 'images')
        {{-- Begin images menu
        <div class="ui left pointing dropdown link item" data-inverted="" data-tooltip="Images"
            data-position="top left">
            <i class="icon big image outline"></i>
            <div class="menu">
                <a class="item" href="{{ route('form.imageList') }}">Image List</a>
                <a class="item" href="{{ route('form.uploadImage') }}">Upload New Image</a>
            </div>
        </div>
        {{-- End images menu
        @endif


        @if (env('App_Group') == 'links')
        {{-- Begin anchors menu
        <div class="ui left pointing dropdown link item" data-inverted="" data-tooltip="Links" data-position="top left">
            <i class="icon big chart line"></i>
            <div class="menu">
                <a class="item" href="{{ route('form.searchAnchors') }}">Search Anchors</a>
            </div>
        </div>
        {{-- End anchors menu

        {{-- Begin monitoring menu
        <div class="ui left pointing dropdown link item" data-inverted="" data-tooltip="Link Monitor" data-position="top left">
            <i class="icon big desktop line"></i>
            <div class="menu">
                <a class="item" href="{{ route('form.clients') }}">Clients</a>
                <a class="item" href="{{ route('form.providers') }}">Providers</a>
                <a class="item" href="{{ route('form.tier1link') }}">Tier 1 Links</a>
                <a class="item" href="{{ route('form.tier2link') }}">Tier 2 Links</a>
            </div>
        </div>
        {{-- End monitoring menu
        @endif


        @if (env('App_Group') == 'images')
        <a class="item" href="{{ route('categoryList') }}" data-inverted="" data-tooltip="Category" data-position="top left">
            <i class="icon big folder open outline"></i>
        </a>
        @endif

    </div>

    {{-- End icon menu --}}
</div>
