<!-- Modal -->
<div wire:ignore.self class="modal fade" id="modalProvisionExport" data-backdrop="static" data-keyboard="false"
    tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">

            <div wire:loading.class='overlay' wire:target='exportVariableExcel' class="d-none dark"
                wire:loading.class.remove='d-none'>
                <i class="fas fa-2x fa-sync-alt fa-spin"></i>
            </div>

            <div class="modal-header bg-info">
                <h5 class="modal-title">EXPORTAR DEUDAS</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="">MES</label>
                            <input type="month" wire:model.defer='date' class="form-control form-control"
                                name="" id="" aria-describedby="helpId" placeholder="">
                            @error('date')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">CATEGORÍA</label>
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
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                {{-- @php
                    $tipo = 3;
                    $url = "?tipo=" . $tipo . "&categoria=" .
                    $category_id . "&mes=" . $meses . "&estage=" . $stage_id . "";
                @endphp --}}
                {{-- <button type="button" wire:click='exportVariablePdf' class="btn btn-danger"><i class="fa fa-file-pdf" aria-hidden="true"></i> PDF</button> --}}
                {{-- <a href="{{ url('admin/export/excel/provisiones'.$url) }}" class="btn btn-success"><i class="fa fa-file-excel" aria-hidden="true"></i> Excel</a> --}}
                <a href="#" wire:click='exportVariableExcel' class="btn btn-success"><i class="fa fa-file-excel"
                        aria-hidden="true"></i> Excel</a>
            </div>
        </div>
    </div>
</div>
