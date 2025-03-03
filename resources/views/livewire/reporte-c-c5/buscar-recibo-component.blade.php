<div class="pt-4">
    <div class="container-fluid">
        <div class="card p-4">
            <div wire:loading.class='overlay' class="d-none dark" wire:loading.class.remove='d-none'>
                <i class="fas fa-2x fa-sync-alt fa-spin"></i>
            </div>
            <div class="card-header">
                <h3 class="card-title">Buscar Recibo</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <div class="row">
                            {{-- <div class="col-md-4 mb-2">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="start_date">Desde</label>
                                        <input type="date" class="form-control form-control-sm" id="start_date"
                                            wire:model.defer="start_date">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="end_date">Hasta</label>
                                        <input type="date" class="form-control form-control-sm" id="end_date"
                                            wire:model.defer="end_date">
                                    </div>
                                </div>
                            </div> --}}
                            {{-- <div class="col-md-3 mb-2">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input custom-control-input-danger" type="checkbox"
                                        id="userCheck" checked="" wire:model.lazy='hasUser'>
                                    <label for="userCheck" class="custom-control-label">Usuario</label>
                                </div>
                                <div class="form-group mt-2">
                                    <select class="custom-select custom-select-sm" {{ !$hasUser ? 'disabled' : '' }}
                                        id="inputGroupSelectUsuarios" wire:model.defer="user">
                                        <option value="" selected>Elija...</option>
                                        @foreach ($users as $name => $id)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> --}}
                            {{-- <div class="col-md-3 mb-2">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input custom-control-input-danger" type="checkbox"
                                        id="stageCheck" checked="" wire:model.lazy='hasEtapa'>
                                    <label for="stageCheck" class="custom-control-label">Etapa</label>
                                </div>
                                <div class="form-group mt-2">
                                    <select class="custom-select custom-select-sm" {{ !$hasEtapa ? 'disabled' : '' }}
                                        id="inputGroupSelectEtapa" wire:model.defer="etapa">
                                        <option value="" selected>Elija...</option>
                                        @foreach ($etapas as $name => $id)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> --}}
                            <div class="col-md-6 mb-2">
                                <div class="form-group">
                                    <label for="">Serie</label>
                                    <input type="text" class="form-control form-control-sm" name=""
                                        id="" aria-describedby="helpId" placeholder="" wire:model.defer="serie"
                                        value="{{ old('serie') }}">
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="form-group">
                                    <label for="">Numero</label>
                                    <input type="text" class="form-control form-control-sm" name=""
                                        id="" aria-describedby="helpId" placeholder=""
                                        wire:model.defer="numero" value="{{ old('numero') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-2 col-md-6 mt-4 text-center">
                        <div class="btn-group mt-1" role="group" aria-label="">
                            <button type="button" class="btn btn-sm btn-primary" wire:click='getReceipt'><i
                                    class="fa fa-search" aria-hidden="true"></i> Buscar</button>
                        </div>
                    </div>
                </div>

                <hr>

                @if ($idSummary)
                    {{-- @if ($summary->status == 'PAID')
                        @can('recibos.edit')
                            <a href="" class="btn btn-sm btn-warning float-right"><i class="fa fa-pen"
                                    aria-hidden="true"></i>
                                Editar</a>
                        @endcan
                    @endif --}}
                    @if ($summary->status == 'PAID' and $summary->date > $limitDate)
                        {{-- @can('recibos.delete') --}}
                        <button class="btn btn-sm btn-danger float-right mr-2" wire:click="showAnularModal"><i
                                class="fa fa-pen" aria-hidden="true"></i>
                            Anular</button>
                        {{-- @endcan --}}
                    @endif
                    @if ($summary->type == 'add')

                        <div class="btn-group mt-1" role="group" aria-label="">
                            <button type="button" class="btn btn-sm btn-danger mb-2" wire:click="changeType(1)"><i
                                    class="fa fa-file-pdf-o" aria-hidden="true"></i> A4</button>
                        </div>
                        @if ($showA4)
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe class="embed-responsive-item" id="{{ $iframeId }}"
                                    src="{{ route('movimientos.a4.recibo', $idSummary) }}" allowfullscreen></iframe>
                            </div>
                        @else
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe class="embed-responsive-item"
                                    src="{{ route('movimientos.cc5.recibo', $idSummary) }}" allowfullscreen></iframe>
                            </div>
                        @endif
                    @else
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item" id="{{ $iframeId }}"
                                src="{{ route('movimientos.a4.recibo', $idSummary) }}" allowfullscreen></iframe>
                        </div>
                    @endif

                @endif

            </div>
        </div>
    </div>
    @include('livewire.reporte-c-c5.anular-modal')
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
            window.livewire.on('showAnularModal', msg => {
                $('#modalAnularRegistro').modal('show');
            });
            window.livewire.on('closeModalAnular', msg => {
                $('#modalAnularRegistro').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Anulado...!',
                    text: msg,
                });

                var iframeId = "{{ $iframeId }}";
                document.getElementById(iframeId).contentWindow.location.reload();
            });

        });

        function anularRecibo() {
            Swal.fire({
                title: 'Estas seguro de anular este recibo?',
                text: "Este proceso no se puede revertir!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, Anular!'
            }).then((result) => {
                if (result.isConfirmed) {
                    livewire.emit('anularRecibo');

                }
            })
        }
    </script>
@endpush
