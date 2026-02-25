@if ($paginator->hasPages())
    <style>
        /* Tighten pagination spacing across the app where this component is used */
        .pagination {
            display: flex;
            gap: 6px !important;
            list-style: none;
            padding: 0 !important;
            margin: 0 !important;
            justify-content: center;
        }

        .pagination .page-item {
            margin: 0 !important;
        }

        .pagination .page-link {
            padding: 6px 10px !important;
            min-width: 36px;
            border-radius: 8px;
            text-align: center;
        }

        .pagination .page-item.active .page-link {
            transform: none !important;
        }
    </style>

    <nav aria-label="Navigation">
        <ul class="pagination mb-0">
            {{-- Lien précédent --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">Précédent</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">Précédent</a>
                </li>
            @endif

            {{-- Numéros de page --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Lien suivant --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Suivant</a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">Suivant</span>
                </li>
            @endif
        </ul>
    </nav>
@endif