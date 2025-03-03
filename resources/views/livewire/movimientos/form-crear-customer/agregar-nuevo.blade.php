@include('common.modal.modalHeader')
<div class="modal-body">

    <div wire:loading.class='overlay' class="d-none" wire:loading.class.remove='d-none'>
        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
    </div>

    <form class="">
        <div class="form-row">
            <div class="form-group col-md-6">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <select class="form-control form-control-sm" wire:model.lazy='document_type'
                            wire:change='chageDocumentType()'>
                            <option value="1">DNI</option>
                            <option value="6">RUC</option>
                            <option value="0">OTRO</option>
                        </select>
                    </div>
                    <input type="number" wire:model.defer='document' class="form-control form-control-sm"
                        wire:change='clearDataApi()' wire:loading.attr='readonly'
                        aria-label="Text input with dropdown button">

                    <div class="input-group-append">
                        <button type="button" {{ $document_type != 0 ? '' : 'disabled' }} wire:click="ConsutasApi()"
                            class="btn btn-sm btn-info"><i class="fas fa-search"></i></button>
                    </div>
                </div>
                @error('document_type')
                    <span class="error">{{ $message }}</span>
                @enderror
                @error('document')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-6">
                <input type="text" class="form-control form-control-sm" wire:model.lazy='full_name' id="full_name"
                    placeholder="Nombre o razon social" readonly>
                @error('full_name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <input type="text" class="form-control form-control-sm" wire:model.lazy='first_name' id="fist_name"
                    placeholder="Nombres" {{ $document_type == '0' ? '' : 'readonly' }}>
                @error('first_name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-6">
                <input type="text" class="form-control form-control-sm" wire:model.lazy='last_name' id="last_name"
                    placeholder="Apellidos" {{ $document_type == '0' ? '' : 'readonly' }}>
                @error('last_name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <input type="text" class="form-control form-control-sm" wire:model.lazy='address' id="address"
                placeholder="1234 Main St" {{ $document_type == '0' ? '' : 'readonly' }}>
            @error('address')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <input type="email" class="form-control form-control-sm" wire:model.defer='email' id="email"
                    placeholder="correo">
                @error('email')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group col-md-6">
                <input type="tel" class="form-control form-control-sm" wire:model.defer='phone' id="phone"
                    placeholder="telefono o celular">
                @error('phone')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </form>
</div>
@include('common.modal.modalFooter')
