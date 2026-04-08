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
use App\Http\Controllers\ApiConsultasController;
use App\Models\SchoolYear;

class CrearMovimiento extends Component
{
    // UI state
    public $cuentas;
    public $paymentMethods;
    public $categorias;
    public $subcategorias;
    public $componentName;
    public $selected_id;
    public $validezFecha;
    public $showReceiptModal;
    public $receipt;
    public $summary_id;

    // Movement fields
    public $date;
    public $concept;
    public $type;
    public $status;
    public $amount;
    public $tax;
    public $recipt_series;
    public $puesto;
    public $recipt_number;
    public $future;
    public $account_id;
    public $category_id;
    public $subcategoria_id;
    public $payment_method;
    public $numero_operacion;
    public $user_id;
    public $customer_id;
    public $paid_by;
    public $observation;

    // Exchange rate
    public $tc;
    private $tipoCambio;
    protected $schoolYear;

    protected $dataApi = [];

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
    public $total_prov;
    public $total_prov_dolar;
    public $total_prov_cobrar;
    public $nuevos_detalles;
    public $total_new;
    public $students;
    public $student_id;
    public $student_name;
    public $provisions = [];
    public $provisionsCobrar = [];
    public $checkedProvision = [];
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
    public $mensaje;

    // ─────────────────────────────────────────────────────────────
    // Lifecycle
    // ─────────────────────────────────────────────────────────────

    public function mount()
    {
        $this->componentName = "Cliente/proveedor";
        $this->document_type = 1;
        $this->documento = 99999999;
        $this->customer_name = "Clientes/Proveedores Varios";
        $this->date = Carbon::now()->format("Y-m-d");
        $this->validezFecha = true;
        $this->selected_id = 0;
        $this->type = "add";
        $this->status = "PAID";
        $this->payment_method = 1;
        $this->tax = 0;
        $this->cuentas = Account::all();
        $this->account_id = $this->cuentas->first()->id;
        $this->paymentMethods = PaymentMethod::all();
        $this->categorias = Category::where("id", "!=", 1)
            ->where("type", $this->type)
            ->pluck("id", "name");
        $this->tipoCambio = new TipoCambioService();
        $this->tc = $this->tipoCambio->getValue($this->date);
    }

    public function render()
    {
        $this->updateTotal();
        return view("livewire.movimientos.crear-movimiento")->extends(
            "adminlte::page",
        );
    }

    // ─────────────────────────────────────────────────────────────
    // Date & totals
    // ─────────────────────────────────────────────────────────────

    public function updatedDate()
    {
        $this->tipoCambio = new TipoCambioService();
        $this->tc = $this->tipoCambio->getValue($this->date);
        $this->SelectedProvisions();
    }

    public function updateTotal()
    {
        $this->total_new = collect($this->provisions)->sum("amount");
    }

    // ─────────────────────────────────────────────────────────────
    // Receipt modal
    // ─────────────────────────────────────────────────────────────

    public function showReceiptModal($event)
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

    public function searchStandProvision($filterBy = "all")
    {
        $filterBy = SchoolYear::current()->year ?? now()->format("Y");
        $this->resetValidation();
        $this->validate(["provision_code" => "required"]);

        $student = Student::where("document", $this->provision_code)->first();

        if (!$student) {
            $this->emit("error", "No Existen Datos");
            return;
        }

        $this->student_id = $student->id;

        $baseQuery = Detail::when($filterBy !== "all", function ($query) use (
            $filterBy,
        ) {
            $query->whereYear("date", $filterBy);
        })
            ->where("student_id", $student->id)
            ->whereStatus(false)
            ->orderBy("date", "asc");

        $student_details = (clone $baseQuery)->get();

        $suma = (clone $baseQuery)
            ->where(function ($query) {
                $query
                    ->where("currency_id", "!=", 2)
                    ->orWhereNull("currency_id");
            })
            ->sum("amount");

        $sumaDolar = (clone $baseQuery)->where("currency_id", 2)->sum("amount");

        $this->documento = $student->tutor->document;
        $this->customer_name = $student->tutor->full_name;
        $this->student_name = $student->full_name;
        $this->paid_by = $this->customer_name;

        if ($student_details->count() > 0) {
            $this->provision_detalles = $student_details;
            $this->provisionsCobrar = [];
            $this->checkedProvision = [];
            $this->total_prov_cobrar = 0;
            $this->total_prov = $suma;
            $this->total_prov_dolar = $sumaDolar;
            $this->emit("mostrarModalProvision", "mostrar modal");
        } else {
            $this->documento = 99999999;
            $this->customer_name = "Clientes/Proveedores Varios";
            $this->provision_detalles = [];
            $this->provisionsCobrar = [];
            $this->checkedProvision = [];
            $this->total_prov_cobrar = 0;
            $this->total_prov = 0;
            $this->total_prov_dolar = 0;
            $this->paid_by = "";
            $this->emit(
                "error",
                "No existe el Stand o no existe provisiones actuales para dicho Stand",
            );
        }
    }

    public function selectAll()
    {
        $this->checkedProvision = $this->provision_detalles->pluck("id");
    }

    public function selectNow()
    {
        $date = Carbon::now();
        $start = $date->startOfMonth()->format("Y-m-d H:i:s");
        $end = $date->endOfMonth()->format("Y-m-d H:i:s");

        $this->checkedProvision = $this->provision_detalles
            ->whereBetween("date", [$start, $end])
            ->pluck("id");
    }

    public function SelectedProvisions()
    {
        $this->total_prov_cobrar = 0;
        $this->provisionsCobrar = Detail::whereKey(
            $this->checkedProvision,
        )->get();

        foreach ($this->provisionsCobrar as $cobrarItem) {
            if ($cobrarItem->currency->id != 2) {
                $this->total_prov_cobrar += $cobrarItem->amount;
            } else {
                $this->total_prov_cobrar += $cobrarItem->amount * $this->tc;
            }
        }
    }

    public function validarSeleccionados()
    {
        $this->det_selected = count($this->checkedProvision);
    }

    // ─────────────────────────────────────────────────────────────
    // Provision rows (nuevos detalles)
    // ─────────────────────────────────────────────────────────────

    public function Add()
    {
        $student = Student::findOrFail($this->student_id);

        if ($this->provision_code) {
            if ($student) {
                $this->provisions[] = [
                    "status" => "false",
                    "description" => "",
                    "type" => 2,
                    "date" => Carbon::now()->format("Y-m"),
                    "date_paid" => Carbon::now(),
                    "amount" => null,
                    "category_id" => "Elegir",
                    "student_id" => $student->id,
                    "summary_id" => null,
                ];
            } else {
                $this->emit(
                    "error",
                    "Estudiante no encontrado o código errado",
                );
            }
        } else {
            try {
                $this->provisions[] = [
                    "status" => "false",
                    "description" => null,
                    "type" => 2,
                    "date" => Carbon::now()->format("Y-m"),
                    "amount" => null,
                    "category_id" => "Elegir",
                    "student_id" => $student->id,
                    "summary_id" => null,
                ];
            } catch (\Throwable $th) {
                $this->emit("error", $th);
            }
        }
    }

    public function removeProvision($key)
    {
        array_splice($this->provisions, $key, 1);
        $this->updateTotal();
    }

    public function validarProvisions()
    {
        $this->validate([
            "provisions.*.date" => "required|date",
            "provisions.*.description" => "required|min:5|max:200",
            "provisions.*.category_id" => "required|not_in:Elegir",
            "provisions.*.amount" => "required|numeric|min:0.01",
            "provisions.*.student_id" => "required",
        ]);
    }

    public function Save()
    {
        $this->validarProvisions();
        dd($this->provisions);
    }

    // ─────────────────────────────────────────────────────────────
    // Save movement
    // ─────────────────────────────────────────────────────────────

    public function crearMovimiento()
    {
        $this->validarFechas();

        if ($this->validezFecha == false) {
            return;
        }

        $this->user_id = Auth::id();

        $cliente = Customer::where("document", $this->documento)->first();
        $student = Student::findOrFail($this->student_id);

        if ($cliente != null || $student != null) {
            $this->customer_id = $cliente->id;
            $this->student_id = $student->id;
            $this->student_tutor_id = $student->tutor->id;
        } else {
            $this->mensaje =
                "No existe el Cliente o proveedor, SI ES ESTUDIANTE (CREARLO MEDIANTE EL MODULO DE ESTUDIENTES)";
            $this->emit("error", $this->mensaje);
            return;
        }

        $this->amount = $this->total_prov_cobrar + $this->total_new;

        $this->validate(
            $this->crearMovimientoRules(),
            $this->crearMovimientoMessages(),
        );

        try {
            $this->validarProvisions();

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

            foreach ($this->provisionsCobrar as $data) {
                $data->update([
                    "status" => true,
                    "summary_type" => $summary->type,
                    "date_paid" => $summary->date,
                    "changed_amount" =>
                        $data->currency->id == 2
                            ? round($data->amount * $this->tc, 2)
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

            $this->emit(
                "movimiento_added",
                "El movimiento ha sido registrado exitosamente",
            );
        } catch (\Throwable $th) {
            $this->emit("error", $th->getMessage());
        }
    }

    // ─────────────────────────────────────────────────────────────
    // Date validation
    // ─────────────────────────────────────────────────────────────

    public function validarFechas()
    {
        $hoy = Carbon::now();
        $limitDateService = new LimitDateService();
        $numberDays = $limitDateService->getIncomeNumberDays();

        if ($this->date > $hoy->format("Y-m-d")) {
            $this->date = Carbon::now()->format("Y-m-d");
            $this->updatedDate();
            $this->updateTotal();
            $this->emit(
                "error_fecha",
                "La fecha no debe ser mayor al día de hoy",
            );
            $this->validezFecha = false;
        } elseif ($this->date < $hoy->subDays($numberDays)) {
            $this->date = Carbon::now()->format("Y-m-d");
            $this->updatedDate();
            $this->updateTotal();
            $this->emit(
                "error_fecha",
                "La fecha solo puede ser menor a {$numberDays} dias de la fecha de hoy",
            );
            $this->validezFecha = false;
        } else {
            $this->validezFecha = true;
        }
    }

    // ─────────────────────────────────────────────────────────────
    // Category
    // ─────────────────────────────────────────────────────────────

    public function categoryType()
    {
        $this->category_id = "";
        $this->subcategoria_id = "";

        if ($this->type !== null) {
            $this->categorias = Category::where("type", $this->type)->get();
        }
    }

    public function changeCategory()
    {
        $this->subcategoria_id = null;
        $this->subcategorias = AttrValue::where(
            "category_id",
            $this->category_id,
        )->get();

        if ($this->subcategorias->count() > 0) {
            $this->emit("tiene_subcategorias", "Hay subcategorias");
        }
    }

    // ─────────────────────────────────────────────────────────────
    // Customer / API
    // ─────────────────────────────────────────────────────────────

    public function chageDocumentType()
    {
        $this->full_name = "";
        $this->first_name = "";
        $this->last_name = "";
        $this->document = "";
        $this->address = "";
    }

    public function ConsutasCustomer()
    {
        $cust = Customer::where("document", $this->documento)->first();

        if ($cust != null) {
            $this->customer_name = $cust->full_name;
        } else {
            $this->mensaje =
                "No existe el Cliente o proveedor, SI ES SOCIO (CREARLO MEDIANTE EL MODULO DE SOCIOS)";
            $this->emit("error", $this->mensaje);
            $this->customer_name = null;
        }
    }

    public function create()
    {
        if ($this->document_type == 0) {
            $this->full_name = $this->first_name . " " . $this->last_name;
        }

        $this->validate(
            [
                "full_name" => "required",
                "document_type" => "required",
                "document" => "required|unique:customers",
            ],
            [
                "full_name.required" =>
                    "El nombre del cliente o proveedor es requerido",
                "document_type.required" => "El tipo de documento es requerido",
                "document.required" => "El documento es requerido",
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
            "Cliente o proveedor registrado exitosamente",
        );

        $this->documento = $customer->document;
        $this->customer_name = $customer->full_name;
    }

    public function clearDataApi()
    {
        $this->full_name = "";
        $this->first_name = "";
        $this->last_name = "";
        $this->address = "";
        $this->customer_name = null;
        $this->provisions = [];
    }

    public function ConsutasApi()
    {
        sleep(1);

        if ($this->selected_id == 0) {
            $cust = Customer::where("document", $this->document)->first();
        }

        if ($cust != null) {
            $this->mensaje = "Ya existe el cliente o proveedor";
            $this->documento = $cust->document;
            $this->customer_name = $cust->full_name;
            $this->emit("registro-existente", $this->mensaje);
            return;
        }

        $api = new ApiConsultasController();

        if ($this->document_type == "1") {
            $this->dataApi = $api->apiDni($this->document);
        } elseif ($this->document_type == "6") {
            $this->dataApi = $api->apiRuc($this->document);
        } else {
            $this->mensaje = "No es un documento";
            return;
        }

        if (isset($this->dataApi->error)) {
            $this->mensaje = $this->dataApi->error;
            $this->emit("error", $this->mensaje);
            return;
        }

        if ($this->dataApi == null) {
            $this->mensaje =
                "No existe el documento o ingrese manualmente los campos con la opcion de documento OTRO";
            $this->emit("error", $this->mensaje);
            return;
        }

        switch ($this->dataApi->tipoDocumento) {
            case "1":
                $this->full_name = $this->dataApi->nombre;
                $this->first_name = $this->dataApi->nombres;
                $this->last_name =
                    $this->dataApi->apellidoPaterno .
                    " " .
                    $this->dataApi->apellidoMaterno;
                $this->document_type = $this->dataApi->tipoDocumento;
                $this->document = $this->dataApi->numeroDocumento;
                $this->address = $this->buildAddressFromApi($this->dataApi);
                break;

            case "6":
                $this->full_name = $this->dataApi->nombre;
                $this->first_name = null;
                $this->last_name = null;
                $this->document_type = $this->dataApi->tipoDocumento;
                $this->document = $this->dataApi->numeroDocumento;
                $this->address = $this->buildAddressFromApi($this->dataApi);
                break;
        }
    }

    // ─────────────────────────────────────────────────────────────
    // Reset helpers
    // ─────────────────────────────────────────────────────────────

    public function resetUI()
    {
        $this->resetPersonFields();
        $this->customer_name = "Clientes/Proveedores Varios";
        $this->documento = 99999999;
        $this->resetValidation();
    }

    public function resetUiApi()
    {
        $this->resetPersonFields();
        $this->resetValidation();
    }

    // ─────────────────────────────────────────────────────────────
    // Private helpers
    // ─────────────────────────────────────────────────────────────

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
            "documento.required" => "El documento es requerido",
            "customer_name.required" =>
                "El nombre del cliente o proveedor es requerido",
            "date.required" => "La fecha es requerida",
            "date.date" => "La fecha debe ser una fecha válida",
            "type.required" => "El tipo de movimiento es requerido",
            "amount.required" => "El monto es requerido",
            "amount.numeric" => "El monto debe ser un valor positivo",
            "amount.min" => "El monto deberia ser mayor a 0",
            "account_id.required" => "La cuenta es requerida",
            "user_id.required" => "El usuario es requerido",
            "provision_code.required" => "El código es requerido",
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
