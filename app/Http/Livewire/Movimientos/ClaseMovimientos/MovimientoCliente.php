<?php

namespace App\Http\Livewire\Movimientos\ClaseMovimientos;

use Carbon\Carbon;
use App\Models\Stand;
use App\Models\Detail;
use App\Models\Account;
use App\Models\Partner;
use App\Models\Summary;
use Livewire\Component;
use App\Models\Bitacora;
use App\Models\Category;
use App\Models\Customer;
use App\Models\AttrValue;
use App\Models\PaymentMethod;
use App\Services\TipoCambioService;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiConsultasController;
use App\Models\Student;
use App\Models\StudentTutor;

class MovimientoCliente extends Component
{
    public $cuentas, $paymentMethods, $categorias, $subcategorias, $componentName, $selected_id, $validezFecha, $showReceiptModal, $receipt, $summary_id;

    public $date, $concept, $type, $status, $amount, $tax, $recipt_series,$puesto, $recipt_number, $future, $account_id, $category_id, $subcategoria_id, $payment_method, $numero_operacion, $paid_by, $observation, $user_id, $customer_id, $student_tutor_id;

    protected $dataApi=[];

    protected $listeners = [
        'resetUI',
        'resetUiApi',
        'redireccionar',
        'summaryCreated' => 'showReceiptModal',
        'buscar_provision' => 'searchStandProvision',
        'selectSearch',
    ];

    public $documento, $customer_name;
    public $provision_detalles, $detalles, $total_prov, $total_prov_dolar, $nuevos_detalles, $total_new, $students, $student_id;
    public $provisions = [];

    public $document_type, $document, $full_name, $first_name, $last_name, $address, $email, $phone, $mensaje;
    public $customers;

    public $checkedProvision, $provisionsCobrar, $total_prov_cobrar;

    private $tipoCambio;
    public $tc;

    public function updatedDate()
    {
        $this->tipoCambio = new TipoCambioService();
        $this->tc = $this->tipoCambio->getValue($this->date);
        $this->SelectedProvisions();
    }

    public function showReceiptModal($event)
    {
        $this->showReceiptModal = true;
    }

    public function searchStandProvision()
    {
        $tutor = StudentTutor::whereDocument($this->documento)->first();
        if($tutor)
        {
            $this->students = $tutor->students;
            $this->detalles = Detail::where('student_tutor_id', '=', $tutor->id)->whereStatus(false)->get();

            // dd($this->provision_detalles);
            $this->total_prov = $this->detalles->where('currency_id', '!=', 2)->sum('amount');
            $this->total_prov_dolar = $this->detalles->where('currency_id', 2)->sum('amount');

            $this->provision_detalles = $this->detalles;

            $this->provisionsCobrar = [];
            $this->checkedProvision = [];
            $this->total_prov_cobrar = 0;

            $this->emit('mostrarModalProvision', 'mostrar modal');
        }else{
            // $this->documento = 99999999;
            // $this->customer_name = 'Clientes/Proveedores Varios';
            $this->emit('error', 'No existe el Estudiente o no existe provisiones actuales para dicho Estudiente o Tutor');
            $this->provision_detalles = [];
            $this->total_prov = 0;
            $this->total_prov_dolar = 0;
        }
    }

    public function selectAll()
    {
        $this->checkedProvision = $this->provision_detalles->pluck('id');
    }

    public function selectNow()
    {
        $date = Carbon::now();
        $start = $date->startOfMonth()->format('Y-m-d H:i:s'); // 2000-02-01 00:00:00
        $end = $date->endOfMonth()->format('Y-m-d H:i:s'); // 2000-02-29 23:59:59

        // dd($start, $end);
        $this->checkedProvision = $this->provision_detalles->whereBetween('date', [$start, $end])->pluck('id');
    }

    public function SelectedProvisions()
    {
        $this->total_prov_cobrar = 0;
        $this->provisionsCobrar = Detail::whereKey($this->checkedProvision)->get();
        foreach($this->provisionsCobrar as $cobrarItem){
            if ($cobrarItem->currency->id != 2) {
                $this->total_prov_cobrar += $cobrarItem->amount;
            }else {
                $this->total_prov_cobrar += $cobrarItem->amount * $this->tc;
            }
        };
    }

    public function setDefaultAmount()
    {
        if (!$this->amount) {
            $this->amount = 0;
        }
    }

    public function Add()
    {
        try {
            // $tutor = StudentTutor::where('document', $this->documento)->first();
            // $this->students = Student::where('student_tutor_id', $tutor->student_tutor_id)->pluck('id', 'full_name');
            // dd($this->stands);
            if ($this->students) {
                // dd('hola');
                $this->provisions[] = [
                    'status' => 'false',
                    'description' => '',
                    'type' => 2,
                    'date' => Carbon::now()->format('Y-m'),
                    'amount' => 0,
                    'category_id' => 'Elegir',
                    'summary_id' => null,
                ];
            } else {
                // dd('hola');
                $this->provisions[] = [
                    'status' => 'false',
                    'description' => '',
                    'type' => 2,
                    'date' => Carbon::now()->format('Y-m'),
                    'amount' => 0,
                    'category_id' => 'Elegir',
                    'student_id' => null,
                    'student_tutor_id' => null,
                    'summary_id' => null,
                ];

            }

        } catch (\Throwable $th) {
            //throw $th;
            $this->emit('error', $th->getMessage());
        }


    }

    public function removeProvision($key)
    {
        array_splice($this->provisions, $key, 1);
        $this->updateTotal();
    }

    public function validarProvisions()
    {
        $rules = [
            'provisions.*.date' => 'required|date',
            'provisions.*.description' => 'required|min:5|max:200',
            'provisions.*.category_id' => 'required|not_in:Elegir',
            'provisions.*.amount' => 'required|numeric|min:0.01',
        ];

        $validatedData = $this->validate($rules);
    }

    public function updateTotal()
    {
        try {
            $this->total_new = collect($this->provisions)->sum('amount');
        } catch (\Throwable $th) {
            //throw $th;
            $this->emit('error', $th->getMessage());
        }
    }

    public function Save()
    {
        dd($this->provisions);
    }

    public function mount()
    {
        $this->componentName = 'Cliente/proveedor';
        $this->document_type = 1;
        $this->documento = 99999999;
        $this->customer_name = 'Clientes/Proveedores Varios';
        $this->date = Carbon::now()->format('Y-m-d');
        $this->validezFecha = true;
        $this->selected_id = 0;
        $this->type = 'add';
        $this->status = 'PAID';
        $this->payment_method = 1;
        $this->tax = 0;

        $this->cuentas = Account::all();
        $this->account_id = $this->cuentas->first()->id;
        $this->paymentMethods = PaymentMethod::all();
        $this->categorias = Category::where('type', '=', $this->type)->where('id', '!=', 1)->pluck('id', 'name');
        $this->tipoCambio = new TipoCambioService();
        $this->tc = $this->tipoCambio->getValue($this->date);
        $this->customers = Customer::pluck('id', 'full_name');
    }

    public function render()
    {
        $this->updateTotal();
        return view('livewire.movimientos.clase-movimientos.movimiento-cliente')->extends('adminlte::page');
    }

    public function crearMovimiento()
    {

        $hoy = Carbon::now();

        $this->validarFechas();

        // dd($this->validezFecha);
        if($this->validezFecha == false){
            // dd('hola');
            return;
        }else{

            $this->user_id = Auth::id();

            $cliente = Customer::where('document', $this->documento)->first();
            $tutor = StudentTutor::where('document', $this->documento)->first();

            if($cliente != null || $tutor != null){
                $this->customer_id = $cliente->id;
                $this->student_tutor_id = $cliente->is_tutor ? $tutor->id : null;
            }else{
                $this->mensaje = 'No existe el Cliente o proveedor, SI ES ESTUDIANTE (CREARLO MEDIANTE EL MODULO DE ESTUDIANTES)';
                $this->emit('error', $this->mensaje);
                return;
            }

            // dd($this->customer_id, $this->partner_id);

            $this->amount = $this->total_prov_cobrar + $this->total_new;
            $iva = $this->tax;

            $rules = [
                'documento' => 'required',
                'customer_name' => 'required',
                'date' => 'required|date',
                'type' => 'required',
                'amount' => 'required|numeric|min:0.01',
                'account_id' => 'required',
                'user_id' => 'required',
            ];

            $messages = [
                'documento.required' => 'El documento es requerido',
                'customer_name.required' => 'El nombre del cliente o proveedor es requerido',
                'date.required' => 'La fecha es requerida',
                'date.date' => 'La fecha debe ser una fecha válida',

                'type.required' => 'El tipo de movimiento es requerido',
                'amount.required' => 'El monto es requerido',
                'amount.numeric' => 'El monto debe ser un valor positivo',
                'amount.min' => 'El monto deberia ser mayor 0',

                'account_id.required' => 'La cuenta es requerida',

                'user_id.required' => 'El usuario es requerido',
            ];

            $this->validate($rules, $messages);

            try {

                $this->validarProvisions();

                $summary = Summary::create([

                    'date' => $this->date,
                    'type'=> $this->type,
                    'status' => $this->status,
                    'amount'=> $this->amount,
                    'tipo_cambio' => $this->tc,
                    'recipt_series'=> $this->recipt_series,
                    'recipt_number'=> $this->recipt_number,

                    'account_id'=> $this->account_id,
                    'user_id'=>$this->user_id,
                    'future'=>1,
                    'operation_number' => $this->numero_operacion,

                    'paid_by' => $this->paid_by,
                    'observation' => $this->observation,

                    'customer_id' => $this->customer_id,
                    'student_tutor_id' => null,
                    'student_id' => null,
                    'payment_method_id' => $this->payment_method,

                ]);

                if($this->provisions)
                {
                    foreach ($this->provisions as $input) {
                        $unique_code = strval(Carbon::parse($input['date'])->format('Y-m').$input['category_id'].$summary->id);
                        Detail::create([
                            'unique_code' => $unique_code,
                            'status' => true,
                            'summary_type' => $summary->type,
                            'date' => $input['date'],
                            'description' => $input['description'],
                            'date_paid' => $summary->date,
                            'category_id' => $input['category_id'],
                            'student_id' => null,
                            'amount' => $input['amount'],
                            'summary_id' => $summary->id,
                        ]);
                    }
                }

                if($this->provisionsCobrar)
                {
                    foreach ($this->provisionsCobrar as $data) {
                        $data->update([
                            'status' => true,
                            'summary_type' => $summary->type,
                            'date_paid' => $summary->date,
                            'changed_amount' => $data->currency->id == 2 ? round($data->amount * $this->tc, 2) : 0,
                            'summary_id' => $summary->id,
                        ]);
                    }
                }

                $this->emit('movimiento_added', 'El movimiento ha sido registrado exitosamente');

                $this->receipt = $summary->recipt_series .'-'. str_pad($summary->recipt_number, 8, '0', STR_PAD_LEFT);
                $this->summary_id = $summary->id;
                $this->recipt_series = $summary->recipt_series;
                $this->recipt_number = $summary->recipt_number;
                $this->showReceiptModal = true;


                $bitacora = Bitacora::create([
                    'type' => 'add',
                    'activity' => "El usuario ha creado un nuevo movimiento: $this->recipt_series $this->recipt_number",
                    'activity_id' => $summary->id,
                    'user_id' => Auth::user()->id,
                ]);
            } catch (\Throwable $th) {
                //throw $th;
                $this->emit('error', $th->getMessage());
            }


        }
    }

    public function redireccionar()
    {
        return redirect()->route('movimientos.listado');
    }

    public function validarFechas()
    {
        $hoy = Carbon::now();

        if($this->date > $hoy->format('Y-m-d')){

            $this->date = Carbon::now()->format('Y-m-d');
            $this->updatedDate();
            $this->updateTotal();
            $this->emit('error_fecha', 'La fecha no debe ser mayor al día de hoy');
            $this->validezFecha = false;

        }elseif($this->date < $hoy->subDays(3)){

            $this->date = Carbon::now()->format('Y-m-d');
            $this->updatedDate();
            $this->updateTotal();
            $this->emit('error_fecha', 'La fecha solo puede ser menor a 3 dias de la fecha de hoy');
            $this->validezFecha = false;
        }else{
            $this->validezFecha = true;
        }
    }

    public function categoryType()
    {
        $this->category_id = '';
        $this->subcategoria_id = '';

        if($this->type !== null){
            $this->categorias = Category::where('type', '=', $this->type)->get();
        }
    }

    public function changeCategory()
    {
        $this->subcategoria_id = null;
        $this->subcategorias = AttrValue::where('category_id', $this->category_id)->get();

        if($this->subcategorias->count() > 0){
            $this->emit('tiene_subcategorias', 'Hay subcategorias');
        }

    }

    public function chageDocumentType()
    {
        $this->full_name = '';
        $this->first_name = '';
        $this->last_name = '';
        $this->document = '';
        $this->address = '';
    }

    public function ConsutasCustomer()
    {
        $cust = Customer::where('document', $this->documento)->first();
        // dd($cust->full_name);
        if($cust != null){
            $this->customer_name = $cust->full_name;
        }else{
            $this->mensaje = 'No existe el Cliente o proveedor, SI ES SOCIO (CREARLO MEDIANTE EL MODULO DE SOCIOS)';
            $this->emit('error', $this->mensaje);
            $this->customer_name = null;
        }

        $this->paid_by = $this->customer_name;

        $this->emit('updateSelect', $cust->id ?? null);
    }

    public function selectSearch()
    {
        $customer = Customer::find($this->customer_id);
        $this->documento = $customer->document ?? 99999999;
        $this->provisions = [];
        $this->provisionsCobrar = [];
        $this->checkedProvision = [];
        $this->total_prov_cobrar = 0;
        $this->paid_by = $customer->full_name ?? '';
    }

    //crear nuevo cliente o proveedor
    public function create()
    {
        if($this->document_type == 0)
        {
            $this->full_name = $this->first_name.' '.$this->last_name;
        }

        $rules = [
            'full_name' => 'required',
            'document_type' => 'required',
            'document' => 'required|unique:customers',

        ];

        $messages = [
            'full_name.required' => 'El nombre del cliente o proveedor es requerido',
            'document_type.required' => 'El tipo de documento es requerido',
            'document.required' => 'El documento es requerido',
        ];

        $this->validate($rules, $messages);


        $customer = Customer::create([
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
            'partner_id' => null,
            'sublet_id' => null,
        ]);

        $this->emit('customer_added', 'Cliente o proveedor registrado exitosamente');
        $this->customers = Customer::pluck('id', 'full_name');
        $this->documento = $customer->document;

        $this->customer_name = $customer->full_name;
        $this->emit('updateSelect', $customer->id ?? null);

        $this->resetUI();

    }

    public function clearDataApi()
    {
        $this->full_name = '';
        $this->first_name = '';
        $this->last_name = '';
        $this->address = '';
        $this->customer_name = null;
        $this->provisions = [];
        $this->provisionsCobrar = [];
        $this->checkedProvision = [];
        $this->total_prov_cobrar = 0;
        $this->emit('clearSelect');
    }



    public function ConsutasApi()
    {

        sleep(1);

        if ($this->selected_id == 0){

            $cust = Customer::where('document', $this->document)->first();
        }

        if($cust != null){
                $this->mensaje = 'Ya existe el cliente o proveedor';
                $this->documento = $cust->document;
                $this->customer_name = $cust->full_name;
                $this->ConsutasCustomer();
                $this->emit('registro-existente', $this->mensaje);
        }else{

            if($this->document_type == '1'){

                $this->dataApi = (new ApiConsultasController)->apiDni($dni = $this->document);

            }elseif($this->document_type == '6'){

                $this->dataApi = (new ApiConsultasController)->apiRuc($ruc = $this->document);

            }else {

                $this->mensaje = "No es un documento";

            }

            if(isset($this->dataApi->error)){

                $this->mensaje = $this->dataApi->error;
                $this->emit('error', $this->mensaje);
                return;

            }else{
                if($this->document_type != "0"){

                    if($this->dataApi == null){

                        $this->mensaje = 'No existe el documento o ingrese manualmente los campos con la opcion de documento OTRO';
                        $this->emit('error', $this->mensaje);
                        return;

                    }else{

                        switch ($this->dataApi->tipoDocumento) {
                            case '1':
                                $this->full_name = $this->dataApi->nombre;
                                $this->first_name = $this->dataApi->nombres;
                                $this->last_name = $this->dataApi->apellidoPaterno . ' '. $this->dataApi->apellidoMaterno;
                                $this->document_type = $this->dataApi->tipoDocumento;
                                $this->document = $this->dataApi->numeroDocumento;
                                $this->address = ''.$this->dataApi->viaTipo. ' ' . $this->dataApi->viaNombre . ' ' . $this->dataApi->numero. ' - '. $this->dataApi->zonaCodigo. ' ' . $this->dataApi->zonaTipo. ' - ' . $this->dataApi->departamento. ' - '. $this->dataApi->provincia. ' - '.$this->dataApi->distrito.'';
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
        $this->document_type = 6;
        $this->document = '';
        $this->email = '';
        $this->phone = '';
        $this->address = '';

        $this->resetValidation();
    }
    public function resetUiApi()
    {
        $this->selected_id = 0;
        $this->full_name = '';
        $this->first_name = '';
        $this->last_name = '';
        $this->document_type = 6;
        $this->document = '';
        $this->email = '';
        $this->phone = '';
        $this->address = '';

        $this->resetValidation();
    }

}
