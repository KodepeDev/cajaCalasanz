@include('common.modal.modalHeader')
<div class="modal-body">

    <div wire:loading.class='overlay' class="d-none" wire:loading.class.remove='d-none'>
        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
    </div>

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
                    <button type="button" wire:click="ConsutasApi()" class="btn btn-sm btn-info"><i
                            class="fas fa-search"></i></button>
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
                placeholder="Nombres" {{ $document_type == '6' ? 'readonly' : '' }}>
            @error('first_name')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group col-md-6">
            <input type="text" class="form-control form-control-sm" wire:model.lazy='last_name' id="last_name"
                placeholder="Apellidos" {{ $document_type == '6' ? 'readonly' : '' }}>
            @error('last_name')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-8">
            <input type="text" class="form-control form-control-sm" wire:model.lazy='address' id="address"
                placeholder="1234 Main St" {{ $document_type == '0' ? '' : 'readonly' }}>
            @error('address')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>
        {{-- <div class="form-group col-md-4">
                <input type="text" class="form-control form-control-sm" wire:model.defer='stand' id="stand" placeholder="ALMX">
                @error('stand')
                    <span class="error">{{$message}}</span>
                @enderror
            </div> --}}
    </div>
    <div class="form-row">
        <div class="form-group col-md-8">
            <div class="mb-3">
                <input type="email" class="form-control form-control-sm" wire:model.defer='email' id="email"
                    placeholder="correo">
                @error('email')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <input type="tel" class="form-control form-control-sm" wire:model.defer='phone' id="phone"
                        placeholder="telefono o celular">
                    @error('phone')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Activo / Baja</label>
                        <select class="form-control form-control-sm" name="" id=""
                            wire:model.defer='is_active'>
                            @if ($is_active)
                                <option value="1" selected>Activo</option>
                                <option value="0">Baja</option>
                            @else
                                <option value="1">Activo</option>
                                <option value="0" selected>Baja</option>
                            @endif
                        </select>
                    </div>
                </div>
            </div>
            <div class="mb-4">
                <input type="file" class="" wire:model.lazy='photo' id="{{ $photoId }}customFileLang"
                    accept="image/*" lang="es">
                @error('photo')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="form-group col-md-4 text-right">
            @if ($photo)
                <img class="rounder shadow" src="{{ $photo->temporaryUrl() }}" width="200px" height="200px"
                    alt="">
            @else
                <img class="rounder shadow" src="{{ asset('imagenes/profile-default.png') }}" width="200px"
                    height="200px" alt="">
            @endif
        </div>
    </div>
</div>
@include('common.modal.modalFooter')
