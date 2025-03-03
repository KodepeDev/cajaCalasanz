<div class="pt-4">
    <div class="card">

        <div wire:loading.class='overlay' class="d-none dark" wire:loading.class.remove='d-none'>
            <i class="fas fa-2x fa-sync-alt fa-spin"></i>
        </div>

        <div class="card-header">
            <h3 class="card-title">PROVISION FIJA</h3>
            <br>
            <div class="row mt-4">
                <div class="col-md-9">
                    @include('livewire.movimientos.provisiones.searBoxProvision')
                </div>
                <div class="col-md-3 text-right">
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modalEliminarFija">
                        <i class="fas fa-trash"></i> Eliminar</button>
                    <button type="button" class="btn btn-primary" data-toggle="modal"
                        data-target="#modalProvisionFija"> <i class="fas fa-plus-circle"></i> Nuevo</button>
                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalProvisionExport">
                        <i class="fas fa-download"></i> Exportar</button>
                </div>
                <!-- Button trigger modal -->
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>MES</th>
                            <th>DESCRIPCION</th>
                            <th>CATEGORIA</th>
                            <th>ESTUDIANTE</th>
                            <th>MONTO</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($total + $totalDolar > 0)
                            @foreach ($detalles as $item)
                                <tr>
                                    <td scope="row">{{ $item->date->format('m-Y') }}</td>
                                    <td>{{ $item->description }}</td>
                                    <td>{{ $item->category->name }}</td>
                                    <td>{{ $item->student->full_name }}</td>
                                    <td> {{ number_format($item->amount, 2) }}
                                        {{ $item->currency->name ?? 'Soles' }}
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="text-center" scope="row" colspan="6">No hay ningún registro</td>
                            </tr>
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6">
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
                    <h3><span class="badge badge-pill badge-warning">Total $. {{ number_format($totalDolar, 2) }}</span>
                    </h3>
                </div>
            </div>
        </div>
    </div>

    @include('livewire.movimientos.provisiones.fijas.form')
    @include('livewire.movimientos.provisiones.fijas.form-delete')

    {{-- @include('livewire.movimientos.provisiones.variables.modal-variable-export') --}}

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
        $('#modalProvisionExport').on('show.bs.modal', function() {
            livewire.emit('resetUI');
        });

    });
</script>
