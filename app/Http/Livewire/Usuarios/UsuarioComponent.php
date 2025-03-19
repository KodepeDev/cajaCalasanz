<?php

namespace App\Http\Livewire\Usuarios;

use App\Models\User;
use Livewire\Component;
use App\Http\Controllers\ApiConsultasController;
use Livewire\WithFileUploads;
use Spatie\Permission\Models\Role;

class UsuarioComponent extends Component
{
    use WithFileUploads;

    protected $listeners = ['deleteRow' => 'deleteUser'];


    public $users, $roles;
    private $dataApi = [];
    public $userId, $userFirstName, $userLastName, $email, $document, $userPassword, $userStatus, $userProfileImage;
    public $selected_id, $componentName, $photoId, $mensaje, $role;

    public function updated($propertyName)
    {
        if ($propertyName == 'userProfileImage' && $this->userProfileImage) {
            $allowedTypes = ['image/jpeg', 'image/png'];
            if (!in_array($this->userProfileImage->getMimeType(), $allowedTypes)) {
                $this->reset('userProfileImage');
                $this->photoId = rand();
            }
        }
    }

    public function mount()
    {
        $this->photoId = rand();
        $this->userStatus = false;
        $this->roles = Role::orderBy('name', 'asc')->get();
        $this->role = 'Elegir';
    }


    public function render()
    {

        $this->users = User::where('id', '!=', 1)->get();

        return view('livewire.usuarios.usuario-component')->extends('adminlte::page');
    }

    public function createUser()
    {
        $roles = [
            'userFirstName' => 'required|min:3|max:30',
            'userLastName' => 'required|min:3|max:60',
            'role' => 'required|not_in:Elegir',
            'email' => 'required|email|unique:users',
            'document' => 'required|numeric|unique:users|digits:8',
            'userProfileImage' => 'nullable|image|max:2048',
            'userPassword' => 'required|min:8',
        ];

        $messages = [
            'userFirstName.required' => 'El nombre es requerido',
            'userFirstName.min' => 'El nombre debe tener como minimo 3 caracteres',
            'userFirstName.max' => 'El nombre debe tener como maximo 30 caracteres',
            'userLastName.required' => 'El apellido es requerido',
            'userLastName.min' => 'El apellido debe tener como minimo 3 caracteres',
            'userLastName.max' => 'El apellido debe tener como maximo 60 caracteres',
            'role.required' => 'Debe elegir un rol para el usuario',
            'role.not_in' => 'Debe elegir un rol para el usuario',
            'email.required' => 'El campo correo es requerido',
            'email.email' => 'El campo debe contener un correo válido',
            'email.unique' => 'El correo ya se encuentra registrado',
            'document.required' => 'El DNI del usuario es un campo requerido',
            'document.numeric' => 'El DNI del usuario debe ser un campo numérico',
            'document.digits' => 'El DNI del usuario debe teber 8 digitos',
            'document.unique' => 'El usuario con el documento ingresado ya ha sido registrado',
            'userProfileImage.image' => 'El avatar debe ser una imagen válida',
            'userProfileImage.max' => 'El avatar debe ser como máximo de 2048 Kb',
            'userPassword.required' => 'La contraseña es obligatorio',
            'userPassword.min' => 'La contraseña debe tener al menos 8 caracteres'
        ];

        $this->validate($roles, $messages);

        if($this->validarCrear()){

            $user = User::create([
                'first_name' => $this->userFirstName,
                'last_name' => $this->userLastName,
                'document' => $this->document,
                'email' => $this->email,
                'status' => $this->userStatus,
                'password' => bcrypt($this->userPassword),
            ]);

            $user->syncRoles($this->role);


            if($this->userProfileImage)
            {
                $customFileName = uniqid().'_.'.$this->userProfileImage->extension();

                $this->userProfileImage->storeAs('public/usuarios', $customFileName);

                $user->profile_image = $customFileName;
                $user->save();

            }

            $this->emit('userAdded', 'El usuario fue creado exitosamente');
            $this->resetUI();
        }

    }

    public function editUser($userId)
    {
        $user = User::find($userId);

        $this->userFirstName = $user->first_name;
        $this->userLastName = $user->last_name;
        $this->selected_id = $user->id;
        $this->document = $user->document;
        $this->userStatus = $user->status;
        $this->email = $user->email;
        $this->role = $user->roles->first()->id;

        $this->emit('modalShow', 'Abril modal');
    }

    public function updateUser()
    {
        $roles = [
            'userFirstName' => 'required|min:3|max:30',
            'userLastName' => 'required|min:3|max:60',
            'role' => 'required|not_in:null',
            'email' => "required|email|unique:users,email,{$this->selected_id}",
            'document' => "required|numeric|digits:8|unique:users,document,{$this->selected_id}",
            'userProfileImage' => 'nullable|image|max:2048',
            'userPassword' => 'nullable|min:8',
        ];

        $messages = [
            'userFirstName.required' => 'El nombre es requerido',
            'userFirstName.min' => 'El nombre debe tener como minimo 3 caracteres',
            'userFirstName.max' => 'El nombre debe tener como maximo 30 caracteres',
            'userLastName.required' => 'El apellido es requerido',
            'userLastName.min' => 'El apellido debe tener como minimo 3 caracteres',
            'userLastName.max' => 'El apellido debe tener como maximo 60 caracteres',
            'role.required' => 'Debe elegir un rol para el usuario',
            'role.not_in' => 'Debe elegir un rol para el usuario',
            'email.required' => 'El campo correo es requerido',
            'email.email' => 'El campo debe contener un correo válido',
            'email.unique' => 'El correo ya se encuentra registrado',
            'document.required' => 'El DNI del usuario es un campo requerido',
            'document.numeric' => 'El DNI del usuario debe ser un campo numérico',
            'document.digits' => 'El DNI del usuario debe teber 8 digitos',
            'document.unique' => 'El usuario con el documento ingresado ya ha sido registrado',
            'userProfileImage.image' => 'El avatar debe ser una imagen válida',
            'userProfileImage.max' => 'El avatar debe ser como máximo de 2048 Kb',
            'userPassword.min' => 'La contraseña debe tener al menos 8 caracteres'
        ];

        $this->validate($roles, $messages);

        $user = User::find($this->selected_id);

        $imagenAntigua = $user->profile_image;

        $user->update([
            'first_name' => $this->userFirstName,
            'last_name' => $this->userLastName,
            'document' => $this->document,
            'email' => $this->email,
            'status' => $this->userStatus,
        ]);

        if($this->userPassword != ''){
            $user->password = bcrypt($this->userPassword);
            $user->save();
        }
        $user->syncRoles($this->role);

        if($this->userProfileImage)
            {
                $customFileName = uniqid(). '_.' .$this->userProfileImage->extension();
                $this->userProfileImage->storeAs('public/usuarios/', $customFileName);

                $user->profile_image = $customFileName;

                $user->save();

                if ($imagenAntigua != null) {

                    if (file_exists('storage/usuarios/' .$imagenAntigua)) {

                       unlink('storage/usuarios/' .$imagenAntigua);
                    }
                }
            }


        $this->emit('userUpdated', 'El usuario fue actualizado exitosamente');

        $this->resetUI();
    }

    public function deleteUser($userId)
    {
        $usuario = User::findOrFail($userId);

        $imagenAntigua = $usuario->profile_image;

        if($usuario->summaries->count() > 0){

            $this->emit('error', 'El usuario tiene movimientos registrados');

        }else{

            $usuario->delete();


            if ($imagenAntigua != null) {

                if (file_exists('storage/usuarios/' .$imagenAntigua)) {

                   unlink('storage/usuarios/' .$imagenAntigua);
                }
            }

            $this->emit('userDeleted', 'El ususario fue eliminado');
        }

    }

    public function resetUI()
    {
        $this->userFirstName = '';
        $this->userLastName = '';
        $this->selected_id = 0;
        $this->document = '';
        $this->email = '';
        $this->userPassword = '';
        $this->userProfileImage = '';
        $this->role = '';

        $this->photoId = rand();

        $this->resetValidation();
    }


    public function ConsutasApi()
    {

        $roles = [
            'document' => 'required|numeric|digits:8',
        ];

        $messages = [
            'document.required' => 'El DNI del usuario es un campo requerido',
            'document.numeric' => 'El DNI debe ser un valor numerico',
            'document.digits' => 'El DNI del usuario debe teber 8 digitos',
        ];

        $this->validate($roles, $messages);

        sleep(1);

        if ($this->selected_id !== 0){

            $cust = User::where('document', $this->document)->get();
        }

        if($cust->count() > 1){
                $this->mensaje = 'El usuario ya se encuentra registrado';
                $this->emit('error', $this->mensaje);
        }else{

            $this->dataApi = (new ApiConsultasController)->apiDni($dni = $this->document);

            if(isset($this->dataApi['error'])){

                $this->mensaje = $this->dataApi['error'];
                $this->emit('error', $this->mensaje);
                return;

            }else{

                if($this->dataApi == null){

                    $this->mensaje = 'No existe el documento o ingrese manualmente los datos del usuario';
                    $this->emit('error', $this->mensaje);
                    return;

                }else{

                    $this->userFirstName = $this->dataApi['nombres'];
                    $this->userLastName = $this->dataApi['apellidoPaterno'] . ' '. $this->dataApi['apellidoMaterno'];
                    $this->document = $this->dataApi['numeroDocumento'];

                }
            }
        }
    }

    public function clearDataApi()
    {
        $this->userFirstName = '';
        $this->userLastName = '';
    }

    public function activarUsuario($id)
    {
        $usuario = User::findOrFail($id);

        $usuario->status = 1;
        $usuario->save();

        $this->emit('userUpdated', 'El usuario fue actualizado exitosamente');
    }
    public function desactivarUsuario($id)
    {
        $usuario = User::findOrFail($id);

        $usuario->status = 0;
        $usuario->save();

        $this->emit('userUpdated', 'El usuario fue actualizado exitosamente');
    }

    public function validarCrear()
    {
        $usuario = User::all();

        if($usuario->count() < 6){
            return true;
        }else{
            $this->emit('error', 'Ya llegó al limite de usuarios admitidos');
            return false;
        }
    }
}
