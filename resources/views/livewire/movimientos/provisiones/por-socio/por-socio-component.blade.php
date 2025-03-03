<div class="pt-4">
    <div class="card">

        <div wire:loading.class='overlay' class="d-none dark" wire:loading.class.remove='d-none'>
            <i class="fas fa-2x fa-sync-alt fa-spin"></i>
        </div>

        <div class="card-header">
            {{-- <button type="button" class="btn btn-info" wire:click="Add()"> <i class="fas fa-plus"></i> Add</button> --}}
            <div class="row">
                <div class="col-md-3">
                    <h3 class="card-title">Provisiones x Socio</h3>
                </div>
                <div class="col-md-6 mr-4">
                    @include('livewire.movimientos.provisiones.searBoxProvision')
                </div>
                <div class="col-ms-3 text-right">
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalEliminarFija">
                        <i class="fas fa-trash"></i> Eliminar</button>
                    <button type="button" class="btn btn-primary" data-toggle="modal"
                        data-target="#modalProvisionFija"> <i class="fas fa-plus-circle"></i> Nuevo</button>
                </div>
                <!-- Button trigger modal -->
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>MES</th>
                            <th>DESCRIPCION</th>
                            <th>CATEGORIA</th>
                            <th>SOCIO</th>
                            <th class="text-center">MONTO</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($total > 0 || $totalDolar > 0)
                            @foreach ($detalles as $item)
                                <tr>
                                    <td scope="row">
                                        {{ $item->date->format('m-Y') }}
                                    </td>
                                    <td>
                                        @if ($selected_id == $item->id)
                                            <input type="text" class="form-control form-control-sm"
                                                wire:model.defer="description">
                                        @else
                                            {{ $item->description }}
                                        @endif
                                    </td>
                                    <td>
                                        {{ $item->category->name }}
                                    </td>
                                    <td>
                                        {{ $item->partner->full_name }}
                                    </td>
                                    <td class="text-center">

                                        {{-- S/. <input type="number"> --}}
                                        <div class="btn-group ml-2" role="group" aria-label="">
                                            @if ($selected_id == $item->id)
                                                <input type="number" id="ed1_{{ $item->id }}"
                                                    style="min-width: 100px" class="form-control form-control-sm"
                                                    wire:focusout="update()" wire:model.defer='amount'
                                                    onfocus="this.select();">
                                                <button type="button" wire:click='update()'
                                                    class="btn btn-sm btn-success"><i class="fa fa-check"></i></button>
                                                <button type="button" class="btn btn-sm btn-danger"><i
                                                        class="fa fa-trash"></i></button>
                                            @else
                                                <input type="number" id="ed2_{{ $item->id }}"
                                                    style="min-width: 100px" wire:focusin="edit({{ $item->id }})"
                                                    class="form-control form-control-sm" value="{{ $item->amount }}"
                                                    readonly>
                                                <button type="button" wire:click='edit({{ $item->id }})'
                                                    class="btn btn-sm btn-primary"><i class="fa fa-pen"></i></button>
                                                <button type="button" wire:click='deleteRow({{ $item->id }})'
                                                    class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                                            @endif

                                        </div>

                                        <span>{{ $item->currency->name ?? 'Soles' }}</span>

                                        @if ($selected_id == $item->id)
                                            @error('amount')
                                                <span class="error d-block">{{ $message }}</span>
                                            @enderror
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="text-center" scope="row" colspan="5">No hay ningún registro</td>
                            </tr>
                        @endif
                    </tbody>

                    <tfoot>
                        <tr>
                            <td colspan="5">
                                {{ $detalles->links() }}
                            </td>
                        </tr>
                    </tfoot>


                </table>
            </div>
        </div>
        <div class="card-footer text-muted">
            <div class="row">
                <div class="col-md-6">

                </div>
                <div class="col-md-3 text-center">
                    <h3><span class="badge badge-pill badge-info">Total S/. {{ number_format($total, 2) }}</span></h3>
                </div>
                <div class="col-md-3 text-center">
                    <h3><span class="badge badge-pill badge-warning">Total $.
                            {{ number_format($totalDolar, 2) }}</span>
                    </h3>
                </div>
            </div>
        </div>
    </div>

    @include('livewire.movimientos.provisiones.por-socio.form_create')
    @include('livewire.movimientos.provisiones.por-socio.form_delete')

</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        window.livewire.on('error', msg => {
            Swal.fire({
                icon: 'error',
                title: 'Opss...!',
                text: msg,
            });
        });

        window.livewire.on('provision_agregado', msg => {
            Swal.fire({
                icon: 'success',
                title: '¡Buen Trabajo!',
                text: msg,
            });

            $('#modalProvisionFija').modal('hide');
        });

        window.livewire.on('provision_eliminado', msg => {
            Swal.fire({
                icon: 'success',
                title: '¡Buen Trabajo!',
                text: msg,
            });

            $('#modalEliminarFija').modal('hide');
        });


        $('#modalProvisionFija').on('show.bs.modal', function() {
            livewire.emit('resetUI');
        });
        $('#modalEliminarFija').on('show.bs.modal', function() {
            livewire.emit('resetUI');
        });

    });
</script>
