<?php

namespace App\Http\Livewire\Movimientos\ClaseMovimientos;

use App\Models\Customer;
use Livewire\Component;
use App\Http\Controllers\ApiConsultasController;

class CrearClienteModal extends Component
{
    public string $componentName = 'Cliente / Proveedor';
    public int $selected_id = 0;

    // ── Form fields ───────────────────────────────────────────────────────────
    public $document_type = 1;
    public $document      = '';
    public $full_name     = '';
    public $first_name    = '';
    public $last_name     = '';
    public $address       = '';
    public $email         = '';
    public $phone         = '';

    protected $listeners = ['resetUI', 'resetUiApi'];

    // ── Lifecycle ─────────────────────────────────────────────────────────────

    public function render()
    {
        return view('livewire.movimientos.clase-movimientos.crear-cliente-modal');
    }

    // ── API lookup ────────────────────────────────────────────────────────────

    public function changeDocumentType(): void
    {
        $this->full_name  = '';
        $this->first_name = '';
        $this->last_name  = '';
        $this->document   = '';
        $this->address    = '';
        $this->resetValidation();
    }

    public function clearDataApi(): void
    {
        $this->full_name  = '';
        $this->first_name = '';
        $this->last_name  = '';
        $this->address    = '';
        $this->resetValidation();
    }

    public function consultasApi(): void
    {
        $existing = Customer::where('document', $this->document)->first();

        if ($existing) {
            $this->emit('registro-existente', 'Ya existe el cliente o proveedor.');
            $this->emit('customerCreated', $existing->id, $existing->document, $existing->full_name);
            return;
        }

        $api = new ApiConsultasController();

        if ($this->document_type == '1') {
            $dataApi = $api->apiDni($this->document);
        } elseif ($this->document_type == '6') {
            $dataApi = $api->apiRuc($this->document);
        } else {
            $this->emit('error', 'Seleccione un tipo de documento válido (DNI o RUC).');
            return;
        }

        if (isset($dataApi['error'])) {
            $this->emit('error', $dataApi['error']);
            return;
        }

        if ($dataApi === null) {
            $this->emit('error', 'No se encontró el documento. Intente ingresarlo manualmente con la opción "Otro".');
            return;
        }

        $this->document_type = $dataApi['tipoDocumento'];
        $this->document      = $dataApi['numeroDocumento'];
        $this->full_name     = $dataApi['nombre'];
        $this->address       = $this->buildAddressFromApi($dataApi);

        if ($dataApi['tipoDocumento'] === '1') {
            $this->first_name = $dataApi['nombres'];
            $this->last_name  = $dataApi['apellidoPaterno'] . ' ' . $dataApi['apellidoMaterno'];
        } else {
            $this->first_name = '';
            $this->last_name  = '';
        }
    }

    // ── Save ──────────────────────────────────────────────────────────────────

    public function create(): void
    {
        if ($this->document_type == 0) {
            $this->full_name = trim("{$this->first_name} {$this->last_name}");
        }

        $this->validate(
            [
                'full_name'     => 'required',
                'document_type' => 'required',
                'document'      => 'required|unique:customers',
            ],
            [
                'full_name.required'     => 'El nombre del cliente o proveedor es requerido.',
                'document_type.required' => 'El tipo de documento es requerido.',
                'document.required'      => 'El documento es requerido.',
                'document.unique'        => 'Ya existe un cliente con ese documento.',
            ]
        );

        $customer = Customer::create([
            'full_name'        => $this->full_name,
            'first_name'       => $this->first_name,
            'last_name'        => $this->last_name,
            'email'            => $this->email,
            'document_type'    => $this->document_type,
            'document'         => $this->document,
            'phone'            => $this->phone,
            'address'          => $this->address,
            'etapa'            => 1,
            'is_ative'         => true,
            'is_client'        => true,
            'is_tutor'         => false,
            'student_tutor_id' => null,
            'student_id'       => null,
        ]);

        $this->resetUI();

        // Notify parent component with the new customer data
        $this->emit('customerCreated', $customer->id, $customer->document, $customer->full_name);
        $this->emit('customer_added', 'Cliente o proveedor registrado exitosamente.');
    }

    // ── Reset ─────────────────────────────────────────────────────────────────

    public function resetUI(): void
    {
        $this->selected_id   = 0;
        $this->document_type = 1;
        $this->document      = '';
        $this->full_name     = '';
        $this->first_name    = '';
        $this->last_name     = '';
        $this->address       = '';
        $this->email         = '';
        $this->phone         = '';
        $this->resetValidation();
    }

    public function resetUiApi(): void
    {
        $this->resetUI();
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    private function buildAddressFromApi(array $data): string
    {
        return trim(implode(' ', [
            $data['viaTipo'],
            $data['viaNombre'],
            $data['numero'],
            '-',
            $data['zonaCodigo'],
            $data['zonaTipo'],
            '-',
            $data['departamento'],
            '-',
            $data['provincia'],
            '-',
            $data['distrito'],
        ]));
    }
}
