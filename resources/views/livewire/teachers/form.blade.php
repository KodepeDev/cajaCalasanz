@include('common.modal.modalHeader')
<div class="modal-body">

    <div wire:loading.class='overlay' class="dark d-none" wire:loading.class.remove='d-none'>
        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
    </div>

        <div class="card">
            <div class="card-body">
                <h3>DATOS DEL DOCENTE</h3>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>DOCUMENTO</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <select class="form-control" wire:model.defer='document_type'>
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
                    <div class="form-group col-md-6">
                        <label for="">NOMBRE COMPLETO</label>
                        <input type="text" class="form-control" wire:model.defer='full_name' id="full_name"
                            placeholder="Apellidos">
                        @error('full_name')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-8">
                        <label for="">DIRECCIÓN</label>
                        <input type="text" class="form-control" wire:model.defer='address' id="address"
                            placeholder="1234 Main St">
                        @error('address')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">GRADO</label>
                        <select class="custom-select" name="" id="" wire:model.defer='grade_id'>
                            <option value="" selected>Seleccione un Grado</option>
                            @foreach ($grades as $name => $id)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('grade_id')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-8">
                        <div class="mb-3">
                            <label for="">CORREO</label>
                            <input type="email" class="form-control" wire:model.defer='email' id="email"
                                placeholder="correo">
                            @error('email')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="">CELULAR</label>
                                <input type="tel" class="form-control" wire:model.defer='phone' id="phone"
                                    placeholder="telefono o celular">
                                @error('phone')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
@include('common.modal.modalFooter')