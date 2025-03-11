<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Category;
use Carbon\Carbon;
use App\Models\Summary;
use App\Models\Customer;
use App\Models\Detail;
use App\Models\Partner;
use App\Models\Setting;
use App\Models\Student;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Luecano\NumeroALetras\NumeroALetras;

class ExportController extends Controller
{
    //
    public function printReceiptTicket($id)
    {
        $data = Summary::find($id);

        $formatter = new NumeroALetras();

        $textoTotal = $formatter->toInvoice($data->amount, 2, 'SOLES', 'CENTIMOS');

        $pdf = PDF::loadView('pdf.reciboTicket', compact('data', 'textoTotal'))->setPaper(array(0,0,360,800));

        return $pdf->stream('ticket.pdf');
    }
    public function printReceiptA4($id)
    {
        $data = Summary::find($id);

        $formatter = new NumeroALetras();

        $textoTotal = $formatter->toInvoice($data->amount, 2, 'SOLES', 'CENTIMOS');

        // dd($data->student->grade); // Verifica si obtiene el grado


        if ($data->type == "add" && $data->student_id) {
            $pdf = PDF::loadView('pdf.reciboA4', compact('data', 'textoTotal'))->setPaper('a4', 'landscape');
        } else {
            $pdf = PDF::loadView('pdf.reciboA4', compact('data', 'textoTotal'))->setPaper('a4', 'portrait');
        }



        return $pdf->stream('ReciboA4.pdf');
    }

    public function reportePdfMovimiento(Request $request)
    {

        // dd($request->all());
        $tipo = $request->tipo;
        $cuenta_id = $request->cuentas;
        $documento = $request->documento;
        $categoria_id = $request->categoria;
        $start = $request->start;
        $finish = $request->finish;

        $hoy = Carbon::now()->format('Y-m-d');

        // $summary = Summary::whereStatus('PAID')->where('future','=',1)->whereDate('date','<=',$this->hoy);

        // if($this->validarFechas($start, $finish)){


        $filter=array();

        if($tipo != null) {

            if($tipo==1){

            $filter[] = array('category_id','=',$tipo);
            $summary = Summary::where($filter)->whereDate('date','<=',$hoy)->where('future','=',1);

            }else{

            $filter[] = array('type','=',$tipo);
            $summary = Summary::where($filter)->whereDate('date','<=',$hoy)->where('future','=',1);
            }
        }
        if($cuenta_id != null) {

            $filter[] = array('account_id','=',$cuenta_id);
            $summary = Summary::where($filter)->whereDate('date','<=',$hoy)->where('future','=',1);

        }
        if($documento != null) {

            $customer = Customer::where('document','=',$documento)->first();
            // dd($customer);
            if($customer){

                $filter[] = array('customer_id','=',$customer->id);
                $summary = Summary::where($filter)->whereDate('date','<=',$hoy)->where('future','=',1);
            }else{
                return;
            }

        }

        if($categoria_id != null) {

            $filter[] = array('category_id','=',$categoria_id);
            $summary = Summary::where($filter)->whereDate('date','<=',$hoy)->where('future','=',1);

        }
        if(isset($subcategorias)) {

            $filter[] = array('id_attr','=',$subcategorias);
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


        $cuenta = Account::where('id', $cuenta_id)->select('account_name')->first();
        $categoria = Category::where('id', $categoria_id)->select('name')->first();
        $fechaInicio = Carbon::parse($start)->format('d/m/Y');
        $fechaFin = Carbon::parse($finish)->format('d/m/Y');
        $company = Setting::first();
        // dd($data, $cuenta, $categoria, $fechaInicio, $fechaFin);

        $pdf = Pdf::loadView('pdf.summary_registros_pdf', compact('data', 'tipo', 'cuenta', 'categoria', 'fechaInicio', 'fechaFin', 'sumaIngresos', 'sumaEgresos', 'totalFinal', 'company'));
        return $pdf->stream('Reporte_de_Moviemientos.pdf');


        // }
    }
    public function reportePdfConceptos(Request $request)
    {

        // dd($request->all());
        $tipo = $request->tipo;
        $cuenta_id = $request->cuentas;
        $documento = $request->documento;
        $categoria_id = $request->categoria;
        $start = $request->start;
        $finish = $request->finish;

        $hoy = Carbon::now()->format('Y-m-d');

        // $summary = Summary::whereStatus('PAID')->where('future','=',1)->whereDate('date','<=',$this->hoy);

        // if($this->validarFechas($start, $finish)){


        $filter=array();

        if($tipo != null) {

            if($tipo==1){

            $filter[] = array('category_id','=',$tipo);
            $summary = Summary::where($filter)->whereDate('date','<=',$hoy)->where('future','=',1);

            }else{

            $filter[] = array('type','=',$tipo);
            $summary = Summary::where($filter)->whereDate('date','<=',$hoy)->where('future','=',1);
            }
        }
        if($cuenta_id != null) {

            $filter[] = array('account_id','=',$cuenta_id);
            $summary = Summary::where($filter)->whereDate('date','<=',$hoy)->where('future','=',1);

        }
        if($documento != null) {

            $customer = Customer::where('document','=',$documento)->first();
            // dd($customer);
            if($customer){

                $filter[] = array('customer_id','=',$customer->id);
                $summary = Summary::where($filter)->whereDate('date','<=',$hoy)->where('future','=',1);
            }else{
                return;
            }

        }

        if($categoria_id != null) {

            $filter[] = array('category_id','=',$categoria_id);
            $summary = Summary::where($filter)->whereDate('date','<=',$hoy)->where('future','=',1);

        }
        if(isset($subcategorias)) {

            $filter[] = array('id_attr','=',$subcategorias);
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

        $data = $summary->whereStatus('PAID')->orderBy('date', 'asc')->get();

        $sumaIngresos = $data->where('type','=', 'add')->sum('amount');
        $sumaEgresos = $data->where('type','=', 'out')->sum('amount');

        $company = Setting::first();

        // dd($sumaIngresos, $sumaEgresos);

        $totalFinal = $sumaIngresos - $sumaEgresos;


        $cuenta = Account::where('id', $cuenta_id)->select('account_name')->first();
        $categoria = Category::where('id', $categoria_id)->select('name')->first();
        $fechaInicio = Carbon::parse($start)->format('d/m/Y');
        $fechaFin = Carbon::parse($finish)->format('d/m/Y');
        // dd($data, $cuenta, $categoria, $fechaInicio, $fechaFin);

        $pdf = Pdf::loadView('pdf.conceptoSummaries', compact('data', 'tipo', 'cuenta', 'categoria', 'fechaInicio', 'fechaFin', 'sumaIngresos', 'sumaEgresos', 'totalFinal', 'company'));
        return $pdf->stream('Reporte_por_conceptos.pdf');


        // }
    }
    public function reportePdfSocio(Request $request)
    {
        $student_id = $request->student;
        $start  = Carbon::parse($request->start)->format('Y-m-d');
        $finish  = Carbon::parse($request->finish)->format('Y-m-d');
        $student = Student::where('id', $student_id)->first();

        // $sumaTotal = Detail::whereStatus(1)->where('student_id', $student_id)->whereSummaryType('add')->whereBetween('date_paid', [$start, $finish])->sum('amount') - Detail::whereStatus(1)->where('student_id', $student_id)->whereSummaryType('out')->whereBetween('date_paid', [$start, $finish])->sum('amount');

        $sumaTotalPendiente = Detail::where('status', 0)
            ->where('student_id', $student_id)
            ->where(function ($query) {
                $query->where('currency_id', '!=', 2)
                    ->orWhereNull('currency_id'); // Incluir currency_id NULL
            }) // Filtra solo los soles
            ->selectRaw("SUM(CASE WHEN summary_type = 'add' THEN amount ELSE 0 END) - SUM(CASE WHEN summary_type = 'out' THEN amount ELSE 0 END) as total")
            ->value('total');

        $sumaTotalPendienteDolar = Detail::where('status', 0)
            ->where('student_id', $student_id)
            ->where('currency_id', 2) // Filtra solo los dolares
            ->selectRaw("SUM(CASE WHEN summary_type = 'add' THEN amount ELSE 0 END) - SUM(CASE WHEN summary_type = 'out' THEN amount ELSE 0 END) as total")
            ->value('total');

        $movimientos = Detail::where('student_id', $student_id)->whereStatus(1)->whereBetween('date_paid', [$start, $finish])->orderBy('date_paid', 'desc')->get();
        $sumaTotal = $movimientos->where('summary_type', 'add')->sum('amount') - $movimientos->where('summary_type', 'out')->sum('amount');
        $pendientes = Detail::whereStatus(0)->where('student_id', $student_id)->get();

        $pdf = Pdf::loadView('pdf.socio_pdf', compact('movimientos', 'pendientes', 'student', 'start', 'finish', 'sumaTotal', 'sumaTotalPendiente', 'sumaTotalPendienteDolar'));

        return $pdf->stream('Reporte_Estudiante_'.$student->full_name.'.pdf');
    }

    public function validarFechas($start, $finish)
    {
        $inicio = Carbon::parse($start);
        $fin = Carbon::parse($finish);

        if ($inicio->month !== $fin->month) {
            // $this->emit('error', 'El rango de fechas debe ser del mismo mes.');
            return false;
        }else {
            return true;
        }

        // if ($inicio->day !== 1) {
        //     $this->start1 = $inicio->firstOfMonth()->format('Y-m-d');
        // }

        // if ($fin->day !== $fin->daysInMonth) {
        //     $this->finish1 = $fin->lastOfMonth()->format('Y-m-d');
        // }
    }
}
