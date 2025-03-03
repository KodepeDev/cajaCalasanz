<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Detail;
use App\Models\Account;
use App\Models\Summary;
use App\Models\Category;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Exports\DetailsExport;
use App\Exports\DetailsProvisionExport;
use App\Models\Partner;
use App\Models\Setting;
use App\Models\Stand;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Luecano\NumeroALetras\NumeroALetras;

class ReportController extends Controller
{
    //
    public function reportePdfDetalles(Request $request)
    {
        // dd($request->all());
        $tipo = $request->tipo;
        // $cuenta_id = $request->cuentas;
        // $documento = $request->documento;
        $categoria_id = $request->categoria;
        $start = $request->start;
        $finish = $request->finish;

        $hoy = Carbon::now()->format('Y-m-d');

        // $summary = Summary::whereStatus('PAID')->whereDate('date','<=',$this->hoy);

        // if($this->validarFechas($start, $finish)){


        $filter=array();

        if($tipo != null) {

            $filter[] = array('summary_type','=',$tipo);
            $summary = Detail::where($filter)->whereDate('date','<=',$hoy);

        }

        if($categoria_id != null) {

            $filter[] = array('category_id','=',$categoria_id);
            $summary = Detail::where($filter)->whereDate('date','<=',$hoy);

        }

        if((isset($start)) and (isset($finish))){
            // dd(isset($start));

            $start1 = Carbon::parse($start)->format('Y-m-d');
            $finish1 = Carbon::parse($finish)->format('Y-m-d');


            $summary = Detail::whereBetween('date_paid', [$start1, $finish1])->where($filter);

        }else{

            if($filter) {
                $summary = Detail::where('date_paid','=',$hoy)->where($filter);
            }else {
                $summary = Detail::where('date_paid','=',$hoy);
            }
        }

        $data = $summary->whereStatus(1)->orderBy('date_paid', 'asc')->get();

        $sumaEgresos = $data->where('summary_type','=', 'out')->where('currency_id', '!=', 2)->sum('amount');
        $sumaEgresosDolar = $data->where('summary_type','=', 'out')->where('currency_id', 2)->sum('amount');
        $sumaIngresos = $data->where('summary_type','=', 'add')->where('currency_id', '!=', 2)->sum('amount');
        $sumaIngresosDolar = $data->where('summary_type','=', 'add')->where('currency_id', 2)->sum('amount');

        // dd($sumaIngresos, $sumaEgresos);

        $totalFinal = $sumaIngresos - $sumaEgresos;
        $totalFinalDolar = $sumaIngresosDolar - $sumaEgresosDolar;


        // $cuenta = Account::where('id', $cuenta_id)->select('account_name')->first();
        $categoria = Category::where('id', $categoria_id)->select('name')->first();
        $fechaInicio = Carbon::parse($start)->format('d/m/Y');
        $fechaFin = Carbon::parse($finish)->format('d/m/Y');
        // dd($data, $cuenta, $categoria, $fechaInicio, $fechaFin);

        $pdf = Pdf::loadView('pdf.exports.export_pdf', compact('data', 'tipo', 'categoria', 'fechaInicio', 'fechaFin', 'sumaIngresos', 'sumaEgresos', 'totalFinal', 'sumaIngresosDolar', 'sumaEgresosDolar', 'totalFinalDolar'));
        return $pdf->stream('Reporte_detale_PDF.pdf');
    }

    public function reportePdfGeneral($tipo, $fechaInicio, $fechaFin)
    {

        // dd($request->all());
        $tipo = $tipo;
        $start = $fechaInicio;
        $finish = $fechaFin;

        $hoy = Carbon::now()->format('Y-m-d');

        // $summary = Summary::whereStatus('PAID')->where('future','=',1)->whereDate('date','<=',$this->hoy);

        // if($this->validarFechas($start, $finish)){


        $filter=array();

        if($tipo != null) {

            $filter[] = array('type','=',$tipo);
            $summary = Summary::where($filter)->whereDate('date','<=',$hoy)->where('future','=',1);
        }

        if((isset($start)) and (isset($finish))){
            // dd(isset($start));

            $start1 = Carbon::parse($start)->format('Y-m-d');
            $finish1 = Carbon::parse($finish)->format('Y-m-d');


            $summary = Summary::whereBetween('date', [$start1, $finish1])->where($filter)->where('future','=',1);

        }else{

            if($filter) {
                $summary = Summary::where('date','=',$hoy)->where('future','=',1)->where($filter);
            }else {
                $summary = Summary::where('date','=',$hoy)->where('future','=',1);
            }
        }

        $data = $summary->whereStatus('PAID')->orderBy('date', 'asc')->select('recipt_series', 'recipt_number', 'type', 'amount', 'date', 'customer_id')->get();

        $sumaEgresos = $data->where('type','=', 'out')->sum('amount');
        $sumaIngresos = $data->where('type','=', 'add')->sum('amount');

        // dd($sumaIngresos, $sumaEgresos);

        $totalFinal = $sumaIngresos - $sumaEgresos;

        $fechaInicio = Carbon::parse($start)->format('d/m/Y');
        $fechaFin = Carbon::parse($finish)->format('d/m/Y');
        // dd($data, $cuenta, $categoria, $fechaInicio, $fechaFin);

        $pdf = Pdf::loadView('pdf.summary_pdf', compact('data', 'tipo', 'fechaInicio', 'fechaFin', 'sumaIngresos', 'sumaEgresos', 'totalFinal'));

        return $pdf->download('Reporte_de_Moviemientos.pdf');
    }



    public function reporteSocios()
    {
        $socios = Partner::with('stands')->get();
        $empresa = Setting::first();
        $pdf = Pdf::loadView('pdf.exports.reporte-socio', compact('socios', 'empresa'));

        return $pdf->download('Reporte_de_Socios.pdf');
    }
    public function reporteStands()
    {
        $stands = Stand::orderBy('name', 'asc')->get();

        $pdf = Pdf::loadView('pdf.exports.reporte-stands', compact('stands'));

        return $pdf->download('Reporte_de_Stands.pdf');
    }





    public function reporteExcelDetalles(Request $request)
    {
        // dd($request->all());
        $tipo = $request->tipo;
        // $cuenta_id = $request->cuentas;
        // $documento = $request->documento;
        $categoria_id = $request->categoria;
        $start = $request->start;
        $finish = $request->finish;

        $hoy = Carbon::now()->format('Y-m-d');

        // $summary = Summary::whereStatus('PAID')->whereDate('date','<=',$this->hoy);

        // if($this->validarFechas($start, $finish)){

        return (new DetailsExport($tipo, $categoria_id, $start, $finish))->download('Detalle_de_Moviemientos_CC5.xlsx');
        // return Excel::download(new DetailsExport($tipo, $categoria_id, $start, $finish), 'invoices.pdf', \Maatwebsite\Excel\Excel::DOMPDF);

    }
    public function reporteExcelDetallesVariables(Request $request)
    {
        $tipo = $request->tipo;
        $categoria_id = $request->categoria;
        $mes = $request->mes;
        $etapa = $request->stage;

        $hoy = Carbon::now()->format('Y-m-d');

        // $summary = Summary::whereStatus('PAID')->whereDate('date','<=',$this->hoy);

        // if($this->validarFechas($start, $finish)){


        // return Excel::download(new DetailsExport($tipo, $categoria_id, $start, $finish), 'invoices.pdf', \Maatwebsite\Excel\Excel::DOMPDF);

    }




}
