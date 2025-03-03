<div class="row pt-4">
    <div class="col-sm-12">
        <div class="card card-warning">
            <div class="card-header">

                <h4 class="card-title">
                    <b>{{$componentName}}</b>
                </h4>

            </div>

            <div class="card-body">

                <div class="form-inline">
                    <div class="mr-5 form-group">
                        <select wire:model="role" class="form-control">
                            <option value="Elegir" selected>== Seleccione el Rol ==</option>
                            @foreach ($roles as $role)
                                @if ($role->id != 1)
                                <option value="{{$role->id}}"> {{$role->name}} </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <button wire:click.prevent="SyncAll()" type="button" class="btn btn-info mbmobile inblock"><i class="fas fa-check-circle"></i> Sincronizar todos</button>
                    <button onclick="Revocar()" type="button" class="ml-4 btn btn-danger mbmobile inblock"><i class="fas fa-times-circle"></i> Revocar todos</button>
                </div>



                <div class="mt-3 row">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table mt-1 table-bordered table-striped">
                                <thead class="thead-dark">
                                    <tr>
                                        <th class="text-white table-th">ID</th>
                                        <th class="text-center text-white table-th">PERMISO</th>
                                        <th class="text-center text-white table-th">ROLES CON EL PERMISO</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($permisos as $permiso)
                                    <tr>
                                        <td><h6>{{$permiso->id}}</h6></td>

                                        <td class="text-center">
                                            <div class="form-group">
                                                <div class="custom-control custom-switch custom-switch-on-success">

                                                    <input type="checkbox" class="custom-control-input" wire:change="SyncPermiso($('#p' + {{$permiso->id}}).is(':checked'), '{{$permiso->name}}')" id="p{{$permiso->id}}" value="{{$permiso->id}}" {{$permiso->checked == 1 ? 'checked' : ''}}>

                                                    <label class="custom-control-label" for="p{{$permiso->id}}">{{$permiso->name}}</label>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="text-center">
                                            <h6>{{ \App\Models\User::permission($permiso->name)->count() }}</h6>
                                        </td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>

                            {{$permisos->links()}}
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function(){
        window.livewire.on('sync-error', msg => {
            Swal.fire({
                title: 'Ops...!',
                text: msg,
                icon: 'error',

                confirmButtonText: 'Aceptar',
            })
        })
        window.livewire.on('permi', msg => {
            Swal.fire({
                title: 'Buen Trabajo!',
                text: msg,
                icon: 'success',

                confirmButtonText: 'Aceptar',
            })
        })
        window.livewire.on('sync-all', msg => {
            Swal.fire({
                title: 'Buen Trabajo!',
                text: msg,
                icon: 'success',

                confirmButtonText: 'Aceptar',
            })
        })
        window.livewire.on('remove-all', msg => {
            Swal.fire({
                title: 'Buen Trabajo!',
                text: msg,
                icon: 'success',

                confirmButtonText: 'Aceptar',
            })
        })
    });

    function Revocar(){

        Swal.fire({
            title: 'Confirmar',
            text: '¿Confirmas revocar todos los permisos?',
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Cerrar',

            confirmButtonText: 'Aceptar',
        }).then(function(result){
            if(result.value){
                window.livewire.emit('revoke-all')
                Swal.close();
            }
        });
    }
</script>

