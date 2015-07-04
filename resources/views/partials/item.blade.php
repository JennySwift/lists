<li>
    {{ $item->title }}
    @if($item->children != NULL)
    <ul>
        @foreach($item->children as $item)
            @include('partials.item', $item)
        @endforeach
    </ul>
    @endif
</li>