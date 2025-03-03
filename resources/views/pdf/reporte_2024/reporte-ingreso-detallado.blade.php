<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Ventas</title>
    <style>
        @page {
            /* margin-top: 310px; */
            /* size: A4; */
            margin-top: 20px;
            margin-bottom: 40px;
            position: relative;

            @bottom-left {
                content: counter(page) ' of ' counter(pages);
            }
        }

        .header h1 {
            margin: 0;
        }

        .footer {
            font-size: 7pt;
            color: #333333;
            border-top: 1px solid #1f1f1f;
            z-index: 1000;
        }

        footer {
            position: fixed;
            left: 0px;
            right: 0px;
            bottom: -15px;
            height: 30px;
            /* margin-top: -60px; */
        }

        body {
            font-family: 'Helvetica';
            font-size: 7pt;
            color: #111111;
        }

        .invoice-logo {
            max-height: 90px;
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
                    <span style="font-size: 20px; font-weight: bold">{{ config('kodepe.empresa') }}</span>
                </td>
            </tr>
            <tr>
                <td width="40%">
                    <img src="{{ config('kodepe.logo') }}" alt="" class="invoice-logo">
                </td>

                <td width="45%" class="text-company">
                    <h1>Reporte de Ingresos Detallados</h1>
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
        <table cellpadding="0" celspacing="0" width="100%" class="table-bordered">
            <thead>
                <tr>
                    <th>Fecha Emision</th>
                    <th>Documento</th>
                    <th>Stand</th>
                    <th>Cliente</th>
                    <th>Etapa</th>
                    <th>Concepto</th>
                    <th>Importe</th>
                    <th>Usuario</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $detail)
                    <tr>
                        <td scope="row">{{ $detail->date_paid->format('d/m/Y') }}</td>
                        <td>{{ $detail->summary->recipt_series . ' - ' . str_pad($detail->summary->recipt_number, 8, '0', STR_PAD_LEFT) }}
                        </td>
                        <td>{{ $detail->stand ? $detail->stand->name : 'S/N' }}</td>
                        <td>{{ $detail->partner ? $detail->partner->full_name : 'Clientes Varios' }}</td>
                        <td>{{ $detail->stand ? $detail->stand->stage->name : 'S/N' }}</td>
                        <td>{{ $detail->description }}</td>
                        <td>{{ number_format($detail->amount, 2) }}</td>
                        <td>{{ $detail->summary->user->first_name }}</td>
                    </tr>
                @endforeach>
            </tbody>
            <tfoot>
                <tr>
                    <td></td>
                    <td align="right" colspan="3">Total </td>
                    <td align="right" colspan="3">{{ number_format($data->sum('amount'), 2) }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </main>


    <script type="text/php">
        if ( isset($pdf) ) {
            $pdf->page_script('
                $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
                $pdf->text(750, 558, "Pág $PAGE_NUM de $PAGE_COUNT", $font, 8);
            ');
        }
	</script>
</body>

</html>
