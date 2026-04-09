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
use App\Models\Enrollment;
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

    // Campos del estudiante
    public $full_name,
        $first_name,
        $last_name,
        $email,
        $document_type,
        $document,
        $phone,
        $address,
        $photo,
        $is_active,
        $description;

    // Campos del tutor
    public $tutor_full_name,
        $tutor_first_name,
        $tutor_last_name,
        $tutor_email,
        $tutor_document_type,
        $tutor_document,
        $tutor_phone,
        $tutor_address,
        $tutor_is_active,
        $tutor_is_client,
        $tutor_type,
        $tutor_description;

    // Estado del componente
    public $componentName,
        $selected_id,
        $mensaje,
        $photoId,
        $search = "";

    private $dataApi = [];

    // Campos de matrícula
    public $teachers,
        $teacher_id,
        $grades,
        $grade_id,
        $sections,
        $section_id,
        $schoolYear;

    protected $listeners = ["resetUI", "anularSocio" => "anular"];

    protected $paginationTheme = "bootstrap";

    // ─── Reglas de validación compartidas ───────────────────────────────────────

    private function commonRules(bool $isUpdate = false): array
    {
        $uniqueDocument = $isUpdate
            ? "required|min:8|max:11|unique:students,document,{$this->selected_id}"
            : "required|min:8|max:11";

        return [
            "first_name" => "required|min:3",
            "last_name" => "required|min:3",
            "document_type" => "required",
            "document" => $uniqueDocument,
            "photo" => "nullable|image|mimes:jpeg,png|max:2048",
            "description" => "nullable",
            "tutor_first_name" => "required|min:3",
            "tutor_last_name" => "required|min:3",
            "tutor_email" => "nullable|email|max:100",
            "tutor_document" => "required|min:8|max:11",
            "tutor_type" => "required",
            "tutor_description" => "nullable",
            "grade_id" => "required",
            "teacher_id" => "nullable",
        ];
    }

    private function commonMessages(): array
    {
        return [
            "first_name.required" => "El nombre del estudiante es requerido",
            "last_name.required" => "El apellido del estudiante es requerido",
            "document_type.required" => "El tipo de documento es requerido",
            "document.required" => "El documento es requerido",
            "document.min" => "El documento debe tener al menos 8 dígitos",
            "document.max" => "El documento debe tener como máximo 11 dígitos",
            "document.unique" => "El documento ya está registrado",
            "photo.max" => "El archivo de foto debe ser menor a 2 MB",
            "photo.image" => "El archivo debe ser de tipo jpg o png",
            "photo.mimes" => "El archivo debe ser de tipo jpg o png",
            "tutor_first_name.required" => "El nombre del tutor es requerido",
            "tutor_last_name.required" => "El apellido del tutor es requerido",
            "tutor_document.required" => "El documento del tutor es requerido",
            "tutor_type.required" => "El tipo de tutor es requerido",
            "grade_id.required" => "El grado es requerido",
        ];
    }

    // ─── Hooks del ciclo de vida ─────────────────────────────────────────────────

    public function updated($propertyName)
    {
        if ($propertyName === "photo" && $this->photo) {
            $allowed = ["image/jpeg", "image/png"];
            if (!in_array($this->photo->getMimeType(), $allowed)) {
                $this->reset("photo");
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

        $this->teachers = Teacher::where("is_active", true)->pluck(
            "full_name",
            "id",
        );
        $this->grades = Grade::pluck("name", "id");
        $this->schoolYear = SchoolYear::find(session("current_school_year_id"));
    }

    // ─── Render ──────────────────────────────────────────────────────────────────

    public function render()
    {
        $columns = ["id", "document", "full_name", "document_type"];

        $students = Student::select($columns)
            ->whereHas(
                "enrollments",
                fn($q) => $q->where("school_year_id", $this->schoolYear->id),
            )
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where("document", "like", "%{$this->search}%")->orWhere(
                        "full_name",
                        "like",
                        "%{$this->search}%",
                    );
                });
            })
            ->paginate(10);

        return view(
            "livewire.students.students-component",
            compact("students"),
        )->extends("adminlte::page");
    }

    // ─── CRUD ────────────────────────────────────────────────────────────────────

    public function save()
    {
        $this->validate($this->commonRules(), $this->commonMessages());

        try {
            $tutor = $this->findOrCreateTutor();
            $student = [];

            $student = Student::where("document", $this->document)->first();

            if (!$student) {
                $student = Student::create([
                    "full_name" => "{$this->last_name} {$this->first_name}",
                    "first_name" => $this->first_name,
                    "last_name" => $this->last_name,
                    "email" => $this->email,
                    "document_type" => $this->document_type,
                    "document" => $this->document,
                    "phone" => $this->phone,
                    "address" => $this->address,
                    "is_active" => $this->is_active ?? true,
                    "description" => $this->description,
                    "student_tutor_id" => $tutor->id,
                    "teacher_id" => $this->teacher_id,
                ]);
            }

            $this->storePhoto($student);

            Enrollment::create([
                "student_id" => $student->id,
                "grade_id" => $this->grade_id,
                "section_id" => $this->section_id,
                "school_year_id" => $this->schoolYear->id,
            ]);

            $this->syncCustomer($tutor);

            $this->emit(
                "socio_added",
                "Los datos del estudiante han sido registrados exitosamente.",
            );
            $this->resetUI();
        } catch (\Throwable $e) {
            $this->emit("error", $e->getMessage());
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
        $this->description = $student->description;
        $this->teacher_id = $student->teacher_id;

        // Cargar grado desde la matrícula activa
        $enrollment = Enrollment::where("student_id", $student->id)
            ->latest()
            ->first();
        $this->grade_id = $enrollment?->grade_id;
        $this->section_id = $enrollment?->section_id;

        $tutor = $student->tutor;
        if ($tutor) {
            $this->tutor_first_name = $tutor->first_name;
            $this->tutor_last_name = $tutor->last_name;
            $this->tutor_email = $tutor->email;
            $this->tutor_document_type = $tutor->document_type;
            $this->tutor_document = $tutor->document;
            $this->tutor_phone = $tutor->phone;
            $this->tutor_address = $tutor->address;
            $this->tutor_is_active = $tutor->is_active;
            $this->tutor_description = $tutor->description;
            $this->tutor_type = $tutor->type;
        }

        $this->emit("show-modal", "mostrar modal");
    }

    public function update()
    {
        $teacher_id = $this->teacher_id == "" ? null : $this->teacher_id;

        $this->validate($this->commonRules(true), $this->commonMessages());

        try {
            $student = Student::findOrFail($this->selected_id);
            $imagenAntigua = $student->photo;

            $tutorEdit = $this->resolveUpdateTutor($student);

            $student->update([
                "full_name" => "{$this->last_name} {$this->first_name}",
                "first_name" => $this->first_name,
                "last_name" => $this->last_name,
                "email" => $this->email,
                "document_type" => $this->document_type,
                "document" => $this->document,
                "phone" => $this->phone,
                "address" => $this->address,
                "is_active" => $this->is_active ?? true,
                "description" => $this->description,
                "student_tutor_id" => $tutorEdit->id,
                "teacher_id" => $teacher_id,
            ]);

            $this->storePhoto($student, $imagenAntigua);

            // BUG CORREGIDO: antes usaba "id" en lugar de "student_id"
            $enrollment = Enrollment::where("student_id", $student->id)
                ->latest()
                ->first();
            if ($enrollment) {
                $enrollment->update([
                    "grade_id" => $this->grade_id,
                    "section_id" => $this->section_id,
                ]);
            }

            $this->syncCustomer($tutorEdit);

            $this->emit(
                "socio_updated",
                "Los datos del estudiante han sido actualizados exitosamente.",
            );
            $this->resetUI();
        } catch (\Throwable $e) {
            $this->emit("error", $e->getMessage());
        }
    }

    // ─── Helpers privados ────────────────────────────────────────────────────────

    /**
     * Busca un tutor existente por documento o crea uno nuevo.
     */
    private function findOrCreateTutor(): StudentTutor
    {
        return StudentTutor::firstOrCreate(
            ["document" => $this->tutor_document],
            [
                "full_name" => "{$this->tutor_last_name} {$this->tutor_first_name}",
                "first_name" => $this->tutor_first_name,
                "last_name" => $this->tutor_last_name,
                "email" => $this->tutor_email,
                "document_type" => $this->tutor_document_type,
                "phone" => $this->tutor_phone,
                "address" => $this->tutor_address,
                "type" => $this->tutor_type,
                "is_client" => true,
                "description" => $this->tutor_description,
            ],
        );
    }

    /**
     * Resuelve el tutor durante una actualización:
     * - Si el documento no cambió, actualiza el tutor actual.
     * - Si cambió, usa el existente o crea uno nuevo.
     */
    private function resolveUpdateTutor(Student $student): StudentTutor
    {
        $tutorData = [
            "full_name" => "{$this->tutor_last_name} {$this->tutor_first_name}",
            "first_name" => $this->tutor_first_name,
            "last_name" => $this->tutor_last_name,
            "email" => $this->tutor_email,
            "document_type" => $this->tutor_document_type,
            "document" => $this->tutor_document,
            "phone" => $this->tutor_phone,
            "address" => $this->tutor_address,
            "type" => $this->tutor_type,
            "is_client" => true,
            "description" => $this->tutor_description,
        ];

        $currentTutor = StudentTutor::find($student->student_tutor_id);

        if (
            $currentTutor &&
            $currentTutor->document === $this->tutor_document
        ) {
            $currentTutor->update($tutorData);
            return $currentTutor->fresh();
        }

        // Documento diferente: buscar existente o crear
        $existing = StudentTutor::where(
            "document",
            $this->tutor_document,
        )->first();
        if ($existing) {
            $existing->update($tutorData);
            return $existing->fresh();
        }

        return StudentTutor::create($tutorData);
    }

    /**
     * Guarda o reemplaza la foto del estudiante.
     */
    private function storePhoto(
        Student $student,
        ?string $oldPhoto = null,
    ): void {
        if (!$this->photo) {
            return;
        }

        $fileName = uniqid() . "_." . $this->photo->extension();
        $this->photo->storeAs("public/students", $fileName);

        $student->photo = $fileName;
        $student->save();

        if ($oldPhoto && Storage::exists("public/students/{$oldPhoto}")) {
            Storage::delete("public/students/{$oldPhoto}");
        }
    }

    /**
     * Sincroniza el tutor con la tabla de clientes (customers).
     */
    private function syncCustomer(StudentTutor $tutor): void
    {
        if (!$tutor->is_client) {
            return;
        }

        $customerData = [
            "full_name" => $tutor->full_name,
            "first_name" => $tutor->first_name,
            "last_name" => $tutor->last_name,
            "email" => $tutor->email,
            "document_type" => $tutor->document_type,
            "document" => $tutor->document,
            "phone" => $tutor->phone,
            "address" => $tutor->address,
            "is_active" => true,
            "is_tutor" => true,
            "student_tutor_id" => $tutor->id,
        ];

        Customer::updateOrCreate(
            ["document" => $tutor->document],
            $customerData,
        );
    }

    // ─── Métodos auxiliares del formulario ───────────────────────────────────────

    public function chageDocumentType()
    {
        $this->tutor_full_name = "";
        $this->tutor_first_name = "";
        $this->tutor_last_name = "";
        $this->tutor_document = "";
        $this->tutor_address = "";
    }

    public function clearDataApi()
    {
        $this->tutor_full_name = "";
        $this->tutor_first_name = "";
        $this->tutor_last_name = "";
        $this->tutor_address = "";
        $this->tutor_phone = ""; // BUG CORREGIDO: antes no tenía asignación
        $this->tutor_type = "";
        $this->tutor_description = "";
    }

    public function ConsutasApi()
    {
        sleep(1);

        // Si estamos creando, verificar si ya existe el tutor en BD
        if ($this->selected_id == 0) {
            $existing = StudentTutor::where(
                "document",
                $this->tutor_document,
            )->first();

            if ($existing) {
                $this->tutor_first_name = $existing->first_name;
                $this->tutor_last_name = $existing->last_name;
                $this->tutor_document_type = $existing->document_type;
                $this->tutor_document = $existing->document;
                $this->tutor_address = $existing->address;
                $this->tutor_type = $existing->type;
                $this->tutor_description = $existing->description;
                $this->tutor_email = $existing->email;
                $this->tutor_phone = $existing->phone;

                $this->emit(
                    "error",
                    "Ya existe el tutor registrado en el sistema.",
                );
                return;
            }
        }

        // BUG CORREGIDO: se usaba $this->document_type en lugar de $this->tutor_document_type
        $api = new ApiConsultasController();
        if ($this->tutor_document_type == "1") {
            $this->dataApi = $api->apiDni($this->tutor_document);
        } elseif ($this->tutor_document_type == "6") {
            $this->dataApi = $api->apiRuc($this->tutor_document);
        } else {
            $this->emit("error", "Ingrese los datos manualmente.");
            return;
        }

        if (isset($this->dataApi["error"])) {
            $this->emit("error", $this->dataApi["error"]);
            return;
        }

        if (empty($this->dataApi)) {
            $this->emit(
                "error",
                'No existe el documento o ingrese manualmente los campos con la opción "OTRO".',
            );
            return;
        }

        $tipo = $this->dataApi["tipoDocumento"] ?? null;

        match ($tipo) {
            "1" => $this->fillTutorFromDni(),
            "6" => $this->fillStudentFromRuc(),
            default => $this->emit(
                "error",
                "Tipo de documento no reconocido.",
            ),
        };
    }

    private function fillTutorFromDni(): void
    {
        $this->tutor_full_name = $this->dataApi["nombre"] ?? "";
        $this->tutor_first_name = $this->dataApi["nombres"] ?? "";
        $this->tutor_last_name = trim(
            ($this->dataApi["apellidoPaterno"] ?? "") .
                " " .
                ($this->dataApi["apellidoMaterno"] ?? ""),
        );
        $this->tutor_document_type = $this->dataApi["tipoDocumento"];
        $this->tutor_document = $this->dataApi["numeroDocumento"];
        $this->tutor_address = isset($this->dataApi["direccion"])
            ? "{$this->dataApi["direccion"]} {$this->dataApi["departamento"]} - {$this->dataApi["provincia"]} - {$this->dataApi["distrito"]}"
            : "";
    }

    private function fillStudentFromRuc(): void
    {
        $this->full_name = $this->dataApi["nombre"] ?? "";
        $this->first_name = null;
        $this->last_name = null;
        $this->document_type = $this->dataApi["tipoDocumento"];
        $this->document = $this->dataApi["numeroDocumento"];
        $this->address = "{$this->dataApi["viaTipo"]} {$this->dataApi["viaNombre"]} {$this->dataApi["numero"]} - {$this->dataApi["zonaCodigo"]} {$this->dataApi["zonaTipo"]} - {$this->dataApi["departamento"]} - {$this->dataApi["provincia"]} - {$this->dataApi["distrito"]}";
    }

    // ─── Reset UI ────────────────────────────────────────────────────────────────

    public function resetUI()
    {
        $this->selected_id = 0;

        // Estudiante
        $this->full_name = "";
        $this->first_name = "";
        $this->last_name = "";
        $this->document_type = 1;
        $this->document = "";
        $this->email = "";
        $this->phone = "";
        $this->photo = null;
        $this->address = "";
        $this->description = "";
        $this->is_active = true;
        $this->photoId = rand();

        // Tutor - BUG CORREGIDO: antes no se reseteaban estos campos
        $this->tutor_full_name = "";
        $this->tutor_first_name = "";
        $this->tutor_last_name = "";
        $this->tutor_email = "";
        $this->tutor_document_type = 1;
        $this->tutor_document = "";
        $this->tutor_phone = "";
        $this->tutor_address = "";
        $this->tutor_is_active = true;
        $this->tutor_type = "";
        $this->tutor_description = "";

        // Matrícula - BUG CORREGIDO: antes no se reseteaban
        $this->grade_id = null;
        $this->section_id = null;
        $this->teacher_id = null;

        $this->resetValidation();
    }

    // ─── Anular ──────────────────────────────────────────────────────────────────

    public function anular($id)
    {
        $student = Student::findOrFail($id);

        // Verificar relaciones antes de anular (adaptar según modelos reales del proyecto)
        $hasRelations = $student->enrollments()->exists();

        if ($hasRelations) {
            $this->emit(
                "error",
                "No se puede anular al estudiante porque tiene matrículas relacionadas.",
            );
            return;
        }

        try {
            $student->update(["is_active" => false]); // BUG CORREGIDO: era "is_ative"
        } catch (\Throwable $th) {
            $this->emit("error", $th->getMessage());
        }
    }
}
