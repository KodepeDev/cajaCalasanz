<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    //
    public function categories(){
        return view('admin.categorias.index');
    }

    // public function personas(){
    //     $personas = Customer::select('first_name', 'last_name', 'email', 'dni', 'etapa');
    //     return datatables()->of($personas)->toJson();
    // }
}
