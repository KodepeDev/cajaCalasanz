<div class="pt-3">
    <section class="content">
        <div class="container-fluid">
            <div class="row">

                {{-- ── Sidebar del estudiante ───────────────────────────────── --}}
                <div class="col-md-3 col-lg-2">

                    {{-- Perfil --}}
                    <div class="card card-danger card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <img class="profile-user-img img-fluid img-circle"
                                     src="{{ asset($student->foto) }}"
                                     alt="Foto de {{ $student->full_name }}">
                            </div>
                            <h3 class="profile-username text-center mt-2">{{ $student->full_name }}</h3>
                            <p class="text-muted text-center">
                                <i class="fas fa-user-graduate mr-1"></i>Detalle de Estudiante
                            </p>

                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>Pagos {{ $year }}</b>
                                    <span class="float-right {{ $suma2023 >= 0 ? 'text-success' : 'text-danger' }} font-weight-bold">
                                        S/. {{ number_format($suma2023, 2) }}
                                    </span>
                                </li>
                                <li class="list-group-item">
                                    <b>Pagos {{ $year - 1 }}</b>
                                    <span class="float-right {{ $suma2022 >= 0 ? 'text-success' : 'text-danger' }} font-weight-bold">
                                        S/. {{ number_format($suma2022, 2) }}
                                    </span>
                                </li>
                            </ul>

                            <a href="{{ route('students.index') }}" class="btn btn-default btn-block btn-sm">
                                <i class="fas fa-arrow-left mr-1"></i> Volver al listado
                            </a>
                        </div>
                    </div>

                    {{-- Info del estudiante --}}
                    <div class="card card-warning card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-info-circle mr-1"></i> Información
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">

                                <li class="list-group-item">
                                    <small class="text-muted d-block">
                                        <i class="fas fa-id-card mr-1"></i> Documento
                                    </small>
                                    @switch($student->document_type)
                                        @case(1) DNI @break
                                        @case(6) RUC @break
                                        @default  OTRO
                                    @endswitch
                                    — {{ $student->document }}
                                </li>

                                <li class="list-group-item">
                                    <small class="text-muted d-block">
                                        <i class="fas fa-user mr-1"></i> Nombre completo
                                    </small>
                                    {{ $student->first_name }} {{ $student->last_name }}
                                </li>

                                <li class="list-group-item">
                                    <small class="text-muted d-block">
                                        <i class="fas fa-layer-group mr-1"></i> Grado
                                    </small>
                                    {{ $student->grade->name }}
                                </li>

                                <li class="list-group-item">
                                    <small class="text-muted d-block">
                                        <i class="fas fa-map-marker-alt mr-1"></i> Dirección
                                    </small>
                                    {{ $student->address ?: '—' }}
                                </li>

                                <li class="list-group-item">
                                    <small class="text-muted d-block">
                                        <i class="fas fa-user-friends mr-1"></i> {{ $student->tutor->type }}
                                    </small>
                                    {{ $student->tutor->first_name }} {{ $student->tutor->last_name }}
                                </li>

                                @if($student->teacher)
                                <li class="list-group-item">
                                    <small class="text-muted d-block">
                                        <i class="fas fa-chalkboard-teacher mr-1"></i> Docente tutor
                                    </small>
                                    {{ $student->teacher->full_name }}
                                </li>
                                @endif

                            </ul>
                        </div>
                    </div>

                </div>
                {{-- /.sidebar --}}

                {{-- ── Panel principal ─────────────────────────────────────── --}}
                <div class="col-md-9 col-lg-10">

                    {{-- Info boxes de resumen --}}
                    <div class="row">
                        <div class="col-sm-6 col-lg-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-success elevation-1">
                                    <i class="fas fa-check-circle"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Pagado (S/.)</span>
                                    <span class="info-box-number">{{ number_format($sumaTotal, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-danger elevation-1">
                                    <i class="fas fa-clock"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Pendiente (S/.)</span>
                                    <span class="info-box-number">{{ number_format($sumaTotalPendiente, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info elevation-1">
                                    <i class="fas fa-dollar-sign"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Pagado ($)</span>
                                    <span class="info-box-number">{{ number_format($sumaTotalDolar, 2) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning elevation-1">
                                    <i class="fas fa-hourglass-half"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Pendiente ($)</span>
                                    <span class="info-box-number">{{ number_format($sumaTotalPendienteDolar, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tabla de movimientos --}}
                    <div class="card card-warning card-outline">

                        <div wire:loading.class="overlay" class="d-none" wire:loading.class.remove="d-none">
                            <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                        </div>

                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-list-alt mr-1"></i> Pagos y Deudas
                                <span class="badge badge-warning ml-1">{{ $year }}</span>
                            </h3>
                            <div class="card-tools">
                                @if($url)
                                    <a href="{{ $url }}" target="_blank" class="btn btn-danger btn-sm mr-1">
                                        <i class="fas fa-file-pdf mr-1"></i> PDF
                                    </a>
                                @endif
                                <button class="btn btn-primary btn-sm"
                                        data-toggle="modal" data-target="#nuevoRegistro">
                                    <i class="fas fa-plus mr-1"></i> Nuevo
                                </button>
                            </div>
                        </div>

                        <div class="card-body">

                            {{-- Filtro de fechas --}}
                            <div class="row mb-3">
                                <div class="col-sm-4">
                                    <div class="form-group mb-0">
                                        <label class="mb-1"><small>Fecha inicio</small></label>
                                        <input type="date"
                                               wire:model.defer="start1"
                                               class="form-control form-control-sm">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group mb-0">
                                        <label class="mb-1"><small>Fecha fin</small></label>
                                        <input type="date"
                                               wire:model.defer="finish1"
                                               class="form-control form-control-sm">
                                    </div>
                                </div>
                                <div class="col-sm-4 d-flex align-items-end">
                                    <div class="btn-group btn-block">
                                        <button wire:click.prevent="Filter"
                                                class="btn btn-info btn-sm">
                                            <i class="fas fa-filter mr-1"></i> Filtrar
                                        </button>
                                        <button wire:click.prevent="clearFilter"
                                                class="btn btn-default btn-sm">
                                            <i class="fas fa-broom mr-1"></i> Limpiar
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- Tabla --}}
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-sm mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th style="width:30%">Concepto</th>
                                            <th class="text-center" style="width:18%">Categoría</th>
                                            <th class="text-center" style="width:10%">Periodo</th>
                                            <th class="text-center" style="width:12%">Estado</th>
                                            <th class="text-center" style="width:15%">Importe</th>
                                            <th class="text-center" style="width:15%">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($movimientos as $item)
                                            <tr>
                                                <td class="align-middle">{{ $item->description }}</td>

                                                <td class="text-center align-middle">
                                                    {{ $item->category->name }}
                                                </td>

                                                <td class="text-center align-middle">
                                                    {{ $item->date->format('m/Y') }}
                                                </td>

                                                <td class="text-center align-middle">
                                                    @if($item->status == 1)
                                                        <span class="badge badge-success">
                                                            <i class="fas fa-check mr-1"></i>Pagado
                                                        </span>
                                                        <br>
                                                        <small class="text-muted">{{ $item->date_paid->format('d/m/Y') }}</small>
                                                    @else
                                                        <span class="badge badge-danger">
                                                            <i class="fas fa-clock mr-1"></i>Pendiente
                                                        </span>
                                                    @endif
                                                </td>

                                                <td class="text-center align-middle">
                                                    <span class="font-weight-bold">
                                                        {{ number_format($item->amount, 2) }}
                                                    </span>
                                                    <span class="badge {{ $item->summary_type == 'add' ? 'badge-success' : 'badge-danger' }} ml-1">
                                                        {{ $item->currency->name ?? 'S/.' }}
                                                        <i class="fas fa-arrow-{{ $item->summary_type == 'add' ? 'up' : 'down' }}"></i>
                                                    </span>
                                                </td>

                                                <td class="text-center align-middle">
                                                    @if($item->status == 0)
                                                        <div class="btn-group btn-group-sm">
                                                            <button onclick="editar({{ $item->id }})"
                                                                    class="btn btn-primary btn-sm"
                                                                    title="Editar">
                                                                <i class="fas fa-pen"></i>
                                                            </button>
                                                            <button onclick="eliminar({{ $item->id }})"
                                                                    class="btn btn-danger btn-sm"
                                                                    title="Eliminar">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted p-4">
                                                    <i class="fas fa-inbox fa-2x d-block mb-2"></i>
                                                    No se encontró ningún registro.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot class="thead-light">
                                        <tr>
                                            <th colspan="6">
                                                {{ $movimientos->links() }}
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                        </div>
                    </div>
                    {{-- /.card --}}

                </div>
                {{-- /.col principal --}}

            </div>
        </div>
    </section>

    @livewire('socios.detalles.nuevo-registro', ['student' => $student], key($student->id))
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        window.livewire.on('error', msg => {
            Swal.fire({ icon: 'error', title: 'Opss...!', text: msg });
        });

        window.livewire.on('error_fecha', msg => {
            Swal.fire({ icon: 'error', title: 'Oops!', text: msg });
        });

        window.livewire.on('showDetail', () => {
            $('#modalRegistro').modal('show');
        });

        window.livewire.on('close_modal', () => {
            $('#modalRegistro').modal('hide');
        });

        window.livewire.on('eliminado', () => {
            Swal.fire('Eliminado!', 'Tu registro se eliminó exitosamente.', 'success');
        });

    });

    function editar(id) {
        livewire.emit('editMovimiento', id);
    }

    function eliminar(id) {
        Swal.fire({
            title: '¿Eliminar este registro?',
            text: 'Recuerde que ya no podrá ser recuperado.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
        }).then(result => {
            if (result.isConfirmed) {
                livewire.emit('eliminarDetalle', id);
            }
        });
    }
</script>
