<div class="modal fade" id="reviewDetailModal" tabindex="-1" aria-labelledby="reviewDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewDetailModalLabel">Détail de l'avis</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">Informations auteur</h6>
                                <p class="mb-1"><strong>Nom :</strong> <span id="modalUserName"></span></p>
                                <p class="mb-0"><strong>Email :</strong> <span id="modalUserEmail"></span></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">Informations élément</h6>
                                <p class="mb-1"><strong>Type :</strong> <span id="modalItemType"></span></p>
                                <p class="mb-0"><strong>Nom :</strong> <span id="modalItemName"></span></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">Note :</h6>
                            <div>
                                <span id="modalRatingStars" class="text-warning"></span>
                                <span class="badge bg-primary ms-2" id="modalRatingValue"></span>
                            </div>
                        </div>
                        <h6>Commentaire :</h6>
                        <div id="modalComment" class="p-3 bg-light rounded" style="min-height: 100px; max-height: 200px; overflow-y: auto;"></div>
                        <p class="mt-3 mb-0 text-muted"><small>Posté le <span id="modalDate"></span></small></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function showReviewDetail(data) {
        // Générer les étoiles
        var stars = '';
        for(var i = 1; i <= 5; i++) {
            stars += i <= data.rating ? '★' : '☆';
        }
        
        $('#modalUserName').text(data.userName);
        $('#modalUserEmail').text(data.userEmail);
        $('#modalItemType').text(data.itemType);
        $('#modalItemName').text(data.itemName);
        $('#modalRatingStars').html(stars);
        $('#modalRatingValue').text(data.rating + '/5');
        $('#modalComment').text(data.comment);
        $('#modalDate').text(data.date);
        
        $('#reviewDetailModal').modal('show');
    }
</script>
@endpush