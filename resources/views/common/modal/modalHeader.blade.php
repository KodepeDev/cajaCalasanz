<div wire:ignore.self class="modal fade" data-backdrop="static" id="globalModal" role="dialog"
    aria-labelledby="globalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-maroon">
                <h5 class="modal-title"><b>{{ $componentName }}</b> | {{ $selected_id > 0 ? 'Editar' : 'Crear' }}</h5>
                <button type="button" wire:click.prevent="resetUI()" class="close" data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
