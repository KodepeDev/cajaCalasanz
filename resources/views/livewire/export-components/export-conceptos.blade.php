<div class="pt-4">

    <div class="card">
        <div class="card-header">
            <h5 class="card-title" id="staticBackdropLabel">Reporte de Conceptos</h5>
            <div class="card-tools">
                <button type="button" class="btn btn-success" wire:click='exportarExcel' wire:loading.attr='disabled'>
                    <i class="fa fa-file-excel" aria-hidden="true"></i> Exportar
                </button>
            </div>
        </div>
        <div class="card-body">

            <div wire:loading.class='overlay' class="d-none" wire:loading.class.remove='d-none'>
                <i class="fas fa-2x fa-sync-alt fa-spin"></i>
            </div>

            <div class="row">
                <div class="form-group col-md-4">
                    <label for="">Estado</label>
                    <select class="form-control" wire:model="status">
                        <option value="1">PAGADOS</option>
                        <option value="0">PENDIENTES</option>
                    </select>
                    <small id="helpId" class="text-muted">*Pagado o pendientes</small>
                    @error('status')
                        <small id="helpId" class="text-muted error">*{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group col-md-4">
                    <label for="">Mes</label>
                    <input type="month" name="" id="" wire:model="meses" class="form-control"
                        placeholder="" aria-describedby="helpId">
                    <small id="helpId" class="text-muted">Periodo correspondiente</small>
                    @error('meses')
                        <small id="helpId" class="text-muted error">*{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group col-md-4">
                    <label for="">Conceptos</label>
                    <select class="custom-select" wire:model='category_id' name="" id="">
                        <option value="" selected>Todos o Seleccione uno</option>
                        @foreach ($categorias as $name => $id)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    <small id="helpId" class="text-muted">Concepto del movimiento</small>
                    @error('category_id')
                        <small id="helpId" class="text-muted error">*{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div class="table-responsive" id="printJS-form">

                @if ($cats->count() > 0)
                    @foreach ($cats as $categoria)
                        @php
                            $first_day = Carbon\Carbon::parse($meses)->firstOfMonth();
                            $last_day = Carbon\Carbon::parse($meses)->endOfMonth();
                            $movis = $categoria
                                ->detalles($categoria->id, $first_day, $last_day, $status)
                                ->paginate(10, ['*'], 'movimientos_' . $categoria->id);
                        @endphp
                        @if (count($movis) > 0)
                            <table class="table table-bordered table-sm" width="100%">
                                <thead class="thead-dark">
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="10%">MES</th>
                                        <th width="40%">DESCIPCION</th>
                                        <th width="10%">STAND</th>
                                        <th width="15%">SOCIO</th>
                                        <th width="10%">ESTADO</th>
                                        <th class="text-center">
                                            TOTAL
                                        </th>
                                    </tr>
                                    <tr class="bg-primary">
                                        <th colspan="5">{{ $categoria->name }}</th>
                                        @if ($status)
                                            <th colspan="2" class="text-center"> Total: S/.
                                                {{ number_format($categoria->detalles($categoria->id, $first_day, $last_day, $status)->where('currency_id', '!=', 2)->sum('amount') +$categoria->detalles($categoria->id, $first_day, $last_day, $status)->where('currency_id', 2)->sum('changed_amount'),2) }}
                                            </th>
                                        @else
                                            <th colspan="1" class="text-center"> Total: S/.
                                                {{ number_format($categoria->detalles($categoria->id, $first_day, $last_day, $status)->where('currency_id', '!=', 2)->sum('amount'),2) }}
                                            </th>
                                            <th colspan="1" class="text-center"> Total: $.
                                                {{ number_format($categoria->detalles($categoria->id, $first_day, $last_day, $status)->where('currency_id', 2)->sum('amount'),2) }}
                                            </th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($movis as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td scope="row">{{ $item->date->format('m-Y') }}</td>
                                            <td>{{ $item->description }}</td>
                                            @if ($item->stand)
                                                <td>{{ $item->stand->name }}</td>
                                            @else
                                                <td>S/N</td>
                                            @endif
                                            @if ($item->partner)
                                                <td>{{ $item->partner->full_name }}</td>
                                            @else
                                                <td>S/N</td>
                                            @endif
                                            <td>{{ $item->status === 1 ? 'PAGADO' : 'PENDIENTE' }}</td>
                                            <td class="text-center">
                                                @if ($item->currency->id == 2)
                                                    $. {{ number_format($item->amount, 2) }}
                                                @else
                                                    S/. {{ number_format($item->amount, 2) }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                <tfoot>
                                    <tr>
                                        <td colspan="7" id="dasda{{ $categoria->id }}">
                                            {{ $movis->appends(['tab' => 'movimientos_' . $categoria->id])->links() }}
                                        </td>
                                    </tr>
                                </tfoot>
                                </tbody>
                            </table>
                        @endif
                    @endforeach
                @endif
            </div>

        </div>
        <div class="card-footer">

        </div>
    </div>

</div>

@section('js')>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.livewire.on('error', msg => {
                Swal.fire({
                    icon: 'error',
                    title: 'Opss...!',
                    text: msg,
                });
            });
        });
    </script>
@stop
