<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="application/pdf; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('pdf/socio_pdf.css') }}">
    <title>ESTUDIANTE {{ $student->full_name }}</title>

    <style>
        .foto_socio {
            background-image: url("{{ asset('storage/students/' . $student->foto) }}");
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center center;
            width: 100px;
            height: 100px;
        }

        body {
            font-family: 'Helvetica';
            font-size: 7pt;
            color: #111111;
        }

        .invoice-logo {
            max-height: 80px;
            max-width: 150px;
            padding: 0.1rem;
        }

        table {
            border-collapse: collapse;
        }


        .table-heading {
            background-color: #dfdfdf;
            border: 1px solid black;
        }

        .table-bordered thead tr th {
            border: 1px solid black;
            font-weight: bold;
            text-align: center;
            padding: 4px;
        }

        .table-bordered tbody tr td {
            border: 1px solid black;
            padding: 4px;
        }

        .table-bordered tfoot tr td {
            border-bottom: 1px solid black;
            font-weight: bolder;
            padding: 4px;
            background-color: yellow;
        }

        .text-center {
            text-align: center;
        }
    </style>

</head>

<body>
    <header>
        <div class="cabecera_socio">
            <h1 class="cabecera">REPORTE DE PAGOS Y DEUDAS</h1>
            <div class="nombre_socio">
                <table style="width: 100%;">
                    <thead>
                        <tr>
                            <th rowspan="6" align="left" class="ticket_logo">
                                <img src="{{ config('kodepe.logo') }}" alt="" class="invoice-logo">
                            </th>
                        </tr>
                    </thead>
                    <tbody valign="center">
                        <tr>
                            <td colspan="1" style="text-align: left">ESTUDIANTE:</td>
                            <td colspan="1" style="text-align: left">{{ $student->full_name }}</td>
                        </tr>
                        <tr>
                            <td colspan="1" style="text-align: left">RUC/DNI/OTRO:</td>
                            <td colspan="1" style="text-align: left">{{ $student->document }}</td>
                        </tr>
                        <tr>
                            <td colspan="1" style="text-align: left">Dirección:</td>
                            <td colspan="1" style="text-align: left">{{ $student->address }}</td>
                        </tr>
                        <tr>
                            <td colspan="1" style="text-align: left">PADRE/MADRE O TUTOR:</td>
                            <td colspan="1" style="text-align: left">{{ $student->tutor->full_name }}</td>
                        </tr>
                        <tr>
                            <td colspan="1" style="text-align: left">Rango de Consulta:</td>
                            <td colspan="1" style="text-align: left">{{ $start }} al {{ $finish }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="foto_socio">
                {{-- <img src="{{asset('imagenes/5cont.webp')}}" alt="" width="98%"> --}}
            </div>
        </div>
    </header>
    <main style="margin-bottom: 8px; clear: both;">
        <table class="table-bordered" style="margin-bottom: 10px; margin-top: 10px;" width="100%">
            <thead class="table-heading">
                <tr>
                    <th colspan="5">HISTORIAL DE PAGOS</th>
                </tr>
                <tr>
                    <th style="text-align: left" class="px-6 py-3" width="10%">PERIODO</th>
                    <th style="text-align: left" class="px-6 py-3" width="10%">RECIBO</th>
                    <th style="text-align: left" class="px-6 py-3">DESCRIPCIÓN</th>
                    <th class="px-6 py-3">FECHA DE PAGO</th>
                    <th style="text-align: right" class="px-6 py-3">MONTO</th>
                </tr>
            </thead>
            <tbody>
                @if (count($movimientos) > 0)
                    @foreach ($movimientos as $detail)
                        <tr>
                            <td style="text-align: left" scope="row">{{ $detail->date->format('m-Y') }}</td>
                            <td style="text-align: left" scope="row">{{ $detail->summary->recipt_series }} -
                                {{ $detail->summary->recipt_number }}</td>
                            <td style="text-align: center" width="50%" scope="row">{{ $detail->description }}
                            </td>
                            <td>{{ $detail->date_paid->format('d/m/Y') }}</td>
                            <td
                                style="text-align: right; color: {{ $detail->summary_type == 'out' ? 'red' : 'auto' }}">
                                {{ $detail->summary_type == 'out' ? '-' : '' }}{{ $detail->currency->id == 1 ? number_format($detail->amount, 2) : number_format($detail->changed_amount, 2) }}
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" style="text-align: center">No hay movimientos</td>
                    </tr>
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" scope="row" style="text-align: right"><b>TOTAL S/.:</b></td>
                    <td scope="row" class="text-red-800 text-right" style="text-align: right" colspan="1">
                        <b>{{ number_format($sumaTotal, 2) }}</b>
                    </td>
                </tr>
            </tfoot>
        </table>
        <hr>
        <table class="table-bordered" style="margin-bottom: 20px;" width="100%">
            <thead class="table-heading">
                <tr>
                    <th colspan="4">HISTORIAL DE DEUDAS</th>
                </tr>
                <tr>
                    <th style="text-align: left" class="px-6 py-3">PERIODO</th>
                    <th class="px-6 py-3">DESCRIPCIÓN</th>
                    <th style="text-align: right" class="px-6 py-3">SOLES</th>
                    <th style="text-align: right" class="px-6 py-3">DOLARES</th>
                </tr>
            </thead>
            <tbody>
                @if (count($pendientes) > 0)
                    @foreach ($pendientes as $item)
                        <tr>
                            <td style="text-align: left" scope="row">{{ $item->date->format('m-Y') }}</td>
                            <td style="text-align: left" scope="row">{{ $item->description }}</td>
                            <td style="text-align: right; color: {{ $item->summary_type == 'out' ? 'red' : 'auto' }}">
                                {{ $item->summary_type == 'out' ? '-' : '' }}
                                @if ($item->currency->id !== 2)
                                    S/. {{ number_format($item->amount, 2) }}
                                @endif
                            </td>
                            <td style="text-align: right; color: {{ $item->summary_type == 'out' ? 'red' : 'auto' }}">
                                {{ $item->summary_type == 'out' ? '-' : '' }}
                                @if ($item->currency->id == 2)
                                    $ {{ number_format($item->amount, 2) }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4" style="text-align: center">No hay movimientos</td>
                    </tr>
                @endif

            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" scope="row" style="text-align: right"><b>TOTAL:</b></td>
                    <td scope="row" class="text-red-800 text-right" style="text-align: right">
                        <b>S/. {{ number_format($sumaTotalPendiente, 2) }}</b>
                    </td>
                    <td scope="row" class="text-red-800 text-right" style="text-align: right">
                        <b>$ {{ number_format($sumaTotalPendienteDolar, 2) }}</b>
                    </td>
                </tr>
            </tfoot>
        </table>
    </main>

    {{-- footer --}}
    @include('pdf.commons.footer')


    <script type="text/php">
        if ( isset($pdf) ) {
            $pdf->page_script('
                $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
                $pdf->text(500, 802, "Pág $PAGE_NUM de $PAGE_COUNT", $font, 8);
            ');
        }
	</script>
</body>

</html>
