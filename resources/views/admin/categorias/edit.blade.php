@extends('adminlte::page')
@section('title', 'Editar Categoria')

@section('css')

@stop

    @section('content_header')
    <h1>Editar Categoría (Concepto)</h1>
    @stop

        @section('content')
        <form role="form" action="/admin/categories/edit/{{ $data->id }}" method="post">
            @method('PUT')
            @csrf
        <div class="card card-danger">
            <div class="card-header">
                <h3 class="card-title">Editar Categoria</h3>
                <div class="card-tools">
                    {{-- <button class="btn btn-warning" type="button" id="btn_add_attr">
                        <i class="fa fa-plus"></i>
                    </button>
                    <button class="btn btn-danger shadow" type="button" id="buttonremove">
                        <i class="fa fa-minus"></i>
                    </button> --}}
                </div>
            </div>

            <!-- /.card-header -->
            <!-- form start -->
            <div class="card-body">
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="exampleInputEmail1">Nombre de la categoria</label>
                        <input required maxlength="200" type="text" name="name" value="{{ $data->name }}"
                            class="form-control" placeholder="Nombre de la categoria ">
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="exampleInputPassword1">Tipo de Categoria</label>
                        <select name="type" class="form-control">
                            @if($data->type=='add')
                                <option value="add" selected>Categoria de Ingreso</option>
                                <option value="out">Categoria de Gasto</option>
                            @else
                                <option value="add">Categoria de Ingreso</option>
                                <option value="out" selected>Categoria de Gasto</option>
                            @endif
                        </select>
                    </div>
                    <div class="form-group col-sm-12">
                        <label for="">Descripción</label>
                        <textarea maxlength="200" name="description" class="form-control" cols="" rows="5">{{ $data->description }}</textarea>
                    </div>
                </div>
                {{-- <div class="bg-secondary rounded p-3">
                    <h5>Subcategorías</h5>
                    <hr>
                    @foreach($data1 as $data1s)
                        <div class="row">
                            <div class="col-md-6">
                                <label for="exampleInputPassword1">Nombre </label>
                                <div class="form-group">

                                    <input type="hidden" value="{{ $data1s->id }}" name="id[]">
                                    <input required maxlength="200" name="name_[]" type="text"
                                        value=" {{ $data1s->name }}" class="form-control"
                                        placeholder="Nombre de la subcategoria">

                                </div>
                            </div>
                            <div class="col-md-5">
                                <label for="exampleInputPassword1"> Descripción </label>
                                <div class="form-group">
                                    <input required maxlength="200" name="value_[]" type="text"
                                        value="{{ $data1s->value }}" class="form-control" placeholder="Descripcion de la subcategoria">

                                </div>
                            </div>
                            <div class="col-md-1">
                                <label for="exampleInputPassword1"> &nbsp; </label>
                                <div class="form-group">
                                    <a class="text-danger" onclick='if(confirmDel() == false){return false;}'
                                        href="/admin/categories/eliminarattr/{{ $data1s->id }}"><i
                                            class="fa fa-trash"></i></a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                <div class="row" id="list_attr">
                </div> --}}
            {{-- </div> --}}
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-warning">Actualizar</button>
            </div>

        </div>
        </form>
        @stop



        @section('js')
        <script>
            document.addEventListener('DOMContentLoaded', function () {

                $('#btn_add_attr').on('click',function(){
                    console.log('el boton funciona');
                }); //

                $('#btn_add_attr').on('click',function(){
                    $('#list_attr').append('<div class="form-group col-sm-6"><label for="exampleInputPassword1">Nombre</label>\
                                <input required maxlength="200" name="name_[]" type="text" class="form-control" placeholder="nombre de la subcategoria">\
                                </div>\
                                <div class="form-group col-sm-6">\
                                <label for="exampleInputPassword1">Descripción</label>\
                                <input required maxlength="200" name="value_[]" type="text"  class="form-control" placeholder="descripción de la subcategoria">\
                                <input type="hidden" value="0" name="id[]">\
                                </div>');

                });
                //tours
                $('#btn_add_attr2').on('click',function(){
                    $('#list_attr2').append('<div class="form-group col-sm-6"><label for="exampleInputPassword1">Fecha de salida</label>\
                                <input required maxlength="200" name="date[]" type="date" class="form-control" placeholder="Fecha">\
                                </div>\
                                <div class="form-group col-sm-6">\
                                <label for="exampleInputPassword1">precio</label>\
                                <input required maxlength="200" name="price[]" type="text"   data-mask="000,000,000,000,000.00" data-mask-reverse="true"    class="form-control"  placeholder="Precio">\
                                <input type="hidden" value="0" name="id[]">\
                                </div>');
                    $('input[type="date"]').attr('type','date1');
                /**/
                $( 'input[type="date1"]' ).datepicker({dateFormat:"yy-mm-dd"});
                });

                $("#buttonremove").click(function(){
                $("#list_attr2").empty();
                });

                $("#buttonremove").click(function(){
                $("#list_attr").empty();
                });

                });
        </script>
        @stop
