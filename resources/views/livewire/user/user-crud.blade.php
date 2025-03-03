<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Lista de usuarios</h3>

            <div class="card-tools">
                <button wire:click="$set('isCreating', true)" class="btn btn-primary btn-sm">Crear usuario</button>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Correo electrónico</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <button wire:click="editUser({{ $user->id }})" class="btn btn-primary btn-sm">Editar</button>
                                <button wire:click="deleteUser({{ $user->id }})" class="btn btn-danger btn-sm">Eliminar</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if ($isCreating || $isEditing)
        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">{{ $isCreating ? 'Crear' : 'Editar' }} usuario</h3>
            </div>
            <div class="card-body">
                <form wire:submit.prevent="{{ $isCreating ? 'createUser' : 'updateUser' }}">
                    <input type="hidden" wire:model="userId">
                    <div class="form-group">
                        <label for="userName">Nombre</label>
                        <input type="text" wire:model.lazy="userName" id="userName" class="form-control {{ $errors->has('userName') ? 'is-invalid' : '' }}">
                        @error('userName')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="userEmail">Correo electrónico</label>
                        <input type="email" wire:model.lazy="userEmail" id="userEmail" class="form-control {{ $errors->has('userEmail') ? 'is-invalid' : ''}}">
                        @error('userEmail')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">{{ $isCreating ? 'Crear' : 'Actualizar' }}</button>
                    <button wire:click="$set('isCreating', false)" wire:click="$set('isEditing', false)" class="btn btn-secondary btn-sm">Cancelar</button>
                </form>
            </div>
        </div>
    @endif
</div>
