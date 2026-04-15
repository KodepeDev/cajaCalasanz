<div class="pt-3">

    {{-- Loading overlay --}}
    <div wire:loading.flex class="position-fixed w-100 h-100 justify-content-center align-items-center"
        style="top:0;left:0;z-index:9999;background:rgba(0,0,0,.35);">
        <div class="text-white text-center">
            <i class="fas fa-3x fa-circle-notch fa-spin"></i>
            <div class="mt-2 font-weight-bold">Procesando...</div>
        </div>
    </div>

    <div class="card card-primary">

        {{-- Card header --}}
        <div class="card-header d-flex align-items-center flex-wrap" style="gap:.5rem;">
            <div class="d-flex align-items-center flex-fill">
                <i class="fas fa-sliders-h mr-2"></i>
                <h3 class="card-title mb-0">Provisiones Variables</h3>
            </div>
            <div class="d-flex" style="gap:.4rem;">
                <button type="button" class="btn btn-sm btn-secondary" wire:click="toggleDirection"
                    title="Ordenar por nombre">
                    <i class="fas fa-sort-alpha-{{ $direction === 'asc' ? 'down' : 'up' }}"></i>
                </button>
                <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modalEliminarFija">
                    <i class="fas fa-trash mr-1"></i> Eliminar
                </button>
                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalProvisionFija">
                    <i class="fas fa-plus-circle mr-1"></i> Nuevo
                </button>
                <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#modalProvisionExport">
                    <i class="fas fa-download mr-1"></i> Exportar
                </button>
            </div>
        </div>

        {{-- Filters --}}
        <div class="card-body pb-0">
            @include('livewire.movimientos.provisiones.searBoxProvision')
        </div>

        {{-- Table --}}
        <div class="card-body pt-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm mb-0">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th style="width:80px">MES</th>
                            <th>DESCRIPCIÓN</th>
                            <th style="width:160px">CATEGORÍA</th>
                            <th>ESTUDIANTE</th>
                            <th class="text-center" style="width:200px">MONTO</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($detalles as $item)
                            <tr class="{{ $selected_id == $item->id ? 'table-warning' : '' }}">

                                {{-- Mes --}}
                                <td class="text-center text-nowrap align-middle">
                                    <span class="badge badge-secondary">
                                        {{ $item->date->format('m/Y') }}
                                    </span>
                                </td>

                                {{-- Descripción --}}
                                <td class="align-middle">
                                    @if ($selected_id == $item->id)
                                        <input type="text" class="form-control form-control-sm"
                                            wire:model.defer="description">
                                    @else
                                        {{ $item->description }}
                                    @endif
                                </td>

                                {{-- Categoría --}}
                                <td class="align-middle">
                                    <span class="badge badge-light border">
                                        {{ $item->category->name }}
                                    </span>
                                </td>

                                {{-- Estudiante --}}
                                <td class="align-middle">
                                    {{ $item->student->full_name ?? 'NO DEFINIDO' }}
                                </td>

                                {{-- Monto (inline edit) --}}
                                <td class="text-center align-middle">
                                    @if ($selected_id == $item->id)
                                        <div class="d-flex align-items-center" style="gap:.25rem;">
                                            <input type="number" class="form-control form-control-sm"
                                                style="min-width:90px;"
                                                wire:model.defer="amount"
                                                wire:focusout="update"
                                                onfocus="this.select();">
                                            <button type="button" wire:click="update"
                                                class="btn btn-xs btn-success" title="Guardar">
                                                <i class="fa fa-check"></i>
                                            </button>
                                            <button type="button" wire:click="deleteRow({{ $item->id }})"
                                                class="btn btn-xs btn-danger" title="Eliminar">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                        @error('amount')
                                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                                        @enderror
                                    @else
                                        <div class="d-flex align-items-center justify-content-center" style="gap:.25rem;">
                                            <input type="number" class="form-control form-control-sm"
                                                style="min-width:90px;"
                                                value="{{ $item->amount }}"
                                                wire:focusin="edit({{ $item->id }})"
                                                readonly>
                                            <button type="button" wire:click="edit({{ $item->id }})"
                                                class="btn btn-xs btn-primary" title="Editar">
                                                <i class="fa fa-pen"></i>
                                            </button>
                                            <button type="button" wire:click="deleteRow({{ $item->id }})"
                                                class="btn btn-xs btn-danger" title="Eliminar">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    @endif
                                    <small class="text-muted">{{ $item->currency->name ?? 'S/.' }}</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5" style="background:#fafafa;">
                                    <i class="fas fa-sliders-h fa-2x d-block mb-2 text-muted" style="opacity:.35;"></i>
                                    <span class="text-muted">No hay provisiones variables para el período seleccionado.</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5">{{ $detalles->links() }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Footer totals --}}
        <div class="card-footer">
            <div class="d-flex justify-content-end align-items-center" style="gap:1rem;">
                @if ($total > 0)
                    <div class="text-center">
                        <div class="text-muted" style="font-size:.7rem;text-transform:uppercase;letter-spacing:.5px;">Total Soles</div>
                        <span class="badge badge-pill badge-info px-3" style="font-size:.85rem;">
                            S/. {{ number_format($total, 2) }}
                        </span>
                    </div>
                @endif
                @if ($totalDolar > 0)
                    <div class="text-center">
                        <div class="text-muted" style="font-size:.7rem;text-transform:uppercase;letter-spacing:.5px;">Total Dólares</div>
                        <span class="badge badge-pill badge-warning px-3" style="font-size:.85rem;">
                            $. {{ number_format($totalDolar, 2) }}
                        </span>
                    </div>
                @endif
                @if ($total == 0 && $totalDolar == 0)
                    <span class="text-muted small">Sin movimientos en el período</span>
                @endif
            </div>
        </div>
    </div>

    @include('livewire.movimientos.provisiones.fijas.form')
    @include('livewire.movimientos.provisiones.fijas.form-delete')
    @include('livewire.movimientos.provisiones.variables.modal-variable-export')

</div>

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const alertError = msg => Swal.fire({ icon: 'error', title: 'Error', text: msg });

        window.livewire.on('error', alertError);

        window.livewire.on('provision_agregado', msg => {
            Swal.fire({ icon: 'success', title: '¡Registrado!', text: msg });
            $('#modalProvisionFija').modal('hide');
        });

        window.livewire.on('provision_eliminado', msg => {
            Swal.fire({ icon: 'success', title: '¡Eliminado!', text: msg });
            $('#modalEliminarFija').modal('hide');
        });

        $('#modalProvisionFija').on('show.bs.modal', () => livewire.emit('resetUI'));
        $('#modalEliminarFija').on('show.bs.modal', () => livewire.emit('resetUI'));
        $('#modalProvisionExport').on('show.bs.modal', () => livewire.emit('resetUI'));
    });
</script>
@endpush
