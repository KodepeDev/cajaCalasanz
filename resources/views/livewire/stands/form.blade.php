@include('common.modal.modalHeader')
<div class="modal-body">
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <strong></strong>
    </div>

    <script>
        $(".alert").alert();
    </script>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="name"># Código</label>
                <input type="text" wire:model.defer='name' class="form-control" id="name" placeholder="1212">
                @error('name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="etapa">Etapa</label>
                <select class="custom-select" wire:model.defer='stage_id' name="etapa" id="">
                    <option selected>Seleccione la Etapa</option>
                    @foreach ($stages as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
                @error('stage_id')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="col-md-4" wire:ignore>
            <div class="form-group">
                <label for="socio">Dueño</label>
                {{-- <select class="custom-select" wire:model.defer='partner_id' name="etapa" id="select_socios">
                    <option selected>Seleccione Socio</option>
                    @foreach ($partners as $full_name => $id)
                        <option value="{{$id}}">{{$full_name}}</option>
                    @endforeach
                </select> --}}
                <x-adminlte-select name="socio" class="custom-select" id="select_socios" wire:model.defer='partner_id'
                    style="width: 100%;">
                    <option value="" selected>Seleccione Socio</option>
                    @foreach ($partners as $full_name => $id)
                        <option value="{{ $id }}">{{ $full_name }}</option>
                    @endforeach
                </x-adminlte-select>
            </div>
            @error('partner_id')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

    </div>
</div>
@include('common.modal.modalFooter')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if ({{ $selected_id }} > 0) {

            // $("#select_socios").select2().select2("val", {{ $partner_id }});

            console.log('hola esta funcionando');
        }
    });
</script>
