<div class="form-row mb-3" wire:ignore>
    <div class="form-group col-md-3 col-sm-6">
        <input type="month" name="name" wire:model.lazy='meses' id="name" class="form-control">
    </div>
    <div class="form-group col-md-3 col-sm-6 {{ Request::routeIs('provision.socio') ? 'invisible' : '' }}">
        <input class="form-control" type="text" wire:model.lazy='search' placeholder="Busca por nombre">
    </div>
    <div class="form-group col-md-3 col-sm-6">
        <select class="form-control select2" id="inputGroupSelect01" wire:model.defer='partner_id'>
            <option value="">Elige un Estudiante...</option>
            @foreach ($students as $full_name => $id)
                <option value="{{ $id }}">{{ $full_name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-md-3 col-sm-6">
        <button class="btn btn-info" wire:click='limpiar()' onclick="limpiarSelect();">Limpiar</button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        $('#inputGroupSelect01').select2();
        $("#inputGroupSelect01").select2({
            theme: 'bootstrap4',
        });
        $('#inputGroupSelect01').on('change', function(e) {
            let socioID = $('#inputGroupSelect01').select2('val');
            @this.set('student_id', socioID);
        });

    });

    function limpiarSelect() {
        $("#inputGroupSelect01").val('').trigger('change');
        $("#inputGroupSelect01").select2();
        $("#inputGroupSelect01").select2({
            theme: 'bootstrap4',
        });
    }
</script>
