@if ($paginator->hasPages())
    <div class="blog-list__pagination">
        <ul class="pg-pagination list-unstyled">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="prev disabled">
                    <a href="#" aria-label="prev"><i class="fas fa-arrow-left"></i></a>
                </li>
            @else
                <li class="prev">
                    <a href="{{ $paginator->previousPageUrl() }}" aria-label="prev">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="count disabled">
                        <span>{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="count active">
                                <a href="#">{{ str_pad($page, 2, '0', STR_PAD_LEFT) }}</a>
                            </li>
                        @else
                            <li class="count">
                                <a href="{{ $url }}">{{ str_pad($page, 2, '0', STR_PAD_LEFT) }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="next">
                    <a href="{{ $paginator->nextPageUrl() }}" aria-label="Next">
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </li>
            @else
                <li class="next disabled">
                    <a href="#" aria-label="Next"><i class="fas fa-arrow-right"></i></a>
                </li>
            @endif
        </ul>
    </div>
@endif
