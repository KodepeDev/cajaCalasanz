@extends('adminlte::page')
@section('title', 'Usuario no activo o no autorizado')

@section('css')
    <style>

        .error-title{
            color: #fff;
            font-size: 200px!important;
            line-height: 1;
            margin-top: 20px;
            margin-bottom: 40px;
            font-weight: 300;
            text-stroke: 1px transparent;
            display: block;
            text-shadow: 0 1px 0 #ccc, 0 2px 0 #c9c9c9, 0 3px 0 #bbb, 0 4px 0 #b9b9b9, 0 5px 0 #aaa, 0 6px 1px rgba(0, 0, 0, 0.1), 0 0 5px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.3), 0 3px 5px rgba(0, 0, 0, 0.2), 0 5px 10px rgba(0, 0, 0, 0.25), 0 10px 10px rgba(0, 0, 0, 0.2), 0 20px 20px rgba(0, 0, 0, 0.15);
        }
        .title-error {
            font-weight: 700;
            font-size: 30px;
        }
</style>
@stop

@section('content_header')

    <div class="content d-flex justify-content-center align-items-center">
        <div class="flex-fill">
            <div class="text-center mb-3">
                <h1 class="error-title">401</h1>
                <h5 class="no-spacing mt-4 mb-4">Vaya, se ha producido un error. </h5>
                <h3 class="title-error no-spacing">¡No tienes permisos o no estas habilitado para acceder a esta página!</h3>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="row">
                        <div class="col-lg-12 mt-2 text-center title-error">Contacte con el Administrador del sistema</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('content')

@stop

@section('js')

@stop
