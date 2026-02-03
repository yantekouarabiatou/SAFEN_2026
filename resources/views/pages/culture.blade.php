@extends('layouts.app')

@section('title', 'Culture Béninoise - AFRI-HERITAGE')

@section('content')
<section class="py-5">
    <div class="container">
        <h1 class="fw-bold text-center mb-5">Culture Béninoise</h1>

        <div class="row">
            <div class="col-lg-8">
                <h2 class="fw-bold mb-4">Découvrez la richesse culturelle du Bénin</h2>
                <p class="lead">
                    Le Bénin, berceau de la civilisation vodoun, regorge d'une diversité culturelle exceptionnelle.
                    Chaque ethnie, chaque région a ses traditions, sa musique, ses danses et son artisanat unique.
                </p>

                <div class="row mt-5">
                    <div class="col-md-6 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <img src="https://images.unsplash.com/photo-1578946956088-940c3b502864?q=80&w=2070" class="card-img-top" alt="Artisanat">
                            <div class="card-body">
                                <h5 class="card-title fw-bold">Artisanat Traditionnel</h5>
                                <p class="card-text">Découvrez l'artisanat béninois : sculpture sur bois, poterie, tissage, et bien plus.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <img src="https://images.unsplash.com/photo-1518837695005-2083093ee35b?q=80&w=2070" class="card-img-top" alt="Musique">
                            <div class="card-body">
                                <h5 class="card-title fw-bold">Musique et Danse</h5>
                                <p class="card-text">Les rythmes et danses traditionnelles du Bénin, une expérience unique.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title fw-bold">Anecdotes Culturelles</h5>
                        <p class="card-text">
                            <strong>Le masque Guèlèdè</strong> : Patrimoine de l'UNESCO, ces masques célèbrent les femmes et la maternité dans la culture Yoruba.
                        </p>
                        <p class="card-text">
                            <strong>La Route de l'Esclave</strong> : Ouidah, classée au patrimoine mondial de l'UNESCO, est un lieu de mémoire de la traite négrière.
                        </p>
                        <p class="card-text">
                            <strong>Les Tata Somba</strong> : Ces maisons-forteresses en terre du peuple Bétammaribé sont uniques au monde.
                        </p>
                        <a href="#" class="btn btn-benin-green w-100">Poser une question à Anansi</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
