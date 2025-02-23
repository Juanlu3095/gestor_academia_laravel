@if ($paginator->hasPages())
    <ul class="pagination d-flex justify-content-evenly">
        {{-- Enlace a anterior --}}
        @if ($paginator->onFirstPage())
            <li class="disabled"><span>Anterior</span></li>
        @else
            <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev">Anterior</a></li>
        @endif
        
        
        {{ "Página " . $paginator->currentPage() . "  de  " . $paginator->lastPage() }}
       
        
        {{-- Enlace a siguiente --}}
        @if ($paginator->hasMorePages())
            <li><a href="{{ $paginator->nextPageUrl() }}" rel="next">Siguiente</a></li>
        @else
            <li class="disabled"><span>Siguiente</span></li>
        @endif
    </ul>
@endif