<div class="pt-4">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de usuarios</h3>

            <div class="card-tools">
                <button class="btn btn-primary btn-sm" data-toggle="modal"
                    data-target="#userModal">Crear usuario</button>
            </div>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Nombre</th>
                        <th>Documento</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th>Status</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->first_name }}</td>
                            <td>{{ $user->document }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->roles->first()->name }}</td>
                            <td>
                                @if($user->id!=1)
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default">
                                            @if($user->status==1)
                                                Activo &nbsp;&nbsp;
                                            @else
                                                Inactivo
                                            @endif

                                        </button>
                                        @if(Auth::id()==1)
                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                                <span class="caret"></span>
                                                <span class="sr-only"></span>
                                            </button>
                                        @endif

                                        <ul class="dropdown-menu" role="menu">
                                            @if($user->status==1)
                                                <li><a class="dropdown-item" wire:click="desactivarUsuario({{$user->id}})" href="javascript:void(0)">Desactivar</a></li>
                                            @else
                                                <li><a class="dropdown-item" wire:click="activarUsuario({{$user->id}})" href="javascript:void(0)">Activar</a></li>
                                            @endif


                                        </ul>
                                    </div>
                                @else
                                    <label>Superusuario</label>
                                @endif
                            </td>
                            <td>
                                @if(Auth::id() != $user->id)
                                    @can('usuarios.delete')
                                    <button wire:click="editUser({{ $user->id }})" class="btn btn-primary btn-sm"><i class=" fa fa-cog"></i></a></button>
                                    @endcan
                                    @can('usuarios.delete')
                                    <button onclick="confirmarEliminado('{{$user->id}}')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                    @endcan
                                @else
                                    @if(Auth::id() == $user->id)
                                        @can('usuarios.edit')
                                        <button wire:click="editUser({{ $user->id }})" class="btn btn-primary btn-sm"><i class=" fa fa-cog"></i></a></button>
                                        @endcan
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="userModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">
                        {{ $selected_id == 0 ? 'Crear' : 'Editar' }} Usuario
                    </h5>
                    <button type="button" wire:loading.attr='disabled' wire:target='createUser, ConsutasApi' class="close" data-dismiss="modal" wire:click="resetUI" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div wire:loading.class='overlay' class="d-none" wire:loading.class.remove='d-none'>
                        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="userFirstName">Documento DNI</label>
                            <div class="input-group">

                                <div class="input-group-prepend">
                                <select class="form-control custom-select" disabled>
                                    <option value="1">DNI</option>
                                </select>
                                </div>
                                <input type="number" wire:model.defer="document" id="document" class="form-control {{ $errors->has('document') ? 'is-invalid' : '' }}" wire:loading.attr='readonly' wire:change='clearDataApi()' aria-label="Text input with dropdown button">
                                <div class="input-group-append">
                                    <button type="button" wire:click="ConsutasApi()" class="btn btn-sm btn-info"><i class="fas fa-search"></i>RENIEC</button>
                                </div>
                                @error('document')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                        </div>

                        <div class="form-group col-md-6">
                            <label for="userFirstName">Nombres</label>
                            <input type="text" wire:model.lazy="userFirstName" id="userFirstName"
                                class="form-control {{ $errors->has('userFirstName') ? 'is-invalid' : '' }}">
                            @error('userFirstName')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group col-md-8">
                            <label for="userLastName">Apellidos</label>
                            <input type="text" wire:model.lazy="userLastName" id="userLastName"
                                class="form-control {{ $errors->has('userLastName') ? 'is-invalid' : '' }}">
                            @error('userLastName')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="role">Rol</label>
                            <select class="form-control custom-select {{ $errors->has('role') ? 'is-invalid' : '' }}" wire:model.defer="role">
                                <option value="Elegir">=== Elegir ===</option>
                                @foreach ($roles as $rol)
                                    @if ($rol->id != 1)
                                        <option value="{{$rol->id}}">{{$rol->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('role')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-8">
                            <div class="mb-3">
                                <label for="">Correo</label>
                                <input type="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" wire:model.defer='email' id="email" placeholder="correo">
                                @error('email')
                                <span class="error">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="">Contraseña</label>
                                <input type="password" class="form-control {{ $errors->has('userPassword') ? 'is-invalid' : '' }}" wire:model.defer='userPassword' id="userPassword" placeholder="contraseña ********">
                                @error('userPassword')
                                <span class="error">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="">Foto de Perfil</label>
                                <input type="file" class="form-control {{ $errors->has('userProfileImage') ? 'is-invalid' : '' }}" wire:model.lazy='userProfileImage' id="{{$photoId}}customFileLang" accept="image/*" lang="es">
                                @error('photo')
                                <span class="error">{{$message}}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group col-md-4 d-flex justify-content-center align-items-center">
                            @if ($userProfileImage)
                            <img class="shadow img-fluid img-circle" width="150px" height="150px" src="{{$userProfileImage->temporaryUrl()}}" alt="">
                            @else
                            <img class="shadow img-fluid img-circle" width="150px" height="150px" src="{{asset('imagenes/profile-default.png')}}" alt="">
                            @endif
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:loading.attr='disabled' wire:target='createUser, ConsutasApi' data-dismiss="modal" wire:click="resetUI()">Cancelar</button>
                    @if ($selected_id == 0)
                    <button type="button" class="btn btn-primary" wire:loading.attr='disabled' wire:target='createUser, ConsutasApi' wire:click.prevent="createUser()">Crear</button>
                    @else
                    <button type="button" class="btn btn-primary" wire:loading.attr='disabled' wire:target='updateUser, ConsutasApi' wire:click.prevent="updateUser()">Actualizar</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    document.addEventListener('DOMContentLoaded', function(){
        window.livewire.on('modalShow', msg => {
            $('#userModal').modal('show');
        });
        window.livewire.on('error', msg => {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: msg,
            });

            livewire.emit('resetUI');
        });
        window.livewire.on('userAdded', msg => {
            Swal.fire({
                icon: 'success',
                title: 'Buen Trabajo!',
                text: msg,
            });
            $('#userModal').modal('hide');
            livewire.emit('resetUI');
        });
        window.livewire.on('userUpdated', msg => {
            Swal.fire({
                icon: 'success',
                title: 'Buen Trabajo!',
                text: msg,
            });
            $('#userModal').modal('hide');
            livewire.emit('resetUI');
        });
        window.livewire.on('userDeleted', msg => {
            Swal.fire({
                icon: 'success',
                title: 'Buen Trabajo!',
                text: msg,
            });
        });

    });

    function confirmarEliminado(id){

            Swal.fire({
                icon: 'warning',
                title: 'Confirmar',
                text: '¿Confirmas el eliminado del registro?',
                showCancelButton: true,
                cancelButtonText: 'Cerrar',

                confirmButtonText: 'Aceptar',
            }).then(function(result){
                if(result.value){
                    window.livewire.emit('deleteRow', id)
                    Swal.close();
                }
            });
        }

</script>
