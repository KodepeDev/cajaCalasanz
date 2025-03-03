<div>
    <div class="row pt-4">

        <div class="col-md-12 ">
            <div class="card card-danger">
                <div class="card-header">
                    <h3 class="card-title"><b>Montos Totales </b> <i class="fa fa-credit-card"></i></h3>
                    <div class="card-tools">
                        <button class="btn btn-info" onclick="window.print()"><i class="fas fa-print"></i></button>
                    </div>
                </div>
                <div class="card-body table-responsive">

                    <div id="lista_item_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre de cuenta</th>
                                    <th>tipo de cuenta</th>
                                    <th>Numero</th>
                                    <th>Saldo</th>
                                    <th class="text-center">Movimientos</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cuentas as $cuenta)
                                    <tr>
                                        <td>{{ $cuenta->id }}</td>
                                        <td>{{ $cuenta->account_name }}</td>
                                        <td>{{ $cuenta->account_type }}</td>
                                        <td>{{ $cuenta->account_number }}</td>
                                        <td>
                                            <strong>{{ number_format($cuenta->summaries->where('type', 'add')->where('date', '<=', now())->sum('amount') - $cuenta->summaries->where('type', 'out')->where('date', '<=', now())->sum('amount'), 2) }}</strong>
                                        </td>
                                        <td class="text-center">
                                            <a class="btn btn-sm btn-info"
                                                href="{{ route('account.show', $cuenta->id) }}"><i
                                                    class="fa fa fa-eye"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12 ">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ number_format($totalSaldo, 2) }}
                    </h3>
                    <p>{{ $divisa }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-wallet"></i>
                </div>
                <label class="small-box-footer">
                    Saldo Total
                </label>
            </div>
        </div>

        <div class="col-md-5">
            <ul class="list-group bg-info">
                <li class="list-group-item list-group-item-success">
                    Abonos Futuros: <span class="badge badge-success">{{ number_format($abonosFuturos, 2) }}</span>
                </li>
                <li class="list-group-item list-group-item-danger">
                    Retiros Futuros: <span class="badge badge-danger">{{ number_format($retirosFuturos, 2) }}</span>
                </li>
                <li class="list-group-item list-group-item-secondary">
                    <a href="javascript:void(0)" class="text-center mt-2">Total Movimientos Futuros
                        @if ($abonosFuturos - $retirosFuturos >= 0)
                            <span
                                class="badge badge-success">{{ number_format($abonosFuturos - $retirosFuturos, 2) }}</span>
                            {{ $divisa }}
                        @else
                            <span
                                class="badge badge-danger">{{ number_format($abonosFuturos - $retirosFuturos, 2) }}</span>
                            {{ $divisa }}
                        @endif
                    </a>
                </li>
            </ul>
            <!-- /.widget-user -->
        </div>


        <div class="col-md-4">
            <div class="">
                <ul class="list-group">
                    @if ($liquidezMes1 > 0)
                        <li class="list-group-item">
                            <a href="javascript:void(0)">Liquidez 1 Mes <span
                                    class="badge badge-success">{{ number_format($liquidezMes1, 2) }}
                                    {{ $divisa }}</span> - al
                                {{ Carbon\Carbon::now()->addMonth()->endOfMonth()->format('d-m-Y') }}</a>
                        </li>
                    @else
                        <li class="list-group-item">
                            <a href="javascript:void(0)">Liquidez 1 Mes <span
                                    class="badge badge-danger">{{ number_format($liquidezMes1, 2) }}
                                    {{ $divisa }}</span> - al
                                {{ Carbon\Carbon::now()->addMonth()->endOfMonth()->format('d-m-Y') }}</a>
                        </li>
                    @endif
                    @if ($liquidezMes3 > 0)
                        <li class="list-group-item">
                            <a href="javascript:void(0)">Liquidez 3 Meses <span
                                    class="badge badge-success">{{ number_format($liquidezMes3, 2) }}
                                    {{ $divisa }}</span> - al
                                {{ Carbon\Carbon::now()->addMonths(3)->endOfMonth()->format('d-m-Y') }}</a>
                        </li>
                    @else
                        <li class="list-group-item">
                            <a href="javascript:void(0)">Liquidez 3 Meses <span
                                    class="badge badge-danger">{{ number_format($liquidezMes3, 2) }}
                                    {{ $divisa }}</span> - al
                                {{ Carbon\Carbon::now()->addMonths(3)->endOfMonth()->format('d-m-Y') }}</a>
                        </li>
                    @endif
                    @if ($liquidezMes6 > 0)
                        <li class="list-group-item">
                            <a href="javascript:void(0)">Liquidez 6 Meses <span
                                    class="badge badge-success">{{ number_format($liquidezMes6, 2) }}
                                    {{ $divisa }}</span> - al
                                {{ Carbon\Carbon::now()->addMonths(6)->endOfMonth()->format('d-m-Y') }}</a>
                        </li>
                    @else
                        <li class="list-group-item">
                            <a href="javascript:void(0)">Liquidez 6 Meses <span
                                    class="badge badge-danger">{{ number_format($liquidezMes6, 2) }}
                                    {{ $divisa }}</span> - al
                                {{ Carbon\Carbon::now()->addMonths(6)->endOfMonth()->format('d-m-Y') }}</a>
                        </li>
                    @endif

                </ul>
            </div>
        </div>

    </div>
</div>
