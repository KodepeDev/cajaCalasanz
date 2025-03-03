<?php

namespace App\Http\Livewire\Movimientos;

use COM;
use Carbon\Carbon;
use App\Models\Account;
use App\Models\Summary;
use Livewire\Component;
use App\Models\Bitacora;
use App\Models\Category;
use App\Models\Customer;
use App\Models\AttrValue;
use App\Models\Detail;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;
use JeroenNoten\LaravelAdminLte\View\Components\Widget\Card;

class EditarMovimiento extends Component
{
    public $summary_id;
    public $cuentas, $paymentMethods, $categorias, $subcategorias, $componentName, $selected_id, $validezFecha;

    public $date, $originalDate, $concept, $type, $status, $amount, $tax, $recipt_series,$puesto, $recipt_number, $future, $account_id, $category_id, $total, $payment_method, $numero_operacion, $section_type, $paid_by, $observation, $user_id, $customer_id, $partner_id, $sublet_id;

    protected $dataApi=[];
    public $detalles;

    public $tc = 3.735;

    protected $listeners = [
        'resetUI',
        'resetUiApi',
    ];

    public $documento, $customer_name;

    public $document_type, $document, $full_name, $first_name, $last_name, $address, $email, $phone, $mensaje;

    public function mount($id)
    {
        $this->summary_id = $id;
        $summary = Summary::find($this->summary_id);
        if ($summary->status == 'NULLED') {
            return redirect()->route('movimientos.listado');
        } else {
            $this->selected_id = $id;
        $this->validezFecha = true;

        $this->detalles = Detail::where('summary_id', $id)->get();
        $this->total = $summary->amount;

        $this->date = $summary->date->format('Y-m-d');
        $this->originalDate = $summary->created_at->format('Y-m-d');
        $this->concept = $summary->concept;
        $this->type = $summary->type;
        $this->status = $summary->status;
        $this->amount = $summary->amount;
        $this->recipt_series = $summary->recipt_series;
        $this->recipt_number = $summary->recipt_number;
        $this->paid_by = $summary->paid_by;
        $this->observation = $summary->observation;
        $this->future = $summary->future;
        $this->numero_operacion = $summary->operation_number;
        $this->section_type = $summary->section_type;
        $this->account_id = $summary->account_id;
        $this->payment_method = $summary->payment_method_id;

        $customer = Customer::where('id', '=', $summary->customer_id)->first();

        $this->document_type = $customer->document_type;
        $this->document = $customer->document;
        $this->full_name = $customer->full_name;

        $this->documento = $customer->document;
        $this->customer_name = $customer->full_name;

        $this->categorias = Category::all();
        $this->cuentas = Account::all();
        $this->paymentMethods = PaymentMethod::all();
        }
    }

    public function render()
    {

        return view('livewire.movimientos.editar-movimiento')->extends('adminlte::page');
    }

    public function updateMovimiento()
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

            if($cliente != null){
                $this->customer_id = $cliente->id;
                $this->partner_id = $cliente->partner_id;
            }else{
                $this->mensaje = 'No existe el Cliente o proveedor, SI ES SOCIO (CREARLO MEDIANTE EL MODULO DE SOCIOS)';
                $this->emit('error', $this->mensaje);
                return;
            }

            // dd($this->customer_id, $this->partner_id);

            $str = $this->amount;
            $iva = $this->tax;

            $rules = [
                'documento' => 'required',
                'customer_name' => 'required',
                'date' => 'required|date',
                'type' => 'required',
                'amount' => 'required|numeric|min:0',
                'account_id' => 'required',
            ];

            $messages = [
                'documento.required' => 'El documento es requerido',
                'customer_name.required' => 'El nombre del cliente o proveedor es requerido',
                'date.required' => 'La fecha es requerida',
                'date.date' => 'La fecha debe ser una fecha válida',

                'type.required' => 'El tipo de movimiento es requerido',
                'amount.required' => 'El monto es requerido',
                'amount.numeric' => 'El monto debe ser un valor positivo',
                'amount.min' => 'El monto deberia ser como mínimo 0',

                'account_id.required' => 'La cuenta es requerida',
            ];

            $this->validate($rules, $messages);

            $summary = Summary::find($this->summary_id);
            // dd($summary);
            $summary->update([

                // 'date' => $this->date,
                // 'type'=> $this->type,
                'status'=>$this->status,
                'amount'=> $str,

                // 'account_id'=> $this->account_id,
                'user_id'=>$this->user_id,
                // 'future'=>1,
                'operation_number' => $this->numero_operacion,

                // 'section_type'=> $this->section_type,

                // 'paid_by' => $this->paid_by,
                'observation' => $this->observation,

                'customer_id' => $this->customer_id,
                'partner_id' => $this->partner_id,
                'sublet_id' => null,
                // 'payment_method_id' => $this->payment_method,

            ]);
            foreach($summary->details as $item){
                $item->update([
                    'date_paid' => $summary->date,
                ]);
            }

            // dd($summary->details);

            $bitacora = Bitacora::create([
                'type' => 'update',
                'activity' => "El usuario ha actualizado el movimiento: $this->recipt_series $this->recipt_number",
                'activity_id' => $summary->id,
                'user_id' => Auth::user()->id,
            ]);
        }


        $this->emit('movimiento_actualizado', 'El movimiento se ha actualizado con éxito');

        return redirect()->route('movimientos.ver', $summary->id);
    }

    public function validarFechas()
    {
        $hoy = Carbon::now();
        $date = Carbon::parse($this->originalDate);

        // dd($hoy->subDays(3));

        if($this->date > $date->format('Y-m-d')){

            $this->emit('error_fecha', 'La fecha no debe ser mayor a la fecha original');
            $this->validezFecha = false;

        }elseif($this->date < $date->subDays(3)){

            $this->emit('error_fecha', 'La fecha solo puede ser menor a 3 dias de la fecha de fecha original');
            $this->validezFecha = false;

        }else{
            $this->validezFecha = true;
        }
    }

    // public function categoryType()
    // {
    //     $this->category_id = '';
    //     $this->subcategoria_id = '';

    //     if($this->type !== null){
    //         $this->categorias = Category::where('type', '=', $this->type)->get();
    //     }
    // }

    // public function changeCategory()
    // {
    //     $this->subcategoria_id = null;
    //     $this->subcategorias = AttrValue::where('category_id', $this->category_id)->get();

    //     if($this->subcategorias->count() > 0){
    //         $this->emit('tiene_subcategorias', 'Hay subcategorias');
    //     }

    // }

    public function chageDocumentType()
    {
        $this->full_name = '';
        $this->first_name = '';
        $this->last_name = '';
        $this->document = '';
        $this->address = '';
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
        $this->puesto = '';
        $this->numero_operacion = '';
        $this->payment_method = 1;
        $this->phone = '';
        $this->address = '';
        $this->customer_name = 'Clientes/Proveedores Varios';
        $this->documento = 99999999;
        $this->resetValidation();
    }
}
