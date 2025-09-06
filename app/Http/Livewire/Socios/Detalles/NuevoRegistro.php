<?php

namespace App\Http\Livewire\Socios\Detalles;

use Carbon\Carbon;
use App\Models\Stand;
use App\Models\Account;
use App\Models\Summary;
use Livewire\Component;
use App\Models\Bitacora;
use App\Models\Category;
use App\Models\Customer;
use App\Models\AttrValue;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiConsultasController;
use App\Models\Currency;
use App\Models\Detail;
use App\Models\Student;

class NuevoRegistro extends Component
{
    public $cuentas, $paymentMethods, $categorias, $selected_id, $validezFecha, $status, $receipt, $summary_id, $unique_code;

    public $date, $concept, $type, $amount, $tax, $recipt_series,$puesto, $recipt_number, $future, $account_id, $category_id, $subcategoria_id, $payment_method, $numero_operacion, $user_id, $customer_id, $student_id, $student_tutor_id;


    public $documento, $customer_name;
    public $currency, $currency_id;

    public $document_type, $document, $full_name, $first_name, $last_name, $address, $email, $phone, $mensaje;


    protected $listeners = ['editMovimiento'];

    // public function showReceiptModal($event)
    // {
    //     $this->showReceiptModal = true;
    // }

    public function mount(Student $student)
    {
        $this->student_id = $student->id;

        // dd($student->tutor);
        $customer = Customer::whereDocument($student->tutor->document)->first();
        // dd($customer);
        $this->currency = Currency::pluck('id', 'name');
        $this->currency_id = 1;
        $this->document_type = $customer->document_type;
        $this->documento = $customer->document;
        $this->customer_id = $customer->id;
        $this->student_tutor_id = $student->tutor->id;
        $this->customer_name = $customer->full_name;

        $this->date = Carbon::now()->format('Y-m-d');

        $this->validezFecha = true;

        $this->selected_id = 0;

        $this->type = 'add';
        $this->status = false;

        $this->payment_method = 1;

        $this->tax = 0;
        $this->cuentas = Account::all();
        $this->paymentMethods = PaymentMethod::all();
        $this->categorias = Category::whereType($this->type)->get();
    }


    public function render()
    {
        return view('livewire.socios.detalles.nuevo-registro');
    }

    public function crearMovimiento()
    {

        $hoy = Carbon::now();

        // $this->validarFechas();

        // dd($this->validezFecha);
        if($this->validezFecha == false){
            // dd('hola');
            return;
        }else{

            $this->user_id = Auth::id();

            $str = $this->amount;
            $iva = $this->tax;

            $rules = [
                'documento' => 'required',
                'customer_name' => 'required',
                'date' => 'required|date',
                'concept' => 'required|min:5|max:255',
                'type' => 'required',
                'amount' => 'required|numeric|min:0.01',
                'category_id' => 'required',
                'user_id' => 'required',
            ];

            $messages = [
                'documento.required' => 'El documento es requerido',
                'customer_name.required' => 'El nombre del cliente o proveedor es requerido',
                'date.required' => 'La fecha es requerida',
                'date.date' => 'La fecha debe ser una fecha válida',
                'concept.required' => 'El concepto del movimiento es requerido',
                'concept.min' => 'El concepto debe tenero como mínimo 5 caracteres',
                'concept.max' => 'El concepto debe tenero como máximo 255 caracteres',
                'type.required' => 'El tipo de movimiento es requerido',
                'amount.required' => 'El monto es requerido',
                'amount.numeric' => 'El monto debe ser un valor positivo',
                'amount.min' => 'El monto deberia ser como mínimo 0',
                'category_id.required' => 'La categoría es requerida',
                'user_id.required' => 'El usuario es requerido',
            ];

            $this->validate($rules, $messages);

            $this->unique_code = strval(Carbon::parse($this->date)->format('Y-m').str_pad($this->category_id, 4, "0", STR_PAD_LEFT).str_pad($this->student_id, 6, "0", STR_PAD_LEFT));
            $detail = Detail::where('unique_code', $this->unique_code)->first();

            if(!$detail)
            {
                $summary = Detail::create([
                    'unique_code' => $this->unique_code,
                    'date' => $this->date,
                    'description'=>  $this->concept,
                    'summary_type'=> $this->type,
                    'type'=> 2,
                    'status' => $this->status,
                    'amount'=> $str,
                    'category_id'=>$this->category_id,
                    'student_id' => $this->student_id,
                    'student_tutor_id' => $this->student_tutor_id,
                    'currency_id' => $this->currency_id,

                ]);
                $this->emit('movimiento_added', 'El movimiento ha sido registrado exitosamente');
            }else {
                $this->emit('error', 'Ya existe un registro similar al registro que desea agregar o provisionar');
            }



            $this->resetUI();
            $this->emit('render', 'render');

        }
    }

    public function editMovimiento($id)
    {
        // dd($id);
        $this->summary_id = $id;
        $this->selected_id = $id;

        $summary = Detail::find($this->summary_id);


        $this->date = $summary->date->format('Y-m-d');
        $this->concept = $summary->description;
        $this->type = $summary->summary_type;
        $this->status = $summary->status;
        $this->amount = $summary->amount;
        $this->category_id = $summary->category_id;

        $student = Student::where('id', '=', $summary->student_id)->first();
        $customer = Customer::whereDocument($student->tutor->document)->first();
        // dd($customer);

        $this->document_type = $customer->document_type;
        $this->document = $customer->document;
        $this->customer_id = $customer->id;
        $this->full_name = $customer->full_name;

        $this->documento = $customer->document;
        $this->customer_name = $customer->full_name;

        $this->currency_id = $summary->currency_id;

        $this->categorias = Category::whereType($this->type)->get();;

        $this->emit('show_modal');
    }

    public function updateMovimiento()
    {

        $hoy = Carbon::now();

        if($this->validezFecha == true){

            $this->user_id = Auth::id();

            $str = $this->amount;
            $iva = $this->tax;

            $rules = [
                'documento' => 'required',
                'customer_name' => 'required',
                'date' => 'required|date',
                'concept' => 'required|min:5|max:255',
                'type' => 'required',
                'amount' => 'required|numeric|min:0',
                'category_id' => 'required',
            ];

            $messages = [
                'documento.required' => 'El documento es requerido',
                'customer_name.required' => 'El nombre del cliente o proveedor es requerido',
                'date.required' => 'La fecha es requerida',
                'date.date' => 'La fecha debe ser una fecha válida',
                'concept.required' => 'El concepto del movimiento es requerido',
                'concept.min' => 'El concepto debe tenero como mínimo 5 caracteres',
                'concept.max' => 'El concepto debe tenero como máximo 255 caracteres',
                'type.required' => 'El tipo de movimiento es requerido',
                'amount.required' => 'El monto es requerido',
                'amount.numeric' => 'El monto debe ser un valor positivo',
                'amount.min' => 'El monto deberia ser como mínimo 0',
                'category_id.required' => 'La categoría es requerida',
            ];

            $this->validate($rules, $messages);

            $this->unique_code = strval(Carbon::parse($this->date)->format('Y-m').str_pad($this->category_id, 4, "0", STR_PAD_LEFT).str_pad($this->student_id, 6, "0", STR_PAD_LEFT));
            $detail = Detail::where('id', '!=', $this->summary_id )->where('unique_code', $this->unique_code)->first();

            $summary = Detail::find($this->summary_id);
            // dd($summary);
            if (!$detail) {
                $summary->update([

                    'unique_code' => $this->unique_code,
                    'date' => $this->date,
                    'description'=>  $this->concept,
                    'summary_type'=> $this->type,
                    'type'=> 2,
                    'status' => $this->status,
                    'amount'=> $str,

                    'category_id'=>$this->category_id,
                    'student_id' => $this->student_id,
                    'currency_id' => $this->currency_id,

                ]);
                $this->emit('movimiento_actualizado', 'El movimiento se ha actualizado con éxito');
            } else {
                $this->emit('error', 'Ya existe un registro similar al registro que desea actualizar');
            }

        }


        $this->resetUI();
        $this->emit('render', 'render');
    }

    public function categoryType()
    {
        $this->category_id = '';
        $this->subcategoria_id = '';

        if($this->type !== null){
            $this->categorias = Category::where('type', '=', $this->type)->get();
        }
    }


    public function validarFechas()
    {
        $hoy = Carbon::now();

        if($this->date > $hoy->format('Y-m-d')){

            $this->date = Carbon::now()->format('Y-m-d');
            $this->emit('error_fecha', 'La fecha no debe ser mayor al día de hoy');
            $this->validezFecha = false;

        }elseif($this->date < $hoy->subDays(3)){

            $this->date = Carbon::now()->format('Y-m-d');
            $this->emit('error_fecha', 'La fecha solo puede ser menor a 3 dias de la fecha de hoy');
            $this->validezFecha = false;
        }else {
            $this->validezFecha = true;
        }
    }

    public function resetUI()
    {
        $this->selected_id = 0;
        $this->resetValidation();
        $this->validezFecha = true;
        $this->date = Carbon::now()->format('Y-m-d');
        $this->concept = '';
        $this->category_id = null;
        $this->amount = 0;
        $this->currency_id = 1;
        $this->emit('close_modal', 'close modal');

    }
}
