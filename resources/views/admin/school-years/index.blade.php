@extends('adminlte::page')
@section('title', 'Años Escolares')

@section('content_header')
    <h1 class="m-0 text-dark font-weight-bold">
        <i class="fas fa-calendar-alt mr-2 text-primary"></i>Gestión de Años Escolares
    </h1>
@stop

@section('content')
    @livewire('school-year.school-year-manager')
@stop
