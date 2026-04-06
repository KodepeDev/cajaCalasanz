<?php

namespace App\Http\Livewire\Socios\Detalles;

use Carbon\Carbon;
use App\Models\Account;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Detail;
use App\Models\PaymentMethod;
use App\Models\SchoolYear;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NuevoRegistro extends Component
{
    // ─── Estado del componente ───────────────────────────────────────────────────

    public $cuentas,
        $paymentMethods,
        $categorias,
        $selected_id,
        $validezFecha,
        $status,
        $receipt,
        $summary_id,
        $unique_code;

    public $date,
        $concept,
        $type,
        $amount,
        $tax,
        $recipt_series,
        $puesto,
        $recipt_number,
        $future,
        $account_id,
        $category_id,
        $subcategoria_id,
        $payment_method,
        $numero_operacion,
        $user_id,
        $customer_id,
        $student_id,
        $student_tutor_id;

    public $documento, $customer_name;
    public $currency, $currency_id;

    public $document_type,
        $document,
        $full_name,
        $first_name,
        $last_name,
        $address,
        $email,
        $phone,
        $mensaje,
        $schoolYear;

    protected $listeners = ["editMovimiento"];

    // ─── Reglas de validación compartidas ───────────────────────────────────────

    private function movimientoRules(bool $requireUser = true): array
    {
        $rules = [
            "documento" => "required",
            "customer_name" => "required",
            "date" => "required|date",
            "concept" => "required|min:5|max:255",
            "type" => "required",
            "amount" => "required|numeric|min:0.01",
            "category_id" => "required",
        ];

        if ($requireUser) {
            $rules["user_id"] = "required";
        }

        return $rules;
    }

    private function movimientoMessages(): array
    {
        return [
            "documento.required" => "El documento es requerido",
            "customer_name.required" =>
                "El nombre del cliente o proveedor es requerido",
            "date.required" => "La fecha es requerida",
            "date.date" => "La fecha debe ser una fecha válida",
            "concept.required" => "El concepto del movimiento es requerido",
            "concept.min" => "El concepto debe tener como mínimo 5 caracteres",
            "concept.max" =>
                "El concepto debe tener como máximo 255 caracteres",
            "type.required" => "El tipo de movimiento es requerido",
            "amount.required" => "El monto es requerido",
            "amount.numeric" => "El monto debe ser un valor numérico",
            "amount.min" => "El monto debe ser como mínimo 0.01",
            "category_id.required" => "La categoría es requerida",
            "user_id.required" => "El usuario es requerido",
        ];
    }

    // ─── Mount ───────────────────────────────────────────────────────────────────

    public function mount(Student $student)
    {
        // BUG CORREGIDO: se eliminó dd($student) que bloqueaba la ejecución
        // BUG CORREGIDO: faltaba punto y coma en la línea original
        $this->student_id = $student->id;
        $this->schoolYear = SchoolYear::current();

        $customer = Customer::where(
            "document",
            $student->tutor->document,
        )->firstOrFail();

        $this->currency_id = 1;
        $this->document_type = $customer->document_type;
        $this->documento = $customer->document;
        $this->customer_id = $customer->id;
        $this->student_tutor_id = $student->tutor->id;
        $this->customer_name = $customer->full_name;

        $this->date = Carbon::now()->format("Y-m-d");
        $this->validezFecha = true;
        $this->selected_id = 0;
        $this->type = "add";
        $this->status = false;
        $this->payment_method = 1;
        $this->tax = 0;

        $this->cuentas = Account::all();
        $this->paymentMethods = PaymentMethod::all();
        $this->categorias = Category::where("type", $this->type)->get();
    }

    // ─── Render ──────────────────────────────────────────────────────────────────

    public function render()
    {
        // BUG CORREGIDO: pluck(value, key) — "name" es la etiqueta, "id" es el valor
        $this->currency = Currency::pluck("name", "id");

        return view("livewire.socios.detalles.nuevo-registro");
    }

    // ─── Crear movimiento ────────────────────────────────────────────────────────

    public function crearMovimiento()
    {
        $this->validarFechas();

        // BUG CORREGIDO: se retornaba implícitamente si validezFecha era false,
        // ahora es explícito y evita seguir ejecutando lógica.
        if (!$this->validezFecha) {
            return;
        }

        $this->user_id = Auth::id();

        $this->validate(
            $this->movimientoRules(true),
            $this->movimientoMessages(),
        );

        $this->unique_code = $this->buildUniqueCode();

        $existe = Detail::where("unique_code", $this->unique_code)->exists();

        if ($existe) {
            $this->emit(
                "error",
                "Ya existe un registro similar al que desea agregar o provisionar.",
            );
            return; // BUG CORREGIDO: antes continuaba y llamaba resetUI() cerrando el modal en error
        }

        Detail::create([
            "unique_code" => $this->unique_code,
            "date" => $this->date,
            "description" => $this->concept,
            "summary_type" => $this->type,
            "type" => 2,
            "status" => $this->status,
            "amount" => $this->amount,
            "category_id" => $this->category_id,
            "student_id" => $this->student_id,
            "student_tutor_id" => $this->student_tutor_id,
            "currency_id" => $this->currency_id,
        ]);

        $this->emit(
            "movimiento_added",
            "El movimiento ha sido registrado exitosamente.",
        );
        $this->resetUI();
        $this->emit("render", "render");
    }

    // ─── Editar movimiento ───────────────────────────────────────────────────────

    public function editMovimiento($id)
    {
        $this->summary_id = $id;
        $this->selected_id = $id;

        $summary = Detail::findOrFail($id);

        $this->date = $summary->date->format("Y-m-d");
        $this->concept = $summary->description;
        $this->type = $summary->summary_type;
        $this->status = $summary->status;
        $this->amount = $summary->amount;
        $this->category_id = $summary->category_id;
        $this->currency_id = $summary->currency_id;
        $this->student_id = $summary->student_id;

        $student = Student::findOrFail($summary->student_id);
        $customer = Customer::where(
            "document",
            $student->tutor->document,
        )->firstOrFail();

        $this->document_type = $customer->document_type;
        $this->documento = $customer->document; // campo unificado
        $this->customer_id = $customer->id;
        $this->customer_name = $customer->full_name;

        $this->categorias = Category::where("type", $this->type)->get();

        $this->emit("show_modal");
    }

    // ─── Actualizar movimiento ───────────────────────────────────────────────────

    public function updateMovimiento()
    {
        $this->validarFechas();

        if (!$this->validezFecha) {
            return;
        }

        $this->user_id = Auth::id();

        // BUG CORREGIDO: reglas extraídas, sin duplicación; $iva eliminado (nunca se usó)
        $this->validate(
            $this->movimientoRules(false),
            $this->movimientoMessages(),
        );

        $this->unique_code = $this->buildUniqueCode();

        $existe = Detail::where("id", "!=", $this->summary_id)
            ->where("unique_code", $this->unique_code)
            ->exists();

        if ($existe) {
            $this->emit(
                "error",
                "Ya existe un registro similar al que desea actualizar.",
            );
            return;
        }

        $summary = Detail::findOrFail($this->summary_id);

        $summary->update([
            "unique_code" => $this->unique_code,
            "date" => $this->date,
            "description" => $this->concept,
            "summary_type" => $this->type,
            "type" => 2,
            "status" => $this->status,
            "amount" => $this->amount,
            "category_id" => $this->category_id,
            "student_id" => $this->student_id,
            "currency_id" => $this->currency_id,
        ]);

        $this->emit(
            "movimiento_actualizado",
            "El movimiento se ha actualizado con éxito.",
        );
        $this->resetUI();
        $this->emit("render", "render");
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────────

    /**
     * Genera el código único: YYYY-MM + category_id (4 dígitos) + student_id (6 dígitos)
     */
    private function buildUniqueCode(): string
    {
        return Carbon::parse($this->date)->format("Y-m") .
            str_pad($this->category_id, 4, "0", STR_PAD_LEFT) .
            str_pad($this->student_id, 6, "0", STR_PAD_LEFT);
    }

    public function categoryType()
    {
        $this->category_id = "";
        $this->subcategoria_id = "";

        if ($this->type !== null) {
            $this->categorias = Category::where("type", $this->type)->get();
        }
    }

    public function validarFechas()
    {
        $hoy = Carbon::now();
        $hoyStr = $hoy->format("Y-m-d");

        // BUG CORREGIDO: subDays() muta el objeto Carbon original.
        // Se usa copy() para no afectar $hoy en comparaciones posteriores.
        $limiteStr = $hoy->copy()->subDays(3)->format("Y-m-d");

        if ($this->date > $hoyStr) {
            $this->date = $hoyStr;
            $this->emit(
                "error_fecha",
                "La fecha no debe ser mayor al día de hoy.",
            );
            $this->validezFecha = false;
        } elseif ($this->date < $limiteStr) {
            $this->date = $hoyStr;
            $this->emit(
                "error_fecha",
                "La fecha solo puede ser hasta 3 días antes de hoy.",
            );
            $this->validezFecha = false;
        } else {
            $this->validezFecha = true;
        }
    }

    public function resetUI()
    {
        $this->selected_id = 0;
        $this->summary_id = null;
        $this->validezFecha = true;
        $this->date = Carbon::now()->format("Y-m-d");
        $this->concept = "";
        $this->category_id = null;
        $this->amount = 0;
        $this->currency_id = 1;
        $this->status = false;
        $this->type = "add";
        $this->categorias = Category::where("type", $this->type)->get();

        $this->resetValidation();
        $this->emit("close_modal", "close modal");
    }
}
