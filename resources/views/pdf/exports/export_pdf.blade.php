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
                    <span style="font-size: 20px; font-weight: bold">{{ config('kodepe.empresa') }}</span>
                </td>
            </tr>
            <tr>
                <td width="40%">
                    <img src="{{ config('kodepe.logo') }}" alt="" class="invoice_logo">
                </td>

                <td width="60%" class="text-left text-company">
                    <span style="font-size: 16px"><strong>Reporte detalles de
                            @if (($tipo == 'add') | ($tipo == 'out'))
                                {{ $tipo == 'add' ? 'INGRESOS' : 'GASTOS' }}
                            @else
                                INGRESOS Y GASTOS
                            @endif
                        </strong></span>
                    <br>
                    <span style="font-size: 14px"><strong>Filtros:</strong>
                        @if ($categoria)
                            Categoria: {{ $categoria->name }},
                        @else
                            Categoria: todos,
                        @endif
                        @if ($fechaInicio && $fechaFin)
                            Fecha: de {{ $fechaInicio }} a {{ $fechaFin }}
                        @endif
                    </span><br>
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
                    <th width="20%">FECHA</th>
                    <th width="">DESCRIPCION</th>
                    <th width="10%">T</th>
                    {{-- <th width="30%">
                        @if (($tipo == 'add') | ($tipo == 'out'))
                            {{$tipo == 'add' ? 'CLIENTE' : 'PROVEEDOR'}}
                        @else
                            CLIENTE/PROVEEDOR
                        @endif
                    </th> --}}
                    <th width="10%">SOLES</th>
                    <th width="10%">DOLAR</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    <tr>
                        <td align="center">{{ $item->date->format('d/m/Y') }}</td>
                        <td align="left">{{ $item->description }}</td>
                        <td align="center">
                            {{ $item->summary_type == 'add' ? 'I' : 'G' }}
                        </td>
                        {{-- @if ($item->partner)
                        <td align="center">{{$item->partner->full_name}}</td>
                        @else
                        <td align="center">-</td>
                        @endif --}}
                        @if ($item->currency->id !== 2)
                            <td align="center">S/.
                                {{ $item->summary_type == 'add' ? number_format($item->amount, 2) : -number_format($item->amount, 2) }}
                            </td>
                            <td align="center"></td>
                        @else
                            <td align="center"></td>
                            <td align="center">$.
                                {{ $item->summary_type == 'add' ? number_format($item->amount, 2) : -number_format($item->amount, 2) }}
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
            <tfoot style="">
                <br>
                <tr>
                    <td colspan="1" class="text-center">

                    </td>
                    <td class="text-right" colspan="2">
                        <span><b>TOTAL INGRESOS:</b></span>
                    </td>
                    <td colspan="1" class="text-right"><span><strong>S/.
                                {{ number_format($sumaIngresos, 2) }}</strong></span></td>
                    <td colspan="1" class="text-right"><span><strong>$.
                                {{ number_format($sumaIngresosDolar, 2) }}</strong></span></td>
                </tr>
                <tr>
                    <td colspan="1" class="text-center">

                    </td>
                    <td class="text-right" colspan="2">
                        <span><b>TOTAL GASTOS:</b></span>
                    </td>
                    <td colspan="1" class="text-right"><span><strong>S/.
                                -{{ number_format($sumaEgresos, 2) }}</strong></span></td>
                    <td colspan="1" class="text-right"><span><strong>$.
                                -{{ number_format($sumaEgresosDolar, 2) }}</strong></span></td>
                </tr>
                <tr>
                    <td colspan="1" class="text-center">

                    </td>
                    <td class="text-right" colspan="2">
                        <span><b>TOTAL FINAL:</b></span>
                    </td>
                    <td colspan="1" class="text-right"><span><strong>S/.
                                {{ number_format($totalFinal, 2) }}</strong></span></td>
                    <td colspan="1" class="text-right"><span><strong>$.
                                {{ number_format($totalFinalDolar, 2) }}</strong></span></td>
                </tr>
            </tfoot>
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
