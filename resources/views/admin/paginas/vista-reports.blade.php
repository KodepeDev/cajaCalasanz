@extends('adminlte::page')
@section('title', 'Dashboard')

@section('css')

@stop

@section('content_header')
    <div class="card p-4">
        <h1 class="card-title">Reporte de Socios, Stands y Deudas</h1>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body row">
            <div class="col-md-4">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3>Socios</h3>
                        <p>Reporte de Socios</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-file"></i>
                    </div>
                    <button type="button" class="btn btn-block btn-danger"
                        onclick="printJS({printable:'{{ route('reportes.socios') }}', type: 'pdf', showModal:true, modalMessage: 'Cargando Documento ...'})">
                        <i class="fa fa-file-pdf"></i> Socios PDF
                    </button>
                </div>
            </div>

            <div class="col-md-4">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>Stands</h3>
                        <p>Reporte de Stands</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-file"></i>
                    </div>
                    <div class="btn-group d-flex" role="group" aria-label="">
                        <button type="button" class="btn btn-danger"
                            onclick="printJS({printable:'{{ route('reportes.stands') }}', type: 'pdf', showModal:true, modalMessage: 'Cargando Documento ...'})">
                            <i class="fa fa-file-pdf"></i> Stands PDF
                        </button>

                        <a class="btn btn-success" href="{{ route('reportes.stands.excel') }}">
                            <i class="fa fa-file-excel"></i> Stands Excel
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>Deudas</h3>
                        <p>Reporte de Deudas</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-file"></i>
                    </div>
                    <button type="button" class="btn btn-block btn-danger" data-toggle="modal"
                        data-target="#ModalExportDeuda">
                        <i class="fa fa-file-pdf"></i> Reporte Deudas
                    </button>

                    @livewire('export-components.modal-export-deuda')
                    {{-- <button type="button" class="btn btn-block btn-danger" onclick="printJS({printable:'{{route('reportes.stands')}}', type: 'pdf', showModal:true, modalMessage: 'Cargando Documento ...'})">
                    <i class="fa fa-file-pdf" ></i> Deudas PDFs
                </button> --}}
                </div>
            </div>

        </div>
    </div>
@stop

@section('js')
    <script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
    <link rel="stylesheet" href="https://printjs-4de6.kxcdn.com/print.min.css">
@stop
