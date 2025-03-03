@extends('adminlte::page')
@section('title', 'Dashboard')

@section('css')

@stop

@section('content_header')
    <h1>Panel de Inicio</h1>
    <div class="row mt-2">
        <div class="col-md-3">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>S/. {{ number_format($saldo_actual, 2) }}</h3>
                    <p>Saldo Actual</p>
                </div>
                <div class="icon">
                    <i class="fas fa-cart-plus"></i>
                </div>
                <a href="{{ route('movimientos.listado') }}" class="small-box-footer">
                    Ir a movimientos<i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>S/. {{ number_format($ingresos, 2) }}</h3>
                    <p>Total de Ingresos del último año</p>
                </div>
                <div class="icon">
                    <i class="fas fa-cart-plus"></i>
                </div>
                <a href="{{ route('movimientos.listado') }}" class="small-box-footer">
                    Ir a movimientos<i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-md-3">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>S/. {{ number_format($gastos, 2) }}</h3>
                    <p>Total de Gastos del último año</p>
                </div>
                <div class="icon">
                    <i class="fas fa-cart-arrow-down"></i>
                </div>
                <a href="{{ route('movimientos.listado') }}" class="small-box-footer">
                    Ir a movimientos<i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-md-3">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $socios }} Asociados</h3>
                    <p>Total de Socios activos CC</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="{{ route('students.index') }}" class="small-box-footer">
                    Ir a Socios<i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    @livewire('movimientos.components.grafica-movimiento')

@stop

@section('content')

@stop

@section('js')

@stop
