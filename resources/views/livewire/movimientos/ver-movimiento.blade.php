<div class="pt-3">

    @php
        $isIncome  = $movimiento->type === 'add';
        $isPaid    = $movimiento->status === 'PAID';
        $isNulled  = $movimiento->status === 'NULLED';
        $receipt   = $movimiento->recipt_series . '-' . str_pad($movimiento->recipt_number, 8, '0', STR_PAD_LEFT);
        $typeLabel = $isIncome ? 'INGRESO' : 'GASTO';
        $themeClass = $isIncome ? 'card-primary' : 'card-danger';
        $badgeClass = $isPaid ? 'badge-success' : ($isNulled ? 'badge-danger' : 'badge-warning');
        $statusLabel = match($movimiento->status) {
            'PAID'    => 'Pagado',
            'NULLED'  => 'Anulado',
            default   => 'Pendiente',
        };
    @endphp

    <div class="card {{ $themeClass }}">

        {{-- Card header --}}
        <div class="card-header d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <i class="fa fa-file-invoice-dollar mr-2"></i>
                <div>
                    <h3 class="card-title mb-0">Recibo de {{ $typeLabel }}</h3>
                    <br>
                    <small class="text-light opacity-75">
                        {{ $receipt }}
                        &nbsp;&mdash;&nbsp;
                        {{ $date }} &nbsp; {{ $hour }}
                    </small>
                </div>
            </div>
            <span class="badge {{ $badgeClass }} px-3 py-2" style="font-size:.85rem;">
                {{ $statusLabel }}
                @if ($isNulled && $movimiento->nulled_motive)
                    &mdash; {{ $movimiento->nulled_motive }}
                @endif
            </span>
        </div>

        <div class="card-body">

            {{-- ── Info row ── --}}
            <div class="row mb-4">

                {{-- Empresa --}}
                <div class="col-md-4 mb-3 mb-md-0">
                    <div class="p-3 rounded h-100" style="background:#f8f9fa;border-left:4px solid #6c757d;">
                        <div class="font-weight-bold text-uppercase text-muted mb-2" style="font-size:.7rem;letter-spacing:.6px;">
                            <i class="fas fa-building mr-1"></i> Empresa
                        </div>
                        @if ($setting)
                            <div class="font-weight-bold" style="font-size:.9rem;line-height:1.35;">
                                {{ $setting->company_name }}
                            </div>
                            <div class="text-muted mt-1" style="font-size:.8rem;">
                                <span class="font-weight-bold">RUC:</span> {{ $setting->company_ruc }}
                            </div>
                            @if ($setting->address)
                                <div class="text-muted mt-1" style="font-size:.8rem;">
                                    <i class="fas fa-map-marker-alt mr-1"></i>{{ $setting->address }}
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                {{-- Cliente / Proveedor --}}
                <div class="col-md-4 mb-3 mb-md-0">
                    <div class="p-3 rounded h-100" style="background:#f8f9fa;border-left:4px solid {{ $isIncome ? '#007bff' : '#dc3545' }};">
                        <div class="font-weight-bold text-uppercase text-muted mb-2" style="font-size:.7rem;letter-spacing:.6px;">
                            <i class="fas fa-user mr-1"></i> {{ $isIncome ? 'Cliente' : 'Proveedor' }}
                        </div>
                        @if ($movimiento->customer)
                            <div class="font-weight-bold" style="font-size:.9rem;line-height:1.35;">
                                {{ $movimiento->customer->full_name }}
                            </div>
                            <div class="text-muted mt-1" style="font-size:.8rem;">
                                <span class="font-weight-bold">Doc:</span> {{ $movimiento->customer->document }}
                            </div>
                            @if ($movimiento->customer->address)
                                <div class="text-muted mt-1" style="font-size:.8rem;">
                                    <i class="fas fa-map-marker-alt mr-1"></i>{{ $movimiento->customer->address }}
                                </div>
                            @endif
                            @if ($movimiento->tutor)
                                <div class="text-muted mt-1" style="font-size:.8rem;">
                                    <i class="fas fa-user-graduate mr-1"></i>
                                    {{ $movimiento->tutor->students->pluck('full_name')->implode(', ') }}
                                </div>
                            @elseif ($movimiento->student)
                                <div class="text-muted mt-1" style="font-size:.8rem;">
                                    <i class="fas fa-user-graduate mr-1"></i>{{ $movimiento->student->full_name }}
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                {{-- Datos del recibo --}}
                <div class="col-md-4">
                    <div class="p-3 rounded h-100" style="background:#f8f9fa;border-left:4px solid #28a745;">
                        <div class="font-weight-bold text-uppercase text-muted mb-2" style="font-size:.7rem;letter-spacing:.6px;">
                            <i class="fas fa-receipt mr-1"></i> Datos del recibo
                        </div>
                        <table class="w-100" style="font-size:.85rem;">
                            <tr>
                                <td class="text-muted pr-2 py-1">Serie - Número</td>
                                <td class="font-weight-bold">
                                    <span class="badge badge-primary">{{ $receipt }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted pr-2 py-1">Cuenta</td>
                                <td>{{ $movimiento->account->account_name }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted pr-2 py-1">Medio de pago</td>
                                <td>{{ $movimiento->paymentMethod->name }}</td>
                            </tr>
                            @if ($movimiento->operation_number)
                                <tr>
                                    <td class="text-muted pr-2 py-1">N° Operación</td>
                                    <td>{{ $movimiento->operation_number }}</td>
                                </tr>
                            @endif
                            @if ($movimiento->paid_by)
                                <tr>
                                    <td class="text-muted pr-2 py-1">Pagado por</td>
                                    <td>{{ $movimiento->paid_by }}</td>
                                </tr>
                            @endif
                            @if ($movimiento->user)
                                <tr>
                                    <td class="text-muted pr-2 py-1">Registrado por</td>
                                    <td>{{ $movimiento->user->first_name }}</td>
                                </tr>
                            @endif
                            @if ($tc)
                                <tr>
                                    <td class="text-muted pr-2 py-1">T. Cambio</td>
                                    <td>S/. {{ $tc }}</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>

            </div>

            {{-- ── Tabla de conceptos ── --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm">
                    <thead class="{{ $isIncome ? 'bg-primary' : 'bg-danger' }} text-white">
                        <tr>
                            <th style="width:110px">MES</th>
                            <th style="width:40%">DESCRIPCIÓN</th>
                            <th>CATEGORÍA</th>
                            @if ($isIncome)
                                <th class="text-right">SOLES</th>
                                <th class="text-right">DÓLAR</th>
                            @else
                                <th class="text-right">TOTAL</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @if ($isPaid)
                            @forelse ($movimiento->details as $item)
                                <tr>
                                    <td class="small">{{ $item->date->format('m/Y') }}</td>
                                    <td>{{ $item->description }}</td>
                                    <td>{{ $item->category->name }}</td>
                                    @if ($isIncome)
                                        <td class="text-right">
                                            @if ($item->currency_id == 1)
                                                S/. {{ number_format($item->amount, 2) }}
                                            @else
                                                <span class="text-muted small">TC </span>
                                                S/. {{ number_format($item->amount * $tc, 2) }}
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            @if ($item->currency_id == 2)
                                                $. {{ number_format($item->amount, 2) }}
                                            @endif
                                        </td>
                                    @else
                                        <td class="text-right">S/. {{ number_format($item->amount, 2) }}</td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $isIncome ? 5 : 4 }}" class="text-center text-muted py-3">
                                        Sin conceptos registrados.
                                    </td>
                                </tr>
                            @endforelse
                        @else
                            @forelse ($movimiento->nulledDetails as $item)
                                <tr class="table-danger">
                                    <td class="small">{{ $item->date ?? '—' }}</td>
                                    <td>{{ $item->description }}</td>
                                    <td>{{ $item->category }}</td>
                                    <td class="text-right" colspan="{{ $isIncome ? 2 : 1 }}">
                                        S/. {{ number_format($item->amount, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $isIncome ? 5 : 4 }}" class="text-center text-muted py-3">
                                        Sin detalle de anulación.
                                    </td>
                                </tr>
                            @endforelse
                        @endif
                    </tbody>
                    <tfoot>
                        <tr style="background:#e9ecef;border-top:2px solid #ced4da;">
                            <td colspan="{{ $isIncome ? 3 : 3 }}" class="text-right font-weight-bold py-2"
                                style="font-size:.8rem;text-transform:uppercase;letter-spacing:.4px;">
                                Total
                            </td>
                            <td colspan="{{ $isIncome ? 2 : 1 }}" class="text-center py-2">
                                <span class="font-weight-bold {{ $isIncome ? 'text-success' : 'text-danger' }}"
                                    style="font-size:1.05rem;">
                                    S/. {{ number_format($movimiento->amount, 2) }}
                                </span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- ── Observaciones ── --}}
            @if ($movimiento->observation)
                <div class="mt-3 p-3 rounded" style="background:#fffde7;border-left:4px solid #ffc107;">
                    <div class="font-weight-bold text-muted mb-1" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;">
                        <i class="fas fa-sticky-note mr-1"></i> Observaciones
                    </div>
                    <p class="mb-0 text-muted" style="font-size:.9rem;">{{ $movimiento->observation }}</p>
                </div>
            @endif

        </div>

        {{-- Card footer --}}
        <div class="card-footer d-flex justify-content-between align-items-center flex-wrap no-print" style="gap:.5rem;">
            <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                <i class="fa fa-arrow-left mr-1"></i> Volver
            </button>

            <div class="d-flex flex-wrap" style="gap:.5rem;">
                <button type="button" class="btn btn-light" onclick="window.print()">
                    <i class="fas fa-print mr-1"></i> Imprimir
                </button>

                @if ($isPaid)
                    <a href="{{ route('movimientos.a4.recibo', $movimiento->id) }}" target="_blank"
                        class="btn btn-outline-primary">
                        <i class="fas fa-file-pdf mr-1"></i> PDF A4
                    </a>
                    <a href="{{ route('movimientos.a5.recibo', $movimiento->id) }}" target="_blank"
                        class="btn btn-outline-primary">
                        <i class="fas fa-file-pdf mr-1"></i> PDF A5
                    </a>
                    <a href="{{ route('movimientos.editar', $movimiento->id) }}"
                        class="btn btn-warning">
                        <i class="fa fa-edit mr-1"></i> Editar
                    </a>
                @endif
            </div>
        </div>

    </div>
</div>
