<?php

namespace App\Http\Livewire\Usuarios;

use App\Exports\Usuarios\UserDetallesExport;
use App\Exports\Usuarios\UserRecibosExport;
use App\Models\Account;
use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class UserReciptsComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $first_day, $last_day, $type, $account_id;

    public $start, $finish, $tipo;
    public $accounts;

    public function mount()
    {
        $this->first_day = Carbon::now()->firstOfMonth();
        $this->last_day = Carbon::now()->endOfMonth();
        $this->start = $this->first_day->format('Y-m-d');
        $this->finish = $this->last_day->format('Y-m-d');
        $this->accounts = Account::pluck('id', 'account_name');

        // dd($this->first_day);
    }

    public function render()
    {
        $user = Auth::user();

        $summaries = $user->misRecibos($this->first_day, $this->last_day, $this->type, $this->account_id)->paginate(20);
        $totalFinal = $user->misRecibos($this->first_day, $this->last_day, $this->type, $this->account_id)->whereStatus('PAID')->whereType('add')->sum('amount') - $user->misRecibos($this->first_day, $this->last_day, $this->type, $this->account_id)->whereStatus('PAID')->whereType('out')->sum('amount');

        return view('livewire.usuarios.user-recipts-component', compact('summaries', 'totalFinal'));
    }


    public function EportarRecibos()
    {
        return (new UserRecibosExport($this->first_day, $this->last_day, $this->type, $this->account_id))->download('Reporte_cobranza_recibos_de_'.$this->first_day.'_al_'.$this->last_day.''.'.xlsx');
    }

    public function ExportarDetallado()
    {
        return (new UserDetallesExport($this->first_day, $this->last_day, $this->type, $this->account_id))->download('Reporte_cobranza_detallado_de_'.$this->first_day.'_al_'.$this->last_day.''.'.xlsx');
    }


    public function Filter()
    {
        if($this->validarFechas()){
            $this->first_day = $this->start;
            $this->last_day = $this->finish;
            $this->type = $this->tipo;
            $this->resetPage();
        }
    }
    public function clearFilter()
    {
        $this->first_day = Carbon::now()->firstOfMonth();
        $this->last_day = Carbon::now()->endOfMonth();
        $this->start = $this->first_day->format('Y-m-d');
        $this->finish = $this->last_day->format('Y-m-d');
        $this->type = null;
        $this->tipo = null;
        $this->resetPage();
    }

    public function validarFechas()
    {
        try {
            $rules = [
                'start' => 'required|date',
                'finish' => 'required|date',
            ];
            $messages = [
                'start.required' => 'La fecha de inicio es requerida',
                'start.date' => 'Debe elegir una fecha válida',
                'finish.required' => 'La fecha de final es requerida',
                'finish.date' => 'Debe elegir una fecha válida',
            ];
            $this->validate($rules, $messages);

            $inicio = Carbon::parse($this->start);
            $fin = Carbon::parse($this->finish);

            $y1 = Carbon::parse($this->start)->format('Y');
            $y2 = Carbon::parse($this->finish)->format('Y');

            if (($inicio->month !== $fin->month) | ($y1 !== $y2)){
                $this->emit('error', 'El rango de fechas debe ser del mismo mes.');
                return false;
            }else {
                return true;
            }
        } catch (\Throwable $th) {
            //throw $th;
            $this->emit('error', $th->getMessage());
        }
    }
}
