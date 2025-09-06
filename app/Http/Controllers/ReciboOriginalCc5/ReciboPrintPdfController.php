<?php

namespace App\Http\Controllers\ReciboOriginalCc5;

use Dompdf\Dompdf;
use App\Models\Setting;
use App\Models\Summary;
use Illuminate\Http\Request;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Luecano\NumeroALetras\NumeroALetras;

class ReciboPrintPdfController extends Controller
{
    //
    public function recibo($id){

        $data = Summary::find($id);

        $company = Setting::first();

        //->setPaper(array(0,0,710,397) -> recibo_cc5 || pimente

        $formatter = new NumeroALetras();

        $textoTotal = $formatter->toInvoice($data->amount, 2, 'SOLES', 'CENTIMOS');   
             
        if($company->receipt_type == 'pdf.recibo_cc5.pimente'){
            $pdf = PDF::loadView($company->receipt_type, compact('data', 'textoTotal', 'company'))->setPaper('a4', 'landscape');
        } else {
            $paper = array(0, 0, 710, 397);
            $pdf = PDF::loadView($company->receipt_type, compact('data', 'textoTotal', 'company'))->setPaper($paper);
        }
        // $pdf->stream();

        return $pdf->stream('ReciboCaja_'.$data->recipt_series.'-'.$data->recipt_number.'.pdf');
    }

    public function recibosMasivos(Request $request)
    {
        $numero1 = $request->get('numero1');
        $numero2 = $request->get('numero2');
        $serie = $request->get('serie');

        $summarys = array();

        if($serie && ($numero1 < $numero2)){
            $summarys = Summary::where('type', 'add')->where('recipt_series', $serie)->whereBetween('recipt_number', [$numero1, $numero2])->get();
            if($summarys){
                $idSummary = rand(5, 15);
            }else {
                $idSummary = null;
            }
        }else {
            $idSummary = null;
        }

        $formatter = new NumeroALetras();

        $company = Setting::first();
        $html = '';
        foreach ($summarys as $item) {
            $data = $item;
            $textoTotal = $formatter->toInvoice($data->amount, 2, 'SOLES', 'CENTIMOS');

            // Renderizar la vista para cada recibo y concatenarla
            $html .= view($company->receipt_type, compact('data', 'textoTotal', 'company'))->render();
        }

        // Crear el PDF con todas las vistas concatenadas
        if($company->receipt_type == 'pdf.recibo_cc5.pimente'){
            $pdf = PDF::loadHTML($html)->setPaper('a4', 'landscape');
        } else {
            $paper = array(0, 0, 710, 397);
            $pdf = PDF::loadHTML($html)->setPaper($paper);
        }
        // $pdf = PDF::loadHTML($html)->setPaper('a4', 'landscape');

        return $pdf->stream('RecibosMasivos.pdf');
    }
}
