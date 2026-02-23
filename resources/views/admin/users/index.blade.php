@extends('layouts.admin')

@section('title', 'Gestion des utilisateurs')

@section('content')
<div class="section-header">
    <h1>Gestion des utilisateurs</h1>
    <div class="section-header-breadcrumb">
        <div class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></div>
        <div class="breadcrumb-item active">Utilisateurs</div>
    </div>
</div>

<div class="section-body">
    <!-- Statistiques -->
    <div class="row">
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-primary">
                    <i class="fas fa-users"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Total utilisateurs</h4>
                    </div>
                    <div class="card-body">
                        {{ $stats['total'] ?? 0 }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-success">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Nouveaux ce mois</h4>
                    </div>
                    <div class="card-body">
                        {{ $stats['active_this_month'] ?? 0 }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-warning">
                    <i class="fas fa-hands"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Artisans</h4>
                    </div>
                    <div class="card-body">
                        {{ $stats['artisans'] ?? 0 }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
            <div class="card card-statistic-1">
                <div class="card-icon bg-danger">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="card-wrap">
                    <div class="card-header">
                        <h4>Administrateurs</h4>
                    </div>
                    <div class="card-body">
                        {{ $stats['admins'] ?? 0 }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bouton Ajouter + DataTable -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Liste des utilisateurs</h4>
                    <div class="card-header-action">
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Ajouter un utilisateur
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="users-table" class="table table-striped table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Utilisateur</th>
                                    <th>Email</th>
                                    <th>Rôle(s)</th>
                                    <th>Statut</th>
                                    <th>Inscrit le</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<!-- DataTables CSS -->
<link rel="stylesheet" href="{{ asset('admin-assets/bundles/datatables/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('admin-assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap4.min.css">
<style>
    .badge-success { background-color: #28a745; color: #fff; }
    .badge-danger { background-color: #dc3545; color: #fff; }
    .badge-warning { background-color: #ffc107; color: #212529; }
    .badge-info { background-color: #17a2b8; color: #fff; }
    .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #6777ef;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 16px;
    }
    .user-info {
        display: flex;
        align-items: center;
    }
    .user-info img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 10px;
    }
    .user-info .avatar-circle {
        margin-right: 10px;
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('admin-assets/bundles/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('admin-assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
    $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.users.index') }}",
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            {
                data: 'full_name',
                name: 'name',
                render: function(data, type, row) {
                    // Afficher l'avatar ou les initiales
                    if (row.avatar) {
                        return '<div class="user-info"><img src="' + row.avatar_url + '" alt=""><span>' + data + '</span></div>';
                    } else {
                        // Calculer les initiales (on suppose que le backend fournit aussi les initiales ou on les calcule ici)
                        var initials = (row.name || '').split(' ').map(word => word.charAt(0).toUpperCase()).join('').substring(0,2);
                        return '<div class="user-info"><div class="avatar-circle">' + initials + '</div><span>' + data + '</span></div>';
                    }
                }
            },
            { data: 'email', name: 'email' },
            { data: 'roles', name: 'roles.name' },
            { data: 'status', name: 'is_active' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json'
        },
        order: [[5, 'desc']],
        pageLength: 10,
        dom: 'Bfrtip',
        buttons: [
            { extend: 'copy', text: '<i class="fas fa-copy"></i> Copier', className: 'btn btn-sm btn-secondary' },
            { extend: 'excel', text: '<i class="fas fa-file-excel"></i> Excel', className: 'btn btn-sm btn-success' },
            { extend: 'pdf', text: '<i class="fas fa-file-pdf"></i> PDF', className: 'btn btn-sm btn-danger' },
            { extend: 'print', text: '<i class="fas fa-print"></i> Imprimer', className: 'btn btn-sm btn-info' }
        ]
    });

    // Suppression avec SweetAlert
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        var userId = $(this).data('id');

        Swal.fire({
            title: 'Confirmer la suppression ?',
            text: "Cette action est irréversible !",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, supprimer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url("admin/users") }}/' + userId,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire('Supprimé !', 'L\'utilisateur a été supprimé.', 'success');
                        $('#users-table').DataTable().ajax.reload();
                    },
                    error: function(xhr) {
                        Swal.fire('Erreur', xhr.responseJSON?.message || 'Une erreur est survenue.', 'error');
                    }
                });
            }
        });
    });
});
</script>
@endpush
