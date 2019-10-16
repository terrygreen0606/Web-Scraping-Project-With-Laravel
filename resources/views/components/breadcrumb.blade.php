<div class="container">
    <div class="ui breadcrumb">
        @foreach ($breadcrumb as $title => $address)
            @if(count($breadcrumb) == 1)
                <div class="active section">{{ $title }}</div>
            @else
                @if($loop->last)
                    <div class="active section">{{ $title }}</div>
                @else
                    <a class="section" href="{{ $address }}">{{ $title }}</a>
                    <span class="divider">/</span>
                @endif
            @endif
        @endforeach
    </div>
</div>