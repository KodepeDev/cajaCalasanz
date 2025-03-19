<?php

namespace App\Http\Livewire\Teachers;

use App\Models\Grade;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class TeacherComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $full_name, $document, $email, $is_active, $document_type, $phone, $photo, $grade_id;
    public $componentName = 'Docente';
    public $selected_id, $grades;
    public $schoolYear;

    public function mount(){
        $this->document_type = 1;
        $this->is_active = 1;
        $this->selected_id = 0;
        $this->grades = Grade::pluck('id', 'name');
        $this->schoolYear = \App\Models\SchoolYear::current()->id;
    }
    public function render()
    {
        $teachers = \App\Models\Teacher::paginate(10);
        return view('livewire.teachers.teacher-component', compact('teachers'))->extends('adminlte::page');
    }

    public function newTeacher()
    {
        $this->emit('showModal');
    }

    public function create()
    {
        $this->validate([
            'full_name' =>'required|string|max:255',
            'document' => 'required|string|max:255|unique:teachers',
            'email' => 'required|string|email|max:255|unique:teachers',
            'is_active' => 'required|integer',
            'grade_id' =>'required|integer',
            'document_type' => 'required|integer',
        ]);
        $teacher = \App\Models\Teacher::create([
            'full_name' => $this->full_name,
            'document' => $this->document,
            'email' => $this->email,
            'is_active' => $this->is_active,
            'document_type' => $this->document_type,
            'phone' => $this->phone,
            'photo' => $this->photo,
        ]);
        DB::table('teacher_assignments')->insert([
            'teacher_id' => $teacher->id,
            'grade_id' => $this->grade_id,
            'section_id' => null,
            'school_year_id' => $this->schoolYear,
        ]);

        $this->resetUI();
        $this->emit('teacher-added', 'Docente agregado sactisfactoriamente');
    }

    public function edit($id)
    {
        $teacher = \App\Models\Teacher::find($id);
        $this->full_name = $teacher->full_name;
        $this->document = $teacher->document;
        $this->email = $teacher->email;
        $this->is_active = $teacher->is_active;
        $this->document_type = $teacher->document_type;
        $this->phone = $teacher->phone;
        $this->photo = $teacher->photo;
        $this->grade_id = $teacher->grade->id;
        $this->selected_id = $id;
        $this->emit('showModal');
    }

    public function update()
    {
        $this->validate([
            'full_name' =>'required|string|max:255',
            'document' =>'required|string|max:255|unique:teachers,document,'.$this->selected_id,
            'email' =>'required|string|email|max:255|unique:teachers,email,'.$this->selected_id,
            'is_active' =>'required|integer',
            'grade_id' =>'required|integer',
            'document_type' =>'required|integer',
        ]);
        $teacher = \App\Models\Teacher::find($this->selected_id);
        $teacher->update([
            'full_name' => $this->full_name,
            'document' => $this->document,
            'email' => $this->email,
            'is_active' => $this->is_active,
            'document_type' => $this->document_type,
            'phone' => $this->phone,
            'photo' => $this->photo,
        ]);
        DB::table('teacher_assignments')->where('teacher_id', $this->selected_id)->update([
            'grade_id' => $this->grade_id,
            'section_id' => null,
            'school_year_id' => $this->schoolYear,
        ]);
        $this->resetUI();
        $this->emit('teacher-updated', 'Docente actualizado sactisfactoriamente');
    }

    public function resetUI()
    {
        $this->full_name = '';
        $this->document = '';
        $this->email = '';
        $this->is_active = 1;
        $this->document_type = 1;
        $this->phone = '';
        $this->photo = '';
        $this->selected_id = 0;

        $this->resetValidation();
    }
}
