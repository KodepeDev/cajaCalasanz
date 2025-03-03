@extends('adminlte::page')
@section('title', 'Editar Movimiento')

@section('css')

@stop

@section('content_header')
<h1>Editar Movimiento</h1>
@stop

@section('content')

        <div class="card card-primary">
            <div class="card-header with-border">
                <h3 class="card-title">Editar Movimiento <i class="fa fa-edit"></i></h3>


                <div class="card-tools">

                    @if($data->attached)

                    <a href="/attached/edit/{{ $data->attached->id }}"
                        class="btn btn-info  waves-effect waves-light"><i
                            class="fa fa-paperclip"></i> Editar Adjunto</a>
                    @else
                        <a href="/attached/create/{{ $data->id }}" class="btn btn-info  waves-effect waves-light"
                        ><i class="fa fa-paperclip"></i> Agregar Adjunto</a>

                    @endif

                </div>

            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form id="search-form" role="form" action="/admin/summary/editar/{{ $data->id }}" method="post"
                enctype="multipart/form-data">
                @method('PUT')
                @csrf

                <div id="modal" class="modal">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" id="closemodal" class="close"
                                    data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Subcategorias</h4>
                            </div>
                            <div class="modal-body" id="res_ajax">

                            </div>
                            <div class="modal-footer">

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
                                <h4 class="modal-title">Fecha de salida de tours</h4>
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
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="exampleInputPassword1">Tipo de Movimiento</label>

                                <select class="form-control" name="type">

                                    @if($data->type=='add')
                                        <option value="add" selected>
                                            Abono
                                        </option>
                                        <option value="out">
                                            Retiro
                                        </option>

                                    @else
                                        <option value="out" selected>
                                            Retiro
                                        </option>
                                        <option value="add">
                                            Abono
                                        </option>
                                    @endif

                                </select>

                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Motivo</label>
                                <input required maxlength="200" type="text" name="concept" value="{{ $data->concept }}"
                                    class="form-control" placeholder="Motivo">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Monto</label>

                                <input type="text" step="any" required maxlength="200" data-mask-reverse="true" step="0.01"
                                    name="amount" value="<?php echo $data->amount ?>" class="form-control"
                                    placeholder="Monto del Movimiento">

                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Impuesto</label>

                                <input type="text" maxlength="200" name="tax" value="<?php echo $data->tax ?>"
                                    data-mask-reverse="true" class="form-control" placeholder="Impuesto">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="exampleInputPassword1">N° Ref</label>
                                <input maxlength="200" name="factura" value="{{ $data->factura }}" type="text"
                                    class="form-control" placeholder="N° Ref">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1">cuentas</label>

                                <select class="form-control" name="account_id">
                                    @foreach($account as $accounts)
                                        @if($data->account_id == $accounts->id)
                                            <option value="{{ $accounts->id }}" selected>
                                                {{ $accounts->account_name }}
                                            </option>
                                        @else
                                            <option value="{{ $accounts->id }}">
                                                {{ $accounts->account_name }}
                                            </option>
                                        @endif
                                    @endforeach

                                </select>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Categoria</label>
                                <select class="form-control" id="categorie_select" name="categories_id">

                                    @foreach($categories as $categoriess)
                                        @if($data->categories_id == $categoriess->id)

                                            <option value="{{ $categoriess->id }}" selected>
                                                {{ $categoriess->name }}
                                            </option>
                                        @else
                                            <option value="{{ $categoriess->id }}">
                                                {{ $categoriess->name }}
                                            </option>
                                        @endif
                                    @endforeach

                                </select>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="exampleInputPassword1">Tours</label>

                                <select class="form-control" name="tours_id" id="tours_select">
                                    <option class="" value="">No Aplica</option>
                                    @foreach($tours as $tour)
                                        <option class="attr-{{ $tour->price }}" value="{{ $tour->id }}">
                                            {{ $tour->name }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="exampleInputPassword1">Fecha </label>
                                <?php  $datef= date_create($data->created_at);
                            $fecha=date_format($datef, 'Y-m-d ');?>
                                <input required class="form-control" name="created_at" type="date"
                                    value="<?php echo date('Y-m-d',strtotime($fecha)) ?>" />


                                {{-- <input  value="{{ $data->created_at }}" data-mask="0000/00/00 00:00:00" data-mask-reverse="true"   maxlength="200" name="created_at" type="date" required class="form-control"  placeholder="Fecha"> --}}
                            </div>
                        </div>
                    </div>
                </div>




                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <a href="/admin/movimientos" class="btn btn-warning">Cancelar</a>
                </div>
            </form>
        </div>

@stop

@section('js')

@stop
