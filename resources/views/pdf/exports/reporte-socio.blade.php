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
    </style>
</head>

<body id="PrintSocios">

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

    <section class="header" style="">
        <table cellpadding="0" celspacing="0" width="100%">
            <tr>
                <td colspan="5" class="text-center">
                    <span style="font-size: 20px; font-weight: bold">{{ $empresa->company_name }}</span>
                </td>
            </tr>
            <tr>
                <td width="40%">
                    <img src="{{ config('kodepe.logo') }}" alt="" class="invoice-logo">
                </td>

                <td width="60%" class="text-left text-company">
                    <h1>Reporte de Socios</h1>
                </td>
            </tr>
        </table>
    </section>

    <main style="margin-bottom: 8px">
        <table cellpadding="0" celspacing="0" width="100%" class="table-items">
            <thead>
                <tr>
                    <th width="20%">DOCUMENTO</th>
                    <th width="50%">NOMBRES</th>
                    <th width="30%">DIRECCION</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($socios as $socio)
                    <tr>
                        <td>{{ $socio->document }}</td>
                        <td>{{ $socio->full_name }}</td>
                        <td>{{ $socio->address }}</td>
                    </tr>
                    @foreach ($socio->stands as $item)
                        <tr>
                            <td></td>
                            <td><b>STAND: </b>{{ $item->name }}</td>
                            <td><b>ETAPA: </b>{{ $item->stage->name }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
            <tfoot style="">

            </tfoot>
        </table>
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
