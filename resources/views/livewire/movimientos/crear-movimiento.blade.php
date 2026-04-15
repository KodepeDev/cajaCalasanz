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
        <div class="card-header d-flex align-items-center">
            <i class="fa fa-file-invoice-dollar mr-2"></i>
            <div>
                <h3 class="card-title mb-0">Nuevo Movimiento</h3>
                <small class="text-light opacity-75">
                    Las provisiones en dólares están sujetas al tipo de cambio de la fecha de cobro
                </small>
            </div>
        </div>

        {{-- ── Indicador de pasos ── --}}
        <div class="px-4 pt-3 pb-2 border-bottom" style="background:#f8f9fa;">
            <div class="d-flex align-items-center" style="font-size:.82rem;">

                {{-- Paso 1 --}}
                <div class="d-flex align-items-center {{ $student_id ? 'text-muted' : 'text-primary font-weight-bold' }}">
                    <span class="d-flex align-items-center justify-content-center rounded-circle mr-2"
                        style="width:28px;height:28px;font-size:.78rem;flex-shrink:0;
                               background:{{ $student_id ? '#28a745' : '#007bff' }};color:#fff;">
                        @if($student_id) <i class="fas fa-check fa-xs"></i> @else 1 @endif
                    </span>
                    <span class="d-none d-sm-inline">Buscar alumno</span>
                </div>

                <div class="flex-fill mx-2 border-top {{ $student_id ? 'border-success' : '' }}" style="height:2px;min-width:20px;"></div>

                {{-- Paso 2 --}}
                @php $hasItems = count($provisionsCobrar) > 0 || count($provisions) > 0; @endphp
                <div class="d-flex align-items-center
                    {{ !$student_id ? 'text-muted' : ($hasItems ? 'text-muted' : 'text-warning font-weight-bold') }}">
                    <span class="d-flex align-items-center justify-content-center rounded-circle mr-2"
                        style="width:28px;height:28px;font-size:.78rem;flex-shrink:0;
                               background:{{ !$student_id ? '#6c757d' : ($hasItems ? '#28a745' : '#ffc107') }};
                               color:{{ !$student_id || $hasItems ? '#fff' : '#212529' }};">
                        @if($student_id && $hasItems) <i class="fas fa-check fa-xs"></i> @else 2 @endif
                    </span>
                    <span class="d-none d-sm-inline">Seleccionar provisiones</span>
                </div>

                <div class="flex-fill mx-2 border-top {{ $hasItems ? 'border-success' : '' }}" style="height:2px;min-width:20px;"></div>

                {{-- Paso 3 --}}
                <div class="d-flex align-items-center text-muted">
                    <span class="d-flex align-items-center justify-content-center rounded-circle mr-2"
                        style="width:28px;height:28px;font-size:.78rem;flex-shrink:0;background:#6c757d;color:#fff;">3</span>
                    <span class="d-none d-sm-inline">Emitir recibo</span>
                </div>
            </div>
        </div>

        <div class="card-body">

            {{-- ── Buscar provisión ── --}}
            <div class="row align-items-end mb-4 pb-3 border-bottom">
                <div class="col-md-5">
                    <label class="col-form-label-sm font-weight-bold text-uppercase text-muted">
                        Buscar por DNI / Código de Alumno
                    </label>
                    <div class="input-group">
                        <input type="text" maxlength="200"
                            class="form-control @error('provision_code') is-invalid @enderror"
                            placeholder="Ingrese DNI o código"
                            wire:model.lazy="provision_code"
                            id="provision_code">
                        <div class="input-group-append">
                            <button class="btn btn-info" type="button"
                                wire:click="$emit('buscar_provision')"
                                wire:loading.attr="disabled">
                                <i class="fa fa-search mr-1"></i> Buscar
                            </button>
                        </div>
                        @error('provision_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- ── Panel de información del alumno ── --}}
            @if ($student_id)
                <div class="d-flex align-items-center mb-4 p-3 rounded"
                    style="background:linear-gradient(135deg,#e8f4fd,#e8f5e9);border-left:4px solid #1976d2;">
                    <div class="d-flex align-items-center justify-content-center rounded-circle mr-3 flex-shrink-0"
                        style="width:46px;height:46px;background:#1976d2;">
                        <i class="fas fa-user-graduate text-white fa-lg"></i>
                    </div>
                    <div class="flex-fill">
                        <div class="font-weight-bold" style="font-size:.98rem;line-height:1.2;">
                            {{ $student_name }}
                        </div>
                        <div class="text-muted" style="font-size:.8rem;margin-top:2px;">
                            <i class="fas fa-user mr-1"></i>{{ $customer_name }}
                            &nbsp;·&nbsp;
                            <i class="fas fa-id-card mr-1"></i>{{ $documento }}
                        </div>
                    </div>
                    @if ($total_prov > 0 || $total_prov_dolar > 0)
                        <div class="text-right ml-3 pl-3 border-left flex-shrink-0">
                            <div class="text-muted" style="font-size:.7rem;text-transform:uppercase;letter-spacing:.5px;">
                                Saldo pendiente
                            </div>
                            @if ($total_prov > 0)
                                <div class="font-weight-bold text-success" style="font-size:.9rem;">
                                    S/. {{ number_format($total_prov, 2) }}
                                </div>
                            @endif
                            @if ($total_prov_dolar > 0)
                                <div class="font-weight-bold text-primary" style="font-size:.9rem;">
                                    $. {{ number_format($total_prov_dolar, 2) }}
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @endif

            {{-- ── Datos del cliente ── --}}
            <div class="row">
                <div class="form-group col-md-6">
                    <label class="col-form-label-sm font-weight-bold text-uppercase text-muted">
                        {{ $type === 'add' ? 'Cliente' : 'Proveedor' }}
                        @if ($student_name)
                            <span class="badge badge-info ml-1">Alumno: {{ $student_name }}</span>
                        @endif
                    </label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <input type="number" wire:model.defer="documento"
                                class="form-control @error('documento') is-invalid @enderror"
                                readonly placeholder="Nº documento">
                        </div>
                        <input type="text" wire:model.defer="customer_name"
                            class="form-control @error('customer_name') is-invalid @enderror"
                            readonly placeholder="Nombre completo">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                        </div>
                    </div>
                    @error('documento')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                    @error('customer_name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group col-md-6">
                    <label class="col-form-label-sm font-weight-bold text-uppercase text-muted">Pagado por</label>
                    <input type="text" class="form-control" wire:model.defer="paid_by"
                        placeholder="Nombre de quien realiza el pago">
                </div>
            </div>

            {{-- ── Datos del movimiento ── --}}
            <div class="row">
                <div class="form-group col-md-3">
                    <label class="col-form-label-sm font-weight-bold text-uppercase text-muted">
                        Fecha
                        <span class="badge badge-primary ml-1">TC: S/. {{ $tc }}</span>
                    </label>
                    <input type="date" name="date" wire:model="date"
                        class="form-control @error('date') is-invalid @enderror">
                    @error('date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group col-md-3">
                    <label class="col-form-label-sm font-weight-bold text-uppercase text-muted">Cuenta</label>
                    <select id="account_id" class="form-control @error('account_id') is-invalid @enderror"
                        wire:model="account_id">
                        <option value="">Seleccione cuenta</option>
                        @foreach ($cuentas as $cuenta)
                            <option value="{{ $cuenta->id }}">{{ $cuenta->account_name }}</option>
                        @endforeach
                    </select>
                    @error('account_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group col-md-3">
                    <label class="col-form-label-sm font-weight-bold text-uppercase text-muted">Medio de pago</label>
                    <select id="payment_method" class="form-control custom-select" wire:model.defer="payment_method">
                        @foreach ($paymentMethods as $method)
                            <option value="{{ $method->id }}">{{ $method->name }}</option>
                        @endforeach
                    </select>
                    @error('payment_method')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group col-md-3">
                    <label class="col-form-label-sm font-weight-bold text-uppercase text-muted">
                        Nº Operación <small class="text-muted font-weight-normal">(opcional)</small>
                    </label>
                    <input type="text" class="form-control" wire:model.defer="numero_operacion"
                        placeholder="Nº de operación">
                </div>
            </div>

            {{-- ── Tabla de provisiones ── --}}
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th style="width:120px">MES</th>
                                    <th style="width:38%">DESCRIPCIÓN</th>
                                    <th>CATEGORÍA</th>
                                    <th class="text-right">SOLES</th>
                                    <th class="text-right">DÓLAR</th>
                                    <th style="width:80px" class="text-center">
                                        <button type="button" wire:click="add"
                                            class="btn btn-xs btn-warning"
                                            {{ $student_id ? '' : 'disabled' }}
                                            title="Agregar fila">
                                            <i class="fa fa-plus"></i> ADD
                                        </button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($student_id)
                                    {{-- Provisiones seleccionadas (read-only) --}}
                                    @foreach ($provisionsCobrar as $item)
                                        <tr class="table-light">
                                            <td class="text-muted small">{{ $item->date->format('m/Y') }}</td>
                                            <td>{{ $item->description }}</td>
                                            <td>{{ $item->category->name }}</td>
                                            <td class="text-right">
                                                @if ($item->currency->id == 1 || $item->currency->id == null)
                                                    S/. {{ number_format($item->amount, 2) }}
                                                @else
                                                    <span class="text-muted small">TC</span>
                                                    S/. {{ number_format($item->amount * $tc, 2) }}
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                @if ($item->currency->id == 2)
                                                    $. {{ number_format($item->amount, 2) }}
                                                @endif
                                            </td>
                                            <td></td>
                                        </tr>
                                    @endforeach

                                    {{-- Nuevas provisiones (editable) --}}
                                    @foreach ($provisions as $key => $provision)
                                        <tr>
                                            <td>
                                                <input type="month" class="form-control form-control-sm"
                                                    wire:model.defer="provisions.{{ $key }}.date">
                                                @error("provisions.{$key}.date")
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm"
                                                    wire:model.defer="provisions.{{ $key }}.description"
                                                    placeholder="Descripción">
                                                @error("provisions.{$key}.description")
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </td>
                                            <td>
                                                <select class="custom-select custom-select-sm"
                                                    wire:model.defer="provisions.{{ $key }}.category_id">
                                                    <option value="Elegir">Seleccione</option>
                                                    @foreach ($categorias as $name => $id)
                                                        <option value="{{ $id }}">{{ $name }}</option>
                                                    @endforeach
                                                </select>
                                                @error("provisions.{$key}.category_id")
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </td>
                                            <td colspan="2">
                                                <input type="number" class="form-control form-control-sm"
                                                    wire:model.lazy="provisions.{{ $key }}.amount"
                                                    placeholder="0.00" min="0" step="0.01">
                                                @error("provisions.{$key}.amount")
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-xs btn-danger"
                                                    wire:click="removeProvision({{ $key }})"
                                                    title="Eliminar fila">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center py-4" style="background:#fafafa;">
                                            <i class="fas fa-search fa-2x d-block mb-2 text-muted" style="opacity:.35;"></i>
                                            <span class="text-muted">Busque un alumno por DNI o código para cargar sus provisiones</span>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                            <tfoot>
                                <tr style="background:#e9ecef;border-top:2px solid #ced4da;">
                                    <td colspan="3" class="text-right font-weight-bold align-middle py-2"
                                        style="font-size:.8rem;letter-spacing:.4px;text-transform:uppercase;">
                                        Total a cobrar
                                    </td>
                                    <td colspan="3" class="text-center align-middle py-2">
                                        <span class="font-weight-bold text-success" style="font-size:1.1rem;">
                                            S/. {{ number_format($total_prov_cobrar + $total_new, 2) }}
                                        </span>
                                        @error('amount')
                                            <br><small class="text-danger font-weight-normal">{{ $message }}</small>
                                        @enderror
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            {{-- ── Observaciones ── --}}
            <div class="form-group">
                <label class="col-form-label-sm font-weight-bold text-uppercase text-muted">Observaciones</label>
                <textarea class="form-control" wire:model.defer="observation" rows="2"
                    placeholder="Observaciones o notas adicionales (opcional)"></textarea>
            </div>

        </div>

        {{-- Card footer --}}
        <div class="card-footer d-flex justify-content-between align-items-center flex-wrap" style="gap:.5rem;">
            <a href="{{ route('movimientos.listado') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left mr-1"></i> Cancelar
            </a>

            <div class="d-flex align-items-center" style="gap:.75rem;">
                @if ($total_prov_cobrar + $total_new > 0)
                    <span class="text-muted" style="font-size:.85rem;">
                        Total:
                        <strong class="text-success">
                            S/. {{ number_format($total_prov_cobrar + $total_new, 2) }}
                        </strong>
                    </span>
                @endif

                <button type="button" wire:click.prevent="crearMovimiento"
                    wire:loading.attr="disabled"
                    class="btn btn-success px-4"
                    {{ (!$student_id || ($total_prov_cobrar + $total_new) <= 0) ? 'disabled' : '' }}>
                    <span wire:loading.remove wire:target="crearMovimiento">
                        <i class="fa fa-check-circle mr-1"></i> Emitir Recibo
                    </span>
                    <span wire:loading wire:target="crearMovimiento">
                        <i class="fa fa-spinner fa-spin mr-1"></i> Procesando...
                    </span>
                </button>
            </div>
        </div>
    </div>

    {{-- Modal subcategorías --}}
    <div wire:ignore.self id="modalSubcategorias" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">Subcategorías</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    @if ($subcategorias)
                        @foreach ($subcategorias as $item)
                            <div class="form-group form-check">
                                <input class="form-check-input" type="radio" wire:model="subcategoria_id"
                                    name="subcategoria" id="radio{{ $item->id }}" value="{{ $item->id }}">
                                <label class="form-check-label" for="radio{{ $item->id }}">
                                    {{ $item->name }}
                                </label>
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">
                        Confirmar
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal de selección de provisiones --}}
    @include('livewire.movimientos.clase-movimientos.modal-select-detalles')

    {{-- Modal de recibo generado --}}
    <div wire:ignore.self class="modal fade" id="receiptModal" data-backdrop="static"
        data-keyboard="false" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                @if ($showReceiptModal)
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">
                            <i class="fa fa-check-circle mr-1"></i> Recibo Generado
                        </h5>
                    </div>
                    <div class="modal-body text-center py-4">
                        <i class="fas fa-file-invoice fa-3x text-success mb-3 d-block"></i>
                        <p class="mb-1">Número de recibo generado:</p>
                        <h4 class="font-weight-bold text-primary">{{ $receipt }}</h4>
                        <p class="text-muted mt-2">¿Qué desea hacer con el recibo?</p>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <a href="{{ route('movimientos.a4.recibo', $summary_id) }}"
                            wire:click="redireccionar"
                            target="_blank"
                            class="btn btn-primary">
                            <i class="fa fa-print mr-1"></i> A4
                        </a>
                        <a href="{{ route('movimientos.a5.recibo', $summary_id) }}"
                            wire:click="redireccionar"
                            target="_blank"
                            class="btn btn-primary">
                            <i class="fa fa-print mr-1"></i> A5
                        </a>
                        <button type="button" class="btn btn-secondary" wire:click="redireccionar">
                            <i class="fa fa-list mr-1"></i> Ir al listado
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const alertError = msg => Swal.fire({ icon: 'error', title: 'Error', text: msg });

        window.livewire.on('error',       alertError);
        window.livewire.on('error_fecha', alertError);

        window.livewire.on('customer_added', msg => {
            $('#globalModal').modal('hide');
            Swal.fire({ icon: 'success', title: '¡Registrado!', text: msg });
        });

        window.livewire.on('movimiento_added', msg => {
            Swal.fire({ icon: 'success', title: '¡Registrado!', text: msg });
        });

        window.livewire.on('registro-existente', msg => {
            $('#globalModal').modal('hide');
            Swal.fire({ icon: 'info', title: 'Ya existe', text: msg });
        });

        window.livewire.on('mostrarModalProvision', () => {
            $('#modalProvision').modal('show');
        });

        window.addEventListener('show-receipt-modal', () => {
            $('#receiptModal').modal('show');
        });

        $('#globalModal').on('hidden.bs.modal', () => livewire.emit('resetUiApi'));
        $('#receiptModal').on('hidden.bs.modal', () => livewire.emit('redireccionar'));

        // Buscar provisión con Enter
        document.getElementById('provision_code')?.addEventListener('keydown', e => {
            if (e.key === 'Enter') livewire.emit('buscar_provision');
        });
    });
</script>
@endpush
