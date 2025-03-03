<div class="pt-4">
    <div class="invoice p-3 mb-3">
        <!-- title row -->
        <div class="row">
            <div class="col-12 no-print">
                <button class="btn btn-secondary mb-4" onclick="window.history.back()">Volver</button>
                @if ($movimiento->status == 'PAID')
                    <a class="btn btn-warning mb-4" href="{{ route('movimientos.editar', $movimiento->id) }}">Editar</a>
                @endif
            </div>
            <div class="col-12">
                <h4>
                    <i class="fas fa-file-invoice"></i> RECIBO DE {{ $type == 'add' ? 'INGRESO' : 'GASTO' }}
                    <small class="float-right">Fecha: {{ $date }} Hora: {{ $hour }}</small>
                </h4>
            </div>
            <!-- /.col -->
        </div>
        <!-- info row -->
        <hr>
        <div class="row invoice-info">
            <div class="col-sm-4 invoice-col px-4 overflow-wrap">
                <b class="">EMPRESA</b><br>
                <hr>
                <address>
                    <table>
                        <tbody>
                            <tr>
                                <th>RAZON SOCIAL:</th>
                                <td>ASOCIACION DE COMERCIANTES PROPIETARIOS
                                    IMPORTADORES DE LOS 5 CONTINENTES</td>
                            </tr>
                            <tr>
                                <th>RUC:</th>
                                <td>20504414826</td>
                            </tr>
                            <tr>
                                <th>DIRECCIÓN:</th>
                                <td>JR. MONTEVIDEO NRO. 665 (CRUCE CON ABANCAY-3ER PISO) LIMA - LIMA - LIMA</td>
                            </tr>
                        </tbody>
                    </table>
                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
                <strong>{{ $type == 'add' ? 'CLIENTE' : 'PROVEEDOR' }}</strong><br>
                <hr>
                <address>
                    <table>
                        <tbody>
                            <tr>
                                <th>RAZON SOCIAL:</th>
                                <td>{{ $movimiento->customer->full_name }}</td>
                            </tr>
                            <tr>
                                <th>DOC:</th>
                                <td>{{ $movimiento->customer->document }}</td>
                            </tr>
                            <tr>
                                <th>DIRECCIÓN:</th>
                                <td>{{ $movimiento->customer->address }}</td>
                            </tr>
                        </tbody>
                    </table>
                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-4 invoice-col">
                <strong>SERIE-NUMERO: <span
                        class="badge badge-primary">{{ $movimiento->recipt_series . '-' . str_pad($movimiento->recipt_number, 8, '0', STR_PAD_LEFT) }}</span>
                    @if ($movimiento->status == 'NULLED')
                        <span class="error">ANULADO POR: {{ $movimiento->nulled_motive }}</span>
                    @endif
                </strong><br>
                <hr>
                <address>
                    <table>
                        <tbody>
                            <tr>
                                <th>#OPERACION:</th>
                                <td>{{ $numero_operacion }}</td>
                            </tr>
                            <tr>
                                <th>CUENTA:</th>
                                <td>{{ $movimiento->account->account_name }}</td>
                            </tr>
                        </tbody>
                    </table>
                </address>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <!-- Table row -->
        <div class="row">
            <div class="col-12 table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>MES</th>
                            <th width="40%">DESCRIPCIÓN</th>
                            <th>CATEGORIA</th>
                            <th>#STAND</th>
                            <th class="text-center">SOLES</th>
                            <th class="text-center">DOLAR</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($movimiento->status == 'PAID')
                            @foreach ($movimiento->details as $item)
                                <tr>
                                    <td>{{ $item->date->format('m-Y') }}</td>
                                    <td>{{ $item->description }}</td>
                                    <td>{{ $item->category->name }}</td>
                                    @if ($item->stand)
                                        <td>{{ $item->stand->name }}</td>
                                    @else
                                        <td>S/N</td>
                                    @endif
                                    <td class="text-center">
                                        @if ($item->currency->id == 1 || $item->currency->id == null)
                                            S/. {{ number_format($item->amount, 2) }}
                                        @else
                                            S/. {{ number_format($item->amount * $tc, 2) }}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($item->currency->id == 2)
                                            $. {{ number_format($item->amount, 2) }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            @foreach ($movimiento->nulledDetails as $item)
                                <tr>
                                    <td>{{ $item->date->format('m-Y') }}</td>
                                    <td>{{ $item->description }}</td>
                                    <td>{{ $item->category }}</td>
                                    <td>{{ $item->stand }}</td>
                                    <td class="text-center">S/. {{ number_format($item->amount, 2) }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <div class="row">
            <!-- accepted payments column -->
            <div class="col-6">
                <p class="lead">Medio de Pago:</p>

                <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                    {{ $movimiento->paymentMethod->name }}</p>

                <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                    OBSERVACIONES: {{ $movimiento->observation }}</p>
            </div>
            <!-- /.col -->
            <div class="col-6">
                <p class="lead text-center">Fecha de pago {{ $movimiento->date->format('d/m/Y') }} - TC:
                    {{ $tc }}</p>

                <div class="table-responsive">
                    <table class="table">
                        {{-- <tr>
                            <th style="width:50%">Subtotal:</th>
                            <td>S/. {{number_format($movimiento->amount, 2)}}</td>
                        </tr> --}}
                        <tr>
                        <tr>
                            <th>Total:</th>
                            <td class="text-center">S/. {{ number_format($movimiento->amount, 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <!-- this row will not appear when printing -->
        <div class="row no-print">
            <div class="col-12">
                <a href="#" rel="noopener" onclick="window.print();" class="btn btn-default"><i
                        class="fas fa-print"></i> Imprimir</a>

                <a href="{{ route('movimientos.a4.recibo', $movimiento->id) }}" target="_blank"
                    class="btn btn-danger float-right" style="margin-right: 5px;">
                    <i class="fas fa-download"></i> Generar PDF
                </a>
                <a href="{{ route('movimientos.ticket.recibo', $movimiento->id) }}" target="_blank"
                    class="btn btn-info float-right" style="margin-right: 5px;">
                    <i class="fas fa-ticket-alt"></i> Generar Ticket
                </a>
            </div>
        </div>
    </div>
    <!-- /.invoice -->
</div>
