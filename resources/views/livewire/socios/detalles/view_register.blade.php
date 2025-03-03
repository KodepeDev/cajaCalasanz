<div>
    <div wire:ignore.self class="modal fade" id="modalRegistro" data-backdrop="static" data-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="modalRegistroLabel">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" id="modalRegistroLabel">Vista detalle de Registro</h5>
                    <button type="button" class="close" data-dismiss="modal" wire:click='resetDetail'
                        aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if ($detalle != null)
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center" colspan="4">{{ $socio->full_name }}</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td width="40%">
                                        Mes: {{ $detalle->date->format('m-Y') }}
                                    </td>
                                    <td>
                                        Categoria: {{ $detalle->category->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        @if ($detalle->stand)
                                            Stand: {{ $detalle->stand->name }}
                                        @endif
                                    </td>
                                    <td>
                                        Monto: {{ $detalle->currency->id == 1 ? 'S/. ' : '$. ' }}
                                        {{ number_format($detalle->amount, 2) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <p>{{ $detalle->description }}</p>
                                    </td>
                                </tr>
                            </tbody>

                            <tbody>
                                <tr>
                                    <td class="text-center" colspan="4">
                                        Estado: {{ $detalle->status == 1 ? 'PAGADO el ' : 'PENDIENTE DE PAGO' }}

                                        @if ($detalle->status == 1)
                                            {{ $detalle->date_paid->format('d-m-Y') }}
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    @endif
                </div>
                <div class="modal-footer">
                    <button wire:click='resetDetail' wire:loading.attr='disabled'
                        wire:target='crearMovimiento, updateMovimiento' class="btn btn-secondary">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>
