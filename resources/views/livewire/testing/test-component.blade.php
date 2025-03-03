<div class="card-body">
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>MES</th>
                    <th>DESCRIPCION</th>
                    <th>CATEGORIA</th>
                    <th>STAND</th>
                    <th>SOCIO</th>
                    <th class="text-center">MONTO</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($detales as $item)
                <tr>
                    <td scope="row">
                        {{$item->date->format('m-Y')}}
                    </td>
                    <td>
                        @if ($selected_id == $item->id)
                        <input type="text" class="form-control form-control-sm" wire:model.defer="description">
                        @else
                        {{$item->description}}
                        @endif
                    </td>
                    <td>
                        {{$item->category->name}}
                    </td>
                    <td>
                        {{$item->stand->name}}
                    </td>
                    <td>
                        @if ($item->stand->stage)
                        {{$item->stand->stage->name}}
                        @else
                        NO DEFINIDO
                        @endif
                    </td>
                    <td class="text-center">

                    {{-- S/. <input type="number"> --}}
                            <div class="btn-group ml-2" role="group" aria-label="">
                                @if ($selected_id == $item->id)
                                S/. <input type="number" id="ed1_{{$item->id}}" style="min-width: 100px" class="form-control form-control-sm" wire:focusout="update()" wire:model.defer='amount' onfocus="this.select();">
                                <button type="button" wire:click='update()' class="btn btn-sm btn-success"><i class="fa fa-check"></i></button>
                                <button type="button" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                                @else
                                S/. <input type="number" id="ed2_{{$item->id}}" style="min-width: 100px" wire:focusin="edit({{$item->id}})"  class="form-control form-control-sm" value="{{$item->amount}}" readonly>
                                <button type="button" wire:click='edit({{$item->id}})' class="btn btn-sm btn-primary"><i class="fa fa-pen"></i></button>
                                <button type="button" wire:click='deleteRow({{$item->id}})' class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                                @endif

                            </div>
                        @if ($selected_id == $item->id)
                            @error('amount')
                                <span class="error d-block">{{$message}}</span>
                            @enderror
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>


        </table>
    </div>
</div>
