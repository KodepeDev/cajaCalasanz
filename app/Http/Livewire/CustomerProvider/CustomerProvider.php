<?php

namespace App\Http\Livewire\CustomerProvider;

use App\Http\Controllers\ApiConsultasController;
use Livewire\Component;
use App\Models\Customer;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

use function PHPSTORM_META\elementType;

class CustomerProvider extends Component
{
    public $full_name, $document_type,$document, $first_name, $last_name, $email, $phone, $address, $photo, $etapa, $is_ative, $is_client, $is_suplier, $partner_id, $sublet_id;
    public $selected_id, $componentName, $mensaje;
    protected $dataApi = [];

    public function mount()
    {
        $this->componentName = 'Cliente/Proveedor';
        $this->etapa = 1;
        $this->document_type = 6;
        $this->selected_id = 0;
    }
    public function render()
    {
        return view('livewire.customer-provider.customer-provider')->extends('adminlte::page');
    }

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
            'photo' => 'max:2048',

        ];

        $messages = [
            'full_name.required' => 'El nombre del cliente o proveedor es requerido',
            'document_type.required' => 'El tipo de documento es requerido',
            'document.required' => 'El documento es requerido',
            'photo.max' => 'El archivo de foto debe ser menos a 2048 mb',
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
            'etapa' => $this->etapa,
            'is_ative' =>true,
            'is_client' => true,
            'is_suplier' => true,
            'partner_id' => null,
            'sublet_id' => null,
        ]);

        if($this->photo)
        {
            $customFileName = uniqid(). '_.' .$this->photo->extension();
            $this->photo->storeAs('public/customers/', $customFileName);

            $customer->photo = $customFileName;
            $customer->save();
        }

        $this->resetUI();

        $this->emit('customer_added', 'Cliente o proveedor registrado exitosamente');

    }

    public function chageDocumentType()
    {
        $this->full_name = '';
        $this->first_name = '';
        $this->last_name = '';
        $this->document = '';
        $this->address = '';

    }
   public function clearDataApi()
   {
    $this->full_name = '';
    $this->first_name = '';
    $this->last_name = '';
    $this->address = '';
   }


    public function ConsutasApi()
    {

        sleep(1);

        $cust = Customer::where('document', $this->document)->get();

        if($cust->count() >= '1'){
                $this->mensaje = 'Ya existe el Cliente o proveedor';
                $this->emit('error', $this->mensaje);
        }else{

            if($this->document_type == '1'){

                $this->dataApi = (new ApiConsultasController)->apiDni($dni = $this->document);

            }elseif($this->document_type == '6'){

                $this->dataApi = (new ApiConsultasController)->apiRuc($ruc = $this->document);

            }else {

                $this->mensaje = "no es un documento";

            }

            if(isset($this->dataApi->error)){

                $this->mensaje = $this->dataApi->error;
                $this->emit('error', $this->mensaje);
                return;

            }else{
                if($this->document_type != 0){
                    switch ($this->dataApi->tipoDocumento) {
                        case '1':
                            $this->full_name = $this->dataApi->nombre;
                            $this->first_name = $this->dataApi->nombres;
                            $this->last_name = $this->dataApi->apellidoPaterno . ' '. $this->dataApi->apellidoMaterno;
                            $this->document_type = $this->dataApi->tipoDocumento;
                            $this->document = $this->dataApi->numeroDocumento;
                            $this->address = ''.$this->dataApi->viaTipo. ' ' . $this->dataApi->viaNombre . ' ' . $this->dataApi->numero. ' - '. $this->dataApi->zonaCodigo. ' ' . $this->dataApi->zonaTipo. ' - ' . $this->dataApi->departamento. ' - '. $this->dataApi->provincia. ' - '.$this->dataApi->distrito.'"';
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
                }else{
                    $this->mensaje = "Ingrese los datos manualmente.";
                    $this->emit('error', $this->mensaje);
                }
            }

        }


        // dd($this->dataApi);

    }









    public function datatable(Request $request)
    {
        if ($request->ajax()) {

            $customers = Customer::all();

            return Datatables::of($customers)->make(true);
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
        $this->photo = '';
        $this->etapa = '';
        $this->is_ative = '';
        $this->is_client = '';
        $this->is_suplier = '';
        $this->partner_id = '';
        $this->sublet_id = '';
        $this->address = '';
    }
}
