<div class="pt-3">

    {{-- Loading overlay --}}
    <div wire:loading.flex class="position-fixed w-100 h-100 justify-content-center align-items-center"
        style="top:0;left:0;z-index:9999;background:rgba(0,0,0,.4);">
        <div class="text-white text-center">
            <i class="fas fa-3x fa-circle-notch fa-spin"></i>
            <div class="mt-2 font-weight-bold">Guardando...</div>
        </div>
    </div>

    <form wire:submit.prevent="save">

        {{-- ── Sección 1: Información de la Empresa ─────────────────────────── --}}
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-building mr-2"></i> Información de la Empresa
                </h3>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group col-md-8">
                        <label for="company_name">Razón Social <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-briefcase"></i></span>
                            </div>
                            <input type="text" id="company_name" wire:model.defer="companyName"
                                class="form-control @error('companyName') is-invalid @enderror"
                                placeholder="Nombre legal de la empresa">
                            @error('companyName')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="company_ruc">N° RUC <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                            </div>
                            <input type="text" id="company_ruc" wire:model.defer="companyRuc"
                                class="form-control @error('companyRuc') is-invalid @enderror"
                                placeholder="20xxxxxxxxx" maxlength="11">
                            @error('companyRuc')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="company_email">Correo Electrónico</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            </div>
                            <input type="email" id="company_email" wire:model.defer="companyEmail"
                                class="form-control @error('companyEmail') is-invalid @enderror"
                                placeholder="contacto@empresa.com">
                            @error('companyEmail')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="company_phone">Teléfono / Celular</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            </div>
                            <input type="tel" id="company_phone" wire:model.defer="companyPhone"
                                class="form-control @error('companyPhone') is-invalid @enderror"
                                placeholder="(01) 234-5678">
                            @error('companyPhone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-8">
                        <label for="company_address">Dirección</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                            </div>
                            <input type="text" id="company_address" wire:model.defer="companyAddress"
                                class="form-control @error('companyAddress') is-invalid @enderror"
                                placeholder="Av. Ejemplo 123, Lima">
                            @error('companyAddress')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="company_website">Sitio Web</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-globe"></i></span>
                            </div>
                            <input type="url" id="company_website" wire:model.defer="companyWebsite"
                                class="form-control @error('companyWebsite') is-invalid @enderror"
                                placeholder="https://www.empresa.com">
                            @error('companyWebsite')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Sección 2: Identidad Visual (Logo) ───────────────────────────── --}}
        <div class="card card-secondary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-image mr-2"></i> Identidad Visual
                </h3>
            </div>
            <div class="card-body">
                <div class="form-row align-items-center">

                    {{-- Current logo --}}
                    <div class="col-md-4 text-center mb-3">
                        <p class="text-muted small font-weight-bold mb-1">LOGO ACTUAL</p>
                        <div class="border rounded p-2 d-inline-block bg-light">
                            <img src="{{ $logoActual }}" alt="Logo actual"
                                style="max-height:120px;max-width:200px;object-fit:contain;">
                        </div>
                    </div>

                    {{-- Upload input --}}
                    <div class="col-md-4">
                        <div class="form-group mb-0">
                            <label for="logo-input">Cambiar Logo</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('logo') is-invalid @enderror"
                                    id="logo-input" wire:model="logo" accept="image/*">
                                <label class="custom-file-label" for="logo-input">
                                    Seleccionar imagen...
                                </label>
                            </div>
                            <small class="text-muted">JPG, PNG o WebP · Máx. 1 MB</small>
                            @error('logo')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- New logo preview --}}
                    <div class="col-md-4 text-center mb-3">
                        @if ($logo)
                            <p class="text-muted small font-weight-bold mb-1">VISTA PREVIA</p>
                            <div class="border rounded p-2 d-inline-block bg-light">
                                <img src="{{ $logo->temporaryUrl() }}" alt="Vista previa"
                                    style="max-height:120px;max-width:200px;object-fit:contain;">
                            </div>
                            <div class="mt-1">
                                <span class="badge badge-success">
                                    <i class="fas fa-check mr-1"></i> Lista para guardar
                                </span>
                            </div>
                        @else
                            <p class="text-muted small">La vista previa aparecerá aquí</p>
                        @endif
                    </div>

                </div>
            </div>
        </div>

        {{-- ── Sección 3: Configuración Financiera ──────────────────────────── --}}
        <div class="card card-success card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-dollar-sign mr-2"></i> Configuración Financiera
                </h3>
            </div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="currency">Moneda por Defecto</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-coins"></i></span>
                            </div>
                            <select id="currency" class="form-control @error('defaultCurrency') is-invalid @enderror"
                                wire:model.defer="defaultCurrency">
                                <option value="">— Seleccionar —</option>
                                @foreach ($currencies as $code => $id)
                                    <option value="{{ $id }}">{{ $code }}</option>
                                @endforeach
                            </select>
                            @error('defaultCurrency')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="receipt_type">Tipo de Recibo</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-receipt"></i></span>
                            </div>
                            <input type="text" id="receipt_type" wire:model.defer="receiptType"
                                class="form-control @error('receiptType') is-invalid @enderror"
                                placeholder="Ej: RECIBO, BOLETA">
                            @error('receiptType')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="report_type">Tipo de Reporte</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-chart-bar"></i></span>
                            </div>
                            <input type="text" id="report_type" wire:model.defer="reportType"
                                class="form-control @error('reportType') is-invalid @enderror"
                                placeholder="Ej: MENSUAL, ANUAL">
                            @error('reportType')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Sección 4: Control de Fechas Retroactivas ────────────────────── --}}
        <div class="card card-warning card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-calendar-alt mr-2"></i> Control de Fechas Retroactivas
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    {{-- Ingresos --}}
                    <div class="col-md-6">
                        <div class="card card-body bg-light border mb-0">
                            <h6 class="text-success font-weight-bold mb-3">
                                <i class="fas fa-arrow-alt-circle-up mr-1"></i> Ingresos
                            </h6>
                            <div class="form-group d-flex align-items-center mb-2">
                                <div class="custom-control custom-switch mr-3">
                                    <input type="checkbox" class="custom-control-input"
                                        id="active_back_date_add" wire:model.defer="activeBackDateOnAdd">
                                    <label class="custom-control-label" for="active_back_date_add">
                                        Permitir fecha retroactiva
                                    </label>
                                </div>
                            </div>
                            <div class="form-group mb-0">
                                <label for="days_add">Días permitidos hacia atrás</label>
                                <div class="input-group" style="max-width:200px;">
                                    <input type="number" id="days_add" wire:model.defer="daysOnAdd"
                                        class="form-control @error('daysOnAdd') is-invalid @enderror"
                                        min="0" max="365">
                                    <div class="input-group-append">
                                        <span class="input-group-text">días</span>
                                    </div>
                                    @error('daysOnAdd')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Egresos --}}
                    <div class="col-md-6">
                        <div class="card card-body bg-light border mb-0">
                            <h6 class="text-danger font-weight-bold mb-3">
                                <i class="fas fa-arrow-alt-circle-down mr-1"></i> Egresos
                            </h6>
                            <div class="form-group d-flex align-items-center mb-2">
                                <div class="custom-control custom-switch mr-3">
                                    <input type="checkbox" class="custom-control-input"
                                        id="active_back_date_out" wire:model.defer="activeBackDateOut">
                                    <label class="custom-control-label" for="active_back_date_out">
                                        Permitir fecha retroactiva
                                    </label>
                                </div>
                            </div>
                            <div class="form-group mb-0">
                                <label for="days_out">Días permitidos hacia atrás</label>
                                <div class="input-group" style="max-width:200px;">
                                    <input type="number" id="days_out" wire:model.defer="daysOut"
                                        class="form-control @error('daysOut') is-invalid @enderror"
                                        min="0" max="365">
                                    <div class="input-group-append">
                                        <span class="input-group-text">días</span>
                                    </div>
                                    @error('daysOut')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Acción guardar ────────────────────────────────────────────────── --}}
        <div class="d-flex justify-content-end mb-4">
            <button type="submit" class="btn btn-primary px-5"
                wire:loading.attr="disabled" wire:target="save">
                <span wire:loading.remove wire:target="save">
                    <i class="fas fa-save mr-1"></i> Guardar Cambios
                </span>
                <span wire:loading wire:target="save">
                    <i class="fas fa-spinner fa-spin mr-1"></i> Guardando...
                </span>
            </button>
        </div>

    </form>

</div>

@push('js')
<script>
    window.addEventListener('reset-file-input', () => {
        const input = document.getElementById('logo-input');
        if (input) {
            input.value = '';
            const label = input.nextElementSibling;
            if (label) label.textContent = 'Seleccionar imagen...';
        }
    });

    window.livewire.on('companyUpdated', msg => {
        Swal.fire({
            icon: 'success',
            title: 'Buen Trabajo!',
            text: msg,
            timer: 2500,
            showConfirmButton: false,
        });
    });
</script>
@endpush
