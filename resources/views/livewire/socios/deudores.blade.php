<div class="pt-4">
    <div class="container-fluid">
        <div class="card card-primary">
            <div wire:loading.class='overlay' class="d-none dark" wire:loading.class.remove='d-none'>
                <i class="fas fa-2x fa-sync-alt fa-spin"></i>
            </div>
            <div class="card-header">
                <h3 class="card-title"><b>Listado de deudores</b> <i class="fas fa-chart-line"></i></h3>
                <div class="card-tools">
                    <button wire:click="exportDatas" class="btn btn-info"> <i class="fas fa-file-excel"></i>
                        Exportar</button>
                </div>
            </div>

            <div class="card-body">

                <div class="">

                    <table id="deudores" class="table table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th width="10%">DOCUMENTO</th>
                                <th width="30%">ESTUDIANTE</th>
                                <th width="30%">PADRE/MADRE O TUTOR</th>
                                <th>SOLES</th>
                                <th>DOLAR</th>
                                <th class="text-center" width="10%">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($socios as $item)
                                <tr>
                                    <td>{{ $item->document }}</td>
                                    <td>{{ $item->full_name }}</td>
                                    <td>{{ $item->tutor?->full_name }}</td>
                                    <td>S/.
                                        {{ number_format($item->details->where('currency_id', 1)->sum('amount'), 2, '.', ',') }}
                                    </td>
                                    <td>$.
                                        {{ number_format($item->details->where('currency_id', 2)->sum('amount'), 2, '.', ',') }}
                                    </td>
                                    <td class="text-center">
                                        <button wire:click="showModalDetail({{ $item->id }})"
                                            class="btn btn-sm btn-info"><i class="fas fa-eye"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5" class="text-right">{{ $socios->links() }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>

            <!-- Modal -->
            <div wire:ignore.self class="modal fade" id="modalDetails" data-keyboard="false" tabindex="-1"
                aria-labelledby="modalDetailsLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Detalle de Deudas de {{ $socio_name }}
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <table class="table table-hover table-striped">
                                <thead class="bg-secondary">
                                    <tr>
                                        <th>PERIODO</th>
                                        <th>DESCRIPCION</th>
                                        <th>STAND</th>
                                        <th>SOLES</th>
                                        <th>DOLAR</th>
                                    </tr>
                                </thead>
                                @if ($detalles)
                                    <tbody>
                                        @foreach ($detalles as $item)
                                            <tr>
                                                <td scope="row">{{ $item->date->format('m/Y') }}</td>
                                                <td>
                                                    <span data-toggle="tooltip" data-placement="top"
                                                        title="{{ $item->category->name }}">{{ $item->description }}</span>
                                                </td>
                                                <td class="text-center">{{ $item->stand ? $item->stand->name : '' }}
                                                </td>
                                                <td class="text-center">S/.
                                                    {{ number_format($item->currency_id == 1 ? $item->amount : 0, 2, '.', ',') }}
                                                </td>
                                                <td class="text-center">$.
                                                    {{ number_format($item->currency_id == 2 ? $item->amount : 0, 2, '.', ',') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="text-bold">
                                            <td colspan="3"></td>
                                            <td colspan="" class="text-center">
                                                S/.
                                                {{ number_format($detalles->where('currency_id', 1)->sum('amount'), 2, '.', ',') }}
                                            </td>
                                            <td colspan="" class="text-center">
                                                $.
                                                {{ number_format($detalles->where('currency_id', 2)->sum('amount'), 2, '.', ',') }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">CERRAR</button>
                            <button type="button" class="btn btn-success" wire:click="exportData">
                                <i class="fas fa-file-excel"></i> EXPORTAR
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Modal -->
        </div>
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12 ">

                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>S/. {{ number_format($totalSoles, 2, '.', ',') }}</h3>

                        <p>Total de deudas en soles</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <label class="small-box-footer">
                        Total
                    </label>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12 ">

                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>$. {{ number_format($totalDolares, 2, '.', ',') }}</h3>

                        <p>Total de deudas en dolares</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <label class="small-box-footer">
                        Total
                    </label>
                </div>
            </div>
        </div>
    </div>
    @push('js')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                window.livewire.on('showModalDetails', () => {
                    $('#modalDetails').modal('show');
                });
            });
        </script>
    @endpush
</div>
