@extends('adminlte::page')
@section('title', 'Cuentas')

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

    <div class="card card-maroon">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <i class="fas fa-university mr-2"></i>
                <h3 class="card-title mb-0">Cuentas Bancarias / Caja</h3>
            </div>
            @can('cuentas.create')
                <a class="btn btn-warning btn-sm" href="{{ route('account.create') }}">
                    <i class="fa fa-plus mr-1"></i> Nueva Cuenta
                </a>
            @endcan
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="accounts" class="table table-bordered table-hover table-sm">
                    <thead class="bg-maroon text-white">
                        <tr>
                            <th style="width:50px">#</th>
                            <th>Nombre</th>
                            <th>N° de Cuenta</th>
                            <th style="width:110px" class="text-center">Serie Ingreso</th>
                            <th style="width:110px" class="text-center">Serie Gasto</th>
                            <th style="width:100px">Tipo</th>
                            <th style="width:110px" class="text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($accounts as $account)
                            <tr>
                                <td>{{ $account->id }}</td>
                                <td class="font-weight-bold">{{ $account->account_name }}</td>
                                <td class="text-muted">{{ $account->account_number ?? '—' }}</td>
                                <td class="text-center">
                                    <span class="badge badge-success px-2">{{ $account->add_serie }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-danger px-2">{{ $account->out_serie }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-secondary text-capitalize px-2">
                                        {{ $account->account_type }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        @can('cuentas.show')
                                            <a class="btn btn-outline-info"
                                                href="{{ route('account.show', $account->id) }}"
                                                title="Ver movimientos">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        @endcan
                                        @can('cuentas.edit')
                                            <a class="btn btn-outline-primary"
                                                href="{{ route('account.edit', $account->id) }}"
                                                title="Editar">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        @endcan
                                        @can('cuentas.delete')
                                            @if ($account->summaries_count < 1)
                                                <button type="button"
                                                    class="btn btn-outline-danger btn-delete"
                                                    data-id="{{ $account->id }}"
                                                    data-name="{{ $account->account_name }}"
                                                    title="Eliminar">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-outline-secondary" disabled
                                                    title="{{ $account->summaries_count }} movimientos asociados">
                                                    <i class="fa fa-lock"></i>
                                                </button>
                                            @endif
                                        @endcan
                                    </div>

                                    <form id="delete-form-{{ $account->id }}"
                                        action="{{ route('account.destroy', $account->id) }}"
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

        $('#accounts').DataTable({
            order: [[0, 'asc']],
            dom: 'Bfrtip',
            responsive: true,
            language: { url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json' },
            buttons: ['pdf', 'excel'],
        });

        document.querySelectorAll('.btn-delete').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const id   = this.dataset.id;
                const name = this.dataset.name;

                Swal.fire({
                    title: '¿Eliminar cuenta?',
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
