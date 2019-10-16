<span class="ui item borderless">

    <div class="ui pointing top right dropdown top-user-menu">
        <span></span>
        @if ($user->avatar == null)
            <img class="ui avatar image" src="https://www.gravatar.com/avatar/{{ md5($user->email) }}.jpg?s=64">
        @else
            <img class="ui avatar image" src="{{ asset('storage/avatars/' . $user->avatar) }}">
        @endif
        <div class="menu">

            <a class="item" href="{{ route('form.changeAvatar') }}">
                <i class="icon user circle"></i>
                <span>Update Avatar</span>
            </a>
            <a class="item" href="{{ route('form.profile') }}">
                <i class="icon address card"></i>
                <span>Profile</span>
            </a>
                <a class="item" href="{{ route('form.changePassword') }}">
                <i class="icon lock"></i>
                <span>Change Password</span>
            </a>
            <a class="item" href="{{ route('logout') }}">
                <i class="icon sign-out"></i>
                <span>Exit</span>
            </a>
            
        </div>
    </div>
    <div class="ui special popup inverted">
        <div class="header">{{ $user->name }} {{ $user->family }}</div>
        <div>{{ $user->email }}</div>
    </div>
    
</span>