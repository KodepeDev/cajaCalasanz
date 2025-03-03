<div class="pt-4">
    <div class="container-fluid">
        <div class="card p-4">
            <div wire:loading.class='overlay' class="d-none dark" wire:loading.class.remove='d-none'>
                <i class="fas fa-2x fa-sync-alt fa-spin"></i>
            </div>
            <div class="card-header">
                <h3 class="card-title">DESCARGA DE RECIBOS MASIVOS</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <div class="form-group">
                                    <label for="">Serie</label>
                                    <input type="text" class="form-control form-control-sm" name=""
                                        id="" aria-describedby="helpId" placeholder="" wire:model.defer="serie"
                                        value="{{ old('serie') }}">
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="form-group">
                                    <label for="">Numero Inicial</label>
                                    <input type="text" class="form-control form-control-sm" name=""
                                        id="" aria-describedby="helpId" placeholder=""
                                        wire:model.defer="numero1" value="{{ old('numero1') }}">
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="form-group">
                                    <label for="">Numero Final</label>
                                    <input type="text" class="form-control form-control-sm" name=""
                                        id="" aria-describedby="helpId" placeholder=""
                                        wire:model.defer="numero2" value="{{ old('numero2') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-2 col-md-6 mt-4 text-center">
                        <div class="btn-group mt-1" role="group" aria-label="">
                            <button type="button" class="btn btn-sm btn-primary" wire:click='getReceipts'><i
                                    class="fa fa-search" aria-hidden="true"></i> Buscar</button>
                        </div>
                    </div>
                </div>

                <hr>

                @if ($idSummary)
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="{{ $url }}" allowfullscreen></iframe>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            window.livewire.on('error', msg => {
                Swal.fire({
                    icon: 'error',
                    title: 'Opss...!',
                    text: msg,
                });
            });
            // window.livewire.on('closeModalAnular', msg => {
            //     $('#modalAnularRegistro').modal('hide');
            //     Swal.fire({
            //         icon: 'success',
            //         title: 'Anulado...!',
            //         text: msg,
            //     });

            //     var iframeId = "{{ $iframeId }}";
            //     document.getElementById(iframeId).contentWindow.location.reload();
            // });

        });
    </script>
@endpush
