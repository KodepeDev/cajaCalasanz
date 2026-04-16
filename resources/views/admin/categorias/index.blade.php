@extends('adminlte::page')
@section('title', 'Categorías')

@section('content')
<div class="container-fluid pt-4">

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="card card-primary">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <i class="fas fa-tags mr-2"></i>
                <h3 class="card-title mb-0">Categorías / Conceptos</h3>
            </div>
            @can('categorias.create')
                <a class="btn btn-warning btn-sm" href="/admin/categories/create">
                    <i class="fa fa-plus mr-1"></i> Nueva Categoría
                </a>
            @endcan
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="categories" class="table table-bordered table-hover table-sm">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th style="width:50px">#</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th style="width:150px">Tipo</th>
                            <th style="width:80px" class="text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td class="font-weight-bold">{{ $category->name }}</td>
                                <td class="text-muted small">{{ $category->description ?? '—' }}</td>
                                <td>
                                    @if ($category->type === 'add')
                                        <span class="badge badge-success px-2">
                                            <i class="fas fa-arrow-up mr-1"></i> Ingreso
                                        </span>
                                    @else
                                        <span class="badge badge-danger px-2">
                                            <i class="fas fa-arrow-down mr-1"></i> Gasto
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        @can('categorias.edit')
                                            <a class="btn btn-outline-primary"
                                                href="/admin/categories/edit/{{ $category->id }}"
                                                title="Editar">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        @endcan
                                        @can('categorias.delete')
                                            @if ($category->details_count < 1)
                                                <button type="button"
                                                    class="btn btn-outline-danger btn-delete"
                                                    data-id="{{ $category->id }}"
                                                    data-name="{{ $category->name }}"
                                                    title="Eliminar">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-outline-secondary" disabled
                                                    title="Tiene {{ $category->details_count }} detalles asociados">
                                                    <i class="fa fa-lock"></i>
                                                </button>
                                            @endif
                                        @endcan
                                    </div>

                                    {{-- Hidden delete form --}}
                                    <form id="delete-form-{{ $category->id }}"
                                        action="/admin/categories/delete/{{ $category->id }}"
                                        method="POST" class="d-none">
                                        @method('DELETE')
                                        @csrf
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {

        $('#categories').DataTable({
            order: [[0, 'asc']],
            dom: 'Bfrtip',
            responsive: true,
            language: { url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json' },
            buttons: ['pdf', 'excel', 'copy'],
        });

        document.querySelectorAll('.btn-delete').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const id   = this.dataset.id;
                const name = this.dataset.name;

                Swal.fire({
                    title: '¿Eliminar categoría?',
                    html: `<b>${name}</b> será eliminada permanentemente.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                }).then(function (result) {
                    if (result.isConfirmed) {
                        document.getElementById('delete-form-' + id).submit();
                    }
                });
            });
        });

    });
</script>
@stop
