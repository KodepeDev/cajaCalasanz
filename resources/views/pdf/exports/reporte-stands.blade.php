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

    @php
        $company = App\Models\Setting::first();
    @endphp

    <section class="header" style="">
        <table cellpadding="0" celspacing="0" width="100%">
            <tr>
                <td colspan="5" class="text-center">
                    <span style="font-size: 20px; font-weight: bold">{{ $company->company_name }}</span>
                </td>
            </tr>
            <tr>
                <td width="40%">
                    <img src="{{ config('kodepe.logo') }}" alt="" class="invoice_logo">
                </td>

                <td width="60%" class="text-left text-company">
                    <h1>Reporte de Stands</h1>
                </td>
            </tr>
        </table>
    </section>

    <main style="margin-bottom: 8px">
        <table cellpadding="0" celspacing="0" width="100%" class="table-items">
            <thead>
                <tr>
                    <th width="20%">NOMBRE</th>
                    <th width="40%">ETAPA</th>
                    <th width="40%">SOCIO</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($stands as $stand)
                    <tr>
                        <td>{{ $stand->name }}</td>
                        <td>{{ $stand->stage->name }}</td>
                        <td>{{ $stand->partner->full_name }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot style="">

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
