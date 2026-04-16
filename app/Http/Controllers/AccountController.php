<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = Account::withCount('summaries')
            ->orderBy('account_name')
            ->get();

        return view('admin.cuentas.cuenta', compact('accounts'));
    }

    public function create()
    {
        return view('admin.cuentas.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:200',
            'number'    => 'nullable|string|max:50',
            'type'      => 'required|in:corriente,ahorro,caja',
            'add_serie' => 'required|size:4|unique:accounts,add_serie|alpha_num',
            'out_serie' => 'required|size:4|unique:accounts,out_serie|alpha_num',
        ], [
            'add_serie.size'   => 'La serie de ingreso debe tener exactamente 4 caracteres.',
            'add_serie.unique' => 'Esta serie de ingreso ya está en uso.',
            'out_serie.size'   => 'La serie de gasto debe tener exactamente 4 caracteres.',
            'out_serie.unique' => 'Esta serie de gasto ya está en uso.',
        ]);

        Account::create([
            'account_name'   => $data['name'],
            'account_number' => $data['number'] ?? null,
            'account_type'   => $data['type'],
            'add_serie'      => strtoupper($data['add_serie']),
            'out_serie'      => strtoupper($data['out_serie']),
        ]);

        return redirect()->route('account.index')
            ->with('success', 'Cuenta creada correctamente.');
    }

    public function edit(int $id)
    {
        $account = Account::findOrFail($id);

        return view('admin.cuentas.edit', compact('account'));
    }

    public function update(Request $request, int $id)
    {
        $account = Account::findOrFail($id);

        $data = $request->validate([
            'name'      => 'required|string|max:200',
            'number'    => 'nullable|string|max:50',
            'type'      => 'required|in:corriente,ahorro,caja',
            'add_serie' => "required|size:4|unique:accounts,add_serie,{$id}|alpha_num",
            'out_serie' => "required|size:4|unique:accounts,out_serie,{$id}|alpha_num",
        ], [
            'add_serie.size'   => 'La serie de ingreso debe tener exactamente 4 caracteres.',
            'add_serie.unique' => 'Esta serie de ingreso ya está en uso.',
            'out_serie.size'   => 'La serie de gasto debe tener exactamente 4 caracteres.',
            'out_serie.unique' => 'Esta serie de gasto ya está en uso.',
        ]);

        $account->update([
            'account_name'   => $data['name'],
            'account_number' => $data['number'] ?? null,
            'account_type'   => $data['type'],
            'add_serie'      => strtoupper($data['add_serie']),
            'out_serie'      => strtoupper($data['out_serie']),
        ]);

        return redirect()->route('account.index')
            ->with('success', 'Cuenta actualizada correctamente.');
    }

    public function destroy(int $id)
    {
        $account = Account::withCount('summaries')->findOrFail($id);

        if ($account->summaries_count > 0) {
            return redirect()->route('account.index')
                ->with('error', 'No se puede eliminar: la cuenta tiene movimientos asociados.');
        }

        $account->delete();

        return redirect()->route('account.index')
            ->with('success', 'Cuenta eliminada correctamente.');
    }
}
