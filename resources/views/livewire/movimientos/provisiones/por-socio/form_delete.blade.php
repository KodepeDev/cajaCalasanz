<!-- Modal -->
<div wire:ignore.self class="modal fade" id="modalEliminarFija" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">

            <div wire:loading.class='overlay' wire:target='EliminarMes' class="d-none dark"
                wire:loading.class.remove='d-none'>
                <i class="fas fa-2x fa-sync-alt fa-spin"></i>
            </div>

            <div class="modal-header bg-warning">
                <h5 class="modal-title">Eliminar Provisiones</h5>
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
                        .<div class="form-group col-md-12">
                            <label for="">Descripcion</label>
                            <textarea class="form-control" name="" id="" rows="3" wire:model.defer='description'></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click='EliminarMes()' class="btn btn-warning"> <i class="fas fa-trash"></i>
                    Eliminar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
