<div>
    <div class="pt-4">
        <div class="card">

            <div wire:loading.class='overlay' class="d-none dark" wire:loading.class.remove='d-none'>
                <i class="fas fa-2x fa-sync-alt fa-spin"></i>
            </div>

            <div class="card-header">
                <i class="fa fa-bar-chart"></i>
                <h3 class="card-title">CONFIGURACION DEL SISTEMA</h3>
            </div>

            <div class="card-body">
                <form wire:submit.prevent="save" method="post">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="company_name">Razon Social</label>
                            <input type="text" wire:model.defer="companyName" required class="form-control"
                                id="company_name">
                            @error('companyName')
                                <span class="error">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="company_ruc">N° RUC</label>
                            <input type="text" wire:model.defer="companyRuc" required class="form-control"
                                id="company_ruc">

                            @error('companyRuc')
                                <span class="error">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="email">Correo Electrónico</label>
                            <input type="email" wire:model.defer="companyEmail" class="form-control" id="email">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="phone">N° Celular o teléfono</label>
                            <input type="tel" wire:model.defer="companyPhone" class="form-control" id="phone">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="currency">Moneda</label>
                            <select id="currency" class="form-control" wire:model.defer="defaultCurrency">
                                @foreach ($currencies as $code => $id)
                                    <option value="{{ $id }}">{{ $code }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="">Habilitar numero de dias a retroceder en Ingreso</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" wire:model.defer="activeBackDateOnAdd"
                                                type="checkbox" id="ret_fecha">
                                            <label for="ret_fecha" class="custom-control-label"></label>
                                        </div>
                                    </span>
                                </div>
                                <input type="number" class="form-control" wire:model.defer="daysOnAdd">
                                <div class="input-group-append">
                                    <div class="input-group-text">Días en Ingreso</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="">Habilitar numero de dias a retroceder en Egreso</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" wire:model.defer="activeBackDateOut"
                                                type="checkbox" id="ret_fecha_out">
                                            <label for="ret_fecha_out" class="custom-control-label"></label>
                                        </div>
                                    </span>
                                </div>
                                <input type="number" class="form-control" wire:model.defer="daysOut">
                                <div class="input-group-append">
                                    <div class="input-group-text">Días en Egreso</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="">Recibo Tipo</label>
                            <input type="text" name="" id="tipo_recibo" wire:model.defer="receiptType"
                                class="form-control">
                            @error('receiptType')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="">Reporte Tipo</label>
                            <input type="text" name="" id="tipo_reporte" wire:model.defer="reportType"
                                class="form-control">
                            @error('reportType')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="">Logo</label>
                            <input type="file" name="" id="{{ $logoId }}" wire:model="logo"
                                class="form-control">
                            @error('logo')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            @if ($logo)
                                <label for="">Imagen Nueva</label><br>
                                <img src="{{ $logo->temporaryUrl() }}" width="200px">
                            @endif
                        </div>
                        <div class="form-group col-md-4">
                            @if ($logoActual)
                                <label for="">Imagen Actual</label><br>
                                <img src="{{ $logoActual }}" width="200px">
                            @endif
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </form>
            </div>
        </div>

    </div>
</div>

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.livewire.on('companyUpdated', msg => {
                Swal.fire({
                    icon: 'success',
                    title: 'Buen Trabajo!',
                    text: msg,
                });
            });
        });
    </script>
@endpush
