@extends('layouts.admin')

@section('title', 'Configuration')

@section('content')
<div class="section-header">
    <h1>Configuration</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item">Configuration</div>
    </div>
</div>

<div class="section-body">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <a href="{{ route('admin.settings.index', ['section' => 'general']) }}" class="btn btn-sm {{ request('section','general')=='general' ? 'btn-primary' : 'btn-outline-primary' }}">Générales</a>
                <a href="{{ route('admin.settings.index', ['section' => 'payments']) }}" class="btn btn-sm {{ request('section')=='payments' ? 'btn-primary' : 'btn-outline-primary' }}">Paiements</a>
                <a href="{{ route('admin.settings.index', ['section' => 'notifications']) }}" class="btn btn-sm {{ request('section')=='notifications' ? 'btn-primary' : 'btn-outline-primary' }}">Notifications</a>
            </div>

            @php $section = request('section','general'); @endphp

            @if($section === 'general')
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="section" value="general">
                    <div class="form-group">
                        <label>Nom du site</label>
                        <input type="text" name="site_name" class="form-control" value="{{ old('site_name', data_get($general,'site_name.0', 'SAFEN')) }}">
                    </div>
                    <div class="form-group">
                        <label>Slogan / Description</label>
                        <textarea name="site_description" class="form-control">{{ old('site_description', data_get($general,'site_description.0', '')) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Langue par défaut</label>
                        <select name="default_locale" class="form-control">
                            <option value="fr" {{ (data_get($general,'default_locale.0')=='fr')? 'selected':'' }}>Français</option>
                            <option value="en" {{ (data_get($general,'default_locale.0')=='en')? 'selected':'' }}>English</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Mode maintenance</label>
                        <select name="maintenance_mode" class="form-control">
                            <option value="0" {{ (data_get($general,'maintenance_mode.0')=='0')? 'selected':'' }}>Off</option>
                            <option value="1" {{ (data_get($general,'maintenance_mode.0')=='1')? 'selected':'' }}>On</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Message de maintenance</label>
                        <textarea name="maintenance_message" class="form-control">{{ old('maintenance_message', data_get($general,'maintenance_message.0','')) }}</textarea>
                    </div>
                    <button class="btn btn-primary">Enregistrer</button>
                </form>
            @elseif($section === 'payments')
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="section" value="payments">
                    <div class="form-group">
                        <label>Modes de paiement activés (séparés par virgule)</label>
                        <input type="text" name="enabled_methods" class="form-control" value="{{ old('enabled_methods', implode(',', data_get($payments,'enabled_methods.0', []))) }}">
                    </div>
                    <div class="form-group">
                        <label>Clé Kkiapay</label>
                        <input type="text" name="kkiapay_key" class="form-control" value="{{ old('kkiapay_key', data_get($payments,'kkiapay_key.0','')) }}">
                    </div>
                    <div class="form-group">
                        <label>Secret Kkiapay</label>
                        <input type="text" name="kkiapay_secret" class="form-control" value="{{ old('kkiapay_secret', data_get($payments,'kkiapay_secret.0','')) }}">
                    </div>
                    <div class="form-group">
                        <label>Devise par défaut</label>
                        <input type="text" name="currency" class="form-control" value="{{ old('currency', data_get($payments,'currency.0','XOF')) }}">
                    </div>
                    <button class="btn btn-primary">Enregistrer</button>
                </form>
            @elseif($section === 'notifications')
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="section" value="notifications">
                    <div class="form-group">
                        <label>Canaux activés (séparés par virgule)</label>
                        <input type="text" name="channels" class="form-control" value="{{ old('channels', implode(',', data_get($notifications,'channels.0', []))) }}">
                    </div>
                    <div class="form-group">
                        <label>From email</label>
                        <input type="email" name="from_email" class="form-control" value="{{ old('from_email', data_get($notifications,'from_email.0', config('mail.from.address'))) }}">
                    </div>

                    @php
                        $tpls = $templates ?? [];
                        $defaultOrderSubject = 'Votre commande';
                        $defaultOrderBody = 'Bonjour {{user.name}}, votre commande {{order.id}} a été reçue.';
                    @endphp

                    <h5>Templates</h5>
                    <div class="form-group">
                        <label>Order Created - Subject</label>
                        <input type="text" name="templates[order_created][subject]" class="form-control" value="{{ old('templates.order_created.subject', data_get($tpls,'order_created.subject', $defaultOrderSubject)) }}">
                    </div>
                    <div class="form-group">
                        <label>Order Created - Body</label>
                        <textarea name="templates[order_created][body]" class="form-control" rows="5">{{ old('templates.order_created.body', data_get($tpls,'order_created.body', $defaultOrderBody)) }}</textarea>
                    </div>

                    <button class="btn btn-primary">Enregistrer</button>
                </form>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    function getQuery(name){ const params = new URLSearchParams(window.location.search); return params.get(name); }

    var section = getQuery('section');
    // activate tab from query param if present (client fallback)
    if (section) {
        var anchors = document.querySelectorAll('#settingsTabs a');
        anchors.forEach(function(a){
            var href = a.getAttribute('href') || '';
            if (href.indexOf('section=' + section) !== -1 || href === ('#' + section)) {
                if (window.jQuery && jQuery.fn.tab) {
                    jQuery(a).tab('show');
                } else {
                    // mark active
                    anchors.forEach(function(t){ t.classList.remove('active'); });
                    a.classList.add('active');
                    document.querySelectorAll('.tab-pane').forEach(function(p){ p.classList.remove('show','active'); });
                    var pane = document.getElementById(section);
                    if (pane) pane.classList.add('show','active');
                }
            }
        });
    }

    // Only intercept fragment-only links (hrefs that start with '#') to avoid blocking normal navigation
    var tabs = document.querySelectorAll('#settingsTabs a');
    tabs.forEach(function(a){
        a.addEventListener('click', function(e){
            var href = this.getAttribute('href') || '';
            if (href.charAt(0) !== '#') {
                // allow default navigation for links that are full URLs (server will render correct tab)
                return;
            }
            e.preventDefault();
            var sec = href.replace('#','');
            if (window.jQuery && jQuery.fn.tab) {
                jQuery(this).tab('show');
            } else {
                tabs.forEach(function(t){ t.classList.remove('active'); });
                this.classList.add('active');
                document.querySelectorAll('.tab-pane').forEach(function(p){ p.classList.remove('show','active'); });
                var pane = document.getElementById(sec);
                if (pane) pane.classList.add('show','active');
            }
            var url = new URL(window.location);
            url.searchParams.set('section', sec);
            url.hash = '';
            history.replaceState(null, '', url.toString());
        });
    });
});
</script>
@endpush

@endsection
