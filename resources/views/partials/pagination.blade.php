@if ($paginator->hasPages())
    <ul class="pagination" id="goods-pagination">
        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="disabled"><span>{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="with-active active"><span>{{ $page }}</span></li>
                    @else
                        <li class="with-active">{{ $page }}</li>
                    @endif
                @endforeach
            @endif
        @endforeach
    </ul>
@endif
