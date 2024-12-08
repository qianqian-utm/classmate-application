@if ($records->lastPage() > 1)
    <div class="w3-bar w3-center">
        {{-- Previous Page Link --}}
        @if ($records->currentPage() > 1)
            <a href="{{ $records->previousPageUrl() }}" class="w3-button w3-hover-light-grey">&laquo; Previous</a>
        @endif

        {{-- Page Numbers --}}
        @for ($i = 1; $i <= $records->lastPage(); $i++)
            <a href="{{ $records->url($i) }}" 
            class="w3-button {{ ($records->currentPage() == $i) ? 'w3-blue' : 'w3-hover-light-grey' }}">
                {{ $i }}
            </a>
        @endfor

        {{-- Next Page Link --}}
        @if ($records->currentPage() < $records->lastPage())
            <a href="{{ $records->nextPageUrl() }}" class="w3-button w3-hover-light-grey">Next &raquo;</a>
        @endif
    </div>
@endif