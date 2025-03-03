<div class="pt-4">
    <div class="container-fluid">
        <div class="card p-4">
            <div wire:loading.class='overlay' class="d-none dark" wire:loading.class.remove='d-none'>
                <i class="fas fa-2x fa-sync-alt fa-spin"></i>
            </div>
            <div class="card-header">
                <h3 class="card-title">Reporte de Ingresos Detallados</h3>
            </div>
            <div class="card-body">

                <div class="row">
                    <div class="form-group col-md-10">
                        <div class="row">
                            <div class="col-md-4 mb-2">
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
                            </div>
                            <div class="col-md-3 mb-2">
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
                            </div>
                            <div class="col-md-3 mb-2">
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
                            </div>
                            <div class="col-md-2 mb-2">
                                <div class="form-group">
                                    <label for="">Stand</label>
                                    <input type="text" class="form-control form-control-sm" name=""
                                        id="" aria-describedby="helpId" placeholder=""
                                        wire:model.defer="stand">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-2 col-md-2 mt-4 text-center">
                        <div class="btn-group mt-1" role="group" aria-label="">
                            <button type="button" class="btn btn-sm btn-danger m2-2" wire:click="limpiar"><i
                                    class="fa fa-paint-brush" aria-hidden="true"></i> Limpiar</button>
                            <button type="button" class="btn btn-sm btn-primary" wire:click='buscar'><i
                                    class="fa fa-search" aria-hidden="true"></i> Buscar</button>
                        </div>
                    </div>
                </div>

                <hr>

                @if ($url)
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

            window.livewire.on('error_fecha', msg => {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: msg,
                });
            });

        });
    </script>
@endpush
