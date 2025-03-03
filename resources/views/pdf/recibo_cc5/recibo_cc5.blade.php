<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="application/pdf; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Recibo N°{{ $data->recipt_series }}-{{ $data->recipt_number }}</title>
    {{-- <style>

        body {
            max-width: 100%;
            margin: 0 auto;
            margin-top: -20px;
            font-size: 1.2rem;
            line-height: 1;
            font-family: Arial, Helvetica, sans-serif;
            overflow: hidden;
        }
        body p{
            font-size: 1.2rem;
        }
        hr {
            border: 0.4px solid gray;
        }
        table {
            text-align: center;
            width: 100%;
            font-size: 1rem;
            border: 0.5px solid grey;
            border-spacing: 0;
            border-radius: 5px;
            overflow: hidden;

        }
        th, td {
        padding: 10px;
        font-size: 1rem;
        }
        .thead {
            background-color: gray;
            color: white;
            padding: 0.5rem;
        }
        header {
            text-align: center;
            line-height: 1;
        }
        header h1 {
            font-size: 1rem;
        }
        header h6{
            line-height: 1;
        }
        .ticket_logo {
            width: 300px;
        }
        small{
            background-color: #f5e642;
            font-size: 1.5rem;
            padding: 15px;
            border: 0.5px solid grey;
            border-radius: 5px;
            display: block;
        }
    </style> --}}
    <style>
        @font-face {
            font-family: 'RomanSans';
            src: url({{ storage_path('fonts/ArialNarrow.ttf') }});
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: RomanSans, Helvetica, sans-serif;
            max-width: 250mm !important;
        }

        .contenedor {
            /* border: 2px solid red; */
        }

        .box-recibo {
            position: relative;
            margin-left: -1cm;
            margin-top: 0.1cm;
            width: 22.5cm;
            height: 13cm;
            /*border: 2px solid red;*/
        }

        .numero {
            position: absolute;
            margin-top: 4.1cm;
            margin-left: 4.5cm;
        }

        .puesto {
            position: absolute;
            margin-top: 5cm;
            margin-left: 4.5cm;
        }

        .etapa {}

        .socio {
            position: absolute;
            margin-top: 5.5cm;
            margin-left: 4.5cm;
        }

        .fecha {
            position: absolute;
            margin-top: 6cm;
            margin-left: 4.5cm;
        }

        .concepto {
            position: absolute;
            margin-top: 6.5cm;
            margin-left: 4.5cm;
        }

        p {
            font-size: 12pt;
        }

        p.letras {
            position: absolute;
            margin-top: 11.3cm;
            margin-left: 4.5cm;
            font-size: 10pt;
        }

        .observaciones {
            position: absolute;
            margin-top: 12.1cm;
            margin-left: 4.5cm;
        }

        .derecha {
            float: right;
        }

        .monto_total {
            float: right;
            margin-top: 4.1cm;
            margin-right: 4cm;
        }

        .mes {
            float: right;
            margin-top: 5cm;
            margin-right: 6.7cm;
        }

        .sumas {
            float: right;
            margin-top: 6.5cm;
            margin-right: 4cm;
            text-align: right;
        }

        .concepto {
            max-width: 56%;
            max-width: 55%;
            margin-right: .3cm;
        }

        .sumas-item {
            text-transform: uppercase;
            white-space: nowrap;
            margin-left: .3cm;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .paid_by {
            clear: both;
            position: absolute;
            bottom: 1.25cm;
            margin-left: 1cm;

        }

        .paid_by p {
            font-size: 8pt;
        }
    </style>
</head>

<body>
    <div class="contenedor">
        <div class="box-recibo">
            <div class="izquierda">
                <p class="numero">{{ $data->recipt_series }} -
                    {{ str_pad($data->recipt_number, 8, '0', STR_PAD_LEFT) }}</p>
                @php
                    foreach ($data->details as $item) {
                        if ($item->stand) {
                            $stand = $item->stand->name;
                            $area = $item->stand->stage->name;
                        }
                        if ($item->partner) {
                            $socio = $item->partner->full_name;
                        }
                        $mes = $item->date->format('m/Y');
                    }
                @endphp
                @if (!empty($stand))
                    <p class="puesto">
                        {{ $stand }} - {{ strtoupper($area) }}
                    </p>
                @else
                    <p class="puesto">
                        S/N
                    </p>
                @endif
                @if (!empty($socio))
                    <p class="socio">{{ $data->customer->full_name }}</p>
                @else
                    <p class="socio">{{ $data->paid_by }}</p>
                @endif
                <p class="fecha">{{ $data->date->format('d/m/Y') }} {{ $data->created_at->format('h:i A') }}</p>
                <div class="concepto">
                    @foreach ($data->details as $item)
                        <p class="sumas-item">{{ $item->description }} </p>
                    @endforeach
                </div>

                <p class="letras">{{ $textoTotal }}</p>
                <p class="observaciones">{{ $data->observation }}</p>
            </div>

            <div class="derecha">
                <p class="monto_total">S/. {{ number_format($data->amount, 2) }}</p>
                <p class="mes">{{ $mes ?? '' }}</p>
                <div class="sumas">
                    @foreach ($data->details as $item)
                        <p class="">
                            {{ number_format($item->currency_id == 1 ? $item->amount : $item->changed_amount, 2) }}</p>
                    @endforeach
                    <hr>
                    <p>S/. {{ number_format($data->amount, 2) }}</p>
                </div>
                <div class="paid_by">
                    <p>ATENDIDO POR: {{ strtoupper($data->user->first_name) }}</p>
                    <p class="">PAGADO POR: {{ $data->paid_by }}</p>
                </div>

            </div>
        </div>
    </div>
</body>

</html>
