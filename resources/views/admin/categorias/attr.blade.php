@extends('adminlte::page')
@section('title', 'Crear ATTR')

@section('css')

@stop

@section('content_header')
    <h1>Crear ATTR</h1>
@stop

@section('content')
<form role="form" action = "/admin/categories/save_attr/{{$categorie->id}}" method = "POST">
    @method('POST')
    @csrf
    <div class="card card-danger">
        <div class="card-header">
            <h3 class="card-title">Subcategorías <span class="badge badge-warning">Si no desea crear subcategorias puede continuar sin guardar</span></h3>
            <div class="card-tools">
                <button class="btn btn-info" type="button" id="btn_add_attr">
                    <i class="fa fa-plus"></i>
                </button>
                <button class="btn btn-warning" type="button" id="buttonremove">
                <i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="exampleInputPassword1">Nombre</label>
                        <input required maxlength="200" name="name_[]" type="text"   class="form-control"  placeholder="nombre de la subcategoria">
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="exampleInputPassword1">Descripción</label>
                        <input required maxlength="200" name="value_[]" type="text"   class="form-control"  placeholder="descripción de la subcategoria">
                    </div>
                </div>
                <div class="row" id="list_attr">
                </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="{{route('categorias')}}" class="btn btn-warning">Seguir Sin Guardar</a>
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
