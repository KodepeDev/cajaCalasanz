<?php

namespace App\Http\Livewire\Movimientos;

use Carbon\Carbon;
use App\Models\Detail;
use App\Models\Account;
use App\Models\Summary;
use Livewire\Component;
use App\Models\Category;
use App\Models\Customer;
use App\Models\AttrValue;
use App\Models\PaymentMethod;
use App\Models\Student;
use App\Services\LimitDateService;
use App\Services\TipoCambioService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ApiConsultasController;
use App\Models\SchoolYear;

class CrearMovimiento extends Component
{
    // UI state
    public $cuentas;
    public $paymentMethods;
    public $categorias;
    public $subcategorias;
    public $selected_id;
    public $showReceiptModal = false;
    public $receipt;
    public $summary_id;

    // Movement fields
    public $date;
    public $type;
    public $status;
    public $amount;
    public $tax;
    public $recipt_series;
    public $recipt_number;
    public $account_id;
    public $category_id;
    public $subcategoria_id;
    public $payment_method;
    public $numero_operacion;
    public $user_id;
    public $customer_id;
    public $paid_by;
    public $observation;

    // Exchange rate (public so the view can display it)
    public $tc;

    protected $listeners = [
        "resetUI",
        "resetUiApi",
        "redireccionar",
        "summaryCreated" => "showReceiptModal",
        "buscar_provision" => "searchStandProvision",
    ];

    // Customer / provision search
    public $documento;
    public $customer_name;
    public $provision_code;

    // Provisions
    public $provision_detalles;
    public float $total_prov = 0;
    public float $total_prov_dolar = 0;
    public float $total_prov_cobrar = 0;
    public float $total_new = 0;
    public $student_id;
    public $student_name;
    public array $provisions = [];
    public $provisionsCobrar = [];
    public array $checkedProvision = [];
    public $det_selected;
    public $student_tutor_id;

    // Customer form fields
    public $document_type;
    public $document;
    public $full_name;
    public $first_name;
    public $last_name;
    public $address;
    public $email;
    public $phone;

    // ─────────────────────────────────────────────────────────────
    // Lifecycle
    // ─────────────────────────────────────────────────────────────

    public function mount(): void
    {
        $this->document_type = 1;
        $this->documento = 99999999;
        $this->customer_name = "Clientes/Proveedores Varios";
        $this->date = Carbon::now()->toDateString();
        $this->selected_id = 0;
        $this->type = "add";
        $this->status = "PAID";
        $this->payment_method = 1;
        $this->tax = 0;
        $this->cuentas = Account::all();
        $this->account_id = $this->cuentas->first()->id;
        $this->paymentMethods = PaymentMethod::all();
        $this->categorias = $this->getCategoriasForType($this->type);
        $this->tc = $this->fetchTipoCambio($this->date);
    }

    public function render()
    {
        return view("livewire.movimientos.crear-movimiento")->extends(
            "adminlte::page",
        );
    }

    // ─────────────────────────────────────────────────────────────
    // Date & totals
    // ─────────────────────────────────────────────────────────────

    public function updatedDate(): void
    {
        $this->tc = $this->fetchTipoCambio($this->date);
        $this->selectedProvisions();
    }

    public function updateTotal(): void
    {
        $this->total_new = collect($this->provisions)->sum("amount");
    }

    public function updatedProvisions(): void
    {
        $this->updateTotal();
    }

    // ─────────────────────────────────────────────────────────────
    // Receipt modal
    // ─────────────────────────────────────────────────────────────

    public function showReceiptModal($event = null): void
    {
        $this->showReceiptModal = true;
    }

    public function redireccionar()
    {
        return redirect()->route("movimientos.listado");
    }

    // ─────────────────────────────────────────────────────────────
    // Provision search & selection
    // ─────────────────────────────────────────────────────────────

    public function searchStandProvision(): void
    {
        $this->resetValidation();
        $this->validate(["provision_code" => "required"]);

        $student = Student::with("tutor")
            ->where("document", $this->provision_code)
            ->first();

        if (!$student) {
            $this->emit("error", "No se encontraron datos para ese código.");
            return;
        }

        $this->student_id = $student->id;
        $filterYear = SchoolYear::current()->year ?? now()->year;

        $baseQuery = Detail::whereYear("date", $filterYear)
            ->where("student_id", $student->id)
            ->whereStatus(false)
            ->orderBy("date");

        $details = (clone $baseQuery)->with(["currency", "category"])->get();
        $sumaSoles = (clone $baseQuery)
            ->where(
                fn($q) => $q
                    ->where("currency_id", "!=", 2)
                    ->orWhereNull("currency_id"),
            )
            ->sum("amount");
        $sumaDolar = (clone $baseQuery)->where("currency_id", 2)->sum("amount");

        $this->documento = $student->tutor->document;
        $this->customer_name = $student->tutor->full_name;
        $this->student_name = $student->full_name;
        $this->paid_by = $this->customer_name;

        if ($details->isNotEmpty()) {
            $this->provision_detalles = $details;
            $this->provisionsCobrar = [];
            $this->checkedProvision = [];
            $this->total_prov_cobrar = 0;
            $this->total_prov = $sumaSoles;
            $this->total_prov_dolar = $sumaDolar;
            $this->emit("mostrarModalProvision");
        } else {
            // Keep tutor data already set above (documento, customer_name, student_name, paid_by)
            $this->provision_detalles = [];
            $this->provisionsCobrar = [];
            $this->checkedProvision = [];
            $this->total_prov_cobrar = 0;
            $this->total_prov = 0;
            $this->total_prov_dolar = 0;
            $this->emit(
                "error",
                "No existen provisiones pendientes para este alumno.",
            );
        }
    }

    public function selectAll(): void
    {
        $this->checkedProvision = $this->provision_detalles
            ->pluck("id")
            ->toArray();
    }

    public function selectNow(): void
    {
        $this->checkedProvision = $this->provision_detalles
            ->whereBetween("date", [
                now()->startOfMonth()->format("Y-m-d H:i:s"),
                now()->endOfMonth()->format("Y-m-d H:i:s"),
            ])
            ->pluck("id")
            ->toArray();
    }

    public function selectedProvisions(): void
    {
        $this->total_prov_cobrar = 0;
        $this->provisionsCobrar = Detail::whereKey(
            $this->checkedProvision,
        )->get();

        foreach ($this->provisionsCobrar as $item) {
            $this->total_prov_cobrar +=
                $item->currency->id === 2
                    ? $item->amount * $this->tc
                    : $item->amount;
        }
    }

    public function validarSeleccionados(): void
    {
        $this->det_selected = count($this->checkedProvision);
    }

    // ─────────────────────────────────────────────────────────────
    // Provision rows (nuevos detalles)
    // ─────────────────────────────────────────────────────────────

    public function add(): void
    {
        Student::findOrFail($this->student_id);

        $this->provisions[] = [
            "status" => false,
            "description" => null,
            "type" => 2,
            "date" => Carbon::now()->format("Y-m"),
            "date_paid" => Carbon::now()->toDateString(),
            "amount" => null,
            "category_id" => "Elegir",
            "student_id" => $this->student_id,
            "summary_id" => null,
        ];
    }

    public function removeProvision(int $key): void
    {
        array_splice($this->provisions, $key, 1);
        $this->updateTotal();
    }

    public function validarProvisions(): void
    {
        $this->validate([
            "provisions.*.date" => "required|date",
            "provisions.*.description" => "required|min:5|max:200",
            "provisions.*.category_id" => "required|not_in:Elegir",
            "provisions.*.amount" => "required|numeric|min:0.01",
            "provisions.*.student_id" => "required",
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    // Save movement
    // ─────────────────────────────────────────────────────────────

    public function crearMovimiento(): void
    {
        if (!$this->validarFechas()) {
            return;
        }

        $this->user_id = Auth::id();

        $cliente = Customer::where("document", $this->documento)->first();
        $student = Student::with("tutor")->findOrFail($this->student_id);

        if ($cliente === null) {
            $this->emit(
                "error",
                "No existe el cliente/proveedor. Si es un estudiante, créelo mediante el módulo de Estudiantes.",
            );
            return;
        }

        $this->customer_id = $cliente->id;
        $this->student_id = $student->id;
        $this->student_tutor_id = $student->tutor->id;
        $this->amount = $this->total_prov_cobrar + $this->total_new;

        $this->validate(
            $this->crearMovimientoRules(),
            $this->crearMovimientoMessages(),
        );

        try {
            $this->validarProvisions();

            DB::transaction(function () {
                $summary = Summary::create([
                    "date" => $this->date,
                    "type" => $this->type,
                    "status" => $this->status,
                    "amount" => $this->amount,
                    "tipo_cambio" => $this->tc,
                    "tax" => $this->tax,
                    "recipt_series" => $this->recipt_series,
                    "recipt_number" => $this->recipt_number,
                    "account_id" => $this->account_id,
                    "user_id" => $this->user_id,
                    "future" => 1,
                    "operation_number" => $this->numero_operacion,
                    "paid_by" => $this->paid_by,
                    "observation" => $this->observation,
                    "customer_id" => $this->customer_id,
                    "student_id" => $this->student_id,
                    "student_tutor_id" => $this->student_tutor_id,
                    "payment_method_id" => $this->payment_method,
                ]);

                foreach ($this->provisions as $input) {
                    $unique_code =
                        Carbon::parse($input["date"])->format("Y-m") .
                        $input["category_id"] .
                        $this->student_id .
                        $summary->id;

                    Detail::create([
                        "unique_code" => $unique_code,
                        "status" => true,
                        "summary_type" => $summary->type,
                        "date" => $input["date"],
                        "description" => $input["description"],
                        "date_paid" => $summary->date,
                        "category_id" => $input["category_id"],
                        "student_id" => $this->student_id,
                        "student_tutor_id" => $this->student_tutor_id,
                        "amount" => $input["amount"],
                        "summary_id" => $summary->id,
                    ]);
                }

                foreach ($this->provisionsCobrar as $provision) {
                    $provision->update([
                        "status" => true,
                        "summary_type" => $summary->type,
                        "date_paid" => $summary->date,
                        "changed_amount" =>
                            $provision->currency->id === 2
                                ? round($provision->amount * $this->tc, 2)
                                : 0,
                        "summary_id" => $summary->id,
                    ]);
                }

                $this->summary_id = $summary->id;
                $this->recipt_series = $summary->recipt_series;
                $this->recipt_number = $summary->recipt_number;
                $this->receipt =
                    $summary->recipt_series .
                    "-" .
                    str_pad($summary->recipt_number, 8, "0", STR_PAD_LEFT);
                $this->showReceiptModal = true;
            });

            $this->dispatchBrowserEvent("show-receipt-modal");
            $this->emit(
                "movimiento_added",
                "El movimiento ha sido registrado exitosamente.",
            );
        } catch (\Throwable $th) {
            $this->emit("error", $th->getMessage());
        }
    }

    // ─────────────────────────────────────────────────────────────
    // Date validation
    // ─────────────────────────────────────────────────────────────

    private function validarFechas(): bool
    {
        $hoy = Carbon::now();
        $limitDate = new LimitDateService();
        $numberDays = $limitDate->getIncomeNumberDays();

        if ($this->date > $hoy->toDateString()) {
            $this->date = $hoy->toDateString();
            $this->updatedDate();
            $this->emit(
                "error_fecha",
                "La fecha no debe ser mayor al día de hoy.",
            );
            return false;
        }

        if ($this->date < $hoy->copy()->subDays($numberDays)->toDateString()) {
            $this->date = Carbon::now()->toDateString();
            $this->updatedDate();
            $this->emit(
                "error_fecha",
                "La fecha solo puede ser menor a {$numberDays} días de hoy.",
            );
            return false;
        }

        return true;
    }

    // ─────────────────────────────────────────────────────────────
    // Category
    // ─────────────────────────────────────────────────────────────

    public function categoryType(): void
    {
        $this->category_id = "";
        $this->subcategoria_id = "";

        if ($this->type) {
            $this->categorias = $this->getCategoriasForType($this->type);
        }
    }

    public function changeCategory(): void
    {
        $this->subcategoria_id = null;
        $this->subcategorias = AttrValue::where(
            "category_id",
            $this->category_id,
        )->get();

        if ($this->subcategorias->isNotEmpty()) {
            $this->emit("tiene_subcategorias", "Hay subcategorias");
        }
    }

    // ─────────────────────────────────────────────────────────────
    // Customer / API
    // ─────────────────────────────────────────────────────────────

    public function changeDocumentType(): void
    {
        $this->full_name = "";
        $this->first_name = "";
        $this->last_name = "";
        $this->document = "";
        $this->address = "";
    }

    public function consultasCustomer(): void
    {
        $customer = Customer::where("document", $this->documento)->first();

        if ($customer) {
            $this->customer_name = $customer->full_name;
        } else {
            $this->emit(
                "error",
                "No existe el cliente/proveedor. Si es un socio, créelo mediante el módulo de Socios.",
            );
            $this->customer_name = null;
        }
    }

    public function create(): void
    {
        if ($this->document_type == 0) {
            $this->full_name = trim("{$this->first_name} {$this->last_name}");
        }

        $this->validate(
            [
                "full_name" => "required",
                "document_type" => "required",
                "document" => "required|unique:customers",
            ],
            [
                "full_name.required" =>
                    "El nombre del cliente o proveedor es requerido.",
                "document_type.required" =>
                    "El tipo de documento es requerido.",
                "document.required" => "El documento es requerido.",
            ],
        );

        $customer = Customer::create([
            "full_name" => $this->full_name,
            "first_name" => $this->first_name,
            "last_name" => $this->last_name,
            "email" => $this->email,
            "document_type" => $this->document_type,
            "document" => $this->document,
            "phone" => $this->phone,
            "address" => $this->address,
            "etapa" => 1,
            "is_ative" => true,
            "is_client" => true,
            "student_id" => null,
            "student_tutor_id" => null,
        ]);

        $this->resetUI();
        $this->emit(
            "customer_added",
            "Cliente o proveedor registrado exitosamente.",
        );

        $this->documento = $customer->document;
        $this->customer_name = $customer->full_name;
    }

    public function clearDataApi(): void
    {
        $this->full_name = "";
        $this->first_name = "";
        $this->last_name = "";
        $this->address = "";
        $this->customer_name = null;
        $this->provisions = [];
    }

    public function consultasApi(): void
    {
        $cust =
            $this->selected_id == 0
                ? Customer::where("document", $this->document)->first()
                : null;

        if ($cust !== null) {
            $this->documento = $cust->document;
            $this->customer_name = $cust->full_name;
            $this->emit(
                "registro-existente",
                "Ya existe el cliente o proveedor.",
            );
            return;
        }

        $api = new ApiConsultasController();

        if ($this->document_type == "1") {
            $dataApi = $api->apiDni($this->document);
        } elseif ($this->document_type == "6") {
            $dataApi = $api->apiRuc($this->document);
        } else {
            $this->emit(
                "error",
                "Seleccione un tipo de documento válido (DNI o RUC).",
            );
            return;
        }

        if (isset($dataApi->error)) {
            $this->emit("error", $dataApi->error);
            return;
        }

        if ($dataApi === null) {
            $this->emit(
                "error",
                'No se encontró el documento. Intente ingresarlo manualmente con la opción "Otro".',
            );
            return;
        }

        $this->document_type = $dataApi->tipoDocumento;
        $this->document = $dataApi->numeroDocumento;
        $this->full_name = $dataApi->nombre;
        $this->address = $this->buildAddressFromApi($dataApi);

        if ($dataApi->tipoDocumento === "1") {
            $this->first_name = $dataApi->nombres;
            $this->last_name = "{$dataApi->apellidoPaterno} {$dataApi->apellidoMaterno}";
        } else {
            $this->first_name = null;
            $this->last_name = null;
        }
    }

    // ─────────────────────────────────────────────────────────────
    // Reset helpers
    // ─────────────────────────────────────────────────────────────

    public function resetUI(): void
    {
        $this->resetPersonFields();
        $this->customer_name = "Clientes/Proveedores Varios";
        $this->documento = 99999999;
        $this->resetValidation();
    }

    public function resetUiApi(): void
    {
        $this->resetPersonFields();
        $this->resetValidation();
    }

    // ─────────────────────────────────────────────────────────────
    // Private helpers
    // ─────────────────────────────────────────────────────────────

    private function fetchTipoCambio(string $date): float
    {
        $tc = new TipoCambioService();
        return $tc->getValue($date);
    }

    private function getCategoriasForType(
        string $type,
    ): \Illuminate\Support\Collection {
        return Category::where("id", "!=", 1)
            ->where("type", $type)
            ->pluck("id", "name");
    }

    private function resetPersonFields(): void
    {
        $this->selected_id = 0;
        $this->full_name = "";
        $this->first_name = "";
        $this->last_name = "";
        $this->document_type = 6;
        $this->document = "";
        $this->email = "";
        $this->phone = "";
        $this->address = "";
    }

    private function crearMovimientoRules(): array
    {
        return [
            "documento" => "required",
            "customer_name" => "required",
            "date" => "required|date",
            "type" => "required",
            "amount" => "required|numeric|min:0.01",
            "account_id" => "required",
            "user_id" => "required",
            "provision_code" => "required",
        ];
    }

    private function crearMovimientoMessages(): array
    {
        return [
            "documento.required" => "El documento es requerido.",
            "customer_name.required" =>
                "El nombre del cliente o proveedor es requerido.",
            "date.required" => "La fecha es requerida.",
            "date.date" => "La fecha debe ser una fecha válida.",
            "type.required" => "El tipo de movimiento es requerido.",
            "amount.required" => "El monto es requerido.",
            "amount.numeric" => "El monto debe ser un número.",
            "amount.min" => "El monto debe ser mayor a 0.",
            "account_id.required" => "La cuenta es requerida.",
            "user_id.required" => "El usuario es requerido.",
            "provision_code.required" => "El código es requerido.",
        ];
    }

    private function buildAddressFromApi(object $data): string
    {
        return trim(
            implode(" ", [
                $data->viaTipo,
                $data->viaNombre,
                $data->numero,
                "-",
                $data->zonaCodigo,
                $data->zonaTipo,
                "-",
                $data->departamento,
                "-",
                $data->provincia,
                "-",
                $data->distrito,
            ]),
        );
    }
}
