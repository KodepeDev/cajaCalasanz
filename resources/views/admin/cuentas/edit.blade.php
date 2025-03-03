@extends('adminlte::page')
@section('title', 'Editar cuenta')

@section('css')

@stop

@section('content_header')
    <h1>Cuentas</h1>
@stop

@section('content')
    <form role="form" action = "/admin/account/editar/{{ $data->id }}" method="post">
        @method('PUT')
        @csrf
        <div class="card card-danger">
            <div class="card-header with-border">
                <i class="fa fa-bank"></i>
                <h3 class="card-title">Editar Cuenta</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->


            <div class="card-body">
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="exampleInputEmail1">Nombre de la cuenta</label>
                        <input type="text" required maxlength="200" name="name" value="{{ $data->account_name }}"
                            class="form-control" placeholder="Nombre de la cuenta">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="exampleInputPassword1">Número de cuenta</label>
                        <input name="number" maxlength="200" type="number" value="{{ $data->account_number }}"
                            class="form-control" placeholder="Numero de cuenta">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="add_serie">SERIE INGRESO</label>
                        <input name="add_serie" type="text" value="{{ $data->add_serie }}" maxlength="200"
                            class="form-control" placeholder="Numero de cuenta">
                        @error('add_serie')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group col-md-3">
                        <label for="out_serie">SERIE GASTO</label>
                        <input name="out_serie" type="text" value="{{ $data->out_serie }}" maxlength="200"
                            class="form-control" placeholder="Numero de cuenta">
                        @error('out_serie')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label for="exampleInputPassword1">Tipo de Cuenta</label>
                        <select required class="form-control" name="type">

                            @if ($data->account_type == 'ahorro')
                                <option value="ahorro" selected>
                                    ahorro
                                </option>
                                <option value="corriente">
                                    corriente
                                </option>
                            @else
                                <option value="corriente" selected>
                                    corriente
                                </option>
                                <option value="ahorro">
                                    ahorro
                                </option>
                            @endif
                        </select>

                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-info">Actualizar</button>
                <a href="{{ route('account.index') }}" class="btn btn-warning">Cancelar</a>
            </div>

        </div>
    </form>
@stop

@section('js')

@stop
