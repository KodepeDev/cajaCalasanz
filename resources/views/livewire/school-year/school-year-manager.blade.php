<div>

    {{-- Alertas de operación --}}
    @if($successMessage)
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="icon fas fa-check"></i> {{ $successMessage }}
    </div>
    @endif
    @if($errorMessage)
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <i class="icon fas fa-ban"></i> {{ $errorMessage }}
    </div>
    @endif

    {{-- ── Corrección de movimientos huérfanos ─────────────────────── --}}
    @if($orphanCount > 0)
    <div class="callout callout-warning">
        <h5><i class="fas fa-exclamation-triangle mr-1"></i> Movimientos sin año escolar</h5>
        <p>
            Se encontraron <strong>{{ $orphanCount }}</strong> movimiento(s) sin año escolar asignado,
            registrados antes de que esta funcionalidad existiera.
            Selecciona el año destino y corrígelos para que aparezcan en los filtros correctamente.
        </p>
        <div class="form-row align-items-end">
            <div class="col-md-4">
                <div class="form-group mb-0">
                    <label>Año escolar destino</label>
                    <select wire:model="backfillYearId" class="form-control form-control-sm">
                        <option value="">— Seleccionar —</option>
                        @foreach($schoolYears as $sy)
                            <option value="{{ $sy->id }}">
                                {{ $sy->year }}{{ $sy->is_active ? ' (Activo)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <button wire:click="backfillSummaries"
                        wire:confirm="¿Asignar los {{ $orphanCount }} movimientos huérfanos al año seleccionado?"
                        class="btn btn-warning btn-sm">
                    <i class="fas fa-tools mr-1"></i> Corregir {{ $orphanCount }} movimiento(s)
                </button>
            </div>
        </div>
    </div>
    @else
    <div class="callout callout-success">
        <h5><i class="fas fa-check-circle mr-1"></i> Sin pendientes</h5>
        <p class="mb-0">Todos los movimientos tienen año escolar asignado. No se requiere corrección.</p>
    </div>
    @endif

    {{-- ── Listado de años escolares ───────────────────────────────── --}}
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-calendar-alt mr-1"></i> Años Escolares
            </h3>
            <div class="card-tools">
                @if(!$showForm)
                <button wire:click="openCreate" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus mr-1"></i> Nuevo Año Escolar
                </button>
                @endif
            </div>
        </div>

        {{-- Formulario inline --}}
        @if($showForm)
        <div class="card-body border-bottom">
            <h5 class="mb-3">
                <i class="fas fa-{{ $editingId ? 'edit' : 'plus-circle' }} mr-1 text-primary"></i>
                {{ $editingId ? 'Editar Año Escolar' : 'Nuevo Año Escolar' }}
            </h5>
            <div class="form-row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Año <span class="text-danger">*</span></label>
                        <input type="number"
                               wire:model.defer="year"
                               class="form-control @error('year') is-invalid @enderror"
                               placeholder="Ej: 2026"
                               min="2000" max="2100">
                        @error('year')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Fecha de inicio</label>
                        <input type="date"
                               wire:model.defer="start_date"
                               class="form-control @error('start_date') is-invalid @enderror">
                        @error('start_date')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Fecha de fin</label>
                        <input type="date"
                               wire:model.defer="end_date"
                               class="form-control @error('end_date') is-invalid @enderror">
                        @error('end_date')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <div>
                            <button wire:click="save" class="btn btn-success btn-sm">
                                <i class="fas fa-save mr-1"></i> Guardar
                            </button>
                            <button wire:click="cancelForm" class="btn btn-default btn-sm ml-1">
                                <i class="fas fa-times mr-1"></i> Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="card-body table-responsive p-0">
            <table class="table table-bordered table-hover table-sm mb-0">
                <thead>
                    <tr>
                        <th>Año</th>
                        <th>Fecha inicio</th>
                        <th>Fecha fin</th>
                        <th>Movimientos</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schoolYears as $sy)
                    <tr class="{{ $sy->is_active ? 'bg-light' : '' }}">
                        <td class="align-middle">
                            <strong>{{ $sy->year }}</strong>
                        </td>
                        <td class="align-middle">
                            {{ $sy->start_date ? \Carbon\Carbon::parse($sy->start_date)->format('d/m/Y') : '—' }}
                        </td>
                        <td class="align-middle">
                            {{ $sy->end_date ? \Carbon\Carbon::parse($sy->end_date)->format('d/m/Y') : '—' }}
                        </td>
                        <td class="align-middle">
                            <span class="badge badge-secondary">
                                {{ $sy->summaries()->count() }}
                            </span>
                        </td>
                        <td class="align-middle">
                            @if($sy->is_active)
                                <span class="badge badge-success">
                                    <i class="fas fa-check-circle mr-1"></i>Activo
                                </span>
                            @else
                                <span class="badge badge-default">Inactivo</span>
                            @endif
                        </td>
                        <td class="align-middle text-center">
                            <div class="btn-group btn-group-sm">
                                <button wire:click="openEdit({{ $sy->id }})"
                                        class="btn btn-default btn-sm"
                                        title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>

                                @if(!$sy->is_active)
                                    <button wire:click="setActive({{ $sy->id }})"
                                            wire:confirm="¿Marcar {{ $sy->year }} como año escolar activo? Esto desactivará el año activo actual."
                                            class="btn btn-success btn-sm"
                                            title="Marcar como activo">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button wire:click="delete({{ $sy->id }})"
                                            wire:confirm="¿Eliminar el año escolar {{ $sy->year }}? Solo es posible si no tiene movimientos asignados."
                                            class="btn btn-danger btn-sm"
                                            title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @else
                                    <button class="btn btn-success btn-sm" disabled title="Año activo">
                                        <i class="fas fa-lock"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted p-4">
                            <i class="fas fa-calendar-times fa-2x mb-2 d-block"></i>
                            No hay años escolares registrados.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>
