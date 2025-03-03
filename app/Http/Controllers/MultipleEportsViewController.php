<?php

namespace App\Http\Controllers;

use App\Exports\StandsExport;
use App\Models\Stand;
use App\Models\Detail;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class MultipleEportsViewController extends Controller
{
    //

    public function index()
    {
        return view('admin.paginas.vista-reports');
    }

    public function reporteDeudaPdf($stand)
    {

        $provision_detalles = Detail::whereHas('stand', function ($query) use ($stand) {
            $query->where('name', '=', $stand);
        })->whereStatus(false)->orderBy('date', 'desc')->get();
        $total_prov = $provision_detalles->where('currency_id', '!=', 2)->sum('amount');
        $total_prov_dolar = $provision_detalles->where('currency_id', 2)->sum('amount');

        // dd($provision_detalles);
        $stand = Stand::where('name', '=', $stand)->first();
        if ($stand){
            $socio = $stand->partner->full_name;
        }

        $pdf = Pdf::loadView('pdf.exports.deudasPdf', compact('provision_detalles', 'total_prov', 'total_prov_dolar', 'stand', 'socio'));
        return $pdf->stream('Reporte de deudas PDF.pdf');
    }

    public function standExcel()
    {
        return (new StandsExport())->download('Detalle_de_Stands.xlsx');
    }
}
