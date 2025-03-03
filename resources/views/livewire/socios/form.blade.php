@include('common.modal.modalHeader')
<div class="modal-body">

    <div wire:loading.class='overlay' class="dark d-none" wire:loading.class.remove='d-none'>
        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
    </div>

    <form wire:submit.prevent="save">
        <div class="card">
            <div class="card-body">
                <h3>DATOS DEL ESTUDIANTE</h3>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <select class="form-control" wire:model.lazy='document_type'
                                    wire:change='chageDocumentType()'>
                                    <option value="1">DNI</option>
                                </select>
                            </div>
                            <input type="number" wire:model.defer='document' class="form-control"
                                wire:loading.attr='readonly' aria-label="Text input with dropdown button">
                        </div>
                        @error('document_type')
                            <span class="error">{{ $message }}</span>
                        @enderror
                        @error('document')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <input type="text" class="form-control" wire:model.lazy='last_name' id="last_name"
                            placeholder="Apellidos">
                        @error('last_name')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <input type="text" class="form-control" wire:model.lazy='first_name' id="fist_name"
                            placeholder="Nombres">
                        @error('first_name')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-8">
                        <input type="text" class="form-control" wire:model.lazy='address' id="address"
                            placeholder="1234 Main St">
                        @error('address')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <select class="custom-select" name="" id="" wire:model.defer='grade'>
                            <option value="" selected>Seleccione un Grado</option>
                            <option value="INICIAL3">INICIAL 3 AÑOS</option>
                            <option value="INICIAL4">INICIAL 4 AÑOS</option>
                            <option value="INICIAL5">INICIAL 5 AÑOS</option>
                            <option value="PRIMARIA1">PRIMARIA 1</option>
                            <option value="PRIMARIA2">PRIMARIA 2</option>
                            <option value="PRIMARIA3">PRIMARIA 3</option>
                            <option value="PRIMARIA4">PRIMARIA 4</option>
                            <option value="PRIMARIA5">PRIMARIA 5</option>
                            <option value="PRIMARIA6">PRIMARIA 6</option>
                            <option value="SEGUNDARIA1">SEGUNDARIA 1</option>
                            <option value="SEGUNDARIA2">SEGUNDARIA 2</option>
                            <option value="SEGUNDARIA3">SEGUNDARIA 3</option>
                            <option value="SEGUNDARIA4">SEGUNDARIA 4</option>
                            <option value="SEGUNDARIA5">SEGUNDARIA 5</option>
                        </select>
                        @error('grade')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-8">
                        <div class="mb-3">
                            <input type="email" class="form-control" wire:model.defer='email' id="email"
                                placeholder="correo">
                            @error('email')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <input type="tel" class="form-control" wire:model.defer='phone' id="phone"
                                    placeholder="telefono o celular">
                                @error('phone')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-4 custom-file">
                            <input type="file" class="" wire:model.lazy='photo'
                                id="{{ $photoId }}customFileLang" accept="image/*" lang="es">
                            @error('photo')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-12">
                            <label for="">Información Adicional</label>
                            <textarea wire:model.defer='description' class="form-control" name="description" id="description" rows="3"></textarea>
                            @error('description')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group col-md-4 text-center d-flex justify-content-center align-items-center">
                        @if ($photo)
                            <img class="img-fluid img-circle shadow" src="{{ $photo->temporaryUrl() }}"
                                width="200px" height="200px" alt="">
                        @else
                            <img class="img-fluid img-circle shadow"
                                src="{{ asset('imagenes/profile-default.png') }}" width="200px" height="200px"
                                alt="">
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h3 class="mb-1">DATOS DEL PADRE / MADRE O TUTOR</h3>
                <i class="text-warning">El tutor, apoderado u otros, no puede ser un menor de edad.</i>
                <div class="form-row mt-2">
                    <div class="form-group col-md-6">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <select class="form-control" wire:model.lazy='tutor_document_type'
                                    wire:change='chageDocumentType()'>
                                    <option value="1">DNI</option>
                                </select>
                            </div>
                            <input type="number" wire:model.defer='tutor_document' class="form-control"
                                wire:change='clearDataApi()' wire:loading.attr='readonly'
                                aria-label="Text input with dropdown button">

                            <div class="input-group-append">
                                <button type="button" wire:click="ConsutasApi()" class="btn btn-sm btn-info"><i
                                        class="fas fa-search"></i> RECIEC</button>
                            </div>
                        </div>
                        @error('tutor_document_type')
                            <span class="error">{{ $message }}</span>
                        @enderror
                        @error('tutor_document')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <input type="text" class="form-control" wire:model.lazy='tutor_last_name' id="last_name"
                            placeholder="Apellidos">
                        @error('tutor_last_name')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <input type="text" class="form-control" wire:model.lazy='tutor_first_name' id="fist_name"
                            placeholder="Nombres">
                        @error('tutor_first_name')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-8">
                        <input type="text" class="form-control" wire:model.lazy='tutor_address' id="address"
                            placeholder="1234 Main St">
                        @error('tutor_address')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <select class="custom-select" name="" id="" wire:model.defer='tutor_type'>
                            <option value="" selected>Seleccione Parentesco</option>
                            <option value="PADRE">PADRE</option>
                            <option value="MADRE">MADRE</option>
                            <option value="TUTOR">TURTOR</option>
                            <option value="APODERADO">APODERADO</option>
                            <option value="OTROS">OTROS CASOS</option>
                        </select>
                        @error('tutor_type')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <input type="email" class="form-control" wire:model.defer='tutor_email' id="email"
                            placeholder="correo">
                        @error('tutor_email')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <input type="tel" class="form-control" wire:model.defer='tutor_phone' id="phone"
                            placeholder="telefono o celular">
                        @error('tutor_phone')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-12">
                        <label for="">Información Adicional</label>
                        <textarea wire:model.defer='description' class="form-control" name="tutor_description" id="description"
                            rows="3"></textarea>
                        @error('tutor_description')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" wire:loading.attr='disabled' wire:target='photo, save'
            class="btn btn-warning close-modal">GUARDAR</button>
    </form>
</div>

<div class="modal-footer">
    <button type="button" wire:click.prevent="resetUI()" wire:loading.attr='disabled' wire:target='photo, resetUI'
        class="btn btn-secondary" data-dismiss="modal">CERRAR</button>
</div>
</div>
</div>
</div>
