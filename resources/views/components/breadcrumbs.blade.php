{{-- <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        @foreach($breadcrumbs as $breadcrumb)
            @if($loop->last)
                <li class="breadcrumb-item active" aria-current="page">{{ $breadcrumb['text'] }}</li>
            @else
                <li class="breadcrumb-item"><a href="{{ route($breadcrumb['route']) }}">{{ $breadcrumb['text'] }}</a></li>
                <li class="breadcrumb-separator">|</li>
            @endif
        @endforeach
    </ol>
</nav> --}}

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        @foreach ($breadcrumbs as $breadcrumb)
            @if ($loop->last)
                <li class="breadcrumb-item active" aria-current="page">{{ $breadcrumb['text'] }}</li>
            @else
                <li class="breadcrumb-item"><a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['text'] }}</a></li>
            @endif
        @endforeach
    </ol>
</nav>


{{-- <style>
    .breadcrumb-separator {
        margin: 0 5px; /* Adjust margin as needed */
        color: #ccc; /* Adjust color as needed */
    }
</style> --}}
