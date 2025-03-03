<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Summary;
use App\Models\Category;
use App\Models\AttrValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BalanceController extends Controller
{
    public function getAmount($in=0){
        return $in;
    }

    private function generateDateRange(Carbon $start_date, Carbon $end_date)
    {
        $dates = [];
        for($date = $start_date; $date->lte($end_date); $date->addDay()) {
            $dates[] = $date->format('m-Y');
        }
        $dates = array_unique($dates);
        return $dates;
    }

    public function filterData($d,$f){
        $d = $d->filter(function ($value, $key) use($f) {
            return $value->numberOf === $f;
        });
        if(count($d)<1){
            $d  =  null;
            return $d;
        }
        return $d->all();
    }

    public function index(Request $request)
    {

            $year = $request->input('year');
            $tipo = $request->input('tipo');
            $start = $request->input('start');
            $finish = $request->input('finish');
            $categorias = $request->input('categoria');

            if($year && !$start && !$finish && !$categorias){


                $tipo= 'add';$tipo = $request->input('tipo');


                $start = Carbon::parse($start);
                $finish =Carbon::parse($finish);

                $dataTmp = array();

                $categories = Category::whereNotIn('id', [1])->get();
                if( $categorias) {
                    $categoriaselet = Category::where('id','=',$categorias)->first();

                    if($categoriaselet->id){
                        $summary = Summary::where('categories_id','=',$categoriaselet->id)->where('future','=',1)->get();
                        $attrs = AttrValue::where('id_categorie','=',$categoriaselet->id)->get();
                        $data = DB::table('summary')
                            ->join('categories','categories.id','=','summary.categories_id')
                            ->select('summary.*', 'categories.name as categories_name', 'categories.id as categories_id'
                                ,'categories.type as categories_type', 'categories.description as categories_description')
                            ->where('categories_id','=',$categoriaselet->id)
                            ->where('categories.type','=',$tipo)
                            ->whereNotIn('categories.id', [1])
                            ->where('future','=',1)->get();

                        $data = $data->map(function($item) {
                            $summaryCreateAt = Carbon::parse($item->created_at);
                            $numberOf = $summaryCreateAt->format('m-Y');
                            if ($item->id_attr) {
                                $lados = AttrValue::where('id','=',$item->id_attr)->first();
                                //$lados = DB::table('attr_values')->where('id_categorie','=',$item->categories_id)->get();
                                $item->subcats = $lados;
                            }else {
                                $item->subcats = null;
                            }
                            $item->numberOf = $numberOf;
                            //$this->insideArray(null,['key'=>$numberOf,'value'=>$item]);
                            return $item;
                        });

                        $listDates = $this->generateDateRange($start,$finish);
                        foreach ($listDates as $d){
                            $dataTmp[$d] = $this->filterData($data,$d);
                        }
                    }

                    return view('admin.balances.balance',
                        ['categories'=>$categories, 'subcate'=>$summary, 'data'=>$data,'timeline'=>$dataTmp,
                            'subcategorias'=>$attrs, 'getAmount'=>$this->getAmount(), 'cateselet'=>$categoriaselet,'tipom'=>$tipo]);
            }
            else {
                $data = DB::table('summary')
                    ->join('categories','categories.id','=','summary.categories_id')
                    ->select('summary.*', 'categories.name as categories_name', 'categories.id as categories_id'
                        ,'categories.type as categories_type', 'categories.description as categories_description')
                    ->where('categories.type','=',$tipo)
                    ->whereNotIn('categories.id', [1])
                    ->where('future','=',1)->get();

                $data = $data->map(function($item) {
                    $summaryCreateAt = Carbon::parse($item->created_at);
                    $numberOf = $summaryCreateAt->format('m-Y');
                    if ($item->id_attr) {
                        $lados = AttrValue::where('id','=',$item->id_attr)->first();
                    //$lados = DB::table('attr_values')->where('id_categorie','=',$item->categories_id)->get();
                        $item->subcats = $lados;
                    }else {
                        $item->subcats = null;
                    }
                    $item->numberOf = $numberOf;
                    //$this->insideArray(null,['key'=>$numberOf,'value'=>$item]);
                    return $item;
            });

            $dt = $year;

            $f1= $dt.'-01-01';
            $f2= $dt.'-12-31';
            $start =  Carbon::parse( $f1);
            $finish = Carbon::parse($f2);

            $listDates = $this->generateDateRange($start,$finish);
            foreach ($listDates as $d){
                $dataTmp[$d] = $this->filterData($data,$d);
            }
            $summary = array();
            $attrs = Category::whereNotIn('id', [1])->get();
            $categoriaselet = array();
            $catesnull= Category::all();
            $data = array();

            return view('admin.balances.balance',
                ['categories'=>$categories, 'subcate'=>$summary, 'data'=>$data,'timeline'=>$dataTmp,
                    'subcategorias'=>$attrs, 'cateselet'=>$categoriaselet, 'tipom'=>$tipo, 'filter'=>false]);
        }

        } else {
            $start = $request->input('start');
            $finish = $request->input('finish');
            $tipo = $request->input('tipo');
            $categorias = $request->input('categoria');
            if(!$tipo){
                $tipo= 'out';
            }

            $start = Carbon::parse($start);
            $finish =Carbon::parse($finish);

            $dataTmp = array();

                $categories = Category::whereNotIn('id', [1])->get();
                if( $categorias) {
                    $categoriaselet = Category::where('id','=',$categorias)->first();

                    if($categoriaselet->id){
                        $summary = summary::where('categories_id','=',$categoriaselet->id)->where('future','=',1)->get();
                        $attrs = AttrValue::where('id_categorie','=',$categoriaselet->id)->get();

                        $data = DB::table('summary')
                            ->join('categories','categories.id','=','summary.categories_id')
                            ->select('summary.*', 'categories.name as categories_name', 'categories.id as categories_id'
                                ,'categories.type as categories_type', 'categories.description as categories_description')
                            ->where('categories_id','=',$categoriaselet->id)
                            ->where('categories.type','=',$tipo)
                            ->whereNotIn('categories.id', [1])
                            ->where('future','=',1)->get();

                        $data = $data->map(function($item) {
                            $summaryCreateAt = Carbon::parse($item->created_at);
                            $numberOf = $summaryCreateAt->format('m-Y');
                            if ($item->id_attr) {
                                $lados = AttrValue::where('id','=',$item->id_attr)->first();
                                //$lados = DB::table('attr_values')->where('id_categorie','=',$item->categories_id)->get();
                                $item->subcats = $lados;
                            }else {
                                $item->subcats = null;
                            }
                            $item->numberOf = $numberOf;
                            //$this->insideArray(null,['key'=>$numberOf,'value'=>$item]);
                            return $item;
                        });



                        $listDates = $this->generateDateRange($start,$finish);
                        foreach ($listDates as $d){
                            $dataTmp[$d] = $this->filterData($data,$d);
                        }
                    }

                    return view('admin.balances.balance',
                        ['categories'=>$categories, 'subcate'=>$summary, 'data'=>$data,'timeline'=>$dataTmp,
                            'subcategorias'=>$attrs, 'getAmount'=>$this->getAmount(), 'cateselet'=>$categoriaselet,'tipom'=>$tipo, 'filter'=> true]);
                }
                else {
                    $summary = array();
                    $attrs = array();
                    $categoriaselet = array();
                    $data = array();
                    return view('admin.balances.balance',
                        ['categories'=>$categories, 'subcate'=>$summary, 'data'=>$data,'timeline'=>$dataTmp,
                            'subcategorias'=>$attrs, 'cateselet'=>$categoriaselet,'tipom'=>$tipo, 'filter'=> true]);
                }

        }

    }

    public function indexinit(Request $request)
    {
        $start = $request->input('start');
        $finish = $request->input('finish');
        $categorias = $request->input('categoria');
        $tipo= 'out';


        $start = Carbon::parse($start);
        $finish =Carbon::parse($finish);

        $dataTmp = array();

            $categories = Category::whereNotIn('id', [1])->get();
            if( $categorias) {
                $categoriaselet = Category::where('id','=',$categorias)->first();

                if($categoriaselet->id){
                    $summary = Summary::where('categories_id','=',$categoriaselet->id)->where('future','=',1)->get();
                    $attrs = AttrValue::where('id_categorie','=',$categoriaselet->id)->get();
//
                    $data = DB::table('summary')
                        ->join('categories','categories.id','=','summary.categories_id')
                        ->select('summary.*', 'categories.name as categories_name', 'categories.id as categories_id'
                            ,'categories.type as categories_type', 'categories.description as categories_description')
                        ->where('categories_id','=',$categoriaselet->id)
                        ->where('categories.type','=',$tipo)
                        ->whereNotIn('categories.id', [1])
                        ->where('future','=',1)->get();

                    $data = $data->map(function($item) {
                        $summaryCreateAt = Carbon::parse($item->created_at);
                        $numberOf = $summaryCreateAt->format('m-Y');
                        if ($item->id_attr) {
                            $lados = AttrValue::where('id','=',$item->id_attr)->first();
//                    $lados = DB::table('attr_values')->where('id_categorie','=',$item->categories_id)->get();
                            $item->subcats = $lados;
                        }else {
                            $item->subcats = null;
                        }
                        $item->numberOf = $numberOf;
                        //$this->insideArray(null,['key'=>$numberOf,'value'=>$item]);
                        return $item;
                    });



                    $listDates = $this->generateDateRange($start,$finish);
                    foreach ($listDates as $d){
                        $dataTmp[$d] = $this->filterData($data,$d);
                    }
                }

                return view('admin.balances.balance',
                    ['categories'=>$categories, 'subcate'=>$summary, 'data'=>$data,'timeline'=>$dataTmp,
                        'subcategorias'=>$attrs, 'getAmount'=>$this->getAmount(), 'cateselet'=>$categoriaselet,'tipom'=>$tipo]);
            }
            else {
                $data = DB::table('summary')
                    ->join('categories','categories.id','=','summary.categories_id')
                    ->select('summary.*', 'categories.name as categories_name', 'categories.id as categories_id'
                        ,'categories.type as categories_type', 'categories.description as categories_description')
                    ->where('categories.type','=',$tipo)
                    ->whereNotIn('categories.id', [1])
                    ->where('future','=',1)->get();

                $data = $data->map(function($item) {
                    $summaryCreateAt = Carbon::parse($item->created_at);
                    $numberOf = $summaryCreateAt->format('m-Y');
                    if ($item->id_attr) {
                        $lados = AttrValue::where('id','=',$item->id_attr)->first();
                        //$lados = DB::table('attr_values')->where('id_categorie','=',$item->categories_id)->get();
                        $item->subcats = $lados;
                    }else {
                        $item->subcats = null;
                    }
                    $item->numberOf = $numberOf;
                    //$this->insideArray(null,['key'=>$numberOf,'value'=>$item]);
                    return $item;
                });
                $dt = Carbon::now()->year;

                $f1= $dt.'-01-01';
                $f2= $dt.'-12-31';
                $start =  Carbon::parse( $f1);
                $finish = Carbon::parse($f2);

                $listDates = $this->generateDateRange($start,$finish);
                foreach ($listDates as $d){
                    $dataTmp[$d] = $this->filterData($data,$d);
                }
                $summary = array();
                $attrs = Category::whereNotIn('id', [1])->get();
                $categoriaselet = array();
                $catesnull= Category::all();
                $data = array();

                return view('admin.balances.balance',
                    ['categories'=>$categories, 'subcate'=>$summary, 'data'=>$data,'timeline'=>$dataTmp,
                        'subcategorias'=>$attrs, 'cateselet'=>$categoriaselet, 'tipom'=>$tipo, 'filter'=>false]);
            }
    }

    public function indexadd(Request $request)
    {
        $start = $request->input('start');
        $finish = $request->input('finish');
        $categorias = $request->input('categoria');
        $tipo= 'add';


        $start = Carbon::parse($start);
        $finish =Carbon::parse($finish);

        $dataTmp = array();

            $categories = Category::whereNotIn('id', [1])->get();

            if( $categorias) {
                $categoriaselet = Category::where('id','=',$categorias)->first();

                if($categoriaselet->id){
                    $summary = Summary::where('categories_id','=',$categoriaselet->id)->where('future','=',1)->get();
                    $attrs = AttrValue::where('id_categorie','=',$categoriaselet->id)->get();

                    $data = DB::table('summary')
                        ->join('categories','categories.id','=','summary.categories_id')
                        ->select('summary.*', 'categories.name as categories_name', 'categories.id as categories_id'
                            ,'categories.type as categories_type', 'categories.description as categories_description')
                        ->where('categories_id','=',$categoriaselet->id)
                        ->where('categories.type','=',$tipo)
                        ->whereNotIn('categories.id', [1])
                        ->where('future','=',1)->get();

                    $data = $data->map(function($item) {
                        $summaryCreateAt = Carbon::parse($item->created_at);
                        $numberOf = $summaryCreateAt->format('m-Y');
                        if ($item->id_attr) {
                            $lados = AttrValue::where('id','=',$item->id_attr)->first();
                        //  $lados = DB::table('attr_values')->where('id_categorie','=',$item->categories_id)->get();
                            $item->subcats = $lados;
                        }else {
                            $item->subcats = null;
                        }
                        $item->numberOf = $numberOf;
                        //$this->insideArray(null,['key'=>$numberOf,'value'=>$item]);
                        return $item;
                    });



                    $listDates = $this->generateDateRange($start,$finish);
                    foreach ($listDates as $d){
                        $dataTmp[$d] = $this->filterData($data,$d);
                    }
                }

                return view('admin.balances.balance',
                    ['categories'=>$categories, 'subcate'=>$summary, 'data'=>$data,'timeline'=>$dataTmp,
                        'subcategorias'=>$attrs, 'getAmount'=>$this->getAmount(), 'cateselet'=>$categoriaselet,'tipom'=>$tipo]);
            }
            else {
                $data = DB::table('summary')
                    ->join('categories','categories.id','=','summary.categories_id')
                    ->select('summary.*', 'categories.name as categories_name', 'categories.id as categories_id'
                        ,'categories.type as categories_type', 'categories.description as categories_description')
                    ->where('categories.type','=',$tipo)
                    ->whereNotIn('categories.id', [1])
                    ->where('future','=',1)->get();

                $data = $data->map(function($item) {
                    $summaryCreateAt = Carbon::parse($item->created_at);
                    $numberOf = $summaryCreateAt->format('m-Y');
                    if ($item->id_attr) {
                        $lados = AttrValue::where('id','=',$item->id_attr)->first();
//                    $lados = DB::table('attr_values')->where('id_categorie','=',$item->categories_id)->get();
                        $item->subcats = $lados;
                    }else {
                        $item->subcats = null;
                    }
                    $item->numberOf = $numberOf;
                    //$this->insideArray(null,['key'=>$numberOf,'value'=>$item]);
                    return $item;
                });
                $dt = Carbon::now()->year;

                $f1= $dt.'-01-01';
                $f2= $dt.'-12-31';
                $start =  Carbon::parse( $f1);
                $finish = Carbon::parse($f2);

                $listDates = $this->generateDateRange($start,$finish);
                foreach ($listDates as $d){
                    $dataTmp[$d] = $this->filterData($data,$d);
                }
                $summary = array();
                $attrs = Category::whereNotIn('id', [1])->get();
                $categoriaselet = array();
                $catesnull= Category::all();
                $data = array();

                return view('admin.balances.balance',
                    ['categories'=>$categories, 'subcate'=>$summary, 'data'=>$data,'timeline'=>$dataTmp,
                        'subcategorias'=>$attrs, 'cateselet'=>$categoriaselet, 'tipom'=>$tipo, 'filter'=>false]);
            }
    }
}
