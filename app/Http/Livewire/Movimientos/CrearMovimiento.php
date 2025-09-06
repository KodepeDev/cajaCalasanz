<?php

namespace App\Http\Livewire\Movimientos;

use PDF;
use Carbon\Carbon;
use App\Models\Detail;
use App\Models\Account;
use App\Models\Summary;
use Livewire\Component;
// use Barryvdh\DomPDF\Facade as PDF;
use App\Models\Bitacora;
use App\Services\LimitDateService;

use App\Models\Category;
use App\Models\Customer;
use App\Models\AttrValue;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiConsultasController;
use App\Models\Partner;
use App\Models\Stand;
use App\Models\Student;
use App\Services\TipoCambioService;

class CrearMovimiento extends Component
{
    public $cuentas, $paymentMethods, $categorias, $subcategorias, $componentName, $selected_id, $validezFecha, $showReceiptModal, $receipt, $summary_id;

    public $date, $concept, $type, $status, $amount, $tax, $recipt_series,$puesto, $recipt_number, $future, $account_id, $category_id, $subcategoria_id, $payment_method, $numero_operacion, $user_id, $customer_id, $paid_by, $observation;

    private $tipoCambio;
    public $tc;

    protected $dataApi=[];

    protected $listeners = [
        'resetUI',
        'resetUiApi',
        'redireccionar',
        'summaryCreated' => 'showReceiptModal',
        'buscar_provision' => 'searchStandProvision',
    ];

    public $documento, $customer_name, $provision_code;
    public $provision_detalles, $total_prov, $total_prov_dolar, $total_prov_cobrar, $nuevos_detalles, $total_new, $students, $student_id, $student_name;
    public $provisions = [];
    public $provisionsCobrar = [];
    public $checkedProvision = [];
    public $det_selected;

    public $document_type, $document, $full_name, $first_name, $last_name, $address, $email, $phone, $mensaje;

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
        $this->resetValidation();
        $this->validate([
            'provision_code' => 'required',
        ]);
        // dd($this->provision_code);
        $student = Student::where('document', $this->provision_code)->first();
        if(!$student) {
            $this->emit('error', 'No Existen Datos');
            return;
        }
        $baseQuery = Detail::where('student_id', $student->id)->whereStatus(false)->orderBy('date', 'asc');
        // Obtener detalles del stand
        $student_details = (clone $baseQuery)->get();

        // Calcular suma en otra moneda (currency_id != 2)
        $suma = (clone $baseQuery)
        ->where(function ($query) {
            $query->where('currency_id', '!=', 2)
                ->orWhereNull('currency_id'); // Incluir currency_id NULL
        })
        ->sum('amount');
        // Calcular suma en dólares (currency_id = 2)
        $sumaDolar = (clone $baseQuery)->where('currency_id', 2)->sum('amount');

        // dd($student_details);

        if($student_details->count() > 0)
        {
            $this->student_id = $student->id;
            $this->provision_detalles = $student_details;

            $this->provisionsCobrar = [];
            $this->checkedProvision = [];
            $this->total_prov_cobrar = 0;

            $this->total_prov = $suma;
            $this->total_prov_dolar = $sumaDolar;
            $this->documento = $student->tutor->document;
            $this->customer_name = $student->tutor->full_name;
            $this->student_name = $student->full_name;

            $this->paid_by = $this->customer_name;

            $this->emit('mostrarModalProvision', 'mostrar modal');
        }else{
            $this->documento = 99999999;
            $this->customer_name = 'Clientes/Proveedores Varios';
            $this->emit('error', 'No existe el Stand o no existe provisiones actuales para dicho Stand');
            $this->provision_detalles = [];
            $this->provisionsCobrar = [];
            $this->checkedProvision = [];
            $this->total_prov_cobrar = 0;
            $this->total_prov = 0;
            $this->total_prov_dolar = 0;

            $this->paid_by = '';
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

        // dd($this->provisionsCobrar, $this->tc);
    }



    public function Add()
    {
        $student = Student::where('document', '=', $this->provision_code)->first();;
        if($this->provision_code){
            if ($student) {
                $this->provisions[] = [
                    'status' => 'false',
                    'description' => '',
                    'type' => 2,
                    'date' => Carbon::now()->format('Y-m'),
                    'date_paid' => Carbon::now(),
                    'amount' => null,
                    'category_id' => 'Elegir',
                    'student_id' => $student->id,
                    'summary_id' => null,
                ];
            } else {
                $this->emit('error', 'Estudiante no encontrado o código errado');
            }

        }else{
            try {
                $this->provisions[] = [
                    'status' => 'false',
                    'description' => null,
                    'type' => 2,
                    'date' => Carbon::now()->format('Y-m'),
                    'amount' => null,
                    'category_id' => 'Elegir',
                    'student_id' => $student->id,
                    'summary_id' => null,
                ];
            } catch (\Throwable $th) {
                //throw $th;
                $this->emit('error', $th);
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
        $this->total_new = collect($this->provisions)->sum('amount');
    }

    public function Save()
    {
        $this->validarProvisions();
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
        $this->categorias = Category::where('id', '!=', 1)->where('type', '=', $this->type)->pluck('id', 'name');
        $this->tipoCambio = new TipoCambioService();
        $this->tc = $this->tipoCambio->getValue($this->date);

    }

    public function render()
    {
        $this->updateTotal();
        return view('livewire.movimientos.crear-movimiento')->extends('adminlte::page');
    }

    public function crearMovimiento()
    {

        $hoy = Carbon::now();

        // dd($this->tc);
        // dd($this->provisions);

        $this->validarFechas();

        // dd($this->validezFecha);
        if($this->validezFecha == false){
            // dd('hola');
            return;
        }else{


            $this->user_id = Auth::id();

            $cliente = Customer::where('document', $this->documento)->first();
            $student = Student::where('document', $this->provision_code)->first();

            // dd($student, $cliente);
            if($cliente != null || $student != null){
                $this->customer_id = $cliente->id;
                $this->student_id = $student->id;
            }else{
                $this->mensaje = 'No existe el Cliente o proveedor, SI ES ESTUDIANTE (CREARLO MEDIANTE EL MODULO DE ESTUDIENTES)';
                $this->emit('error', $this->mensaje);
                return;
            }

            // dd($this->customer_id, $this->partner_id);

            $iva = $this->tax;

            $this->amount = $this->total_prov_cobrar + $this->total_new;
            $str = $this->amount;

            $rules = [
                'documento' => 'required',
                'customer_name' => 'required',
                'date' => 'required|date',
                'type' => 'required',
                'amount' => 'required|numeric|min:0.01',
                'account_id' => 'required',
                'user_id' => 'required',

                'provision_code' => 'required',
            ];

            $messages = [
                'documento.required' => 'El documento es requerido',
                'customer_name.required' => 'El nombre del cliente o proveedor es requerido',
                'date.required' => 'La fecha es requerida',
                'date.date' => 'La fecha debe ser una fecha válida',
                'type.required' => 'El tipo de movimiento es requerido',
                'amount.required' => 'El monto es requerido',
                'amount.numeric' => 'El monto debe ser un valor positivo',
                'amount.min' => 'El monto deberia ser mayor a 0',
                'account_id.required' => 'La cuenta es requerida',
                'user_id.required' => 'El usuario es requerido',

                'provision_code.required' => 'El código es requerido',
            ];
            $this->validate($rules, $messages);

            try {
                $this->validarProvisions();

                $summary = Summary::create([

                    'date' => $this->date,
                    'type'=> $this->type,
                    'status' => $this->status,
                    'amount'=> $str,
                    'tipo_cambio' => $this->tc,
                    'tax'=> $iva,
                    'recipt_series'=> $this->recipt_series,
                    'recipt_number'=> $this->recipt_number,

                    'account_id'=> $this->account_id,
                    'user_id'=>$this->user_id,
                    'future'=>1,
                    'operation_number' => $this->numero_operacion,

                    'paid_by' => $this->paid_by,
                    'observation' => $this->observation,

                    'customer_id' => $this->customer_id,
                    'student_id' => $this->student_id,
                    'student_tutor_id' => $student->tutor->id,
                    'payment_method_id' => $this->payment_method,

                ]);


                if($this->provisions)
                {
                    foreach ($this->provisions as $input) {
                        $unique_code = strval(Carbon::parse($input['date'])->format('Y-m').$input['category_id'].$this->student_id.$summary->id);
                        Detail::create([
                            'unique_code' => $unique_code,
                            'status' => true,
                            'summary_type' => $summary->type,
                            'date' => $input['date'],
                            'description' => $input['description'],
                            'date_paid' => $summary->date,
                            'category_id' => $input['category_id'],
                            'student_id' => $this->student_id,
                            'student_tutor_id' => $this->student->tutor->id,
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
                // dd($summary, $summary->details);
                $this->summary_id = $summary->id;
                $this->emit('movimiento_added', 'El movimiento ha sido registrado exitosamente');

                $this->receipt = $summary->recipt_series .'-'. str_pad($summary->recipt_number, 8, '0', STR_PAD_LEFT);
                $this->recipt_series = $summary->recipt_series;
                $this->recipt_number = $summary->recipt_number;
                $this->showReceiptModal = true;

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

        $limitDateService = new LimitDateService();
        $numberDays = $limitDateService->getIncomeNumberDays();

        if($this->date > $hoy->format('Y-m-d')){

            $this->date = Carbon::now()->format('Y-m-d');
            $this->updatedDate();
            $this->updateTotal();
            $this->emit('error_fecha', 'La fecha no debe ser mayor al día de hoy');
            $this->validezFecha = false;

        }elseif($this->date < $hoy->subDays($numberDays)){

            $this->date = Carbon::now()->format('Y-m-d');
            $this->updatedDate();
            $this->updateTotal();
            $this->emit('error_fecha', 'La fecha solo puede ser menor a '.$numberDays.' dias de la fecha de hoy');
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
            'student_id' => null,
            'student_tutor_id' => null,
        ]);

        $this->resetUI();

        $this->emit('customer_added', 'Cliente o proveedor registrado exitosamente');

        $this->documento = $customer->document;
        $this->customer_name = $customer->full_name;

    }

    public function clearDataApi()
    {
        $this->full_name = '';
        $this->first_name = '';
        $this->last_name = '';
        $this->address = '';
        $this->customer_name = null;
        $this->provisions = [];
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
        $this->customer_name = 'Clientes/Proveedores Varios';
        $this->documento = 99999999;
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

    public function validarSeleccionados()
    {
        $this->det_selected = count($this->checkedProvision);
        // dd($this->det_selected);
    }


}
