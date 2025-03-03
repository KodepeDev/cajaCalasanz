            <div class="modal-footer">
                <button type="button" wire:click.prevent="resetUI()" wire:loading.attr='disabled'
                    wire:target='photo, resetUI' class="btn btn-sm btn-secondary" data-dismiss="modal">CERRAR</button>
                @if ($selected_id < 1)
                    <button type="button" wire:click.prevent="create()" wire:loading.attr='disabled'
                        wire:target='photo, create' class="btn btn-sm btn-warning close-modal">GUARDAR</button>
                @else
                    <button type="button" wire:click.prevent="update()" wire:loading.attr='disabled'
                        wire:target='photo, update' class="btn btn-sm btn-warning close-modal">ACTUALIZAR</button>
                @endif
            </div>
            </div>
            </div>
            </div>
