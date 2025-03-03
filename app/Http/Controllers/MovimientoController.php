<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Attached;
use App\Models\AttrValue;
use App\Models\Category;
use App\Models\Setting;
use App\Models\Summary;
use App\Models\Tour;
use Attribute;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MovimientoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        //$hoy=date('Y-m-d',strtotime('today - 1 days'));
        $hoy = Carbon::now()->format('Y-m-d');

        $summary = Summary::whereDate('date','<=',$hoy)->where('future','=',1);
        // $summary = $summarys->paginate(10);

        // dd($summary, $hoy);
        // $summary = summary::all();
        $categories = Category::all();
        $tours = Tour::all();
        $account = Account::all();
        $divisa = Setting::where('name','Soles')->first();


        $total=array();
        $totaliva=array();
        $totalivae=array();



        $start = $request->input('start');
        $finish = $request->input('finish');
        $dias = $request->input('dias');
        $tipo = $request->input('tipo');
        $cuentas = $request->input('cuentas');
        $categorias = $request->input('categoria');
        $subcategorias = $request->input('id_attr');
        $tf = $request->input('tf');
        $subcatetours = $request->input('id_attr_tours');


        // $start = '2022-07-12'; $finish = Carbon::now();

        $filter=array();

        // dd($filter);

        if(isset($tipo)) {

          if($tipo==1){

            $filter[] = array('category_id','=',$tipo);
            $summary = Summary::where($filter)->whereDate('date','<=',$hoy)->where('future','=',1);

          }else{

            $filter[] = array('type','=',$tipo);
            $summary = Summary::where($filter)->whereDate('date','<=',$hoy)->where('future','=',1);
          }
        }
        if(isset($cuentas)) {

          $filter[] = array('account_id','=',$cuentas);
          $summary = Summary::where($filter)->whereDate('date','<=',$hoy)->where('future','=',1);

        }
        if(isset($categorias)) {

          $filter[] = array('category_id','=',$categorias);
          $summary = Summary::where($filter)->whereDate('date','<=',$hoy)->where('future','=',1);

        }
        if(isset($subcategorias)) {

          $filter[] = array('id_attr','=',$subcategorias);
          $summary = Summary::where($filter)->whereDate('date','<=',$hoy)->where('future','=',1);

        }

         if(isset($tf)) {

          $filter[] = array('tours_id','=',$tf);
          $summary = Summary::where($filter)->whereDate('date','<=',$hoy)->where('future','=',1);

        }
        if(isset($subcatetours)) {

          $filter[] = array('id_attr_tours','=',$subcatetours);
          $summary = Summary::where($filter)->whereDate('date','<=',$hoy)->where('future','=',1);

        }



        if((isset($start)) and (isset($finish))){

          $start = Carbon::parse($start)->format('Y-m-d');
          $finish = Carbon::parse($finish)->format('Y-m-d');


          $summary = Summary::whereBetween('date', [$start, $finish])->where($filter)->where('future','=',1);

        }elseif((isset($dias))){

            if($dias==30){
              $start = date('Y-m-d',strtotime('today - 30 days'));
            }
            if($dias==15){
              $start = date('Y-m-d',strtotime('today - 15 days'));
            }
            if($dias==7){
              $start = date('Y-m-d',strtotime('today - 7 days'));
            }
            if($dias==1){
              $start = date('Y-m-d',strtotime('today'));
            }

          $summary = Summary::whereBetween('date', [$start, $hoy])->where($filter)->where('future','=',1);

        }else{

            if($filter) {
                $summary = Summary::where('date','<=',$hoy)->where('future','=',1)->where($filter);
            }else {
                $summary = Summary::where('date','<=',$hoy)->where('future','=',1);
            }



        }

        $summaTotales = $summary->get();
        $movimientos = $summary->get();

        // dd($movimientos);

        foreach ($movimientos as $s) {

          $name_account = Account::find($s->account_id);

          $s->setAttribute('name_account',$name_account->account_name);

          $name_categories = Category::find($s->category_id);
          $s->setAttribute('name_categories',$name_categories->name);

        //   dd($summary);

         $name_tours = Tour::find($s->tours_id);

         if($name_tours!=null){

             $s->setAttribute('name_tours',$name_tours->name);
         }

          if(Attached::where('summary_id',$s->id)->exists()){

            $data_attached = Attached::where('summary_id',$s->id)->first();

            $s->setAttribute('attached',$data_attached);

          }else{

            $s->setAttribute('attached',null);

          }

          if(AttrValue::where('category_id',$s->account_id)->exists()){

            $data_attributes = AttrValue::where('category_id',$s->account_id)->first();
            $s->setAttribute('attributes',$data_attributes);

          }else{

            $s->setAttribute('attributes',null);

          }




        }

        $total=array();

        foreach ($account as $a) {

          $total[$a->id] = 0;

          foreach ($summaTotales as $t) {

            if($t->type=='out'){

              $total[$a->id] -= $t->amount;

            }else{

            $total[$a->id] += $t->amount;

            }

          }

        $a->setAttribute('total',$total[$a->id]);

        }

        $totalfinal=0;



        foreach ($total as $b) {

           $totalfinal=$b;
        }

          //impuestos

            //impuestos
        foreach ($account as $e) {

          $totalivae[$e->id] = 0;
          foreach ($summaTotales as $ee) {

            if($ee->type=='add'){
              $totalivae[$e->id] += $ee->tax;
            }
          }

        $e->setAttribute('totaliva',$totalivae[$e->id]);

        }



        foreach ($account as $i) {

          $totaliva[$i->id] = 0;
          foreach ($summaTotales as $ii) {

            if($ii->type=='out'){
              $totaliva[$i->id] += $ii->tax;
            }
          }
        $i->setAttribute('totaliva',$totaliva[$i->id]);

        }


         $totalfinaliva=0;
        foreach ($totaliva as $b) {

            $totalfinaliva=$b;
        }

        $totalfinalivae=0;
        foreach ($totalivae as $be) {

            $totalfinalivae=$be;
        }

        // dd($account, $categories);


        return view('admin.summary.summary',['summaries'=>$movimientos,'divisa'=>$divisa,'data'=>$account,'data2'=>$categories,'totalfinal'=>$totalfinal,'totalfinaliva'=>$totalfinaliva,'totalfinalivae'=>$totalfinalivae,'tours'=>$tours]);


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        $type = $request->input('type');
        $data = Account::all();
        $data2 = Category::all();
        $tours = Tour::all();

        return view('admin.summary.create', ['data'=>$data,'data2'=>$data2,'type'=>$type,'tours'=>$tours]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $adjunto = $request->file('path');

        $hoy=date('Y-m-d H:m:s',strtotime('today'));

        $log = Auth::id();

        $str = str_replace(",", "", $request->amount);
        $iva = str_replace(",", "", $request->tax);


        if($request->created_at > $hoy){

            $alerta=2;

        }else{

            $alerta=1;

        }

      $id = summary::insertGetId([

        'date' => $request->created_at,

        'id_attr' => $request->id_attr,
        'concept'=>  $request->concept,
        'type'=> $request->type,
        'amount'=> $str,
        'tax'=> $iva,

        'recipt_number'=> $request->factura,

        'account_id'=> $request->account_id,
        'category_id'=>$request->categories_id,
        'id_attr_tours'=>$request->id_attr_tours,
        'tours_id'=>$request->tours_id,
        'user_id'=>$log,
        'future'=>$alerta

        ]);



        if($adjunto!=null){

         $file = $request->path->store('attached','public');

           $id2 =Attached::insertGetId([
              'path' =>$file,
              'created_at' => $hoy,
              'updated_at'=>   $hoy,
              'summary_id'=>  $id,
            ]);
        }

        return redirect('/admin/movimientos');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //

        $categories = Category::all();
        $account = Account::all();
        $data = Summary::where('id',$id)->first();
        $tours = Tour::all();

        if($attached = attached::where('summary_id',$id)->exists()){

            $attached = attached::where('summary_id',$id)->first();

            $data->setAttribute('attached',$attached);

        }else{

            $data->setAttribute('attached',null);

        }

        return view('admin.summary.edit',['data'=>$data,'account'=>$account,'categories'=>$categories,'tours'=>$tours]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $hoy=date('Y-m-d H:m:s',strtotime('today'));
        $log = Auth::id();


        $summary = Summary::find($id);

        $summary->date = $request->created_at;
        $summary->concept = $request->concept;
        $summary->type = $request->type;

        $str = str_replace(",", "", $request->amount);
        // $str2 = str_replace(",", ".", $str);
        $summary->amount = $str;

        $iva = str_replace(",", "", $request->tax);
        $summary->tax  = $iva;

        $summary->recipt_number  = $request->factura;
        $summary->account_id  = $request->account_id  ;
        $summary->category_id  = $request->categories_id  ;
        $summary->id_attr_tours = $request->id_attr_tours;
        $summary->tours_id = $request->tours_id;

        $summary->save();

        return redirect('admin/movimientos');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
