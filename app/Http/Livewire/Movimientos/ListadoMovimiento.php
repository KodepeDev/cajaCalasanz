<?php

namespace App\Http\Livewire\Movimientos;

use Carbon\Carbon;
use App\Models\Account;
use App\Models\Summary;
use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;

class ListadoMovimiento extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $accounts;
    public string $hoy = '';

    // Applied filter values (drive the query)
    public $start, $finish, $cuenta_id, $categoria_id, $tipo, $documento;

    // Pending filter values (bound to inputs, applied on filter())
    public $start1, $finish1, $cuenta_id1, $categoria_id1, $tipo1, $documento1;

    // Computed totals
    public float $totalIngresos   = 0;
    public float $totalEgresos    = 0;
    public float $totalFinal      = 0;
    public float $totalIngresosTx = 0;
    public float $totalEgresosTx  = 0;

    protected $queryString = [
        'start1'        => ['except' => ''],
        'finish1'       => ['except' => ''],
        'categoria_id1' => ['except' => ''],
        'cuenta_id1'    => ['except' => ''],
        'tipo1'         => ['except' => ''],
        'documento1'    => ['except' => ''],
        'page'          => ['except' => 1, 'as' => 'p'],
    ];

    public function mount(): void
    {
        $this->accounts = Account::all();
        $this->hoy = Carbon::now()->toDateString();

        // Respect URL query params on initial load
        $this->start  = $this->start1  ?: $this->hoy;
        $this->finish = $this->finish1 ?: $this->hoy;
        $this->start1  = $this->start;
        $this->finish1 = $this->finish;

        $this->tipo         = $this->tipo1        ?? '';
        $this->cuenta_id    = $this->cuenta_id1   ?? '';
        $this->categoria_id = $this->categoria_id1 ?? '';
        $this->documento    = $this->documento1   ?? '';
    }

    public function render()
    {
        $baseQuery = $this->buildQuery();

        // Single aggregate query — avoids loading the full collection into memory
        $totals = (clone $baseQuery)
            ->where('status', 'PAID')
            ->selectRaw("
                COALESCE(SUM(CASE WHEN type = 'add' THEN amount ELSE 0 END), 0) as total_ingresos,
                COALESCE(SUM(CASE WHEN type = 'out' THEN amount ELSE 0 END), 0) as total_egresos,
                COALESCE(SUM(CASE WHEN type = 'add' THEN tax    ELSE 0 END), 0) as total_ingresos_tx,
                COALESCE(SUM(CASE WHEN type = 'out' THEN tax    ELSE 0 END), 0) as total_egresos_tx
            ")
            ->first();

        $this->totalIngresos   = (float) ($totals->total_ingresos    ?? 0);
        $this->totalEgresos    = (float) ($totals->total_egresos     ?? 0);
        $this->totalIngresosTx = (float) ($totals->total_ingresos_tx ?? 0);
        $this->totalEgresosTx  = (float) ($totals->total_egresos_tx  ?? 0);
        $this->totalFinal      = $this->totalIngresos - $this->totalEgresos;

        $movimientos = $baseQuery->with(['customer', 'account', 'student'])->paginate(15);

        return view('livewire.movimientos.listado-movimiento', [
            'summaries' => $movimientos,
        ])->extends('adminlte::page');
    }

    private function buildQuery()
    {
        $query = Summary::query()
            ->where('future', 1)
            ->whereDate('date', '<=', $this->hoy);

        if ($this->tipo) {
            $query->where('type', $this->tipo);
        }

        if ($this->cuenta_id) {
            $query->where('account_id', $this->cuenta_id);
        }

        if ($this->categoria_id) {
            $query->where('category_id', $this->categoria_id);
        }

        if ($this->documento) {
            $customer = Customer::where('document', $this->documento)->first();
            if ($customer) {
                $query->where('customer_id', $customer->id);
            } else {
                $this->emit('error', "No existe ningún registro con el documento: {$this->documento}");
            }
        }

        if ($this->start && $this->finish) {
            $query->whereBetween('date', [
                Carbon::parse($this->start)->toDateString(),
                Carbon::parse($this->finish)->toDateString(),
            ]);
        } else {
            $query->whereDate('date', $this->hoy);
        }

        return $query->latest('date');
    }

    public function filter(): void
    {
        if (!$this->validarFechas()) {
            return;
        }

        $this->start        = $this->start1;
        $this->finish       = $this->finish1;
        $this->cuenta_id    = $this->cuenta_id1;
        $this->categoria_id = $this->categoria_id1;
        $this->documento    = $this->documento1;
        $this->tipo         = $this->tipo1;

        $this->resetPage();
    }

    public function clearFilter(): void
    {
        $this->start = $this->finish = $this->start1 = $this->finish1 = $this->hoy;
        $this->cuenta_id    = $this->cuenta_id1    = '';
        $this->categoria_id = $this->categoria_id1 = '';
        $this->tipo         = $this->tipo1         = '';
        $this->documento    = $this->documento1    = '';

        $this->resetPage();
    }

    private function validarFechas(): bool
    {
        $inicio = Carbon::parse($this->start1);
        $fin    = Carbon::parse($this->finish1);

        if ($inicio->month !== $fin->month || $inicio->year !== $fin->year) {
            $this->emit('error', 'El rango de fechas debe ser del mismo mes.');
            return false;
        }

        return true;
    }

    public function anular(int $id): void
    {
        $this->emitTo('movimientos.anular-movimiento', 'Anular', $id);
    }
}
