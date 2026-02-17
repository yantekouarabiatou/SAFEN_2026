{{-- resources/views/admin/vendors/_dish_row.blade.php --}}
<div class="card mb-4 dish-row shadow-sm" data-index="{{ $index }}">
    <div class="card-body">
        <div class="row g-3">
            <!-- ID caché -->
            <input type="hidden" name="dishes[{{ $index }}][id]" value="{{ $dish->id }}">

            <!-- Nom -->
            <div class="col-md-6">
                <label>Nom du plat <span class="text-danger">*</span></label>
                <input type="text" name="dishes[{{ $index }}][name]" class="form-control"
                       value="{{ old("dishes.$index.name", $dish->name) }}" required>
            </div>

            <!-- Nom local -->
            <div class="col-md-6">
                <label>Nom local (optionnel)</label>
                <input type="text" name="dishes[{{ $index }}][name_local]" class="form-control"
                       value="{{ old("dishes.$index.name_local", $dish->name_local) }}">
            </div>

            <!-- Catégorie -->
            <div class="col-md-4">
                <label>Catégorie <span class="text-danger">*</span></label>
                <select name="dishes[{{ $index }}][category]" class="form-control select2-dish" required>
                    <option value=""></option>
                    @foreach($categories as $value => $label)
                        <option value="{{ $value }}" {{ old("dishes.$index.category", $dish->category) == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Origine ethnique -->
            <div class="col-md-4">
                <label>Origine ethnique</label>
                <input type="text" name="dishes[{{ $index }}][ethnic_origin]" class="form-control"
                       value="{{ old("dishes.$index.ethnic_origin", $dish->ethnic_origin) }}">
            </div>

            <!-- Région -->
            <div class="col-md-4">
                <label>Région</label>
                <input type="text" name="dishes[{{ $index }}][region]" class="form-control"
                       value="{{ old("dishes.$index.region", $dish->region) }}">
            </div>

            <!-- Prix (pivot) -->
            <div class="col-md-4">
                <label>Prix (FCFA) <span class="text-danger">*</span></label>
                <input type="number" name="dishes[{{ $index }}][price]" class="form-control"
                       value="{{ old("dishes.$index.price", $dish->pivot->price) }}" min="0" step="100" required>
            </div>

            <!-- Disponible (pivot) -->
            <div class="col-md-4">
                <label>Disponible</label>
                <select name="dishes[{{ $index }}][available]" class="form-control">
                    <option value="1" {{ old("dishes.$index.available", $dish->pivot->available) == 1 ? 'selected' : '' }}>Oui</option>
                    <option value="0" {{ old("dishes.$index.available", $dish->pivot->available) == 0 ? 'selected' : '' }}>Non</option>
                </select>
            </div>

            <!-- Notes (pivot) -->
            <div class="col-md-4">
                <label>Notes (optionnel)</label>
                <input type="text" name="dishes[{{ $index }}][notes]" class="form-control"
                       value="{{ old("dishes.$index.notes", $dish->pivot->notes) }}">
            </div>

            <!-- Ingrédients (array) -->
            <div class="col-12">
                <label>Ingrédients (séparés par virgule)</label>
                <input type="text" name="dishes[{{ $index }}][ingredients]" class="form-control"
                       placeholder="Ex: igname, sauce tomate, poisson"
                       value="{{ old("dishes.$index.ingredients", is_array($dish->ingredients) ? implode(', ', $dish->ingredients) : $dish->ingredients) }}">
            </div>

            <!-- Description -->
            <div class="col-12">
                <label>Description</label>
                <textarea name="dishes[{{ $index }}][description]" class="form-control" rows="2">{{ old("dishes.$index.description", $dish->description) }}</textarea>
            </div>

            <!-- Images (optionnel) -->
            <div class="col-12">
                <label>Images du plat</label>
                <div class="dropzone dish-dropzone" id="dropzone-{{ $index }}"></div>
                <input type="hidden" name="dishes[{{ $index }}][images]" class="dish-images-hidden">
                @if($dish->images->count())
                    <div class="mt-2">
                        <small class="text-muted">Images existantes :</small>
                        <div class="d-flex flex-wrap">
                            @foreach($dish->images as $image)
                                <div class="mr-2 mb-2 position-relative">
                                    <img src="{{ Storage::url($image->image_url) }}" style="height: 60px; width: 60px; object-fit: cover; border-radius: 4px;">
                                    <button type="button" class="btn btn-sm btn-danger position-absolute" style="top: -5px; right: -5px; padding: 2px 5px;" onclick="deleteImage({{ $image->id }})">×</button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Bouton supprimer -->
            <div class="col-12 text-right mt-3">
                <button type="button" class="btn btn-danger btn-sm remove-dish-btn" data-id="{{ $dish->id }}">
                    <i data-feather="trash-2"></i> Supprimer ce plat
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Fonction pour supprimer une image (à implémenter si besoin)
    function deleteImage(imageId) {
        if (confirm('Supprimer cette image ?')) {
            $.ajax({
                url: '{{ route("admin.dishes.images.destroy", ":id") }}'.replace(':id', imageId),
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function() {
                    location.reload();
                }
            });
        }
    }
</script>