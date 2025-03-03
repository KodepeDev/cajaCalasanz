<div>
    <div wire:ignore.self class="modal fade" id="ModalExportDeuda" data-backdrop="static" data-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Reporte de Deudas</h5>
                    <div class="card-tools float-right">

                        <div class="input-group mb-3">
                            <input type="text" class="form-control" wire:model.defer='stand' placeholder="STAND"
                                aria-label="STAND" aria-describedby="button-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" wire:click='BuscarDeuda()' type="button"
                                    id="button-addon2">Buscar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-body">

                    <div wire:loading.class='overlay' class="d-none" wire:loading.class.remove='d-none'>
                        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                    </div>

                    <div class="table-responsive" id="ImprimirDeuda">
                        <table class="table table-bordered table-hover table-sm" width="100%">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>MES</th>
                                    <th width="40%">DESCIPCION</th>
                                    {{-- <th>CATEGORIA</th> --}}
                                    <th>STAND</th>
                                    <th>SOLES</th>
                                    <th>DOLAR</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($total_prov + $total_prov_dolar > 0)
                                    @foreach ($provision_detalles as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td scope="row">{{ $item->date->format('m-Y') }}</td>
                                            <td>{{ $item->description }}</td>
                                            {{-- <td>{{$item->category->name}}</td> --}}
                                            <td>{{ $item->stand->name }}</td>
                                            <td class="text-center">
                                                @if ($item->currency->id !== 2)
                                                    S/. {{ number_format($item->amount, 2) }}
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($item->currency->id == 2)
                                                    $. {{ number_format($item->amount, 2) }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="text-center" scope="row" colspan="5">No hay ningún registro
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-right">
                                        TOTAL
                                    </td>
                                    <td colspan="1" class="text-center">
                                        S/. {{ number_format($total_prov, 2) }}
                                        @error('amount')
                                            <br><span class="error">{{ $message }}</span>
                                        @enderror
                                    </td>
                                    <td colspan="1" class="text-center">
                                        $. {{ number_format($total_prov_dolar, 2) }}
                                        @error('amount')
                                            <br><span class="error">{{ $message }}</span>
                                        @enderror
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>

                    @if ($total_prov + $total_prov_dolar > 0 && $stand != null)
                        <button type="button" class="btn btn-danger"
                            onclick="printJS({printable:'{{ route('reportes.deudas', $stand) }}', type: 'pdf', showModal:true, modalMessage: 'Cargando Documento ...'})">
                            <i class="fa fa-file-pdf"></i> Deudas PDF
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
