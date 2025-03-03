<div>
    <div wire:loading.class='overlay' class="d-none dark" wire:loading.class.remove='d-none'>
        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
    </div>

    <div class="card-box pd-20">
        <h5 class="h4 text-blue">Importación de Stands - Puestos</h5>
        <div class="mt-4 mb-3">
            <form class="form" wire:submit.prevent="validar" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="file">Archivo .xls/.xlsx</label>
                    <input type="file" class="form-control" wire:model.defer="file" id="file"
                        aria-describedby="fileHelp" accept=".xlsx, .xls">
                    <small id="fileHelp" class="form-text text-muted">Solo se aceptan archivos .xls y .xlsx.</small>
                    @error('file')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Validar Importación</button>
            </form>
        </div>
    </div>
    <!-- Modal -->
    <div wire:ignore class="modal fade" id="wantSaveModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-hidden="true" aria-labelledby="wantSaveModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="wantSaveModalLabel">Registro Validado</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Su datos de importación fueron validados satisfactoriamente. ¿Desea Guardalo?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" wire:click="saveImport">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- end Modal -->
</div>

@push('js')
    {{-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.livewire.on('error', msg => {
                Swal.fire({
                    icon: 'error',
                    title: 'Opss!',
                    text: msg,
                });
            });
            window.livewire.on('close_modal', msg => {
                $('#wantSaveModal').modal('hide');
            });
            window.livewire.on('want_save', msg => {
                $('#wantSaveModal').modal('show');
            });

            $('#wantSaveModal').on('hidden.bs.modal', function(e) {
                // livewire.emit('resetInputs');
            });

        });

        function eliminarAtt(id) {
            Swal.fire({
                title: '¿Estas seguro de eliminar este registro?',
                text: "Recuerde que ya no podrá ser recuperado!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, Eliminar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    livewire.emit('deleteAttendance', id);
                }
            })
        }
    </script>
@endpush
