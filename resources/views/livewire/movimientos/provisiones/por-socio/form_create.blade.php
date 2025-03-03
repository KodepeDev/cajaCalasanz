<!-- Modal -->
<div wire:ignore.self class="modal fade" id="modalProvisionFija" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">

            <div wire:loading.class='overlay' wire:target='generate' class="d-none dark"
                wire:loading.class.remove='d-none'>
                <i class="fas fa-2x fa-sync-alt fa-spin"></i>
            </div>

            <div class="modal-header bg-info">
                <h5 class="modal-title">Crear Provisión x Socio</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="">Mes</label>
                            <input type="month" wire:model='date' class="form-control form-control" name=""
                                id="" aria-describedby="helpId" placeholder="">
                            @error('date')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">Categoría</label>
                            <select class="custom-select" wire:model.defer='category_id' name="" id="">
                                <option selected>Seleccione uno</option>
                                @foreach ($categorias as $name => $id)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        {{-- <div class="form-group col-md-3">
                            <label for="">Área o Etapa</label>
                            <select class="custom-select" wire:model.defer='stage_id' name="" id="">
                                <option selected>Seleccione área</option>
                                @foreach ($stages as $name => $id)
                                <option value="{{$id}}">{{$name}}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <span class="error">{{$message}}</span>
                            @enderror
                        </div> --}}
                        <div class="form-group col-md-6">
                            <label for="">Monto</label>
                            <div class="input-group mb-3">
                                <input required name="amount" id="currency" wire:model.defer="amount" type="number"
                                    min="0" class="form-control currency" placeholder="Monto">
                                <div class="input-group-append">
                                    <select name="" id="" wire:model.defer="currency_id"
                                        class="form-control">
                                        @foreach ($currency as $name => $id)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @error('amount')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="descripcion">Descripción</label>
                            <textarea class="form-control" wire:model.defer='description' required name="descripcion" id="descripcion"
                                rows="3"></textarea>
                            @error('description')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" wire:click='generate()' class="btn btn-primary">Generar</button>
            </div>
        </div>
    </div>
</div>
