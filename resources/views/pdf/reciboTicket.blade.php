<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="application/pdf; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Recibo N°{{ $data->recipt_series }}-{{ $data->recipt_number }}</title>
    <style>
        body {
            max-width: 127mm !important;
            margin: 0 auto;
            margin-top: -20px;
            font-size: 1.2rem;
            line-height: 1;
            font-family: Arial, Helvetica, sans-serif;
            overflow: hidden;
        }

        body p {
            font-size: 0.9rem;
        }

        hr {
            border: 0.4px solid gray;
        }

        table {
            text-align: center;
            width: 100%;
            font-size: 0.9rem;
            border: 0.5px solid grey;
            border-spacing: 0;
            border-radius: 2px;
            overflow: hidden;

        }

        th,
        td {
            padding: 0.4rem;
            font-size: 0.7rem;
        }

        .thead {
            background-color: gray;
            color: white;
            padding: 0.1rem;
        }

        header {
            text-align: center;
            line-height: 1;
        }

        header h1 {
            font-size: 1rem;
        }

        header h6 {
            line-height: 1;
        }

        .ticket_logo {
            width: 70px;
        }

        small {
            background-color: grey;
            padding: 0.4rem;
            border-radius: 2px;
            display: block;
            color: white;
        }

        .body_content {
            position: relative;
        }

        .nulled_container {
            color: red;
            text-align: center;
            border: 2px solid red;
            position: absolute;
            width: 350px;
            height: 180px;
            top: 35%;
            left: 10px;
            transform: translate(50%, -50%);
            transform: rotate(-45deg);
            padding: 5px;
            opacity: .7;
        }

        .nulled_title {
            font-size: 45px;
            letter-spacing: 10px;
            font-weight: bolder;
        }
    </style>
</head>

<body class="body_content">
    @if ($data->status == 'NULLED')
        <div class="nulled_container">
            <h1 class="nulled_title">ANULADO</h1>
            <h5 class="nulled_motive">{{ $data->nulled_motive }}</h5>
        </div>
    @endif
    <header>
        <img src="{{ config('kodepe.logo') }}" alt="" class="ticket_logo">
        <br>
        <h1>{{ config('kodepe.empresa') }}</h1>
        <p><b>RUC:</b> {{ config('kodepe.ruc_empresa') }}</p>
        <small>
            Recibo de caja:
            <b>{{ $data->recipt_series . ' - ' . str_pad($data->recipt_number, 8, '0', STR_PAD_LEFT) }}</b> <br>
            <hr style="border-color: white">
            <b>{{ $data->type == 'add' ? 'INGRESO' : 'GASTO' }}</b>
        </small>
        <hr>
        <table>
            <tr>
                <th style="text-align: left">{{ $data->type == 'add' ? 'Cliente: ' : 'Proveedor: ' }}</th>
                <td style="text-align: left">{{ $data->customer->full_name }}</td>
            </tr>
            <tr>
                <th style="text-align: left">RUC/DNI/OTRO:</th>
                <td style="text-align: left">{{ $data->customer->document }}</td>
            </tr>
            <tr>
                <th style="text-align: left">Dirección:</th>
                <td style="text-align: left">{{ $data->customer->address }}</td>

            </tr>
            <tr>
                <th style="text-align: left">Fecha de Emisión:</th>
                <td style="text-align: left">{{ $data->date->format('d/m/Y') }}
                    {{ $data->created_at->format('h:i A') }}</td>

            </tr>
        </table>
    </header>
    <hr>
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="thead">
            <tr>
                <th style="text-align: left" class="px-6 py-3">MES</th>
                <th style="text-align: left" class="px-6 py-3" width="35%">DESCRIPCIÓN</th>
                {{-- <th style="text-align: left" class="px-6 py-3">CATEGORIA</th> --}}
                @if ($data->type == 'add')
                    <th class="px-6 py-3">STAND</th>
                @endif
                <th style="text-align: right" class="px-6 py-3">MONTO</th>
            </tr>
        </thead>
        <tbody>
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
                            @if ($item->currency->id == 2)
                                S/ {{ number_format($item->changed_amount, 2) }}
                            @else
                                S/ {{ number_format($item->amount, 2) }}
                            @endif
                        </td>
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
        <br>
        <tfoot>
            <tr>
                @if ($data->type == 'add')
                    <td colspan="3" scope="row" style="text-align: right"><b>TOTAL S/.:</b></td>
                @else
                    <td colspan="2" scope="row" style="text-align: right"><b>TOTAL S/.:</b></td>
                @endif
                <td scope="row" class="text-red-800 text-right" style="text-align: right" colspan="1">
                    <b>{{ number_format($data->amount, 2) }}</b>
                </td>
            </tr>
        </tfoot>
    </table>
    <div>
        <h6 style="text-align: center">SON {{ $textoTotal }}</h6>
    </div>

    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="thead">
            <tr>
                <th colspan="2" style="text-align: center" class="px-6 py-3">INFORMACIÓN ADICIONAL</th>
            </tr>
        </thead>
        <tbody>

            <tr>
                <th style="text-align: left" scope="row">PAGADO POR:</th>
                <td style="text-align: left" scope="row">{{ $data->paid_by }}</td>
            </tr>
            <tr>
                <th style="text-align: left" scope="row">#OPER/FACT/BOLET/ETC:</th>
                <td style="text-align: left" scope="row">{{ $data->operation_number }}</td>
            </tr>
            <tr>
                <th style="text-align: left" scope="row">OBSERVACIONES:</th>
                <td style="text-align: left" scope="row">{{ $data->observation }}</td>
            </tr>
            <tr>
                <th style="text-align: center" colspan="2" scope="row">
                    <hr><i>GENERADO POR: {{ $data->user->first_name }}</i>
                </th>
            </tr>

        </tbody>
    </table>
</body>

</html>
