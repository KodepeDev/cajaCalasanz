<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Ventas</title>
    <link rel="stylesheet" href="{{ asset('pdf/custom_page.css') }}">
    {{-- <link rel="stylesheet" href="{{asset('pdf/custom_pdf.css')}}"> --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <style>
        /* .header{
            position: fixed;
        } */

        .invoice-logo {
            max-width: 70px;
            padding: 5px;
        }

        body {
            max-width: 100%;
            padding: 5px;
        }

        .heading_deuda {
            /* max-width: 99%; */
            padding: 5px;
            margin-bottom: 20px;
            border: 2px solid black;
            border-radius: 10px;
        }

        .tb-content {
            border: 2px solid rgb(90, 90, 90);
            border-radius: 10px;
            overflow: hidden;
        }

        .tb-content table {
            font-size: 0.7rem;
        }
    </style>
</head>

<body>

    <section class="header text-center" style="">
        <span style="font-size: 14px; font-weight: bold; text-align: center;">{{ config('kodepe.ruc_empresa') }}</span>
        <br>
        <div class="heading_deuda mt-2">
            <table width="100%">
                <tr>
                    <td align="center" colspan="2">
                        <h2><strong>REPORTE DE DEUDA</strong></h2>
                    </td>
                </tr>
                <tr class="">
                    <td width="40%">
                        <img src="{{ config('kodepe.logo') }}" alt="" class="invoice-logo">
                    </td>

                    <td width="60%" class="text-left ml-2">
                        <h5><strong>SOCIO: {{ $socio }}</strong></h5>
                        <h5><strong>STAND: {{ $stand->name }} - {{ $stand->stage->name }}</strong></h5>
                        <h6><strong>FECHA DE CONSULTA: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</strong></h6>
                    </td>
                </tr>
            </table>
        </div>
    </section>

    <main class="tb-content table-responsive" style="margin-top: 8px">
        <table cellpadding="0" celspacing="0" width="100%" class="table table-sm table-borderless">
            <thead class="bg-warning">
                <tr>
                    <th width="5%">#</th>
                    <th width="10%">MES</th>
                    <th width="70%">DESCRIPCION</th>
                    <th width="15%" style="text-align: center">SOLES</th>
                    <th width="15%" style="text-align: center">DOLAR</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($provision_detalles as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td align="left">{{ $item->date->format('m-Y') }}</td>
                        <td align="left">
                            {{ $item->description }}
                        </td>
                        <td class="text-center">
                            @if ($item->currency->id !== 2)
                                S/. {{ number_format($item->amount, 2) }}
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($item->currency->id == 2)
                                $. {{ number_format($item->amount, 2) }}
                            @endif
                        </td>
                    </tr>
                @endforeach
                <tr style="border-top: 1px solid black">
                    <td></td>
                    <td></td>
                    <td><b>Total Deuda</b></td>
                    <td align="center"><b>S/. {{ number_format($total_prov, 2) }}</b></td>
                    <td align="center"><b>$. {{ number_format($total_prov_dolar, 2) }}</b></td>
                </tr>
            </tbody>
        </table>
    </main>

    <footer class="footer">
        <table cellpadding="0" celspacing="0" width="100%">
            <tr>
                <th width="20%">
                    <span>{{ config('kodepe.empresa') }}</span>
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
