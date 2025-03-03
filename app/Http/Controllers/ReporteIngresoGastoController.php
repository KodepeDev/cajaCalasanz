<?php

namespace App\Http\Controllers;

use App\Models\Stage;
use App\Models\Detail;
use App\Models\Stand;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteIngresoGastoController extends Controller
{
    public function reporteIngresosPdf(Request $request)
    {
        // $stand = $request->stand;
        // $inicio = $request->inicio;
        // $fin = $request->fin;
        // $hasUser = $request->hasUser;
        // $hasEtapa = $request->hasEtapa;
        // $etapa = $request->etapa;
        // $user = $request->user;
        // $data = Stage::with(['stands.details' => function ($query) use ($request){
        //     $query->where('status', true)->where('summary_type', 'add');
        //     $query->whereBetween('date_paid', [$request->inicio, $request->fin])->orderBy('date_paid', 'asc');
        //     $query->when($request->stand, function($q) use ($request) {
        //         $stan = Stand::where('name', '=', $request->stand)->first();
        //         $q->where('stand_id', '=', $stan? $stan->id : null);
        //     })->when($request->hasEtapa, function($q) use ($request){
        //         if($request->etapa){
        //             $q->whereHas('stand', function ($q) use ($request){
        //                 $q->where('stage_id', '=', $request->etapa);
        //             });
        //         }else{
        //             $q->where('stand_id', '=', null);
        //         }
        //     })->when($request->hasUser, function($q) use ($request){
        //         $q->whereHas('summary', function ($q) use ($request){
        //             $q->where('user_id', '=', $request->user);
        //         });
        //     });
        // }])->get();

        // $data2 = [];

        // if(!$request->stand && !$request->etapa)
        // {
        //     $data2 = Detail::whereBetween('date_paid', [$inicio, $fin])->where('summary_type', 'add')->where('stand_id', '=', null)->orderBy('date_paid', 'asc')->get();
        // }else {
        //     $data2 = [];
        // }

        // $pdf = Pdf::loadView('pdf.reporte_2024.reporte-ingreso', compact('data', 'data2', 'inicio', 'fin'))->setPaper('a4', 'landscape');
        // $nombreArchivo = 'Reporte_de_ingresos_'.$inicio.'_al_'.$fin.'.pdf';

        // return response($pdf->stream(), 200, [
        //     'Content-Type' => 'application/pdf',
        //     'Content-Disposition' => 'inline; filename="reporte.pdf"',
        // ]);

        $stand = $request->stand;
        $inicio = $request->inicio;
        $fin = $request->fin;
        $hasUser = $request->hasUser;
        $hasEtapa = $request->hasEtapa;
        $etapa = $request->etapa;
        $user = $request->user;

        // Obtener los datos principales con filtros
        $data = Stage::with(['stands.details' => function ($query) use ($request) {
            $query->where('status', true)
                ->where('summary_type', 'add')
                ->whereBetween('date_paid', [$request->inicio, $request->fin])
                ->orderBy('date_paid', 'asc');

            // Filtrar por stand si existe
            $query->when($request->stand, function($q) use ($request) {
                $stan = Stand::where('name', '=', $request->stand)->first();
                $q->where('stand_id', '=', $stan ? $stan->id : null);
            });

            // Filtrar por etapa si es necesario
            $query->when($request->hasEtapa, function($q) use ($request) {
                $q->whereHas('stand', function ($q) use ($request) {
                    $q->where('stage_id', '=', $request->etapa);
                });
            });

            // Filtrar por usuario si es necesario
            $query->when($request->hasUser, function($q) use ($request) {
                $q->whereHas('summary', function ($q) use ($request) {
                    $q->where('user_id', '=', $request->user);
                });
            });
        }])->get();

        // Si no hay stand o etapa seleccionada, se obtiene otro conjunto de datos
        $data2 = (!$request->stand && !$request->etapa)
            ? Detail::whereBetween('date_paid', [$inicio, $fin])
                ->where('summary_type', 'add')
                ->whereNull('stand_id')
                ->orderBy('date_paid', 'asc')
                ->get()
            : [];

        // Generar el PDF con los datos obtenidos
        $pdf = Pdf::loadView('pdf.reporte_2024.reporte-ingreso', compact('data', 'data2', 'inicio', 'fin'))
            ->setPaper('a4', 'landscape');

        $nombreArchivo = 'Reporte_de_ingresos_'.$inicio.'_al_'.$fin.'.pdf';

        // Retornar el PDF como respuesta
        return response($pdf->stream(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$nombreArchivo.'"',
        ]);
    }

    public function reporteIngresosDetallePdf(Request $request)
    {
        $stand = $request->stand;
        $inicio = $request->inicio;
        $fin = $request->fin;
        $hasUser = $request->hasUser;
        $hasEtapa = $request->hasEtapa;
        $etapa = $request->etapa;
        $user = $request->user;

        $data = Detail::whereBetween('date_paid', [$inicio, $fin])->where('summary_type', 'add')->orderBy('date_paid', 'asc')
        ->when($stand, function($q) use ($stand) {
            $stan = Stand::where('name', '=', $stand)->first();
            $q->where('stand_id', '=', $stan? $stan->id : null);
        })->when($hasEtapa, function($q) use ($etapa){
            if($etapa){
                $q->whereHas('stand', function ($q) use ($etapa){
                    $q->where('stage_id', '=', $etapa);
                });
            }else{
                $q->where('stand_id', '=', null);
            }
        })->when($hasUser, function($q) use ($user){
            $q->whereHas('summary', function ($q) use ($user){
                $q->where('user_id', '=', $user);
            });
        })->get();

        $pdf = Pdf::loadView('pdf.reporte_2024.reporte-ingreso-detallado', compact('data', 'inicio', 'fin'))->setPaper('a4', 'landscape');

        return response($pdf->stream(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="reporteIngreso.pdf"',
        ]);
    }
}
