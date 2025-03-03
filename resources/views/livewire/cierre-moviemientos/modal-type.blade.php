<!-- Modal -->
<div wire:ignore.self class="modal fade" id="modalTypeClose" tabindex="-1" data-backdrop="static" role="dialog"
    aria-labelledby="modalTypeClose" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">TIPO DE CIERRE</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-6">
                        <label for="">MES - AÑO</label>
                        <input type="month" wire:model.defer="year" class="form-control form-control-sm"
                            name="" id="" aria-describedby="helpId" placeholder="">
                    </div>
                </div>
                <div class="row">
                    <div class="form-check col-md-6">
                        <div class="custom-control custom-radio">
                            <input class="custom-control-input" type="radio" id="customRadio1" wire:model.defer='type'
                                name="customRadio" value="MONTH">
                            <label for="customRadio1" class="custom-control-label">MENSUAL</label>
                        </div>
                    </div>
                    <div class="form-check col-md-6">
                        <div class="custom-control custom-radio">
                            <input class="custom-control-input" type="radio" id="customRadio2" wire:model.defer='type'
                                name="customRadio" value="YEAR">
                            <label for="customRadio2" class="custom-control-label">ANUAL</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"
                    wire:click="generar()">GENERAR</button>
            </div>
        </div>
    </div>
</div>
