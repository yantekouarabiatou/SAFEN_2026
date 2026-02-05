@extends('layouts.app')

@section('title', __('artisan_create.form_title') . ' - AFRI-HERITAGE Bénin')

@push('styles')
    <style>
        :root {
            --benin-green: #009639;
            --benin-yellow: #FCD116;
            --benin-red: #E8112D;
            --terracotta: #D4774E;
            --beige: #F5E6D3;
            --charcoal: #2C3E50;
            --light-gray: #f8f9fa;
        }

        .page-header {
            background: linear-gradient(135deg, var(--benin-green) 0%, var(--benin-red) 100%);
            color: white;
            padding: 5rem 0 4rem;
            border-radius: 0 0 30px 30px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.08'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.15;
        }

        .form-container {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.08);
            margin-top: -2rem;
            position: relative;
            z-index: 10;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--charcoal);
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            border: 2px solid var(--beige);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--benin-green);
            box-shadow: 0 0 0 0.2rem rgba(0, 150, 57, 0.25);
        }

        .btn-submit {
            background: var(--benin-green);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 1rem 2rem;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            background: var(--benin-red);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 150, 57, 0.3);
        }

        .image-upload {
            border: 2px dashed var(--beige);
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .image-upload:hover {
            border-color: var(--benin-green);
            background: rgba(0, 150, 57, 0.05);
        }

        .image-preview {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 1rem;
        }

        .image-preview img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid var(--beige);
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 fw-bold mb-3">{{ __('artisan_create.form_title') }}</h1>
                    <p class="lead mb-0">{{ __('artisan_create.form_subtitle') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="form-container">
                    <form action="{{ route('artisans.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="business_name" class="form-label">{{ __('artisan_create.business_name') }} *</label>
                                    <input type="text" class="form-control @error('business_name') is-invalid @enderror"
                                           id="business_name" name="business_name" value="{{ old('business_name') }}" required>
                                    @error('business_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="craft" class="form-label">{{ __('artisan_create.craft') }} *</label>
                                    <select class="form-select @error('craft') is-invalid @enderror" id="craft" name="craft" required>
                                        <option value="">{{ __('artisan_create.choose_craft') }}</option>
                                        <option value="forgeron" {{ old('craft') == 'forgeron' ? 'selected' : '' }}>Forgeron</option>
                                        <option value="tisserand" {{ old('craft') == 'tisserand' ? 'selected' : '' }}>Tisserand</option>
                                        <option value="potier" {{ old('craft') == 'potier' ? 'selected' : '' }}>Potier</option>
                                        <option value="sculpteur" {{ old('craft') == 'sculpteur' ? 'selected' : '' }}>Sculpteur</option>
                                        <option value="bijoutier" {{ old('craft') == 'bijoutier' ? 'selected' : '' }}>Bijoutier</option>
                                        <option value="cordonnier" {{ old('craft') == 'cordonnier' ? 'selected' : '' }}>Cordonnier</option>
                                        <option value="menuisier" {{ old('craft') == 'menuisier' ? 'selected' : '' }}>Menuisier</option>
                                        <option value="autre" {{ old('craft') == 'autre' ? 'selected' : '' }}>Autre</option>
                                    </select>
                                    @error('craft')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone" class="form-label">{{ __('artisan_create.phone') }} *</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                           id="phone" name="phone" value="{{ old('phone') }}" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="form-label">{{ __('artisan_create.email') }}</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           id="email" name="email" value="{{ old('email') }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="city" class="form-label">{{ __('artisan_create.city') }} *</label>
                                    <input type="text" class="form-control @error('city') is-invalid @enderror"
                                           id="city" name="city" value="{{ old('city') }}" required>
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="district" class="form-label">{{ __('artisan_create.district') }}</label>
                                    <input type="text" class="form-control @error('district') is-invalid @enderror"
                                           id="district" name="district" value="{{ old('district') }}">
                                    @error('district')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="postal_code" class="form-label">{{ __('artisan_create.postal_code') }}</label>
                                    <input type="text" class="form-control @error('postal_code') is-invalid @enderror"
                                           id="postal_code" name="postal_code" value="{{ old('postal_code') }}">
                                    @error('postal_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="address" class="form-label">{{ __('artisan_create.address') }}</label>
                            <textarea class="form-control @error('address') is-invalid @enderror"
                                      id="address" name="address" rows="3">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description" class="form-label">{{ __('artisan_create.description') }} *</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="specialties" class="form-label">{{ __('artisan_create.specialties') }}</label>
                            <input type="text" class="form-control @error('specialties') is-invalid @enderror"
                                   id="specialties" name="specialties" value="{{ old('specialties') }}"
                                   placeholder="{{ __('artisan_create.specialties_placeholder') }}">
                            @error('specialties')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="experience_years" class="form-label">{{ __('artisan_create.experience_years') }}</label>
                            <select class="form-select @error('experience_years') is-invalid @enderror" id="experience_years" name="experience_years">
                                <option value="">{{ __('artisan_create.choose') }}</option>
                                <option value="1-2" {{ old('experience_years') == '1-2' ? 'selected' : '' }}>{{ __('artisan_create.exp_1_2') }}</option>
                                <option value="3-5" {{ old('experience_years') == '3-5' ? 'selected' : '' }}>{{ __('artisan_create.exp_3_5') }}</option>
                                <option value="6-10" {{ old('experience_years') == '6-10' ? 'selected' : '' }}>{{ __('artisan_create.exp_6_10') }}</option>
                                <option value="10+" {{ old('experience_years') == '10+' ? 'selected' : '' }}>{{ __('artisan_create.exp_10_plus') }}</option>
                            </select>
                            @error('experience_years')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">{{ __('artisan_create.photos') }}</label>
                            <div class="image-upload" onclick="document.getElementById('photos').click()">
                                <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                <p class="mb-0">{{ __('artisan_create.click_to_add_photos') }}</p>
                                <small class="text-muted">{{ __('artisan_create.photo_requirements') }}</small>
                            </div>
                            <input type="file" id="photos" name="photos[]" multiple accept="image/*" style="display: none;" onchange="previewImages(this)">
                            <div class="image-preview" id="imagePreview"></div>
                            @error('photos.*')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="visible" name="visible" value="1" {{ old('visible', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="visible">
                                {{ __('artisan_create.make_profile_visible') }}
                            </label>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-submit">
                                <i class="fas fa-user-plus me-2"></i>{{ __('artisan_create.submit_button') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function previewImages(input) {
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = '';

            if (input.files) {
                Array.from(input.files).forEach(file => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            preview.appendChild(img);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        }
    </script>
@endpush