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

<body>

    <section class="header" style="">
        <table cellpadding="0" celspacing="0" width="100%">
            <tr>
                <td colspan="5" class="text-center">
                    <span style="font-size: 20px; font-weight: bold">CC 5 CONTINENTES</span>
                </td>
            </tr>
            <tr>
                <td width="40%">
                    <img src="{{ config('kodepe.logo') }}" alt="" class="invoice-logo">
                </td>

                <td width="60%" class="text-left text-company">

                </td>
            </tr>
        </table>
    </section>

    <main style="margin-bottom: 8px">
        <table cellpadding="0" celspacing="0" width="100%" class="table-items">
            <thead>
                <tr>
                    <th width="20%">FECHA</th>
                    <th width="30%">DESCRIPCION</th>
                    <th width="30%">CATEGORIA</th>
                    <th width="10%">T</th>
                    {{-- <th width="30%">
                        @if (($tipo == 'add') | ($tipo == 'out'))
                            {{$tipo == 'add' ? 'CLIENTE' : 'PROVEEDOR'}}
                        @else
                            CLIENTE/PROVEEDOR
                        @endif
                    </th> --}}
                    <th width="10%">MONTO</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
            <tfoot style="">
                <br>
                <tr>
                    <td colspan="1" class="text-center">

                    </td>
                    <td class="text-right" colspan="2">
                        <span><b>TOTAL INGRESOS:</b></span>
                    </td>

                </tr>
                <tr>
                    <td colspan="1" class="text-center">

                    </td>
                    <td class="text-right" colspan="2">
                        <span><b>TOTAL GASTOS:</b></span>
                    </td>

                </tr>
                <tr>
                    <td colspan="1" class="text-center">

                    </td>
                    <td class="text-right" colspan="2">
                        <span><b>TOTAL FINAL:</b></span>
                    </td>

                </tr>
            </tfoot>
        </table>
    </main>

    <footer class="footer">
        <table cellpadding="0" celspacing="0" width="100%">
            <tr>
                <th width="20%">
                    <span>CC5 Continentes v 1.0</span>
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
