@props([
    'id' => 'deleteModal',
    'title' => 'Confirmer la suppression',
    'message' => 'Êtes-vous sûr de vouloir supprimer cet élément ? Cette action est irréversible.',
    'confirmText' => 'Supprimer',
    'cancelText' => 'Annuler'
])

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $id }}Label">{{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <p>{{ $message }}</p>
                @isset($slot)
                    {{ $slot }}
                @endisset
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ $cancelText }}</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">{{ $confirmText }}</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        var deleteUrl = '';
        
        // Déclencher le modal avec l'URL
        $('[data-toggle="delete-modal"]').click(function() {
            deleteUrl = $(this).data('url');
            $('#{{ $id }}').modal('show');
        });
        
        // Confirmer la suppression
        $('#confirmDeleteBtn').click(function() {
            if (deleteUrl) {
                $.ajax({
                    url: deleteUrl,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#{{ $id }}').modal('hide');
                        if (response.success) {
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        alert('Une erreur est survenue');
                    }
                });
            }
        });
    });
</script>
@endpush