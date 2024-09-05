@if ($paginated->lastPage() > 1)
    <ul class="pagination" style="margin-left:3%">
        <li class="page-item {{ $paginated->currentPage() == 1 ? ' disabled' : '' }}">
            <a class="page-link" href="{{ $paginated->url(1) }}">Previous</a>
        </li>

        @php
            $start = max($paginated->currentPage() - 2, 1);
            $end = min($paginated->currentPage() + 2, $paginated->lastPage());
            if ($end - $start < 4) {
                $end = min($start + 4, $paginated->lastPage());
                $start = max($end - 4, 1);
            }
        @endphp

        @for ($i = $start; $i <= $end; $i++)
            <li class="page-item {{ $paginated->currentPage() == $i ? ' active' : '' }}">
                <a class="page-link" href="{{ $paginated->url($i) }}">{{ $i }}</a>
            </li>
        @endfor

        <li class="page-item {{ $paginated->currentPage() == $paginated->lastPage() ? ' disabled' : '' }}">
            <a class="page-link" href="{{ $paginated->url($paginated->currentPage() + 1) }}">Next</a>
        </li>
    </ul>
@endif
