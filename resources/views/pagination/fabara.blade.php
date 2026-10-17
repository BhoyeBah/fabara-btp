@if ($paginator->hasPages())
    <nav class="pager" aria-label="Pagination">
        @if ($paginator->onFirstPage())
            <span class="off">{{ __('pagination.previous') }}</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev">{{ __('pagination.previous') }}</a>
        @endif

        @if (method_exists($paginator, 'lastPage'))
            <span class="off">Page {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}</span>
        @endif

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next">{{ __('pagination.next') }}</a>
        @else
            <span class="off">{{ __('pagination.next') }}</span>
        @endif
    </nav>
@endif
