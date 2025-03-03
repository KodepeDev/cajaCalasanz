<?php

namespace App\Http\Livewire\CierreMoviemientos;

use Carbon\Carbon;
use App\Models\Account;
use App\Models\Summary;
use Livewire\Component;
use App\Models\SummaryClose;
use Illuminate\Support\Facades\Auth;

class NewSummaryCloseComponent extends Component
{
    public $current_incomes, $current_expenses, $current_nulled, $current_balance;
    public $prev_incomes, $prev_expenses, $prev_close;
    public $from_date, $to_date;
    public $account_data = [];
    public $type, $year;

    public function mount()
    {
        $this->year = Carbon::now()->format('Y-m');
        $this->type = 'MONTH'; //MONTH - YEAR
        $this->account_data = [];
    }

    public function render()
    {
        // $this->account_data = json_encode($this->account_data);
        return view('livewire.cierre-moviemientos.new-summary-close-component')->extends('adminlte::page');
    }

    public function generar()
    {
        $hoy = Carbon::parse($this->year);

        if ($this->type == 'MONTH') {
            $this->from_date = $hoy->startOfMonth()->format('Y-m-d');
            $this->to_date = $hoy->endOfMonth()->format('Y-m-d');

        } else {
            $this->from_date = $hoy->startOfYear()->format('Y-m-d');
            $this->to_date = $hoy->endOfYear()->format('Y-m-d');
        }

        $this->prev_close = SummaryClose::whereType($this->type)->latest()->first();
        if ($this->prev_close) {
            $this->prev_incomes = $this->prev_close->current_income_balance;
            $this->prev_expenses = $this->prev_close->current_expense_balance;
        }

        $this->current_incomes = Summary::whereType('add')->whereStatus('PAID')->whereBetween('date', [$this->from_date, $this->to_date])->sum('amount');
        $this->current_expenses = Summary::whereType('out')->whereStatus('PAID')->whereBetween('date', [$this->from_date, $this->to_date])->sum('amount');
        $this->current_nulled = Summary::whereStatus('NULLED')->whereBetween('date', [$this->from_date, $this->to_date])->sum('amount');

        $this->current_balance = $this->prev_close ? ($this->prev_close->current_balance + $this->current_incomes - $this->current_expenses) : $this->current_incomes - $this->current_expenses;

        $cuentas = Account::pluck('id', 'account_name');
        $account_data = array();
        foreach ($cuentas as $cuenta => $id ) {
            $account_data[] = [
                'id' => $id,
                'name' => $cuenta,
                'total_income' => Summary::where('account_id', $id)->whereType('add')->whereStatus('PAID')->whereBetween('date', [$this->from_date, $this->to_date])->sum('amount'),
                'total_expense' => Summary::where('account_id', $id)->whereType('out')->whereStatus('PAID')->whereBetween('date', [$this->from_date, $this->to_date])->sum('amount'),
                'total_nulled' => Summary::where('account_id', $id)->whereStatus('NULLED')->whereBetween('date', [$this->from_date, $this->to_date])->sum('amount'),
            ];
        }
        $this->account_data = $account_data;
    }

    public function saveClose()
    {
        $hoy = Carbon::now()->format('Y-m-d');
        $existe = SummaryClose::whereType($this->type)->where('from_date', $this->from_date)->where('to_date', $this->to_date)->get();

        if (count($existe)) {
            $this->emit('error', 'Ya se realizó el cierre para el periodo selecionado');
        }else {
            $rules = [
                'to_date' => 'date|before:'.$hoy,
                'from_date' => 'date|after:'.$this->prev_close->to_date->format('Y-m-d'),
            ];
            $messages = [
                'to_date.date' => 'Debe elegir una fecha valida',
                'to_date.before' => 'Aun no finaliza el periodo que desea generar',
            ];

            try {
                $this->validate($rules, $messages);

                $summaryClose = SummaryClose::create([
                    'type' => $this->type,
                    'from_date' => $this->from_date,
                    'to_date' => $this->to_date,
                    'previous_balance' => $this->prev_close ? $this->prev_close->current_balance : 0,
                    'previous_income_balance' => $this->prev_incomes,
                    'previous_expense_balance' => $this->prev_expenses,
                    'current_balance' => $this->current_balance,
                    'current_income_balance' => $this->current_incomes,
                    'current_expense_balance' => $this->current_expenses,
                    'account_balance' => json_encode($this->account_data),
                    'current_nulled' => $this->current_nulled,
                    'generated_by' => Auth::user()->full_name,
                ]);

                $this->emit('closeGenerated', 'El cierre fue generado satifactoriamente');
                return redirect()->route('closes.index');
            } catch (\Throwable $th) {
                //throw $th;
                $this->emit('error', $th->getMessage());
            }
        }
    }
}
