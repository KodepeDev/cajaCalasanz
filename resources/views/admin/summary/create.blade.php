@extends('adminlte::page')
@section('title', 'Dashboard')

@section('css')

@stop

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
        <div class="card card-primary">
            <div class="card-header">
                <i class="fa fa-bar-chart"></i>
                <h3 class="card-title">Nuevo Movimiento</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form id="search-form" role="form" action="/admin/summary/save" method="post" enctype="multipart/form-data">
                @method('POST')
                @csrf

                <div id="modal" class="modal">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Subcategorias</h4>
                                <button type="button" id="closemodal" class="close"
                                    data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body" id="res_ajax">

                            </div>
                            <div class="modal-footer">
                                <button style=" margin: 15px;" type="button" id="closemodal3" class="btn btn-default"
                                    data-dismiss="modal">Ok</button>

                            </div>
                        </div>

                    </div>
                </div>

                <div id="modal1" class="modal">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" id="closemodal1" class="close"
                                    data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Fecha de producto</h4>
                            </div>
                            <div class="modal-body" id="res_ajax1">

                            </div>
                            <div class="modal-footer">

                                <button style=" margin: 15px;" type="button" id="closemodal2" class="btn btn-default"
                                    data-dismiss="modal">Ok</button>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-sm-4">
                            <label for="exampleInputPassword1">Tipo de Movimiento</label>
                            <select class="form-control " id="type_movimiento" name="type">
                                <option value="">
                                    Seleccione Tipo
                                </option>
                                @if($type=="add")
                                    <option value="add" selected>
                                        Abono
                                    </option>
                                @elseif($type=="out")
                                    <option value="out" selected>
                                        Retiro
                                    </option>
                                @else
                                    <option value="add">
                                        Abono
                                    </option>
                                    <option value="out">
                                        Retiro
                                    </option>
                                @endif
                            </select>
                        </div>
                        <div class="form-group col-sm-4">
                            <label for="exampleInputEmail1">Concepto</label>
                            <input required maxlength="200" type="text" name="concept" class="form-control"
                                placeholder="motivo del movimiento">
                        </div>
                        <div class="form-group col-sm-4">
                            <label for="exampleInputPassword1">Monto</label>
                            <input required maxlength="200" name="amount" type="text" data-mask="000,000,000,000,000.00"
                                data-mask-reverse="true" class="form-control" placeholder="Monto">
                        </div>
                        <div class="form-group col-sm-4">
                            <label for="exampleInputPassword1">Impuesto</label>
                            <input required maxlength="200" name="tax" type="text" data-mask-reverse="true"
                                class="form-control" data-mask="000,000,000,000,000.00" placeholder="Impuesto">
                        </div>
                        <div class="form-group col-sm-4">
                            <label for="exampleInputPassword1">N° Ref</label>
                            <input maxlength="200" name="factura" type="text" class="form-control"
                                placeholder="N° de control">
                        </div>
                        <div class="form-group col-sm-4">
                            <label for="exampleInputPassword1">Categorias</label>

                            <select required class="form-control" name="categories_id" id="categorie_select">

                                <option class="" value="">Seleccione Categoria</option>
                                @foreach($data2 as $datas2)

                                        <option class="attr-{{ $datas2->type }}" value="{{ $datas2->id }}">

                                            {{ $datas2->name }}

                                        </option>
                                @endforeach
                            </select>

                        </div>
                        <div class="form-group col-sm-4">
                            <label for="exampleInputPassword1">Productos</label>

                            <select class="form-control" name="tours_id" id="tours_select">
                                <option class="" value="">No Aplica</option>
                                @foreach($tours as $tour)
                                    <option class="attr-{{ $tour->price }}" value="{{ $tour->id }}">
                                        {{ $tour->name }}
                                    </option>
                                @endforeach
                            </select>

                        </div>
                        <div class="form-group hidden" id="res_ajax">
                        </div>
                        <div class="form-group col-sm-4">
                            <label for="exampleInputPassword1">Cuentas</label>

                            <select required id="origen" class="form-control" name="account_id">
                                <option value="">Seleccione Cuenta</option>
                                @foreach($data as $datas)
                                    <option value="{{ $datas->id }}">
                                        {{ $datas->account_name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-group hidden" id="res_origen"></div>
                        </div>
                        <div class="form-group col-sm-4">
                            <div class="">
                                <label for="exampleInputPassword1">Fecha</label>
                                <input maxlength="200" name="created_at" type="date" required class="form-control"
                                    placeholder="Fecha">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="exampleInputEmail1">Cargar Adjunto</label>
                            <input type="file" name="path" class="form-control" placeholder="Nombre del archivo">
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>

@stop

@section('js')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $('#categorie_select').on('change',function(){
                    var value = $('#categorie_select').val();
                    $.get(
                            '/admin/categories/get_attr/'+value
                        ).done(function(res){
                            $('#res_ajax').html('');
                            if(res.length<1){
                                $('#res_ajax').addClass('hidden');
                            }else{
                                $('#modal').show();
                                $('#res_ajax').removeClass('hidden');
                                for(i in res){
                                    console.log(res[i].name);
                                    $('#res_ajax').append('<div  class="form-control col-sm-3 mb-3"><label><input type="radio" name="id_attr" value="'+res[i].id+'" >' +res[i].name+ '</label></div>');
                                }
                            }
                    });
                });
            $('#closemodal').click(function() {

                $('#modal').hide();
            });
            $('#closemodal3').click(function() {

                $('#modal').hide();
            });
        });

    </script>

@stop
