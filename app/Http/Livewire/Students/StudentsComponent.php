<?php

namespace App\Http\Livewire\Students;

use Livewire\Component;
use App\Models\Bitacora;
use App\Models\Customer;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\ApiConsultasController;
use App\Models\Grade;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\StudentTutor;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;

class StudentsComponent extends Component
{

    use WithPagination;
    use WithFileUploads;

    public $full_name, $first_name, $last_name, $email, $document_type, $document, $phone, $address, $photo, $is_active, $description;
    public $tutor_full_name, $tutor_first_name, $tutor_last_name, $tutor_email, $tutor_document_type, $tutor_document, $tutor_phone, $tutor_address, $tutor_is_active, $tutor_is_client, $tutor_type, $tutor_description;
    public $componentName, $selected_id, $mensaje, $photoId, $search="";
    private $dataApi = [];

    public $teachers, $teacher_id, $grades, $grade_id, $sections, $section_id, $schoolYear;

    protected $listeners = [
        'resetUI',
        'anularSocio' => 'anular',
    ];

    protected $paginationTheme = 'bootstrap';

    public function updated($propertyName)
    {
        if ($propertyName == 'photo' && $this->photo) {
            $allowedTypes = ['image/jpeg', 'image/png'];
            if (!in_array($this->photo->getMimeType(), $allowedTypes)) {
                $this->reset('photo');
                $this->photoId = rand();
            }
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->componentName = "Estudiantes";
        $this->is_active = true;
        $this->tutor_is_active = true;
        $this->photoId = rand();
        $this->document_type = 1;
        $this->tutor_document_type = 1;
        $this->selected_id = 0;

        $this->teachers = Teacher::where('is_active', true)->pluck('id', 'full_name');
        $this->grades = Grade::pluck('id', 'name');
        $this->schoolYear = SchoolYear::current();
    }

    public function render()
    {
        // dd($this->is_active);
        if($this->search)
        {
            $students = Student::where(function ($query) {
                $query->where('document', 'like', '%'.$this->search.'%')
                    ->orWhere('full_name', 'like', '%'.$this->search.'%');
            })
            ->paginate(10);
        }else{
            $students = Student::select('id', 'document', 'full_name', 'document_type')->paginate(10);
        }

        return view('livewire.students.students-component', compact('students'))->extends('adminlte::page');
    }

    public function save()
    {
        $rules = [
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:3',
            'document_type' => 'required',
            'document' => 'required|min:8|max:11|unique:students',
            'photo' => 'nullable|image|max:2048',
            'description' => 'nullable',

            'tutor_first_name' => 'required|min:3',
            'tutor_last_name' => 'required|min:3',
            'tutor_email' => 'nullable|min:3|max:100',
            'tutor_document' => 'required|min:8|max:11',
            'tutor_type' => 'required',
            'tutor_description' => 'nullable',
            'grade_id' => 'required',
            'teacher_id' => 'required',

        ];

        $messages = [
            'full_name.required' => 'El nombre del socio es requerido',
            'document_type.required' => 'El tipo de documento es requerido',
            'document.required' => 'El documento es requerido',
            'document.min' => 'El documento debe tener al menos 8 digitos',
            'document.max' => 'El documento debe tener como máximo 11 digitos',
            'document.unique' => 'El documento es ya esta registrado',
            'photo.max' => 'El archivo de foto debe ser menor a 2048 mb',
            'photo.image' => 'El archivo debe ser de tipo jpg o png',
        ];

        $this->validate($rules, $messages);

        // dd($this->all());

        try {
            $student_tutor = StudentTutor::where('document', $this->tutor_document)->first();
            $tutor = null;
            if ($student_tutor) {
                $tutor = $student_tutor;
            } else {
                $tutor = StudentTutor::create([
                    'full_name' => $this->tutor_last_name . ' ' . $this->tutor_first_name,
                    'first_name' => $this->tutor_first_name,
                    'last_name' => $this->tutor_last_name,
                    'email' => $this->tutor_email,
                    'document_type' => $this->tutor_document_type,
                    'document' => $this->tutor_document,
                    'phone' => $this->tutor_phone,
                    'address' => $this->tutor_address,
                    'type' => $this->tutor_type,
                    'is_ative' =>$this->tutor_is_active,
                    'is_client' => true,
                    'description' => $this->tutor_description,
                ]);
            }

            $student = Student::create([
                'full_name' => $this->last_name . ' ' . $this->first_name,
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'document_type' => $this->document_type,
                'document' => $this->document,
                'phone' => $this->phone,
                'address' => $this->address,
                'is_active' => true,
                'description' => $this->description,
                'student_tutor_id' => $tutor->id,
                'teacher_id' => $this->teacher_id,
            ]);

            if($this->photo)
            {
                $customFileName = uniqid(). '_.' .$this->photo->extension();
                $this->photo->storeAs('public/students', $customFileName);

                $student->photo = $customFileName;
                $student->save();
            }

            DB::table('enrollments')->insert([
                [
                    'student_id' => $student->id,
                    'grade_id' => $this->grade_id,
                    'section_id' => $this->section_id,
                    'school_year_id' => $this->schoolYear->id,
                ]
            ]);
            DB::table('teacher_assignments')->insert([
                [
                    'teacher_id' => $this->teacher_id,
                    'grade_id' => $this->grade_id,
                    'section_id' => $this->section_id,
                    'school_year_id' => $this->schoolYear->id,
                ]
            ]);

            if($tutor->is_client){

                $cliente = Customer::where('document',$tutor->document)->first();

                if($cliente){
                    $cliente->update([
                        'student_tutor_id' => $tutor->id
                    ]);
                }else{
                    $customer = Customer::create([
                        'full_name' => $tutor->full_name,
                        'first_name'=> $tutor->first_name,
                        'last_name' => $tutor->last_name,
                        'email' => $tutor->email,
                        'document_type' => $tutor->document_type,
                        'document' => $tutor->document,
                        'phone' => $tutor->phone,
                        'address' => $tutor->address,
                        'is_active' =>true,
                        'is_tutor' => true,
                        'student_tutor_id' => $tutor->id,
                    ]);

                    $customer->save();
                }

            }
            $this->emit('socio_added', 'los datos del estudiante ha sido registrado exitosamente');
            $this->resetUI();
        } catch (\Throwable $e) {
            $this->emit('error', $e->getMessage());
        }

    }

    public function edit(Student $student)
    {

        $this->selected_id = $student->id;

        $this->first_name = $student->first_name;
        $this->last_name = $student->last_name;
        $this->email = $student->email;
        $this->document_type = $student->document_type;
        $this->document = $student->document;
        $this->phone = $student->phone;
        $this->address = $student->address;
        $this->is_active = $student->is_active;
        $this->photo = null;

        // dd($this->etapa);

        $this->emit('show-modal', 'mostrar modal');
    }

    public function update()
    {

        if ($this->document_type == 0){

            $this->full_name = $this->first_name . ' ' . $this->last_name;
        }

        $rules = [
            'full_name' => 'required',
            'document_type' => 'required',
            'document' => "required|min:8|max:11|unique:partners,document,{$this->selected_id}",
            'photo' => 'nullable|image|max:2048',
        ];

        $messages = [
            'full_name.required' => 'El nombre del socio es requerido',
            'document_type.required' => 'El tipo de documento es requerido',
            'document.required' => 'El documento es requerido',
            'document.min' => 'El documento debe tener al menos 8 digitos',
            'document.max' => 'El documento debe tener como máximo 11 digitos',
            'document.unique' => 'El documento es ya esta registrado',
            'etapa.required' => 'La etapa a la que pertenece el socio es requerido',
            'photo.max' => 'El archivo de foto debe ser menor a 2048 mb',
            'photo.image' => 'El archivo debe ser de tipo jpg o png',
        ];


        try {
            $this->validate($rules, $messages);

            $student = Student::findOrFail($this->selected_id);

            $imagenAntigua = $student->photo;

            $student->update([
                'full_name' => $this->full_name,
                'first_name'=> $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'document_type' => $this->document_type,
                'document' => $this->document,
                'phone' => $this->phone,
                'address' => $this->address,
                'is_active' =>boolval($this->is_active),
                'is_client' => true,
                'description' => $this->description,
            ]);

            // dd($student);

            if($this->photo)
            {
                $customFileName = uniqid(). '_.' .$this->photo->extension();
                $this->photo->storeAs('public/students/', $customFileName);

                $student->photo = $customFileName;

                $student->save();

                if ($imagenAntigua != null) {

                    if (file_exists('storage/students/' .$imagenAntigua)) {

                        unlink('storage/students/' .$imagenAntigua);
                    }
                }
            }

            $bitacora = Bitacora::create([
                'type' => 'update',
                'activity' => "El usuario ha actualizado la información del un student: $this->full_name, $this->document",
                'activity_id' => $student->id,
                'user_id' => Auth::user()->id,
            ]);
            /// esto actualiza la tabla de clientes y proveedores

            if($this->is_client){

                $customer = Customer::where('partner_id', $this->selected_id)->first();

                $customer->update([
                    'full_name' => $this->full_name,
                    'first_name'=> $this->first_name,
                    'last_name' => $this->last_name,
                    'email' => $this->email,
                    'document_type' => $this->document_type,
                    'document' => $this->document,
                    'phone' => $this->phone,
                    'address' => $this->address,
                    'etapa' => 1,
                    'is_ative' =>true,
                    'is_client' => true,
                    'is_suplier' => true,
                ]);

            }

            $this->resetUI();

            $this->emit('socio_updated', 'El socio ha sido actualizado exitosamente');

        } catch (\Throwable $e) {

            report($e);

            $this->emit('error', $e->getMessage());
        }

    }

    //funciones extra a crud//
    public function chageDocumentType()
    {
        $this->tutor_full_name = '';
        $this->tutor_first_name = '';
        $this->tutor_last_name = '';
        $this->tutor_document = '';
        $this->tutor_address = '';

    }
    public function clearDataApi()
    {
        $this->tutor_full_name = '';
        $this->tutor_first_name = '';
        $this->tutor_last_name = '';
        $this->tutor_address = '';
        $this->tutor_phone;
        $this->tutor_type;
        $this->tutor_description;
    }

    public function ConsutasApi()
    {
        $cust = [];

        sleep(1);

        if ($this->selected_id == 0){

            $cust = StudentTutor::where('document', $this->tutor_document)->first();
        }

        if(!empty($cust)){
                $this->mensaje = 'Ya existe registrado el socio';
                $this->tutor_first_name = $cust->first_name;
                $this->tutor_last_name = $cust->last_name;
                $this->tutor_document_type = $cust->document_type;
                $this->tutor_document = $cust->document;
                $this->tutor_address = $cust->address;
                $this->tutor_type = $cust->type;
                $this->tutor_description = $cust->description;
                $this->tutor_email = $cust->email;
                $this->tutor_phone = $cust->phone;
                $this->emit('error', $this->mensaje);
        }else{

            if($this->tutor_document_type == '1'){

                $this->dataApi = (new ApiConsultasController)->apiDni($dni = $this->tutor_document);

            }elseif($this->document_type == '6'){

                $this->dataApi = (new ApiConsultasController)->apiRuc($ruc = $this->document);

            }else {

                $this->mensaje = "no es un documento";

            }

            // dd($this->dataApi['tipoDocumento']);

            if(isset($this->dataApi['error'])){

                $this->mensaje = $this->dataApi['error'];
                $this->emit('error', $this->mensaje);
                return;

            }else{
                if($this->tutor_document_type != "0"){

                    if($this->dataApi == null){

                        $this->mensaje = 'No existe el documento o ingrese manualmente los campos con la opcion de documento OTRO';
                        $this->emit('error', $this->mensaje);
                        return;

                    }else{

                        switch ($this->dataApi['tipoDocumento']) {
                            case '1':
                                $this->tutor_full_name = $this->dataApi['nombre'];
                                $this->tutor_first_name = $this->dataApi['nombres'];
                                $this->tutor_last_name = $this->dataApi['apellidoPaterno'] . ' '. $this->dataApi['apellidoMaterno'];
                                $this->tutor_document_type = $this->dataApi['tipoDocumento'];
                                $this->tutor_document = $this->dataApi['numeroDocumento'];
                                $this->tutor_address = $this->dataApi['direccion'] ? $this->dataApi['direccion']. $this->dataApi['departamento']. ' - '. $this->dataApi['provincia']. ' - '.$this->dataApi['distrito'].'' : '';
                                break;
                            case '6':
                                $this->full_name = $this->dataApi->nombre;
                                $this->first_name = null;
                                $this->last_name = null;
                                $this->document_type = $this->dataApi->tipoDocumento;
                                $this->document = $this->dataApi->numeroDocumento;
                                $this->address = ''.$this->dataApi->viaTipo. ' ' . $this->dataApi->viaNombre . ' ' . $this->dataApi->numero. ' - '. $this->dataApi->zonaCodigo. ' ' . $this->dataApi->zonaTipo. ' - ' . $this->dataApi->departamento. ' - '. $this->dataApi->provincia. ' - '.$this->dataApi->distrito.'';
                                break;

                            default:
                                # code...
                                break;
                        }
                    }
                }else{
                    $this->mensaje = "Ingrese los datos manualmente.";
                    $this->emit('error', $this->mensaje);
                }
            }
        }
    }

    public function resetUI()
    {
        $this->selected_id = 0;
        $this->full_name = '';
        $this->first_name = '';
        $this->last_name = '';
        $this->document_type = 1;
        $this->document = '';
        $this->email = '';
        $this->phone = '';
        $this->photo = '';
        $this->address = '';
        $this->photoId = rand();

        $this->resetValidation();
    }


    public function anular($id)
    {
        $student = Student::findOrFail($id);
        // dd($student->stands->count() > 0);
        if($student->stands->count() || $student->details->count())
        {
            $this->emit('error', 'Aun no se puede anular estudiantes con con detalles o stands relacionado. Pronto estará disponible!!');
        }
        else{
            try {
                $student->update([
                    'is_ative' => false,
                ]);
                $student->save();
            } catch (\Throwable $th) {
                //throw $th;
                dd($th);
            }
        }
    }
}
