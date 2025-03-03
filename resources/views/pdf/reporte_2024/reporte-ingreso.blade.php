<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Ventas</title>
    <link rel="stylesheet" href="{{ asset('pdf/custom_page.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('pdf/custom_pdf.css') }}"> --}}
    <style>
        body {
            font-family: 'Helvetica';
            font-size: 7pt;
            color: #111111;
        }

        .invoice-logo {
            max-height: 80px;
            max-width: 240px;
            margin-bottom: 10px;
        }

        table {
            border-collapse: collapse;
        }

        table th,
        td {
            padding: 3px;
        }

        .table-heading {
            background-color: #dfdfdf;
            border: 1px solid black;
        }

        .table-bordered thead tr th {
            border: 1px solid black;
            font-weight: bold;
            text-align: center;
        }

        .table-bordered tbody tr td {
            border: 1px solid black;
        }

        .table-bordered tfoot tr td {
            border-bottom: 1px solid black;
            font-weight: bolder;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body id="PrintSocios">
    @include('pdf.commons.footer')

    <section class="header" style="">
        <table cellpadding="0" celspacing="0" width="100%">
            <tr>
                <td colspan="5" class="text-center">
                    <span style="font-size: 12px; font-weight: bold">{{ config('kodepe.empresa') }}</span>
                </td>
            </tr>
            <tr>
                <td width="40%">
                    <img src="{{ config('kodepe.logo') }}" alt="" class="invoice-logo">
                </td>

                <td width="45%" class="text-left text-company">
                    <h3>Reporte de Ingresos</h3>
                    <h3 class="text-center">Del {{ $inicio }} al {{ $fin }}</h3>
                </td>
                <td width="15%" class="text-company">
                    <p>Fecha: {{ Carbon\Carbon::now()->format('d/m/Y') }}</p>
                    <p>Hora : {{ Carbon\Carbon::now()->format('g:i A') }}</p>
                </td>
            </tr>
        </table>
    </section>

    <main style="margin-bottom: 8px">

        @foreach ($data as $detalle)
            @php
                $sumaTotal = $detalle->stands->flatMap->details->sum('amount');
                $sortedDetails = $detalle->stands->flatMap->details->sortBy('date_paid');
            @endphp
            @if ($sumaTotal > 0)
                <table cellpadding="0" celspacing="0" width="100%" class="table-bordered" style="margin-bottom: 5px">
                    <thead class="table-heading">
                        <tr>
                            <th colspan="6">{{ $detalle->name }}</th>
                        </tr>
                        <tr>
                            <th width="10%">Fecha Emision</th>
                            <th width="15%">Documento</th>
                            <th width="10%">Puesto</th>
                            <th width="40%">Cliente</th>
                            <th width="10%">Total</th>
                            <th width="15%">Usuario</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($sortedDetails->count() > 0)
                            @foreach ($sortedDetails as $detail)
                                <tr>
                                    <td scope="row">{{ $detail->date_paid->format('d/m/Y') }}</td>
                                    <td>{{ $detail->summary->recipt_series . ' - ' . str_pad($detail->summary->recipt_number, 8, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td>{{ $detail->stand ? $detail->stand->name : 'S/N' }}</td>
                                    <td>{{ $detail->partner ? $detail->partner->full_name : 'Clientes Varios' }}</td>
                                    <td>{{ number_format($detail->amount, 2) }}</td>
                                    <td>{{ $detail->summary->user->first_name }}</td>
                                </tr>
                            @endforeach
                        @endif
                        <tr>
                            <td></td>
                            <td align="right" colspan="3">Total {{ $detalle->name }}</td>
                            <td colspan="1">{{ number_format($sumaTotal, 2) }}</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            @endif
        @endforeach

        @if (count($data2) > 0)
            <table cellpadding="0" celspacing="0" width="100%" class="table-bordered" style="margin-bottom: 5px">
                <thead class="table-heading">
                    <tr>
                        <th colspan="6">Datos sin Etapa ni Stand</th>
                    </tr>
                    <tr>
                        <th width="10%">Fecha Emision</th>
                        <th width="15%">Documento</th>
                        <th width="10%">Puesto</th>
                        <th width="40%">Cliente</th>
                        <th width="10%">Total</th>
                        <th width="15%">Usuario</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($data2) > 0)
                        @foreach ($data2 as $detail)
                            <tr>
                                <td scope="row">{{ $detail->date_paid->format('d/m/Y') }}</td>
                                <td>{{ $detail->summary->recipt_series . ' - ' . str_pad($detail->summary->recipt_number, 8, '0', STR_PAD_LEFT) }}
                                </td>
                                <td>{{ $detail->stand ? $detail->stand->name : 'S/N' }}</td>
                                <td>{{ $detail->partner ? $detail->partner->full_name : 'Clientes Varios' }}</td>
                                <td>{{ number_format($detail->amount, 2) }}</td>
                                <td>{{ $detail->summary->user->first_name }}</td>
                            </tr>
                        @endforeach
                    @endif
                    <tr>
                        <td></td>
                        <td align="right" colspan="3">Total S/N</td>
                        <td colspan="1">{{ number_format($data2->sum('amount'), 2) }}</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        @endif
    </main>


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
