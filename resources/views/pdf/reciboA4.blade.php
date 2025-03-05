<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="application/pdf; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Recibo N°{{ $data->recipt_series }}-{{ $data->recipt_number }}</title>
    {{-- <link rel="stylesheet" href="{{ asset('pdf/custom_page.css') }}"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('pdf/custom_pdf.css') }}"> --}}
    <style>
        body {
            max-width: 100%;
            margin: 0 auto;
            margin-top: -20px;
            font-size: 1.2rem;
            line-height: 1;
            font-family: Arial, Helvetica, sans-serif;
            overflow: hidden;
        }

        body p {
            font-size: 1.2rem;
        }

        hr {
            border: 0.4px solid gray;
        }

        table {
            text-align: center;
            width: 100%;
            font-size: 1rem;
            border: 0.5px solid grey;
            border-spacing: 0;
            border-radius: 5px;
            overflow: hidden;

        }

        th,
        td {
            padding: 5px;
            font-size: 0.8rem;
        }

        .content th,
        .content td {
            border-bottom: 0.5px solid grey;
        }

        .thead {
            background-color: #76143d;
            color: white;
            padding: 0.5rem;
        }

        header {
            text-align: center;
            line-height: 1;
        }

        header h1 {
            font-size: 1.5rem;
            margin: 0;
            padding: 0;
            text-decoration: underline;
            margin-bottom: 15px;
        }

        .bussiness_info p {
            margin: 0;
            margin-top: 2px;
            padding: 0;
            font-size: .8rem;
        }

        header h6 {
            line-height: 1;
        }

        .ticket_logo {
            width: 100px;
        }

        small {
            background-color: #e3e3e3;
            font-size: 1.5rem;
            padding: 15px;
            border: 2px solid black;
            border-radius: 5px;
            display: block;
        }

        .body_content {
            position: relative;
            height: 100%;
        }

        .nulled_container {
            color: red;
            text-align: center;
            border: 2px solid red;
            position: absolute;
            width: 350px;
            height: 180px;
            top: 35%;
            left: 25%;
            transform: translate(50%, -50%);
            transform: rotate(-45deg);
            padding: 5px;
        }

        .nulled_title {
            font-size: 45px;
            letter-spacing: 10px;
            font-weight: bolder;
        }

        .receipt_number {
            margin-top: 20px;
        }

        .receipt_number tr td {
            border: 0.5px solid gray;
            border-bottom: 0;
            padding: 15px 0 15px 0;
        }

        .rec_name {
            color: white;
            font-weight: bold;
            background-color: #76143d;
        }

        .company_info {
            border: unset;
            margin-bottom: 10px;
        }

        header>table td {
            padding-left: 0;
            padding-right: 0;
        }

        .student_table {
            border: unset;
        }

        footer {
            position: fixed;
            width: 100%;
            bottom: 5px;
        }

        footer table td,
        footer table th {
            font-size: .6rem;
        }

        body {
            padding: 0;
            margin: 0;
        }

        .watermark {
            position: absolute;
            top: 18%;
            left: 25%;
            width: 500px;
            z-index: -1;
            opacity: .1;
        }

        .watermark2 {
            position: absolute;
            top: 22%;
            left: 15%;
            width: 500px;
            z-index: -1;
            opacity: .1;
        }

        .total_letras {
            margin-top: 8px;
            text-align: center;
            font-size: 1rem;
            font-style: italic;
            font-weight: bold;
        }
    </style>
</head>

<body class="body_content" onload="window.print();">
    @if ($data->status == 'NULLED')
        <div class="nulled_container">
            <h1 class="nulled_title">ANULADO</h1>
            <h5 class="nulled_motive">{{ $data->nulled_motive }}</h5>
        </div>
    @endif
    @if ($data->type == 'add' && $data->student)
        <img src="{{ config('kodepe.logo') }}" class="watermark" />
    @else
        <img src="{{ config('kodepe.logo') }}" class="watermark2" />
    @endif
    <header>
        <table class="company_info">
            <tr>
                <td width="12%" align="left">
                    <img src="{{ config('kodepe.logo') }}" alt="" class="ticket_logo">
                </td>
                <td align="left" valign="top" class="bussiness_info">
                    <h1>I.E.P. SAN JOSÉ DE CALASANZ</h1>
                    <p><b>RUC:</b> 20610085548</p>
                    <p><b>CEL:</b> +51 933 043 954</p>
                    <p><b>CORREO:</b> calasanzschool@gmail.com - informes@calasanz.edu.pe</p>
                    <p><b>WEB:</b> www.calasanz.edu.pe</p>
                    <p><b>DIRECCIÓN:</b> Jr. Borax N° 631 Mz. T-III Lt. 36 - Lima - San Juan de Lurigancho</p>
                </td>
                <td width="40%" valign="top">
                    <table class="receipt_number">
                        <tr>
                            <td class="rec_name">
                                RECIBO DE {{ $data->type == 'add' ? 'INGRESO' : 'GASTO' }}
                                <br>
                                @if ($data->section_type)
                                    @switch($data->section_type)
                                        @case('1E')
                                            AREA PRINCIPAL
                                        @break

                                        @case('2E')
                                            - AREA SECUNDARIA
                                        @break

                                        @default
                                            ADMINISTRACION
                                    @endswitch
                                @endif
                            </td>
                            <td class="rec_number">
                                <b>{{ $data->recipt_series . ' - ' . str_pad($data->recipt_number, 8, '0', STR_PAD_LEFT) }}</b>
                            </td>
                        </tr>
                    </table>
                    <br>
                    <i>FECHA DE EMISIÓN: {{ $data->date->format('d/m/Y') }}</i>
                </td>
            </tr>
        </table>

        @if ($data->type == 'add' && $data->student)
            <table class="student_table">
                <tbody class="">
                    <tr>
                        <td style="padding-right: 5px" width="50%">
                            <table class="content">
                                <thead class="thead">
                                    <tr>
                                        <th colspan="2">DATOS DEL ESTUDIANTE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th style="text-align: left">
                                            NOMBRES:
                                        </th>
                                        <td style="text-align: left">{{ $data->student->full_name }}</td>
                                    </tr>
                                    <tr>
                                        <th style="text-align: left">DNI</th>
                                        <td style="text-align: left">{{ $data->student->document }}</td>
                                    </tr>
                                    <tr>
                                        <th style="text-align: left">GRADO:</th>
                                        <td style="text-align: left">{{ $data->student->grade->name }}</td>

                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td style="padding-left: 5px">
                            <table class="content">
                                <thead class="thead">
                                    <tr>
                                        <th colspan="2">DATOS DE PADRE/MADRE O TUTOR</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th style="text-align: left">
                                            NOMBRES:
                                        </th>
                                        <td style="text-align: left">{{ $data->customer->full_name }}</td>
                                    </tr>
                                    <tr>
                                        <th style="text-align: left">DNI:</th>
                                        <td style="text-align: left">{{ $data->customer->document }}</td>
                                    </tr>
                                    <tr>
                                        <th style="text-align: left">Dirección:</th>
                                        <td style="text-align: left">{{ $data->customer->address }}</td>

                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        @else
            <table class="content">
                <tr>
                    <th style="text-align: left">{{ $data->type == 'add' ? 'Cliente: ' : 'Proveedor: ' }}
                    </th>
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
            </table>
        @endif
    </header>

    <hr>
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 content">
        <thead class="thead">
            <tr>
                <th style="text-align: left" class="px-6 py-3">MES</th>
                <th style="text-align: left" class="px-6 py-3" width="35%">DESCRIPCIÓN</th>
                {{-- <th style="text-align: left" class="px-6 py-3">CATEGORIA</th> --}}
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
                        <td style="text-align: right">
                            {{ number_format($items->amount, 2) }}
                        </td>
                    </tr>
                @endforeach
            @endif

        </tbody>
        <br>
        <tfoot>
            <tr>
                <td colspan="2" scope="row" style="text-align: right"><b>TOTAL S/.:</b></td>
                <td scope="row" class="text-red-800 text-right" style="text-align: right" colspan="1">
                    <b>{{ number_format($data->amount, 2) }}</b>
                </td>
            </tr>
        </tfoot>
    </table>

    <p class="total_letras">SON {{ $textoTotal }}</p>

    <footer>
        <table style="border: unset;">
            <tbody>
                <tr>
                    <td width="70%">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="thead">
                                <tr>
                                    <th colspan="2" style="text-align: center" class="px-6 py-3">INFORMACIÓN
                                        ADICIONAL</th>
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
                    </td>
                    <td>
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="thead">
                                <tr>
                                    <th colspan="2" style="text-align: center" class="px-6 py-3">FIRMA</th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td rowspan="9" style="text-align: left" scope="row">

                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </footer>
</body>

</html>
