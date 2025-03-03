<div wire:ignore.self class="modal fade" id="modalProvision" role="dialog" tabindex="-1"
    aria-labelledby="modalProvisionLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title"><b>Seleccione Provisiones</h5>
                {{-- <button type="button"  class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> --}}
                <div class="card-tools">
                    <div class="btn-group" role="group" aria-label="">
                        <button type="button" wire:click="selectAll" class="btn btn-primary mr-2"><i
                                class="fa fa-check-square" aria-hidden="true"></i> Sel. Todo</button>
                        <button type="button" wire:click="selectNow" class="btn btn-info mr-2"><i class="fa fa-check"
                                aria-hidden="true"></i>
                            Solo {{ now()->format('m/Y') }}</button>
                        <button type="button" class="btn btn-danger" wire:click="$set('checkedProvision', [])"><i
                                class="fa fa-paint-brush" aria-hidden="true"></i> Deseleccionar</button>
                    </div>
                </div>
            </div>
            <div class="modal-body">

                <div wire:loading.class='overlay' class="d-none" wire:loading.class.remove='d-none'>
                    <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm">
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
                            @if ($total_prov > 0 || $total_prov_dolar > 0)
                                @foreach ($provision_detalles as $item)
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input " type="checkbox"
                                                    value="{{ $item->id }}" id="check{{ $item->id }}"
                                                    wire:model='checkedProvision'>
                                                <label class="form-check-label" for="defaultCheck1">

                                                </label>
                                            </div>
                                        </td>
                                        <td scope="row">{{ $item->date->format('m-Y') }}</td>
                                        <td>{{ $item->description }}</td>
                                        {{-- <td>{{$item->category->name}}</td> --}}
                                        <td>{{ $item->stand ? $item->stand->name : '' }}</td>
                                        <td class="text-center">
                                            @if ($item->currency->id == 1 || $item->currency->id == null)
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
                                    <td class="text-center" scope="row" colspan="6">No hay ningún registro</td>
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
                <button type="button" wire:click='SelectedProvisions()' wire:loading.attr='disabled'
                    class="btn btn-secondary" data-dismiss="modal">CONFIRMAR</button>
            </div>
        </div>
    </div>
</div>
