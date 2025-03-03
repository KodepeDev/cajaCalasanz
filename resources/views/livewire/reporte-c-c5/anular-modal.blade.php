<div wire:ignore.self class="modal fade" id="modalAnularRegistro" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="modalAnularRegistroLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAnularRegistroLabel">Anular Recibo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="">Fecha de Anulacion</label>
                        <input type="date" wire:model='date' disabled class="form-control" name=""
                            id="">
                    </div>
                    <div class="form-group col-md-12">
                        <label for="">Motivo de Anulación</label>
                        <textarea class="form-control" wire:model.defer='motivo_anulacion' name="" id="" rows="3"></textarea>
                        @error('motivo_anulacion')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <hr>
                <h5 class="text-danger"><i>Los detalles del recibo se revertirán para ser cobrados nuevamente.</i></h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" wire:click="cancelar()"
                    data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" onclick="anularRecibo()">Anular</button>
            </div>

        </div>
    </div>
</div>
