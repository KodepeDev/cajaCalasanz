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
        .table-items {
            font-size: 8px;
        }

        .text-red {
            color: red;
        }
    </style>
</head>

<body>

    <section class="header" style="">
        <table cellpadding="0" celspacing="0" width="100%">
            <tr>
                <td colspan="5" class="text-center">
                    <span style="font-size: 20px; font-weight: bold">{{ $company->company_name }}</span>
                </td>
            </tr>
            <tr>
                <td width="40%">
                    <img src="{{ config('kodepe.logo') }}" alt="" class="invoice-logo">
                </td>

                <td width="60%" class="text-left text-company">
                    <span style="font-size: 16px"><strong>Reporte por Conceptos de
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
                        @if ($cuenta)
                            Cuenta: {{ $cuenta->account_name }},<br>
                        @else
                            Cuenta: todos <br>
                        @endif
                        @if ($fechaInicio && $fechaFin)
                            Fecha: de {{ $fechaInicio }} a {{ $fechaFin }}
                        @endif
                    </span>
                </td>
            </tr>
        </table>
    </section>

    <main style="margin-bottom: 8px">
        <table cellpadding="0" celspacing="0" width="100%" class="table-items">
            <thead>
                <tr>
                    <th width="10%">FECHA</th>
                    <th width="10%">STAND</th>
                    <th width="10%">TIPO</th>
                    <th width="30%">DESCRIPCIÓN</th>
                    <th width="30%">CLIENTE/PROVEEDOR</th>
                    <th width="10%">MONTO</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $item)
                    {{-- {{dd($item)}} --}}
                    @foreach ($item->details as $detail)
                        <tr class="{{ $item->type == 'out' ? 'text-red' : '' }}">
                            <td align="center">{{ $detail->date_paid->format('d/m/Y') }}</td>
                            @if ($detail->stand)
                                <td align="center">{{ $detail->stand->name }}</td>
                            @else
                                <td align="center">S/N</td>
                            @endif
                            <td align="center">
                                {{ $item->type == 'add' ? 'INGRESO' : 'GASTO' }}
                            </td>
                            <td align="left">{{ $detail->category->name }}</td>
                            <td align="left">{{ $item->customer->full_name }}</td>
                            <td align="center">
                                @if ($detail->currency->id == 2)
                                    {{ number_format($detail->changed_amount, 2) }}
                                @else
                                    {{ number_format($detail->amount, 2) }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
            <tfoot style="">
                <br>
                @if ($sumaIngresos)
                    <tr>
                        <td colspan="4" class="text-center">

                        </td>
                        <td class="text-center">
                            <span><b>TOTAL INGRESOS:</b></span>
                        </td>
                        <td colspan="1" class="text-center"><span><strong>S/.
                                    {{ number_format($sumaIngresos, 2) }}</strong></span></td>
                    </tr>
                @endif
                @if ($sumaEgresos)
                    <tr>
                        <td colspan="4" class="text-center">

                        </td>
                        <td class="text-center">
                            <span><b>TOTAL GASTOS:</b></span>
                        </td>
                        <td colspan="1" class="text-center"><span><strong>S/.
                                    -{{ number_format($sumaEgresos, 2) }}</strong></span></td>
                    </tr>
                @endif
                <tr>
                    <td colspan="4" class="text-center">

                    </td>
                    <td class="text-center">
                        <span><b>TOTAL FINAL:</b></span>
                    </td>
                    <td colspan="1" class="text-center"><span><strong>S/.
                                {{ number_format($totalFinal, 2) }}</strong></span></td>
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
