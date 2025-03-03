<table>
    <thead>
        <tr class="content thead">
            <th align="left">
                <img src="{{ config('kodepe.logo') }}" alt="" class="ticket_logo">
            </th>
            <th width="50%" colspan="2">
                <h3>"{{ $company->company_name }}"</h3>
                <h5>{{ config('kodepe.empresa_tag') }}</h5>
                <p>{{ config('kodepe.empresa_history') }} RUC: {{ $company->company_ruc }}</p>
            </th>
            <th width="30%" colspan="2">
                <h3>RECIBO CAJA - <i>{{ $data->type == 'add' ? 'INGRESO' : 'GASTO' }}</i></h3>
                <hr>
                <h3>{{ $data->recipt_series . ' - ' . str_pad($data->recipt_number, 8, '0', STR_PAD_LEFT) }}
                </h3>
            </th>
        </tr>
        <tr class="content">
            <th colspan="3">DATOS CLIENTE O PROVEEDOR</th>
            <th>FECHA DE EMISION</th>
            <th>ATENDIDO POR</th>
        </tr>
        <tr class="content">
            <td><b>N° DOC: </b> {{ $data->customer->document }}</td>
            <td><b>NOMBRE: </b> {{ $data->customer->full_name }}</td>
            <td><b>DIRECCIÓN: </b> {{ $data->customer->address }}</td>
            <td>{{ $data->date->format('d/m/Y') }}
                {{ $data->created_at->format('h:i A') }}</td>
            <td><b>{{ $data->user->first_name }}</b></td>
        </tr>
    </thead>
</table>
<table style="margin-top: 10px">
    <thead class="thead content">
        <tr>
            <th style="text-align: left" class="px-6 py-3">MES</th>
            <th style="text-align: left" class="px-6 py-3" width="40%">DESCRIPCIÓN</th>
            {{-- <th style="text-align: left" class="px-6 py-3">CATEGORIA</th> --}}
            @if ($data->type == 'add')
                <th class="px-6 py-3">STAND</th>
            @endif
            <th style="text-align: right" class="px-6 py-3">MONTO</th>
        </tr>
    </thead>
    <tbody class="content">
        {{-- <tr>
                <td style="text-align: left" scope="row">{{$data->concept}}</td>
                <td>{{$data->stand}}</td>
                <td style="text-align: right">{{number_format($data->amount, 2)}}</td>
            </tr> --}}
        @if ($data->status == 'PAID')
            @foreach ($data->details as $item)
                <tr>
                    <td style="text-align: left" scope="row">{{ $item->date->format('m-Y') }}</td>
                    <td style="text-align: left" scope="row">{{ $item->description }}</td>
                    {{-- <td style="text-align: left" scope="row">{{$item->category->name}}</td> --}}
                    @if ($data->type == 'add')
                        @if ($item->stand)
                            <td style="text-align: left" scope="row">{{ $item->stand->name }}</td>
                        @else
                            <td>S/N</td>
                        @endif
                    @endif
                    <td style="text-align: right">
                        {{ number_format($item->currency_id == 1 ? $item->amount : $item->changed_amount, 2) }}</td>
                </tr>
            @endforeach
        @else
            @foreach ($data->nulledDetails as $items)
                <tr>
                    <td style="text-align: left" scope="row">{{ $items->date->format('m-Y') }}</td>
                    <td style="text-align: left" scope="row">{{ $items->description }}</td>
                    {{-- <td style="text-align: left" scope="row">{{$items->category->name}}</td> --}}
                    @if ($data->type == 'add')
                        <td style="text-align: left" scope="row">{{ $items->stand }}</td>
                    @endif
                    <td style="text-align: right">{{ number_format($items->amount, 2) }}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
    <tfoot>
        <tr>
            @if ($data->type == 'add')
                <td colspan="2"><i style="text-align: center">SON {{ $textoTotal }}</i></td>
                <td colspan="1" scope="row" style="text-align: right"><b>TOTAL S/.:</b></td>
            @else
                <td><i style="text-align: center">SON {{ $textoTotal }}</i></td>
                <td colspan="1" scope="row" style="text-align: right"><b>TOTAL S/.:</b></td>
            @endif
            <td scope="row" class="text-red-800 text-right" style="text-align: right" colspan="1">
                <b>{{ number_format($data->amount, 2) }}</b>
            </td>
        </tr>
    </tfoot>
</table>

<table class="aditional" style="margin-top: 10px;">
    <thead class="thead content">
        <tr>
            <th colspan="4" style="text-align: center">INFORMACIÓN ADICIONAL</th>
        </tr>
    </thead>
    <tbody class="">
        <tr>
            <th style="text-align: left" scope="row">PAGADO POR:</th>
            <td style="text-align: left" scope="row">{{ $data->paid_by }}</td>
            <th style="text-align: left" scope="row">#OPER/FACT/BOLET/ETC:</th>
            <td style="text-align: left" scope="row">{{ $data->operation_number }}</td>
        </tr>
        <tr>
            <th style="text-align: left" scope="row">OBSERVACIONES:</th>
            <td style="text-align: left" scope="row">{{ $data->observation }}</td>
        </tr>
    </tbody>
</table>
