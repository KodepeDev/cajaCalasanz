<div class="pt-3">

    {{-- Loading overlay --}}
    <div wire:loading.flex class="position-fixed w-100 h-100 justify-content-center align-items-center"
        style="top:0;left:0;z-index:9999;background:rgba(0,0,0,.35);">
        <div class="text-white text-center">
            <i class="fas fa-3x fa-circle-notch fa-spin"></i>
            <div class="mt-2 font-weight-bold">Procesando...</div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="card card-maroon">

            <div class="card-header d-flex align-items-center">
                <i class="fas fa-exchange-alt mr-2"></i>
                <h3 class="card-title mb-0">Transferir entre cuentas</h3>
            </div>

            <div class="card-body">

                <div class="row">

                    {{-- Cuenta origen --}}
                    <div class="col-md-6 mb-3">
                        <div class="card card-outline card-success h-100">
                            <div class="card-header py-2">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-arrow-up text-success mr-2"></i>Cuenta Origen
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-2">
                                    <label class="col-form-label-sm font-weight-bold text-uppercase text-muted">
                                        Seleccione origen <span class="text-danger">*</span>
                                    </label>
                                    <select wire:model.lazy="cuenta1"
                                        class="form-control @error('cuenta1') is-invalid @enderror">
                                        <option value="-1">— seleccione —</option>
                                        @foreach ($cuentas as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    @error('cuenta1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                @if ($cuenta1 > 0)
                                    <div class="d-flex align-items-center mt-2">
                                        <span class="text-muted small mr-2">Saldo actual:</span>
                                        <span class="font-weight-bold {{ $saldoCuenta1 >= 0 ? 'text-success' : 'text-danger' }}">
                                            S/. {{ number_format($saldoCuenta1, 2, '.', ',') }}
                                        </span>
                                        @if ($saldoCuenta1 < 0)
                                            <span class="badge badge-danger ml-2">
                                                <i class="fas fa-exclamation-triangle"></i>
                                            </span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Cuenta destino --}}
                    <div class="col-md-6 mb-3">
                        <div class="card card-outline card-info h-100">
                            <div class="card-header py-2">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-arrow-down text-info mr-2"></i>Cuenta Destino
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-2">
                                    <label class="col-form-label-sm font-weight-bold text-uppercase text-muted">
                                        Seleccione destino <span class="text-danger">*</span>
                                    </label>
                                    <select wire:model.lazy="cuenta2"
                                        class="form-control @error('cuenta2') is-invalid @enderror">
                                        <option value="-1">— seleccione —</option>
                                        @foreach ($cuentas as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    @error('cuenta2')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                @if ($cuenta2 > 0)
                                    <div class="d-flex align-items-center mt-2">
                                        <span class="text-muted small mr-2">Saldo actual:</span>
                                        <span class="font-weight-bold {{ $saldoCuenta2 >= 0 ? 'text-success' : 'text-danger' }}">
                                            S/. {{ number_format($saldoCuenta2, 2, '.', ',') }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Amount + Date --}}
                <div class="row">
                    <div class="form-group col-md-6">
                        <label class="col-form-label-sm font-weight-bold text-uppercase text-muted">
                            Monto <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">S/.</span>
                            </div>
                            <input wire:model.defer="amount" type="number" step="0.01" min="0"
                                class="form-control @error('amount') is-invalid @enderror"
                                placeholder="0.00">
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <label class="col-form-label-sm font-weight-bold text-uppercase text-muted">
                            Fecha <span class="text-danger">*</span>
                        </label>
                        <input wire:model.defer="date" type="date"
                            class="form-control @error('date') is-invalid @enderror">
                        @error('date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Fechas futuras quedan como movimiento pendiente.</small>
                    </div>
                </div>

            </div>

            <div class="card-footer d-flex justify-content-between">
                <button type="button" onclick="window.history.back()" class="btn btn-secondary">
                    <i class="fa fa-arrow-left mr-1"></i> Cancelar
                </button>
                <button type="button" wire:click.prevent="crearTransferencia"
                    wire:loading.attr="disabled" class="btn btn-warning px-4">
                    <span wire:loading.remove wire:target="crearTransferencia">
                        <i class="fas fa-exchange-alt mr-1"></i> Transferir
                    </span>
                    <span wire:loading wire:target="crearTransferencia">
                        <i class="fas fa-circle-notch fa-spin mr-1"></i> Procesando...
                    </span>
                </button>
            </div>

        </div>
    </div>
</div>

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Livewire.on('error', msg => {
            Swal.fire({ icon: 'error', title: 'Error', text: msg });
        });
    });
</script>
@endpush
