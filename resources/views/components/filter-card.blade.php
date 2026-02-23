@props([
    'route' => null,
    'filters' => [],
    'showReset' => true
])

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-filter"></i> {{ $title ?? 'Filtres' }}</h4>
                @isset($headerAction)
                    <div class="card-header-action">
                        {{ $headerAction }}
                    </div>
                @endisset
            </div>
            <div class="card-body">
                <form action="{{ $route }}" method="GET" class="row">
                    {{ $slot }}
                    
                    <div class="col-12 d-flex justify-content-end mt-3">
                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-info">
                                <i class="fas fa-search"></i> Filtrer
                            </button>
                            @if($showReset)
                                <a href="{{ $route }}" class="btn btn-secondary">
                                    <i class="fas fa-undo"></i> Réinitialiser
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>