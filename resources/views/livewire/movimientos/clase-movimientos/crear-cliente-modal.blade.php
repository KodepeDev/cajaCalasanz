<div wire:ignore.self class="modal fade" data-backdrop="static" id="globalModal"
    role="dialog" aria-labelledby="globalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">

            {{-- Header --}}
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="globalModalLabel">
                    <i class="fa fa-user-plus mr-2"></i>
                    <b>{{ $componentName }}</b> | {{ $selected_id > 0 ? 'Editar' : 'Crear' }}
                </h5>
                <button type="button" wire:click="resetUI" class="close text-white"
                    data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {{-- Body --}}
            <div class="modal-body" style="position:relative;">

                {{-- Loading overlay sobre el modal-body --}}
                <div wire:loading.flex
                    class="position-absolute justify-content-center align-items-center flex-column"
                    style="inset:0;z-index:10;background:rgba(255,255,255,.75);border-radius:0 0 4px 4px;">
                    <i class="fas fa-circle-notch fa-spin fa-2x text-primary mb-2"></i>
                    <span class="font-weight-bold text-primary" style="font-size:.9rem;">Cargando...</span>
                </div>

                {{-- Documento + búsqueda API --}}
                <div class="form-row mb-3">
                    <div class="col-md-6">
                        <label class="col-form-label-sm font-weight-bold text-uppercase text-muted">
                            Documento
                        </label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <select class="form-control form-control"
                                    wire:model.lazy="document_type"
                                    wire:change="changeDocumentType"
                                    style="max-width:90px;">
                                    <option value="1">DNI</option>
                                    <option value="6">RUC</option>
                                    <option value="0">OTRO</option>
                                </select>
                            </div>
                            <input type="text"
                                class="form-control @error('document') is-invalid @enderror"
                                wire:model.defer="document"
                                wire:change="clearDataApi"
                                placeholder="Nº de documento">
                            <div class="input-group-append">
                                <button type="button"
                                    wire:click="consultasApi"
                                    wire:loading.attr="disabled"
                                    {{ $document_type != 0 ? '' : 'disabled' }}
                                    class="btn btn-info btn-sm">
                                    <span wire:loading.remove wire:target="consultasApi">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <span wire:loading wire:target="consultasApi">
                                        <i class="fas fa-spinner fa-spin"></i>
                                    </span>
                                </button>
                            </div>
                            @error('document')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @error('document_type')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="col-form-label-sm font-weight-bold text-uppercase text-muted">
                            Nombre / Razón social
                        </label>
                        <input type="text"
                            class="form-control @error('full_name') is-invalid @enderror"
                            wire:model.lazy="full_name"
                            placeholder="Nombre o razón social"
                            {{ $document_type == '0' ? '' : 'readonly' }}>
                        @error('full_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Nombres y apellidos --}}
                <div class="form-row mb-3">
                    <div class="col-md-6">
                        <label class="col-form-label-sm font-weight-bold text-uppercase text-muted">Nombres</label>
                        <input type="text"
                            class="form-control"
                            wire:model.lazy="first_name"
                            placeholder="Nombres"
                            {{ $document_type == '0' ? '' : 'readonly' }}>
                    </div>
                    <div class="col-md-6">
                        <label class="col-form-label-sm font-weight-bold text-uppercase text-muted">Apellidos</label>
                        <input type="text"
                            class="form-control"
                            wire:model.lazy="last_name"
                            placeholder="Apellidos"
                            {{ $document_type == '0' ? '' : 'readonly' }}>
                    </div>
                </div>

                {{-- Dirección --}}
                <div class="form-group mb-3">
                    <label class="col-form-label-sm font-weight-bold text-uppercase text-muted">Dirección</label>
                    <input type="text"
                        class="form-control"
                        wire:model.lazy="address"
                        placeholder="Dirección"
                        {{ $document_type == '0' ? '' : 'readonly' }}>
                </div>

                {{-- Email y teléfono --}}
                <div class="form-row">
                    <div class="col-md-6">
                        <label class="col-form-label-sm font-weight-bold text-uppercase text-muted">Correo</label>
                        <input type="email"
                            class="form-control"
                            wire:model.defer="email"
                            placeholder="correo@ejemplo.com">
                    </div>
                    <div class="col-md-6">
                        <label class="col-form-label-sm font-weight-bold text-uppercase text-muted">Teléfono</label>
                        <input type="tel"
                            class="form-control"
                            wire:model.defer="phone"
                            placeholder="Teléfono o celular">
                    </div>
                </div>

            </div>

            {{-- Footer --}}
            <div class="modal-footer">
                <button type="button"
                    wire:click="resetUI"
                    wire:loading.attr="disabled"
                    class="btn btn-secondary"
                    data-dismiss="modal">
                    <i class="fa fa-times mr-1"></i> Cerrar
                </button>
                <button type="button"
                    wire:click="create"
                    wire:loading.attr="disabled"
                    wire:target="create"
                    class="btn btn-warning font-weight-bold">
                    <span wire:loading.remove wire:target="create">
                        <i class="fa fa-save mr-1"></i> Guardar
                    </span>
                    <span wire:loading wire:target="create">
                        <i class="fa fa-spinner fa-spin mr-1"></i> Guardando...
                    </span>
                </button>
            </div>

        </div>
    </div>
</div>
