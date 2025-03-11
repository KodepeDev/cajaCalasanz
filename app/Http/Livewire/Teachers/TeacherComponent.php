<?php

namespace App\Http\Livewire\Teachers;

use Livewire\Component;

class TeacherComponent extends Component
{
    public $full_name, $document, $email, $is_active, $document_type, $phone, $photo;

    public function mount(){
        $this->document_type = 1;
        $this->is_active = 1;
    }
    public function render()
    {
        return view('livewire.teachers.teacher-component')->extends('adminlte::page');
    }

    public function create()
    {
        $this->validate([
            'full_name' => 'required|string|max:255',
            'document' => 'required|string|max:255|unique:teachers',
            'email' => 'required|string|email|max:255|unique:teachers',
            'is_active' => 'required|integer',
            'document_type' => 'required|integer',
        ]);
    }

    public function newTeacher()
    {
        $this->emit('showModal');
    }
}
