@props([
    'item' => null,
    'actions' => ['view', 'approve', 'reject', 'delete'],
    'status' => null,
    'viewData' => []
])

<div class="btn-group" role="group">
    @if(in_array('view', $actions))
        <button type="button" 
                class="btn btn-sm btn-info mx-1 rounded btn-view"
                data-id="{{ $item->id }}"
                data-view-info="{{ json_encode($viewData) }}"
                title="Voir détails">
            <i class="fas fa-eye"></i>
        </button>
    @endif

    @if(in_array('approve', $actions) && $status !== 'approved')
        <button type="button" 
                class="btn btn-sm btn-success mx-1 rounded btn-approve" 
                data-id="{{ $item->id }}"
                title="Approuver">
            <i class="fas fa-check"></i>
        </button>
    @endif

    @if(in_array('reject', $actions) && $status !== 'rejected')
        <button type="button" 
                class="btn btn-sm btn-danger mx-1 rounded btn-reject" 
                data-id="{{ $item->id }}"
                title="Rejeter">
            <i class="fas fa-times"></i>
        </button>
    @endif

    @if(in_array('delete', $actions))
        <button type="button" 
                class="btn btn-sm btn-secondary mx-1 rounded btn-delete" 
                data-id="{{ $item->id }}"
                title="Supprimer">
            <i class="fas fa-trash"></i>
        </button>
    @endif
</div>