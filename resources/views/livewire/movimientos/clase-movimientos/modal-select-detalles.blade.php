<div wire:ignore.self class="modal fade" id="modalProvision" role="dialog" tabindex="-1"
    aria-labelledby="modalProvisionLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius:8px; overflow:hidden;">

            {{-- ══ HEADER ══ --}}
            <div class="modal-header bg-warning border-0" style="padding:1rem 1.25rem;">
                <div class="d-flex align-items-center">
                    <div class="mr-3 d-flex align-items-center justify-content-center flex-shrink-0"
                        style="width:44px;height:44px;border-radius:10px;background:rgba(0,0,0,.12);">
                        <i class="fas fa-clipboard-list fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="modal-title mb-0 font-weight-bold" style="font-size:1rem;">
                            Provisiones Pendientes
                        </h5>
                        @if ($student_name)
                            <small><i class="fas fa-user-graduate mr-1"></i>{{ $student_name }}</small>
                        @else
                            <small>Seleccione los ítems a cobrar en este movimiento</small>
                        @endif
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {{-- ══ TOOLBAR ══ --}}
            <div class="d-flex flex-wrap align-items-center px-3 py-2 border-bottom"
                style="background:#f4f6f9; min-height:48px;">
                <small class="text-muted font-weight-bold text-uppercase mr-3"
                    style="letter-spacing:.5px; font-size:.7rem;">
                    Selección rápida
                </small>

                <button type="button" wire:click="selectAll"
                    wire:loading.attr="disabled" wire:target="selectAll,selectNow,searchStandProvision"
                    class="btn btn-sm btn-light border mr-1">
                    <i class="fas fa-check-double text-primary mr-1"></i> Todo
                </button>

                <button type="button" wire:click="searchStandProvision('all')"
                    wire:loading.attr="disabled" wire:target="selectAll,selectNow,searchStandProvision"
                    class="btn btn-sm btn-light border mr-1">
                    <i class="fas fa-history text-secondary mr-1"></i> Anteriores
                </button>

                <button type="button" wire:click="selectNow"
                    wire:loading.attr="disabled" wire:target="selectAll,selectNow,searchStandProvision"
                    class="btn btn-sm btn-light border mr-1">
                    <i class="fas fa-calendar-alt text-info mr-1"></i> {{ now()->format('m/Y') }}
                </button>

                <button type="button"
                    wire:click="$set('checkedProvision', [])"
                    wire:loading.attr="disabled" wire:target="selectAll,selectNow,searchStandProvision"
                    class="btn btn-sm btn-light border mr-1"
                    onclick="provClearAll()">
                    <i class="fas fa-times text-danger mr-1"></i> Limpiar
                </button>

                <div class="ml-auto d-flex align-items-center">
                    {{-- Spinner de texto (inline-block — Livewire lo oculta sin conflicto) --}}
                    <span wire:loading wire:target="selectAll,selectNow,searchStandProvision"
                        class="text-muted small mr-2">
                        <i class="fas fa-spinner fa-spin mr-1"></i> Actualizando...
                    </span>

                    <span id="prov-badge" class="badge badge-warning text-dark"
                        style="font-size:.78rem; padding:.4em .85em; border-radius:20px; display:none;">
                        <i class="fas fa-check-circle mr-1"></i>
                        <span id="prov-badge-n">0</span> selec.
                    </span>
                </div>
            </div>

            {{-- ══ BARRA DE PROGRESO (loader sin conflicto de display) ══
                 Bootstrap gestiona display:flex via stylesheet.
                 Livewire agrega/quita display:none en inline style → no hay conflicto. --}}
            <div wire:loading wire:target="selectAll,selectNow,searchStandProvision"
                class="progress" style="height:4px; border-radius:0;">
                <div class="progress-bar progress-bar-striped progress-bar-animated bg-warning w-100"></div>
            </div>

            {{-- ══ BODY / TABLA ══ --}}
            <div class="modal-body p-0">
                {{-- overflow-y aquí → position:sticky del thead funciona --}}
                <div style="max-height:52vh; overflow-y:auto;">
                    <table class="table table-sm table-hover mb-0" style="font-size:.875rem;">
                        <thead style="position:sticky; top:0; z-index:10; background:#495057; color:#fff;">
                            <tr>
                                <th class="text-center border-0" style="width:46px; padding:.6rem .5rem;">
                                    <i class="fas fa-check-square" style="opacity:.7;"></i>
                                </th>
                                <th class="border-0" style="width:75px; padding:.6rem .5rem;">MES</th>
                                <th class="border-0" style="padding:.6rem .5rem;">DESCRIPCIÓN</th>
                                <th class="border-0" style="width:120px; padding:.6rem .5rem;">STAND</th>
                                <th class="text-right border-0" style="width:115px; padding:.6rem .5rem;">SOLES</th>
                                <th class="text-right border-0" style="width:115px; padding:.6rem .5rem;">DÓLAR</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($provision_detalles && count($provision_detalles) > 0)
                                @foreach ($provision_detalles as $item)
                                    @php
                                        $isChecked = in_array($item->id, $checkedProvision);
                                        $esSoles   = $item->currency->id == 1 || $item->currency->id == null;
                                    @endphp
                                    <tr data-id="{{ $item->id }}"
                                        style="{{ $isChecked ? 'background:#fff3cd;' : '' }} cursor:pointer;"
                                        onclick="provToggleRow(this)">
                                        {{-- stopPropagation para no doble-toggle al hacer click en el propio checkbox --}}
                                        <td class="text-center align-middle" style="padding:.45rem .5rem;"
                                            onclick="event.stopPropagation()">
                                            <input type="checkbox"
                                                id="chk{{ $item->id }}"
                                                value="{{ $item->id }}"
                                                wire:model.defer="checkedProvision"
                                                {{ $isChecked ? 'checked' : '' }}
                                                onchange="provUpdateRow(this)"
                                                style="width:1.05rem; height:1.05rem; cursor:pointer; accent-color:#e0a800;">
                                        </td>
                                        <td class="align-middle" style="padding:.45rem .5rem;">
                                            <span class="badge badge-secondary" style="font-size:.7rem;">
                                                {{ $item->date->format('m/Y') }}
                                            </span>
                                        </td>
                                        <td class="align-middle" style="padding:.45rem .5rem;">
                                            <label for="chk{{ $item->id }}"
                                                class="mb-0 {{ $isChecked ? 'font-weight-bold' : '' }}"
                                                style="cursor:pointer;">
                                                {{ $item->description }}
                                            </label>
                                        </td>
                                        <td class="align-middle" style="padding:.45rem .5rem;">
                                            @if ($item->stand)
                                                <span class="badge badge-light border" style="font-size:.72rem;">
                                                    {{ $item->stand->name }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-right align-middle" style="padding:.45rem .5rem;">
                                            @if ($esSoles)
                                                <span class="text-success font-weight-bold">
                                                    S/. {{ number_format($item->amount, 2) }}
                                                </span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td class="text-right align-middle" style="padding:.45rem .5rem;">
                                            @if ($item->currency->id == 2)
                                                <span class="text-primary font-weight-bold">
                                                    $. {{ number_format($item->amount, 2) }}
                                                </span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fas fa-folder-open fa-2x d-block mb-2"
                                            style="opacity:.35;"></i>
                                        No hay provisiones pendientes para este alumno
                                    </td>
                                </tr>
                            @endif
                        </tbody>

                        @if ($provision_detalles && count($provision_detalles) > 0)
                            <tfoot>
                                <tr style="background:#e9ecef; border-top:2px solid #ced4da;">
                                    <td colspan="4" class="text-right font-weight-bold align-middle py-2"
                                        style="font-size:.78rem; letter-spacing:.4px;">
                                        TOTAL DISPONIBLE
                                    </td>
                                    <td class="text-right align-middle py-2">
                                        <span class="text-success font-weight-bold" style="font-size:.9rem;">
                                            S/. {{ number_format($total_prov, 2) }}
                                        </span>
                                        @error('amount')
                                            <div class="text-danger" style="font-size:.75rem;">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td class="text-right align-middle py-2">
                                        <span class="text-primary font-weight-bold" style="font-size:.9rem;">
                                            $. {{ number_format($total_prov_dolar, 2) }}
                                        </span>
                                        @error('amount')
                                            <div class="text-danger" style="font-size:.75rem;">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>

            {{-- ══ FOOTER ══ --}}
            <div class="modal-footer border-top d-flex align-items-center"
                style="background:#f4f6f9; padding:.75rem 1rem;">

                {{-- Panel de resumen izquierda --}}
                <div class="mr-auto">
                    <div id="prov-hint" class="text-muted small d-flex align-items-center"
                        style="{{ count($checkedProvision) > 0 ? 'display:none!important;' : '' }}">
                        <i class="fas fa-info-circle text-warning mr-2"></i>
                        Marque los ítems que desea incluir en el movimiento
                    </div>
                    <div id="prov-summary" class="d-flex align-items-center"
                        style="{{ count($checkedProvision) == 0 ? 'display:none!important;' : '' }}">
                        <div class="d-flex align-items-center justify-content-center flex-shrink-0 mr-2"
                            style="width:36px;height:36px;border-radius:50%;background:#d4edda;">
                            <i class="fas fa-check text-success"></i>
                        </div>
                        <div>
                            <div class="font-weight-bold" style="font-size:.85rem; line-height:1.2;">
                                <span id="prov-footer-n">{{ count($checkedProvision) }}</span>
                                ítem(s) seleccionado(s)
                            </div>
                            <div class="text-muted" style="font-size:.78rem;">
                                Total a cobrar:
                                <strong class="text-dark">
                                    S/. {{ number_format($total_prov_cobrar, 2) }}
                                </strong>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Botones --}}
                <button type="button" class="btn btn-light border mr-2" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Cancelar
                </button>

                <button type="button"
                    wire:click="selectedProvisions"
                    wire:loading.attr="disabled"
                    wire:target="selectedProvisions"
                    class="btn btn-success font-weight-bold px-4"
                    data-dismiss="modal">
                    <span wire:loading.remove wire:target="selectedProvisions">
                        <i class="fas fa-check-circle mr-1"></i> Confirmar
                        @if (count($checkedProvision) > 0)
                            ({{ count($checkedProvision) }})
                        @endif
                    </span>
                    <span wire:loading wire:target="selectedProvisions">
                        <i class="fas fa-spinner fa-spin mr-1"></i> Procesando...
                    </span>
                </button>
            </div>

        </div>
    </div>
</div>

<script>
(function () {
    // ── Actualiza fila y badge cuando el usuario toca un checkbox ──────────
    window.provUpdateRow = function (cb) {
        var row = cb.closest('tr');
        if (!row) return;
        row.style.background = cb.checked ? '#fff3cd' : '';
        var lbl = row.querySelector('label');
        if (lbl) lbl.style.fontWeight = cb.checked ? 'bold' : '';
        provSync();
    };

    // ── Click en cualquier celda de la fila activa el checkbox ─────────────
    window.provToggleRow = function (row) {
        var cb = row.querySelector('input[type=checkbox]');
        if (!cb) return;
        cb.checked = !cb.checked;
        provUpdateRow(cb);
    };

    // ── Limpiar todo (botón Limpiar) ───────────────────────────────────────
    window.provClearAll = function () {
        document.querySelectorAll('#modalProvision tbody input[type=checkbox]').forEach(function (cb) {
            cb.checked = false;
            var row = cb.closest('tr');
            if (row) { row.style.background = ''; }
            var lbl = row ? row.querySelector('label') : null;
            if (lbl) lbl.style.fontWeight = '';
        });
        provSync();
    };

    // ── Sincroniza el contador tras un re-render de Livewire ───────────────
    function provSync() {
        var n = document.querySelectorAll('#modalProvision tbody input[type=checkbox]:checked').length;

        var badge    = document.getElementById('prov-badge');
        var badgeN   = document.getElementById('prov-badge-n');
        var footerN  = document.getElementById('prov-footer-n');
        var hint     = document.getElementById('prov-hint');
        var summary  = document.getElementById('prov-summary');

        if (badge)   badge.style.display   = n > 0 ? 'inline-block' : 'none';
        if (badgeN)  badgeN.textContent    = n;
        if (footerN) footerN.textContent   = n;
        if (hint)    hint.style.display    = n > 0 ? 'none'  : 'flex';
        if (summary) summary.style.display = n > 0 ? 'flex'  : 'none';
    }

    // ── Ejecutar tras cada actualización del DOM por Livewire ─────────────
    document.addEventListener('livewire:load', function () {
        provSync();
        if (window.Livewire) {
            Livewire.hook('message.processed', function () {
                provSync();
            });
        }
    });
}());
</script>
