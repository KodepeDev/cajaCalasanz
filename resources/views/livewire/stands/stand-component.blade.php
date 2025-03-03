<div class="pt-4">
    <div class="card col-md-12">
        <div class="card-header">
            <h2 class="card-title">Listado de Stands</h2>
            <div class="card-tools">

                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-addon1">STAND</span>
                    </div>
                    <input type="text" class="form-control" wire:model="stand" placeholder=""
                        aria-describedby="basic-addon1">
                    <button class="btn btn-warning ml-4" data-toggle="modal" data-target="#globalModal">Nuevo</button>
                </div>

            </div>
        </div>
    </div>
    <div class="row mt-2">
        @foreach ($stands as $stand)
            <div class="col-md-3 col-sm-6">
                <div class="card card-info">
                    <div class="card-header">
                        <h1 class="card-title">Stand # {{ $stand->name }}</h1>
                        <div class="card-tools">
                            <button class="btn btn-sm btn-warning" wire:click='edit({{ $stand->id }})'
                                onclick="cargarSelectEdit({{ $stand->id }})"><i class="fas fa-pen"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <h4 class="card-title">Socio:
                            @if ($stand->partner)
                                {{ $stand->partner->full_name }}
                            @else
                                NO SOCIO - NO ALQUILADO
                            @endif
                        </h4>
                        <p class="card-text"># {{ $stand->name }}</p>
                    </div>
                    <div class="card-footer text-muted">
                        AREA: {{ $stand->stage->name }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="row">
        {{ $stands->links() }}
    </div>
    @include('livewire.stands.form')
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        var selectedId = {{ $selected_id }};
        console.log(selectedId);

        if (selectedId > 0) {
            $("#select_socios").select2().select2("val", {{ $partner_id }});
        } else {
            $('#select_socios').select2();
            $('#select_socios').on('change', function(e) {
                let socioId = $('#select_socios').select2("val");
                if (socioId > 0) {
                    @this.set('partner_id', socioId);
                } else {
                    @this.set('partner_id', null);
                }
            });
        }

        // $('#select_socios').val(@json($selected_id)).trigger('change');

        window.livewire.on('select2Refresh', function() {
            $('#select_socios').select2();
        });

        window.livewire.on('new_stand', msg => {
            Swal.fire({
                icon: 'success',
                title: '¡Buen trabajo!',
                text: msg,
            });

            $('#globalModal').modal('hide');

        });

        window.livewire.on('error_fecha', msg => {
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: msg,
            });
        });
        window.livewire.on('mostrar_modal', msg => {
            $('#globalModal').modal('show');
        });

        $('#globalModal').on('hidden.bs.modal', function() {
            livewire.emit('resetUI');
            $('#select_socios').val(null).trigger('change');
        });

    });

    function cargarSelectEdit(socioId) {
        let socio = socioId;
        if (socioId > 0) {
            $("#select_socios").val(socio).trigger('change')
        }
    }
</script>
