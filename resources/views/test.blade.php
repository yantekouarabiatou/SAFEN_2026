@extends('layouts.app')

@section('title', 'Test Multilangue')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h1>{{ __('messages.language') }}: {{ strtoupper(app()->getLocale()) }}</h1>
                </div>
                <div class="card-body">
                    <h2>{{ __('messages.home') }}</h2>
                    <p>{{ __('artisans.title') }}</p>
                    <p>{{ __('artisans.subtitle') }}</p>

                    <hr>

                    <h3>{{ __('messages.search') }}</h3>
                    <p>{{ __('artisans.search_placeholder') }}</p>

                    <hr>

                    <h3>{{ __('artisans.filter_by_craft') }}</h3>
                    <ul>
                        @foreach(__('artisans.crafts') as $key => $craft)
                            <li>{{ $craft }}</li>
                        @endforeach
                    </ul>

                    <hr>

                    <div class="mt-4">
                        <a href="{{ route('lang.switch', 'fr') }}" class="btn btn-primary me-2">Français</a>
                        <a href="{{ route('lang.switch', 'en') }}" class="btn btn-secondary">English</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection