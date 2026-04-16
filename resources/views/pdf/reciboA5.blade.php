<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="application/pdf; charset=utf-8" />
    <title>Recibo N°{{ $data->recipt_series }}-{{ $data->recipt_number }}</title>
    <style>
        @page {
            size: A5 portrait;
            margin: 7mm 7mm 22mm 7mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 8pt;
            line-height: 1.4;
            color: #222;
            margin: 0;
            padding: 0;
        }

        /* ── WATERMARK ─────────────────────────── */
        .watermark {
            position: absolute;
            top: 30%;
            left: 25%;
            width: 250px;
            opacity: 0.06;
            z-index: 0;
        }

        /* ── ANULADO STAMP ─────────────────────── */
        .nulled-stamp {
            position: absolute;
            top: 38%;
            left: 50%;
            width: 220px;
            margin-left: -110px;
            border: 3px solid #cc0000;
            text-align: center;
            padding: 6px 10px;
            z-index: 10;
        }
        .nulled-stamp .nulled-title {
            font-size: 22pt;
            font-weight: bold;
            color: #cc0000;
            letter-spacing: 6px;
            margin: 0;
            padding: 0;
        }
        .nulled-stamp .nulled-motive {
            font-size: 7pt;
            color: #cc0000;
            margin: 2px 0 0 0;
            padding: 0;
        }

        /* ── TOP ACCENT BAR ────────────────────── */
        .accent-bar {
            background-color: #76143d;
            height: 4px;
            width: 100%;
            margin-bottom: 6px;
        }

        /* ── HEADER ────────────────────────────── */
        .header-table {
            width: 100%;
            border: none;
            border-spacing: 0;
            border-collapse: collapse;
            margin-bottom: 6px;
        }
        .header-table td {
            border: none;
            padding: 0;
            vertical-align: top;
        }
        .header-logo {
            width: 70px;
            display: block;
        }
        .company-name {
            font-size: 9pt;
            font-weight: bold;
            color: #76143d;
            margin: 0 0 3px 0;
            padding: 0;
            text-transform: uppercase;
        }
        .company-detail {
            font-size: 6.5pt;
            color: #444;
            margin: 1px 0;
            padding: 0;
        }

        /* ── RECEIPT BOX (top-right) ───────────── */
        .receipt-box {
            border: 1.5px solid #76143d;
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
        }
        .receipt-box td {
            padding: 4px 6px;
            border: none;
        }
        .receipt-box .rtype-cell {
            background-color: #76143d;
            color: #fff;
            font-size: 7pt;
            font-weight: bold;
            text-align: center;
            letter-spacing: 1px;
            padding: 5px 4px;
        }
        .receipt-box .rtype-area {
            font-size: 6pt;
            font-weight: normal;
            letter-spacing: 0;
            display: block;
            margin-top: 1px;
        }
        .receipt-box .rnum-cell {
            text-align: center;
            font-size: 9pt;
            font-weight: bold;
            color: #76143d;
            border-top: 1px solid #76143d;
            padding: 4px;
        }
        .receipt-box .rdate-cell {
            text-align: center;
            font-size: 6.5pt;
            color: #555;
            border-top: 1px dashed #ccc;
            padding: 3px 4px;
        }

        /* ── SECTION DIVIDER ───────────────────── */
        .section-divider {
            border: none;
            border-top: 1px solid #76143d;
            margin: 5px 0;
        }

        /* ── INFO TABLES (student / customer) ──── */
        .info-wrap {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            border: none;
            margin-bottom: 5px;
        }
        .info-wrap > tbody > tr > td {
            border: none;
            padding: 0;
            vertical-align: top;
        }
        .info-wrap > tbody > tr > td:first-child {
            padding-right: 4px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            border: 1px solid #ddd;
            font-size: 7pt;
        }
        .data-table thead tr th {
            background-color: #76143d;
            color: #fff;
            font-size: 7pt;
            font-weight: bold;
            text-align: center;
            padding: 4px 5px;
            letter-spacing: 0.5px;
        }
        .data-table tbody tr th {
            text-align: left;
            font-weight: bold;
            color: #555;
            padding: 3px 5px;
            width: 35%;
            background-color: #f9f9f9;
            border-bottom: 1px solid #eee;
        }
        .data-table tbody tr td {
            text-align: left;
            padding: 3px 5px;
            color: #222;
            border-bottom: 1px solid #eee;
        }

        /* ── ITEMS TABLE ───────────────────────── */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            border: 1px solid #ddd;
            font-size: 7pt;
            margin-top: 6px;
        }
        .items-table thead tr th {
            background-color: #76143d;
            color: #fff;
            font-size: 7pt;
            font-weight: bold;
            padding: 5px 6px;
            border-bottom: 1px solid #5a0f2e;
        }
        .items-table thead tr th.th-right {
            text-align: right;
        }
        .items-table thead tr th.th-left {
            text-align: left;
        }
        .items-table tbody tr td {
            padding: 4px 6px;
            border-bottom: 1px solid #eee;
            vertical-align: top;
        }
        .items-table tbody tr.row-alt td {
            background-color: #fdf5f8;
        }
        .items-table tfoot tr td {
            padding: 5px 6px;
            border-top: 2px solid #76143d;
            background-color: #f9f0f4;
        }
        .total-label {
            text-align: right;
            font-size: 8pt;
            font-weight: bold;
            color: #76143d;
        }
        .total-amount {
            text-align: right;
            font-size: 10pt;
            font-weight: bold;
            color: #76143d;
        }

        /* ── TOTAL EN LETRAS ───────────────────── */
        .total-words {
            text-align: center;
            font-size: 7pt;
            font-style: italic;
            font-weight: bold;
            color: #444;
            border: 1px dashed #bbb;
            padding: 4px 8px;
            margin-top: 6px;
            background-color: #fafafa;
        }

        /* ── FOOTER (fixed at page bottom) ─────── */
        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
        }
        .footer-table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            border: none;
        }
        .footer-table > tbody > tr > td {
            border: none;
            padding: 0;
            vertical-align: top;
        }
        .footer-table > tbody > tr > td:first-child {
            padding-right: 4px;
        }

        .footer-info-table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            border: 1px solid #ddd;
            font-size: 6.5pt;
        }
        .footer-info-table thead tr th {
            background-color: #76143d;
            color: #fff;
            font-size: 6.5pt;
            font-weight: bold;
            text-align: center;
            padding: 3px 5px;
        }
        .footer-info-table tbody tr th {
            text-align: left;
            font-weight: bold;
            color: #555;
            padding: 2px 5px;
            background-color: #f9f9f9;
            border-bottom: 1px solid #eee;
            width: 38%;
        }
        .footer-info-table tbody tr td {
            text-align: left;
            padding: 2px 5px;
            color: #222;
            border-bottom: 1px solid #eee;
        }
        .footer-generated {
            text-align: center;
            font-size: 6pt;
            color: #777;
            font-style: italic;
            padding: 3px 0 0 0;
            border-top: 1px solid #eee;
        }

        .footer-sign-table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            border: 1px solid #ddd;
            font-size: 6.5pt;
        }
        .footer-sign-table thead tr th {
            background-color: #76143d;
            color: #fff;
            font-size: 6.5pt;
            font-weight: bold;
            text-align: center;
            padding: 3px 5px;
        }
        .footer-sign-table tbody tr td {
            height: 47px;
            text-align: center;
            vertical-align: bottom;
            font-size: 6pt;
            color: #999;
            padding: 0 5px 3px;
            border-top: 1px solid #bbb;
        }

        .bottom-bar {
            background-color: #76143d;
            height: 3px;
            width: 100%;
            margin-top: 4px;
        }
    </style>
</head>

<body>
    {{-- Watermark --}}
    <img src="{{ config('kodepe.logo') }}" class="watermark" />

    {{-- ANULADO stamp --}}
    @if ($data->status == 'NULLED')
        <div class="nulled-stamp">
            <p class="nulled-title">ANULADO</p>
            @if ($data->nulled_motive)
                <p class="nulled-motive">{{ $data->nulled_motive }}</p>
            @endif
        </div>
    @endif

    {{-- Top accent bar --}}
    <div class="accent-bar"></div>

    {{-- ══ HEADER ══════════════════════════════════════ --}}
    <table class="header-table">
        <tr>
            {{-- Logo --}}
            <td width="15%" style="padding-right: 6px;">
                <img src="{{ config('kodepe.logo') }}" class="header-logo" alt="Logo" />
            </td>

            {{-- Company info --}}
            <td style="padding-right: 8px;">
                <p class="company-name">I.E.P. San José de Calasanz</p>
                <p class="company-detail"><b>RUC:</b> 20610085548</p>
                <p class="company-detail"><b>CEL:</b> +51 933 043 954</p>
                <p class="company-detail"><b>EMAIL:</b> informes@calasanz.edu.pe</p>
                <p class="company-detail"><b>WEB:</b> www.calasanz.edu.pe</p>
                <p class="company-detail"><b>DIR.:</b> Jr. Borax N° 631 Mz. T-III Lt. 36 - S.J.L., Lima</p>
            </td>

            {{-- Receipt number box --}}
            <td width="38%" valign="top">
                <table class="receipt-box">
                    <tr>
                        <td class="rtype-cell">
                            RECIBO DE {{ $data->type == 'add' ? 'INGRESO' : 'GASTO' }}
                            @if ($data->section_type)
                                <span class="rtype-area">
                                    @switch($data->section_type)
                                        @case('1E') ÁREA PRINCIPAL @break
                                        @case('2E') ÁREA SECUNDARIA @break
                                        @default ADMINISTRACIÓN
                                    @endswitch
                                </span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="rnum-cell">
                            {{ $data->recipt_series }}-{{ str_pad($data->recipt_number, 8, '0', STR_PAD_LEFT) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="rdate-cell">
                            Emisión: <b>{{ $data->date->format('d/m/Y') }}</b>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <hr class="section-divider" />

    {{-- ══ CUSTOMER / STUDENT DATA ══════════════════════ --}}
    @if ($data->type == 'add' && $data->student)
        <table class="info-wrap">
            <tr>
                <td width="50%">
                    <table class="data-table">
                        <thead>
                            <tr><th colspan="2">DATOS DEL ESTUDIANTE</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>NOMBRES:</th>
                                <td>{{ $data->student->full_name }}</td>
                            </tr>
                            <tr>
                                <th>DNI:</th>
                                <td>{{ $data->student->document }}</td>
                            </tr>
                            <tr>
                                <th>GRADO:</th>
                                <td>{{ $data->student->grade->name }}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td width="50%">
                    <table class="data-table">
                        <thead>
                            <tr><th colspan="2">PADRE / MADRE / TUTOR</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>NOMBRES:</th>
                                <td>{{ $data->customer->full_name }}</td>
                            </tr>
                            <tr>
                                <th>DNI:</th>
                                <td>{{ $data->customer->document }}</td>
                            </tr>
                            <tr>
                                <th>DIRECCIÓN:</th>
                                <td>{{ $data->customer->address }}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
    @else
        <table class="data-table" style="margin-bottom: 5px;">
            <thead>
                <tr>
                    <th colspan="2">
                        {{ $data->type == 'add' ? 'DATOS DEL CLIENTE' : 'DATOS DEL PROVEEDOR' }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>{{ $data->type == 'add' ? 'CLIENTE:' : 'PROVEEDOR:' }}</th>
                    <td>{{ $data->customer->full_name }}</td>
                </tr>
                <tr>
                    <th>RUC / DNI:</th>
                    <td>{{ $data->customer->document }}</td>
                </tr>
                <tr>
                    <th>DIRECCIÓN:</th>
                    <td>{{ $data->customer->address }}</td>
                </tr>
            </tbody>
        </table>
    @endif

    {{-- ══ ITEMS TABLE ══════════════════════════════════ --}}
    <table class="items-table">
        <thead>
            <tr>
                <th class="th-left" width="14%">MES</th>
                <th class="th-left">DESCRIPCIÓN</th>
                <th class="th-right" width="20%">MONTO</th>
            </tr>
        </thead>
        <tbody>
            @if ($data->status == 'PAID')
                @foreach ($data->details as $item)
                    <tr class="{{ $loop->even ? 'row-alt' : '' }}">
                        <td style="text-align: left;">{{ $item->date->format('m/Y') }}</td>
                        <td style="text-align: left;">{{ $item->description }}</td>
                        <td style="text-align: right;">
                            @if ($item->currency->id == 2)
                                S/ {{ number_format($item->changed_amount, 2) }}
                            @else
                                S/ {{ number_format($item->amount, 2) }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                @foreach ($data->nulledDetails as $item)
                    <tr class="{{ $loop->even ? 'row-alt' : '' }}">
                        <td style="text-align: left;">{{ $item->date->format('m/Y') }}</td>
                        <td style="text-align: left;">{{ $item->description }}</td>
                        <td style="text-align: right;">S/ {{ number_format($item->amount, 2) }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" class="total-label">TOTAL A PAGAR (S/.):</td>
                <td class="total-amount">{{ number_format($data->amount, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    {{-- Amount in words --}}
    <div class="total-words">SON: {{ $textoTotal }}</div>

    {{-- ══ FOOTER (fixed bottom) ════════════════════════ --}}
    <footer>
        <table class="footer-table">
            <tr>
                <td width="68%">
                    <table class="footer-info-table">
                        <thead>
                            <tr><th colspan="2">INFORMACIÓN ADICIONAL</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>PAGADO POR:</th>
                                <td>{{ $data->paid_by }}</td>
                            </tr>
                            <tr>
                                <th># OPER / COMP.:</th>
                                <td>{{ $data->operation_number }}</td>
                            </tr>
                            <tr>
                                <th>OBSERVACIONES:</th>
                                <td>{{ $data->observation }}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td width="32%">
                    <table class="footer-sign-table">
                        <thead>
                            <tr><th>FIRMA Y SELLO</th></tr>
                        </thead>
                        <tbody>
                            <tr><td>____________________</td></tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
        <p class="footer-generated">Generado por: {{ $data->user->first_name }}</p>
        <div class="bottom-bar"></div>
    </footer>
</body>
</html>
