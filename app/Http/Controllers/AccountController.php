<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Attached;
use App\Models\Category;
use App\Models\Setting;
use App\Models\Summary;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $account = Account::all();
        return view('admin.cuentas.cuenta', compact('account'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.cuentas.create');
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
        $request->validate([
            'add_serie' => 'required|min:4|max:4|unique:accounts,add_serie',
            'out_serie' => 'required|min:4|max:4|unique:accounts,out_serie'
        ]);

        $account = Account::create([
            'account_name' => $request->name,
            'account_number' =>$request->number,
            'account_type'=>  $request->type,
            'add_serie' => strtoupper($request->add_serie),
            'out_serie' => strtoupper($request->out_serie),
        ]);

        return redirect()->route('account.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id=null)
    {
        //
        // $r=(new summaryController)->pass($act='cuentas');
        // if($r>0){

        $hoy = Carbon::now()->format('Y-m-d');

     // $hoy=date('Y-m-d',strtotime('today + 1 day'));
        $categories = Category::all();
        $account = Account::all();
        $divisa = Setting::where('name','Soles')->first();

         //total saldo
        $response =array();

        foreach ($account as $a) {
            $tmp = Summary::where('date','<=',$hoy)->where('account_id',$id)->get();
            $total = 0;
            foreach ($tmp as $t) {
                if($t->type=='out'){
                    $total -= $t->amount;
                }else{
                    $total+= $t->amount;
                }
            }


            // $a->setAttribute('total',$total[$a->id]);
            // dd($total);

        }

        $totalf = $total;

      if(!is_null($id)){

        // dd($id);

        if(Summary::where('account_id',$id)->exists()){

          $summary = Summary::where('account_id',$id)->get();

            $start = $request->input('start');
            $finish = $request->input('finish');


            if((isset($start)) and (isset($finish))){

              $summary = Summary::where('account_id',$id)->whereBetween('date', [$start, $finish])->get();

            }else{

              $summary = Summary::where('date','<=',$hoy)->where('account_id',$id)->get();
            }
            // dd($summary);
            foreach ($summary as $s) {

                $name_account = Account::find($s->account_id);

                $s->setAttribute('name_account',$name_account->account_name);

                $name_categories = Category::find($s->category_id);

                $s->setAttribute('name_categories',$name_categories->name);

                  if(attached::where('summary_id',$s->id)->exists()){

                    $data_attached = attached::where('summary_id',$s->id)->first();

                    $s->setAttribute('attached',$data_attached);

                  }else{

                    $s->setAttribute('attached',null);

                  }
            }
        }else{

            $summary=array();
        }

        $nombre = Account::where('id',$id)->first();

        // dd($nombre);

        return view('admin.cuentas.detalle',['summary'=>$summary,'divisa'=>$divisa,'id'=>$id,'nombre'=>$nombre,'totalf'=>$totalf]);
      }

        $start = $request->input('start');
        $finish = $request->input('finish');

        if((isset($start)) and (isset($finish))){

          $start = Carbon::parse($start)->format('Y-m-d');
          $finish = Carbon::parse($finish)->format('Y-m-d');

          $summary = Summary::whereBetween('date', [$start, $finish])->get();
        }else{
          $summary = Summary::where('account_id',$id)->get();
        }


        foreach ($summary as $s) {
          $name_account = Account::find($s->account_id);

          $s->setAttribute('name_account',$name_account->account_name);

          $name_categories = Category::find($s->category_id);

          $s->setAttribute('name_categories',$name_categories->name);

          if(Attached::where('summary_id',$s->id)->exists()){

            $data_attached = Attached::where('summary_id',$s->id)->first();

            $s->setAttribute('attached',$data_attached);

          }else{

            $s->setAttribute('attached',null);

          }
        }

        return view('admin.cuentas.detalle',['summary'=>$summary,'divisa'=>$divisa,'id'=>null,'nombre'=>null,'totalf'=>null]);

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
        $data = Account::where('id',$id)->first();

        return view('admin.cuentas.edit',['data'=>$data]);
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
        $request->validate([
            'add_serie' => 'required|min:4|max:4|unique:accounts,add_serie,'.$id,
            'out_serie' => 'required|min:4|max:4|unique:accounts,out_serie,'.$id
        ]);

        $account = Account::find($id);
        $account->account_name = $request->name;
        $account->account_number = $request->number;
        $account->account_type = $request->type;
        $account->add_serie = strtoupper($request->add_serie);
        $account->out_serie = strtoupper($request->out_serie);
        $account->save();

        return redirect()->route('account.index');
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
        $account = Account::findOrFail($id);

        $summary = $account->summaries();

        dd($summary);

        $account->delete();

        return redirect()->route('account.index');
    }
}
