<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="application/pdf; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Recibo N°{{ $data->recipt_series }}-{{ $data->recipt_number }}</title>
    <style>
        @font-face {
            font-family: 'ArialNarrow';
            src: url({{ storage_path('fonts/ArialNarrow.ttf') }});
        }

        body {
            max-width: 100%;
            margin: 0 auto;
            margin-top: -20px;
            font-size: .8rem;
            line-height: 1;
            font-family: ArialNarrow, Helvetica, sans-serif;
            overflow: hidden;
        }

        body p {
            font-size: 8rem;
        }

        hr {
            border: 0.4px dashed gray;
        }

        table {
            text-align: center;
            width: 100%;
            font-size: .8rem;
            border: 0.5px solid rgb(184, 0, 70);
            border-spacing: 0;
            border-radius: 5px;
            overflow: hidden;

        }

        th,
        td {
            padding: 5px;
            font-size: 0.5rem;
        }

        .content th,
        .content td {
            border: 0.5px solid rgb(184, 0, 70);
            /* border-bottom: 0.5px solid grey; */
        }

        .thead {
            background-color: rgb(196, 196, 196);
            padding: 0.5rem;
        }

        header {
            text-align: center;
            line-height: 1;
        }

        header h1 {
            font-size: .8rem;
        }

        header h6 {
            line-height: 1;
        }

        .ticket_logo {
            width: 60px;
        }

        small {
            background-color: #e3e3e3;
            font-size: 1rem;
            padding: 15px;
            border: 2px solid black;
            border-radius: 5px;
            display: block;
        }

        .body_content {
            position: relative;
        }

        .nulled_container {
            color: red;
            text-align: center;
            border: 2px solid red;
            position: absolute;
            width: 350px;
            height: 180px;
            top: 35%;
            left: 25%;
            transform: translate(50%, -50%);
            transform: rotate(-45deg);
            padding: 5px;
        }

        .nulled_title {
            font-size: 45px;
            letter-spacing: 10px;
            font-weight: bolder;
        }

        /* para recibos pimentel */
        h3 {
            padding: 0;
            margin: 0;
            font-size: 14px;
        }

        .container {
            position: relative;
            height: 190mm;
        }

        .izquierda {
            float: left;
            width: 45%;
        }

        .derecha {
            float: right;
            width: 45%;
        }

        .divider {
            position: absolute;
            display: block;
            width: 380mm;
            border: 1.4px dashed red;
            transform: rotate(90deg);
            left: -20%;
        }

        .aditional {
            color: rgba(73, 73, 73, 0.534);
        }

        .footer {
            color: #000;
            z-index: 1000;

        }

        .footer tr td {
            font-size: 0.5rem;
            margin: 20px;
            text-align: left;
            border-top: 1px solid #000000;
        }

        .footer table {
            border-spacing: 8px 2px;
        }

        .footer td,
        .footer th {
            padding: 6px;
        }

        footer {
            position: fixed;
            width: 45%;
            margin: 0 auto;
            left: 0px;
            bottom: 5px;
            height: 35px;
        }
    </style>
</head>

<body class="body_content">
    @if ($data->status == 'NULLED')
        <div class="nulled_container">
            <h1 class="nulled_title">ANULADO</h1>
            <h5 class="nulled_motive">{{ $data->nulled_motive }}</h5>
        </div>
    @endif

    <div class="container">
        <div class="izquierda">
            <footer class="footer">
                <table style="border: 0;" cellpadding="0">
                    <tr>
                        <td width="30%">
                            <b>Revisado por: </b>
                            {{-- <i>{{ Auth::user()->first_name }}</i> --}}
                        </td>
                        <td width="30%" class="text-center">
                            <b>Aprobado por: </b>
                            {{-- <i>{{ Auth::user()->first_name }}</i> --}}
                        </td>
                        <td width="30%">
                            <b>Recibido por: </b>
                        </td>
                    </tr>
                </table>
            </footer>
            @include('pdf.recibo_cc5.pimentel-body')
        </div>
        <hr class="divider">
        <div class="derecha">
            <footer class="footer">
                <table style="border: 0;" cellpadding="0">
                    <tr>
                        <td width="30%">
                            <b>Revisado por: </b>
                            {{-- <i>{{ Auth::user()->first_name }}</i> --}}
                        </td>
                        <td width="30%" class="text-center">
                            <b>Aprobado por: </b>
                            {{-- <i>{{ Auth::user()->first_name }}</i> --}}
                        </td>
                        <td width="30%">
                            <b>Recibido por: </b>
                        </td>
                    </tr>
                </table>
            </footer>
            @include('pdf.recibo_cc5.pimentel-body')
        </div>
    </div>
</body>

</html>
