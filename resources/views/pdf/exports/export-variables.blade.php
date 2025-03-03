<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Ventas</title>
    <link rel="stylesheet" href="{{ asset('pdf/custom_page.css') }}">
    <link rel="stylesheet" href="{{ asset('pdf/custom_pdf.css') }}">
    <style>
        /* .header{
            position: fixed;
        } */
        .invoice_logo {
            width: 70px;
        }
    </style>
</head>

<body>

    <section class="header" style="">
        <table cellpadding="0" celspacing="0" width="100%">
            <tr>
                <td colspan="5" class="text-center">
                    <span style="font-size: 20px; font-weight: bold">{{ config('kodepe.ruc_empresa') }}</span>
                </td>
            </tr>
            <tr>
                <td width="40%">
                    <img src="{{ config('kodepe.logo') }}" alt="" class="invoice_logo">
                </td>

                <td width="60%" class="text-left text-company">
                    <span style="font-size: 16px"><strong>Reporte deudores de: </strong>{{ $categoria->name }}</span>
                    <br>
                    <span style="font-size: 14px"><strong>Mes:</strong>{{ $mes }}</span><br>
                    <span style="font-size: 14px"><strong>Área:</strong>{{ $etapa->name }}</span><br>
                    <span style="font-size: 14px"><strong>Fecha de consulta:</strong>
                        {{ \Carbon\Carbon::now()->format('d/m/Y') }}
                    </span>
                </td>
            </tr>
        </table>
    </section>

    <main style="margin-bottom: 8px">
        <table cellpadding="0" celspacing="0" width="100%" class="table-items">
            <thead>
                <tr>
                    <th width="20%">MES</th>
                    <th width="50%">DESCRIPCION</th>
                    <th width="10%">STAND</th>
                    <th width="20%">MONTO</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    <tr>
                        <td align="center">{{ $item->date->format('m/Y') }}</td>
                        <td align="left">{{ $item->description }}</td>
                        <td align="center">
                            {{ $item->stand->name }}
                        </td>
                        <td align="center">{{ number_format($item->amount, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot style="">
                <br>
                <tr>
                    <td colspan="1" class="text-center">

                    </td>
                    <td class="text-right" colspan="2">
                        <span><b>TOTAL FINAL:</b></span>
                    </td>
                    <td colspan="2" class="text-right"><span><strong>S/.
                                {{ number_format($totalFinal, 2) }}</strong></span></td>
                </tr>
            </tfoot>
        </table>
    </main>

    <footer class="footer">
        <table cellpadding="0" celspacing="0" width="100%">
            <tr>
                <th width="20%">
                    <span>v 1.0</span>
                </th>
                <th width="60%" class="text-center">
                    {{ Auth::user()->first_name }}
                </th>
                <th width="20%">
                    <span class="pagenum"></span>
                </th>
            </tr>
        </table>
    </footer>


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
