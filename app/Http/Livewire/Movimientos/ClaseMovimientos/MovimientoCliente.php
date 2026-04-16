<?php

namespace App\Http\Livewire\Movimientos\ClaseMovimientos;

use Carbon\Carbon;
use App\Models\Detail;
use App\Models\Account;
use App\Models\Summary;
use Livewire\Component;
use App\Models\Bitacora;
use App\Models\Category;
use App\Models\Customer;
use App\Models\AttrValue;
use App\Models\PaymentMethod;
use App\Models\StudentTutor;
use App\Services\TipoCambioService;
use App\Services\LimitDateService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MovimientoCliente extends Component
{
    // ── UI state ─────────────────────────────────────────────────────────────
    public $cuentas;
    public $paymentMethods;
    public $categorias;
    public $subcategorias;
    public bool $showReceiptModal = false;
    public $receipt;
    public $summary_id;
    public $customers;

    // ── Movement fields ───────────────────────────────────────────────────────
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
    public $student_tutor_id;

    // ── Exchange rate ─────────────────────────────────────────────────────────
    public $tc;

    // ── Customer / provision search ───────────────────────────────────────────
    public $documento;
    public $customer_name;
    public $student_name;

    // ── Provisions ────────────────────────────────────────────────────────────
    public $provision_detalles;
    public float $total_prov = 0;
    public float $total_prov_dolar = 0;
    public float $total_prov_cobrar = 0;
    public float $total_new = 0;
    public array $provisions = [];
    public $provisionsCobrar = [];
    public array $checkedProvision = [];
    public $students;
    public $student_id;

    protected $listeners = [
        'redireccionar',
        'summaryCreated'   => 'showReceiptModal',
        'buscar_provision' => 'searchStandProvision',
        'selectSearch',
        'customerCreated'  => 'handleCustomerCreated',
    ];

    // ── Lifecycle ─────────────────────────────────────────────────────────────

    public function mount(): void
    {
        $this->documento      = 99999999;
        $this->customer_name  = 'Clientes/Proveedores Varios';
        $this->date           = Carbon::now()->toDateString();
        $this->type           = 'add';
        $this->status         = 'PAID';
        $this->payment_method = 1;
        $this->tax            = 0;

        $this->cuentas        = Account::all();
        $this->account_id     = $this->cuentas->first()->id;
        $this->paymentMethods = PaymentMethod::all();
        $this->categorias     = $this->getCategoriasForType($this->type);
        $this->tc             = $this->fetchTipoCambio($this->date);
        $this->customers      = Customer::pluck('id', 'full_name');
    }

    public function render()
    {
        $this->updateTotal();
        return view('livewire.movimientos.clase-movimientos.movimiento-cliente')
            ->extends('adminlte::page');
    }

    // ── Date & totals ─────────────────────────────────────────────────────────

    public function updatedDate(): void
    {
        $this->tc = $this->fetchTipoCambio($this->date);
        $this->selectedProvisions();
    }

    public function updateTotal(): void
    {
        $this->total_new = collect($this->provisions)->sum('amount');
    }

    // ── Receipt modal ─────────────────────────────────────────────────────────

    public function showReceiptModal($event = null): void
    {
        $this->showReceiptModal = true;
    }

    public function redireccionar()
    {
        return redirect()->route('movimientos.listado');
    }

    // ── New customer created by child modal ───────────────────────────────────

    public function handleCustomerCreated(int $customerId, string $document, string $fullName): void
    {
        $this->customers     = Customer::pluck('id', 'full_name');
        $this->documento     = $document;
        $this->customer_name = $fullName;
        $this->customer_id   = $customerId;
        $this->paid_by       = $fullName;

        $customer = Customer::find($customerId);
        $this->resolveStudentTutor($customer);

        $this->emit('updateSelect', $customerId, $fullName);
    }

    // ── Provision search & selection ──────────────────────────────────────────

    public function searchStandProvision(): void
    {
        $tutor = StudentTutor::where('document', $this->documento)->first();

        if (!$tutor) {
            $this->emit('error', 'No existe el estudiante o tutor, o no tiene provisiones actuales.');
            $this->provision_detalles = [];
            $this->total_prov         = 0;
            $this->total_prov_dolar   = 0;
            return;
        }

        $this->students     = $tutor->students;
        $this->student_name = $tutor->full_name;

        $details = Detail::where('student_tutor_id', $tutor->id)
            ->whereStatus(false)
            ->with(['currency', 'category'])
            ->get();

        $this->provision_detalles = $details;
        $this->total_prov         = $details->where('currency_id', '!=', 2)->sum('amount');
        $this->total_prov_dolar   = $details->where('currency_id', 2)->sum('amount');
        $this->provisionsCobrar   = [];
        $this->checkedProvision   = [];
        $this->total_prov_cobrar  = 0;

        $this->emit('mostrarModalProvision');
    }

    public function selectAll(): void
    {
        $this->checkedProvision = $this->provision_detalles->pluck('id')->toArray();
    }

    public function selectNow(): void
    {
        $this->checkedProvision = $this->provision_detalles
            ->whereBetween('date', [
                now()->startOfMonth()->format('Y-m-d H:i:s'),
                now()->endOfMonth()->format('Y-m-d H:i:s'),
            ])
            ->pluck('id')
            ->toArray();
    }

    public function selectedProvisions(): void
    {
        $this->total_prov_cobrar = 0;
        $this->provisionsCobrar  = Detail::whereKey($this->checkedProvision)->get();

        foreach ($this->provisionsCobrar as $item) {
            $this->total_prov_cobrar += $item->currency->id === 2
                ? $item->amount * $this->tc
                : $item->amount;
        }
    }

    // ── Provision rows ────────────────────────────────────────────────────────

    public function Add(): void
    {
        $this->provisions[] = [
            'status'      => false,
            'description' => null,
            'type'        => 2,
            'date'        => Carbon::now()->format('Y-m'),
            'amount'      => null,
            'category_id' => 'Elegir',
            'student_id'  => $this->student_id,
            'summary_id'  => null,
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
            'provisions.*.date'        => 'required|date',
            'provisions.*.description' => 'required|min:5|max:200',
            'provisions.*.category_id' => 'required|not_in:Elegir',
            'provisions.*.amount'      => 'required|numeric|min:0.01',
        ]);
    }

    // ── Save movement ─────────────────────────────────────────────────────────

    public function crearMovimiento(): void
    {
        if (!$this->validarFechas()) {
            return;
        }

        $this->user_id = Auth::id();

        $cliente = Customer::where('document', $this->documento)->first();

        if ($cliente === null) {
            $this->emit('error', 'No existe el cliente o proveedor. Si es un estudiante, créelo mediante el módulo de Estudiantes.');
            return;
        }

        $this->customer_id = $cliente->id;
        $this->amount      = $this->total_prov_cobrar + $this->total_new;

        $this->validate(
            $this->crearMovimientoRules(),
            $this->crearMovimientoMessages()
        );

        try {
            $this->validarProvisions();

            DB::transaction(function () {
                $summary = Summary::create([
                    'date'              => $this->date,
                    'type'              => $this->type,
                    'status'            => $this->status,
                    'amount'            => $this->amount,
                    'tipo_cambio'       => $this->tc,
                    'recipt_series'     => $this->recipt_series,
                    'recipt_number'     => $this->recipt_number,
                    'account_id'        => $this->account_id,
                    'user_id'           => $this->user_id,
                    'future'            => 1,
                    'operation_number'  => $this->numero_operacion,
                    'paid_by'           => $this->paid_by,
                    'observation'       => $this->observation,
                    'customer_id'       => $this->customer_id,
                    'student_tutor_id'  => $this->student_tutor_id,
                    'student_id'        => $this->student_id,
                    'payment_method_id' => $this->payment_method,
                ]);

                foreach ($this->provisions as $input) {
                    Detail::create([
                        'unique_code'  => Carbon::parse($input['date'])->format('Y-m') . $input['category_id'] . $summary->id,
                        'status'       => true,
                        'summary_type' => $summary->type,
                        'date'         => $input['date'],
                        'description'  => $input['description'],
                        'date_paid'    => $summary->date,
                        'category_id'  => $input['category_id'],
                        'student_id'   => $this->student_id,
                        'amount'       => $input['amount'],
                        'summary_id'   => $summary->id,
                    ]);
                }

                foreach ($this->provisionsCobrar as $provision) {
                    $provision->update([
                        'status'         => true,
                        'summary_type'   => $summary->type,
                        'date_paid'      => $summary->date,
                        'changed_amount' => $provision->currency->id === 2
                            ? round($provision->amount * $this->tc, 2)
                            : 0,
                        'summary_id'     => $summary->id,
                    ]);
                }

                $this->summary_id    = $summary->id;
                $this->recipt_series = $summary->recipt_series;
                $this->recipt_number = $summary->recipt_number;
                $this->receipt       = $summary->recipt_series . '-' . str_pad($summary->recipt_number, 8, '0', STR_PAD_LEFT);
                $this->showReceiptModal = true;

                Bitacora::create([
                    'type'        => 'add',
                    'activity'    => "El usuario ha creado un nuevo movimiento: {$this->recipt_series} {$this->recipt_number}",
                    'activity_id' => $summary->id,
                    'user_id'     => Auth::id(),
                ]);
            });

            $this->dispatchBrowserEvent('show-receipt-modal');
            $this->emit('movimiento_added', 'El movimiento ha sido registrado exitosamente.');

        } catch (\Throwable $th) {
            $this->emit('error', $th->getMessage());
        }
    }

    // ── Customer lookup ───────────────────────────────────────────────────────

    public function consultasCustomer(): void
    {
        $customer = Customer::where('document', $this->documento)->first();

        if (!$customer) {
            $this->emit('error', 'No existe el cliente o proveedor. Si es un socio, créelo mediante el módulo de Socios.');
            $this->customer_name = null;
            return;
        }

        $this->customer_name = $customer->full_name;
        $this->customer_id   = $customer->id;
        $this->paid_by       = $customer->full_name;
        $this->customers     = Customer::pluck('id', 'full_name');

        $this->resolveStudentTutor($customer);

        $this->emit('updateSelect', $customer->id, $customer->full_name);
    }

    public function selectSearch(): void
    {
        $customer = Customer::find($this->customer_id);

        if (!$customer) {
            return;
        }

        $this->documento         = $customer->document;
        $this->customer_name     = $customer->full_name;
        $this->paid_by           = $customer->full_name;
        $this->provisions        = [];
        $this->provisionsCobrar  = [];
        $this->checkedProvision  = [];
        $this->total_prov_cobrar = 0;

        $this->resolveStudentTutor($customer);
    }

    public function clearDataApi(): void
    {
        $this->customer_name     = null;
        $this->customer_id       = null;
        $this->provisions        = [];
        $this->provisionsCobrar  = [];
        $this->checkedProvision  = [];
        $this->total_prov_cobrar = 0;
        $this->student_tutor_id  = null;
        $this->student_id        = null;
        $this->student_name      = null;
        $this->emit('clearSelect');
    }

    // ── Category ──────────────────────────────────────────────────────────────

    public function categoryType(): void
    {
        $this->category_id    = '';
        $this->subcategoria_id = '';

        if ($this->type) {
            $this->categorias = $this->getCategoriasForType($this->type);
        }
    }

    public function changeCategory(): void
    {
        $this->subcategoria_id = null;
        $this->subcategorias   = AttrValue::where('category_id', $this->category_id)->get();

        if ($this->subcategorias->isNotEmpty()) {
            $this->emit('tiene_subcategorias', 'Hay subcategorias');
        }
    }

    // ── Date validation ───────────────────────────────────────────────────────

    private function validarFechas(): bool
    {
        $hoy        = Carbon::now();
        $numberDays = (new LimitDateService())->getIncomeNumberDays();

        if ($this->date > $hoy->toDateString()) {
            $this->date = $hoy->toDateString();
            $this->updatedDate();
            $this->emit('error_fecha', 'La fecha no debe ser mayor al día de hoy.');
            return false;
        }

        if ($this->date < $hoy->copy()->subDays($numberDays)->toDateString()) {
            $this->date = Carbon::now()->toDateString();
            $this->updatedDate();
            $this->emit('error_fecha', "La fecha solo puede ser menor a {$numberDays} días de hoy.");
            return false;
        }

        return true;
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    private function resolveStudentTutor(Customer $customer): void
    {
        if ($customer->is_tutor && $customer->student_tutor_id) {
            $tutor = StudentTutor::with('students')->find($customer->student_tutor_id);

            if ($tutor) {
                $this->student_tutor_id = $tutor->id;
                $this->students         = $tutor->students;
                $this->student_id       = $tutor->students->first()?->id;
                $this->student_name     = $tutor->students->pluck('full_name')->implode(', ');
                return;
            }
        }

        $this->student_tutor_id = null;
        $this->students         = null;
        $this->student_id       = null;
        $this->student_name     = null;
    }

    private function fetchTipoCambio(string $date): float
    {
        return (new TipoCambioService())->getValue($date);
    }

    private function getCategoriasForType(string $type): \Illuminate\Support\Collection
    {
        return Category::where('id', '!=', 1)
            ->where('type', $type)
            ->pluck('id', 'name');
    }

    private function crearMovimientoRules(): array
    {
        return [
            'documento'     => 'required',
            'customer_name' => 'required',
            'date'          => 'required|date',
            'type'          => 'required',
            'amount'        => 'required|numeric|min:0.01',
            'account_id'    => 'required',
            'user_id'       => 'required',
        ];
    }

    private function crearMovimientoMessages(): array
    {
        return [
            'documento.required'     => 'El documento es requerido.',
            'customer_name.required' => 'El nombre del cliente o proveedor es requerido.',
            'date.required'          => 'La fecha es requerida.',
            'date.date'              => 'La fecha debe ser una fecha válida.',
            'type.required'          => 'El tipo de movimiento es requerido.',
            'amount.required'        => 'El monto es requerido.',
            'amount.numeric'         => 'El monto debe ser un número.',
            'amount.min'             => 'El monto debe ser mayor a 0.',
            'account_id.required'    => 'La cuenta es requerida.',
            'user_id.required'       => 'El usuario es requerido.',
        ];
    }
}
