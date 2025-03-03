<?php

namespace App\Http\Livewire\User;

use App\Models\User;
use Livewire\Component;

class UserCrud extends Component
{
    protected $listeners = ['userDeleted' => 'handleUserDeleted'];

    public $users;
    public $userId;
    public $userName;
    public $userEmail;
    public $isCreating = false;
    public $isEditing = false;

    public function mount()
    {
        $this->users = User::all();
    }

    public function render()
    {
        return view('livewire.user.user-crud')->extends('adminlte::page');
    }

    public function createUser()
    {
        $this->validate([
            'userName' => 'required|min:3',
            'userEmail' => 'required|email'
        ]);

        User::create([
            'name' => $this->userName,
            'email' => $this->userEmail
        ]);

        $this->users = User::all();

        $this->resetInputFields();

        $this->isCreating = false;
    }
    public function editUser($userId)
    {
        $user = User::find($userId);

        $this->userId = $user->id;
        $this->userName = $user->name;
        $this->userEmail = $user->email;

        $this->isEditing = true;
    }
    public function updateUser()
    {
        $this->validate([
            'userName' => 'required|min:3',
            'userEmail' => 'required|email'
        ]);

        User::find($this->userId)->update([
            'name' => $this->userName,
            'email' => $this->userEmail
        ]);

        $this->users = User::all();

        $this->resetInputFields();

        $this->isEditing = false;
    }
    public function deleteUser($userId)
    {
        User::destroy($userId);

        $this->emit('userDeleted');
    }

    public function handleUserDeleted()
    {
        $this->users = User::all();
    }

    private function resetInputFields()
    {
        $this->userId = '';
        $this->userName = '';
        $this->userEmail = '';
    }
}
