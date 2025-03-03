<?php

namespace App\Http\Controllers;

use App\Models\AttrValue;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $categories = Category::where('id', '!=', 1)->get();

        return view('admin.categorias.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.categorias.create');
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
        $valores = array(
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            );
        $category = Category::create($valores);

        // dd($category->id);

        // return redirect('/admin/categories/view_attr/'.$category->id);
        return redirect('/admin/categories/');
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
        $category = Category::findOrFail($id);

		$attribute = AttrValue::where('category_id',$id)->get();

		return view('admin.categorias.edit',['data'=>$category,'data1'=>$attribute]);

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
        $name = $request->input('name');
		$names = $request->input('name_');
		$ids= $request->input('id');
    	$values = $request->input('value_');


    	if($names !== null){
            foreach ($names as $n => $v ) {
                $valores = array(
                        'name' => $v,
                        'value' => $values[$n],
                        'category_id' => $id,
                        );
                if($ids[$n]==0){
                    AttrValue::insert($valores);
                }else{
                    AttrValue::where('id',$ids[$n])->update($valores);
                }

            }
        }

        $categories = Category::find($id);
        $categories->name = $request->name;
		$categories->description = $request->description;
		$categories->type = $request->type;
		$categories->save();

        return redirect('admin/categories');
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

        $categories = Category::find($id);

		$data = Category::where('id',$id)->first();
    	$attributes = AttrValue::where('category_id',$id)->delete();

        $categories->delete();

        return redirect('admin/categories');
    }




    public function view_attr($id=null){

        $categorie = Category::find($id);
        return view('admin.categorias.attr',['categorie'=>$categorie]);

    }

    public function save_attr(Request $request,$id=null){


            $name = $request->input('name_');
            $values = $request->input('value_');

            foreach ($name as $n => $v) {
                $valores = array(
                    'name' => $v,
                    'value' => $values[$n],
                    'category_id' => $id
                );
                AttrValue::insert($valores);
            }

            return redirect('admin/categories');

    }

    public function destroyattr( $id)
  	{
        $categories = AttrValue::find($id);
        $categories->delete();

        return back();
    }

    public function get_all($id){

    	$data = AttrValue::where('category_id',$id)->get();
    	return response()->json($data);

    }
}
