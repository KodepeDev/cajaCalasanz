<div class="pt-3">

    {{-- Loading overlay --}}
    <div wire:loading.flex class="position-fixed w-100 h-100 justify-content-center align-items-center"
        style="top:0;left:0;z-index:9999;background:rgba(0,0,0,.35);">
        <div class="text-white text-center">
            <i class="fas fa-3x fa-circle-notch fa-spin"></i>
            <div class="mt-2 font-weight-bold">Procesando...</div>
        </div>
    </div>

    <div class="card card-danger">

        {{-- Card header --}}
        <div class="card-header d-flex align-items-center">
            <i class="fa fa-shopping-cart mr-2"></i>
            <div>
                <h3 class="card-title mb-0">Registrar Nuevo Gasto</h3>
                <br>
                <small class="text-light opacity-75">
                    Registre un gasto o egreso asociado a un cliente o proveedor
                </small>
            </div>
        </div>

        {{-- ── Indicador de pasos ── --}}
        <div class="px-4 pt-3 pb-2 border-bottom" style="background:#f8f9fa;">
            <div class="d-flex align-items-center" style="font-size:.82rem;">

                {{-- Paso 1 --}}
                <div class="d-flex align-items-center {{ $customer_id ? 'text-muted' : 'text-danger font-weight-bold' }}">
                    <span class="d-flex align-items-center justify-content-center rounded-circle mr-2"
                        style="width:28px;height:28px;font-size:.78rem;flex-shrink:0;
                               background:{{ $customer_id ? '#28a745' : '#dc3545' }};color:#fff;">
                        @if($customer_id) <i class="fas fa-check fa-xs"></i> @else 1 @endif
                    </span>
                    <span class="d-none d-sm-inline">Buscar proveedor</span>
                </div>

                <div class="flex-fill mx-2 border-top {{ $customer_id ? 'border-success' : '' }}"
                    style="height:2px;min-width:20px;"></div>

                {{-- Paso 2 --}}
                @php $hasItems = count($provisions) > 0; @endphp
                <div class="d-flex align-items-center
                    {{ !$customer_id ? 'text-muted' : ($hasItems ? 'text-muted' : 'text-warning font-weight-bold') }}">
                    <span class="d-flex align-items-center justify-content-center rounded-circle mr-2"
                        style="width:28px;height:28px;font-size:.78rem;flex-shrink:0;
                               background:{{ !$customer_id ? '#6c757d' : ($hasItems ? '#28a745' : '#ffc107') }};
                               color:{{ !$customer_id || $hasItems ? '#fff' : '#212529' }};">
                        @if($customer_id && $hasItems) <i class="fas fa-check fa-xs"></i> @else 2 @endif
                    </span>
                    <span class="d-none d-sm-inline">Agregar gastos</span>
                </div>

                <div class="flex-fill mx-2 border-top {{ $hasItems ? 'border-success' : '' }}"
                    style="height:2px;min-width:20px;"></div>

                {{-- Paso 3 --}}
                <div class="d-flex align-items-center text-muted">
                    <span class="d-flex align-items-center justify-content-center rounded-circle mr-2"
                        style="width:28px;height:28px;font-size:.78rem;flex-shrink:0;background:#6c757d;color:#fff;">3</span>
                    <span class="d-none d-sm-inline">Emitir recibo</span>
                </div>
            </div>
        </div>

        <div class="card-body">

            {{-- ── Buscar proveedor / cliente ── --}}
            <div class="row align-items-end mb-3 pb-3 border-bottom">
                <div class="col-md-8">
                    <label class="col-form-label-sm font-weight-bold text-uppercase text-muted">
                        Proveedor / Cliente
                        <a href="javascript:void(0)" class="text-danger ml-1 small"
                            data-toggle="modal" data-target="#globalModal">
                            <i class="fa fa-plus-circle"></i> Nuevo
                        </a>
                    </label>
                    <div class="input-group" wire:ignore>
                        <input type="number" wire:model.defer="documento" id="customer_doc"
                            class="form-control @error('documento') is-invalid @enderror"
                            style="max-width:130px;"
                            wire:change="clearDataApi"
                            placeholder="N° doc">
                        <select class="form-control select2" id="customer-select"
                            wire:model.defer="customer_id">
                            <option value="">Elige un proveedor...</option>
                            @foreach ($customers as $full_name => $id)
                                <option value="{{ $id }}">{{ $full_name }}</option>
                            @endforeach
                        </select>
                        <div class="input-group-append">
                            <button type="button" wire:click="consultasCustomer"
                                wire:loading.attr="disabled"
                                class="btn btn-info">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    @error('documento')
                        <small class="text-danger d-block">{{ $message }}</small>
                    @enderror
                    @error('customer_name')
                        <small class="text-danger d-block">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            {{-- ── Panel del cliente/proveedor seleccionado ── --}}
            @if ($customer_name && $customer_id)
                <div class="d-flex align-items-center mb-4 p-3 rounded"
                    style="background:linear-gradient(135deg,#fff5f5,#fce8e8);border-left:4px solid #dc3545;">
                    <div class="d-flex align-items-center justify-content-center rounded-circle mr-3 flex-shrink-0"
                        style="width:46px;height:46px;background:#dc3545;">
                        <i class="fas fa-building text-white fa-lg"></i>
                    </div>
                    <div class="flex-fill">
                        <div class="font-weight-bold" style="font-size:.98rem;line-height:1.2;">
                            {{ $customer_name }}
                        </div>
                        <div class="text-muted" style="font-size:.8rem;margin-top:2px;">
                            <i class="fas fa-id-card mr-1"></i>{{ $documento }}
                        </div>
                        @if ($student_name)
                            <div class="text-muted" style="font-size:.8rem;margin-top:2px;">
                                <i class="fas fa-user-graduate mr-1"></i>{{ $student_name }}
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- ── Datos del movimiento ── --}}
            <div class="row">
                <div class="form-group col-md-6">
                    <label class="col-form-label-sm font-weight-bold text-uppercase text-muted">Pagado por</label>
                    <input type="text" class="form-control" wire:model.defer="paid_by"
                        placeholder="Nombre de quien realiza el pago">
                </div>

                <div class="form-group col-md-3">
                    <label class="col-form-label-sm font-weight-bold text-uppercase text-muted">Fecha</label>
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
                    <select id="payment_method" class="form-control custom-select"
                        wire:model.defer="payment_method">
                        @foreach ($paymentMethods as $method)
                            <option value="{{ $method->id }}">{{ $method->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-3">
                    <label class="col-form-label-sm font-weight-bold text-uppercase text-muted">
                        Nº Operación <small class="text-muted font-weight-normal">(opcional)</small>
                    </label>
                    <input type="text" class="form-control" wire:model.defer="numero_operacion"
                        placeholder="Nº de operación">
                </div>
            </div>

            {{-- ── Tabla de gastos ── --}}
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm">
                            <thead class="bg-danger text-white">
                                <tr>
                                    <th style="width:130px">MES</th>
                                    <th style="width:38%">DESCRIPCIÓN</th>
                                    <th>CATEGORÍA</th>
                                    <th class="text-right">TOTAL</th>
                                    <th style="width:80px" class="text-center">
                                        <button type="button" wire:click="Add"
                                            class="btn btn-xs btn-warning"
                                            {{ $documento ? '' : 'disabled' }}
                                            title="Agregar gasto">
                                            <i class="fa fa-plus"></i> ADD
                                        </button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
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
                                                placeholder="Descripción del gasto">
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
                                        <td>
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

                                @if (empty($provisions))
                                    <tr>
                                        <td colspan="5" class="text-center py-4" style="background:#fafafa;">
                                            <i class="fas fa-shopping-cart fa-2x d-block mb-2 text-muted"
                                                style="opacity:.35;"></i>
                                            <span class="text-muted">
                                                Busque un proveedor y agregue gastos con el botón ADD
                                            </span>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                            <tfoot>
                                <tr style="background:#e9ecef;border-top:2px solid #ced4da;">
                                    <td colspan="3" class="text-right font-weight-bold align-middle py-2"
                                        style="font-size:.8rem;letter-spacing:.4px;text-transform:uppercase;">
                                        Total a pagar
                                    </td>
                                    <td colspan="2" class="text-center align-middle py-2">
                                        <span class="font-weight-bold text-danger" style="font-size:1.1rem;">
                                            S/. {{ number_format($total_new, 2) }}
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
                @if ($total_new > 0)
                    <span class="text-muted" style="font-size:.85rem;">
                        Total:
                        <strong class="text-danger">
                            S/. {{ number_format($total_new, 2) }}
                        </strong>
                    </span>
                @endif

                <button type="button" wire:click.prevent="crearMovimiento"
                    wire:loading.attr="disabled"
                    class="btn btn-danger px-4"
                    {{ (!$customer_id || $total_new <= 0) ? 'disabled' : '' }}>
                    <span wire:loading.remove wire:target="crearMovimiento">
                        <i class="fa fa-check-circle mr-1"></i> Registrar Gasto
                    </span>
                    <span wire:loading wire:target="crearMovimiento">
                        <i class="fa fa-spinner fa-spin mr-1"></i> Procesando...
                    </span>
                </button>
            </div>
        </div>
    </div>

    {{-- Modal: agregar nuevo cliente / proveedor --}}
    @livewire('movimientos.clase-movimientos.crear-cliente-modal')

    {{-- Modal: recibo generado --}}
    <div wire:ignore.self class="modal fade" id="receiptModal" data-backdrop="static"
        data-keyboard="false" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                @if ($showReceiptModal)
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">
                            <i class="fa fa-check-circle mr-1"></i> Recibo Generado
                        </h5>
                    </div>
                    <div class="modal-body text-center py-4">
                        <i class="fas fa-file-invoice fa-3x text-danger mb-3 d-block"></i>
                        <p class="mb-1">Número de recibo generado:</p>
                        <h4 class="font-weight-bold text-primary">{{ $receipt }}</h4>

                        @if ($customer_name)
                            <p class="text-muted mb-0 mt-2">
                                <i class="fas fa-building mr-1"></i>{{ $customer_name }}
                            </p>
                        @endif
                        @if ($student_name)
                            <p class="text-muted mb-0" style="font-size:.85rem;">
                                <i class="fas fa-user-graduate mr-1"></i>{{ $student_name }}
                            </p>
                        @endif

                        <p class="text-muted mt-3 mb-0">¿Qué desea hacer con el recibo?</p>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <a href="{{ route('movimientos.a4.recibo', $summary_id) }}"
                            wire:click="redireccionar" target="_blank"
                            class="btn btn-primary">
                            <i class="fa fa-print mr-1"></i> Imprimir A4
                        </a>
                        <a href="{{ route('movimientos.a5.recibo', $summary_id) }}"
                            wire:click="redireccionar" target="_blank"
                            class="btn btn-primary">
                            <i class="fa fa-print mr-1"></i> Imprimir A5
                        </a>
                        <button type="button" class="btn btn-light" wire:click="redireccionar">
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

        window.addEventListener('show-receipt-modal', () => {
            $('#receiptModal').modal('show');
        });

        $('#globalModal').on('hidden.bs.modal', () => livewire.emit('resetUiApi'));
        $('#receiptModal').on('hidden.bs.modal', () => livewire.emit('redireccionar'));

        // ── Select2 ──────────────────────────────────────────────────────────
        $('#customer-select').select2({ theme: 'bootstrap4' });

        $('#customer-select').on('change', function () {
            let id = $(this).val();
            @this.set('customer_id', id);
            livewire.emit('selectSearch');
        });

        Livewire.on('updateSelect', function (customerId, fullName) {
            $('#customer-select').select2('destroy');

            if (!$('#customer-select option[value="' + customerId + '"]').length) {
                $('#customer-select').append(new Option(fullName, customerId, true, true));
            }

            $('#customer-select').val(customerId).trigger('change');
            $('#customer-select').select2({ theme: 'bootstrap4' });
        });

        window.livewire.on('clearSelect', () => {
            $('#customer-select').val('').trigger('change');
            $('#customer-select').select2({ theme: 'bootstrap4' });
        });
    });
</script>
@endpush
