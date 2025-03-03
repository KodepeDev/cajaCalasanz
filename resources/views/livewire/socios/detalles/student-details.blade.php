<div class="pt-4">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-2">

                    <!-- Profile Image -->
                    <div class="card card-danger card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <img class="profile-user-img img-fluid img-circle"
                                    src="{{ asset('storage/students/' . $student->foto) }}" alt="User profile picture">
                            </div>

                            <h3 class="profile-username text-center">{{ $student->full_name }}</h3>

                            <p class="text-muted text-center">DETALLE DE ESTUDIANTE</p>

                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>Pagos {{ date('Y') }}</b> <a class="float-right">S/.
                                        {{ number_format($suma2023, 2) }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Pagos {{ date('Y') - 1 }}</b> <a class="float-right">S/.
                                        {{ number_format($suma2022, 2) }}</a>
                                </li>
                            </ul>

                            <a href="{{ route('students.index') }}" class="btn btn-secondary btn-block"><b>Volver a
                                    Lista
                                    de estudiantes</b></a>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->

                    <!-- About Me Box -->
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title">Sobre el Estudiante</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <strong><i class="fas fa-book mr-1"></i> DOCUMENTO </strong>

                            <p class="text-muted">
                                @switch($student->document_type)
                                    @case(1)
                                        DNI
                                    @break

                                    @case(6)
                                        RUC
                                    @break

                                    @default
                                        OTRO
                                @endswitch
                                -{{ $student->document }}
                            </p>

                            <hr>

                            <strong><i class="fas fa-map-marker-alt mr-1"></i> DIRECCIÓN</strong>

                            <p class="text-muted">{{ $student->address }}</p>

                            <hr>

                            <strong><i class="far fa-file-alt mr-1"></i> Nombres</strong>

                            <p class="text-muted">{{ $student->first_name }} {{ $student->last_name }}</p>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
                <div class="col-md-10">
                    <div class="card card-warning">

                        <div wire:loading.class='overlay' class="d-none dark" wire:loading.class.remove='d-none'>
                            <i class="fas fa-2x fa-sync-alt fa-spin"></i>
                        </div>

                        <div class="card-header">
                            <h3 class="card-title">PAGOS Y DEUDAS</h3>
                            <div class="card-tools">
                                <button class="btn btn-info" data-toggle="modal" data-target="#nuevoRegistro"><i
                                        class="fa fa-plus" aria-hidden="true"></i> Nuevo</button>
                            </div>
                        </div><!-- /.card-header -->
                        <div class="card-body">

                            <div class="row">
                                <div class="row col-md-8">
                                    <div class="form-group col-sm-6">
                                        <input type="date" wire:model.defer='start1' name="start"
                                            placeholder="Fecha Inicio" class="form-control form-control-sm">
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <input type="date" wire:model.defer="finish1" name="finish"
                                            placeholder="Fecha Final" class="form-control form-control-sm">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="btn-group btn-block" role="group" aria-label="">
                                        <button type="submit" class="btn btn-sm  btn-info"
                                            wire:click.prevent='Filter'><i class="fas fa-filter"></i></button>
                                        <button type="submit" class="btn btn-sm  btn-warning"
                                            wire:click.prevent='clearFilter'><i class="fas fa-broom"></i></button>
                                    </div>
                                </div>
                            </div>

                            <table id="students" class="table table-bordered table-hover">
                                <thead class="bg-primary">
                                    <tr>
                                        <th scope="col" width="30%">CONCEPTO</th>
                                        <th scope="col" width="20%" class="text-center">CATEGORÍA</th>
                                        <th scope="col" width="10%" class="text-center">PERIODO</th>
                                        <th scope="col" width="10%" class="text-center">ESTADO</th>
                                        <th scope="col" width="15%" class="text-center">IMPORTE</th>
                                        <th scope="col" width="15%" class="text-center" style="width: 200px">
                                            ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($movimientos) > 0)
                                        @foreach ($movimientos as $item)
                                            <tr>
                                                <th scope="row">{{ $item->description }}</th>
                                                <td class="text-center">{{ $item->category->name }}</td>
                                                <td class="text-center">{{ $item->date->format('m/Y') }}</td>

                                                @if ($item->status == 1)
                                                    <td class="text-center"><small
                                                            class="badge badge-success bordered">PAGADO <br>
                                                            {{ $item->date_paid->format('d-m-Y') }}</small></td>
                                                @else
                                                    <td class="text-center"><small
                                                            class="badge badge-danger bordered">PENDIENTE</small></td>
                                                @endif


                                                <td class="text-center">{{ number_format($item->amount, 2) }}
                                                    @if ($item->type == 'add')
                                                        <small
                                                            class="badge badge-success bordered float-right">{{ $item->currency->name ?? 'Soles' }}
                                                            <i class="fa fa-sort-up"></i></small>
                                                    @else
                                                        <small
                                                            class="badge badge-danger bordered float-right">{{ $item->currency->name ?? 'Soles' }}
                                                            <i class="fa fa-sort-down"></i></small>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group" role="group" aria-label="">
                                                        {{-- <a href="javascript:void(0)"
                                                            wire:click='showDetail({{ $item->id }})'
                                                            class="btn btn-sm btn-info text-center"><i
                                                                class="fa fa-eye"></i></a> --}}
                                                        @if ($item->status == 0)
                                                            <button onclick="editar({{ $item->id }})"
                                                                class="btn btn-sm btn-primary text-center"><i
                                                                    class="fa fa-pen"></i></button>
                                                            <button onclick="eliminar({{ $item->id }})"
                                                                class="btn btn-sm btn-danger text-center"><i
                                                                    class="fa fa-trash"></i></button>
                                                        @else
                                                            {{-- <a href="{{route('movimientos.ticket.recibo', $item->id)}}" target="_blank" class="btn btn-sm btn-danger" ><i class="fa fa-print"></i></a> --}}
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6" class="text-center">No se encontró ningun registro</td>
                                        </tr>
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th scope="row" colspan="1" class="text-right">Totales</th>
                                        <td colspan="2" class="text-center">Pagados: S/.
                                            {{ number_format($sumaTotal, 2) }} <br>Pendientes: S/.
                                            {{ number_format($sumaTotalPendiente, 2) }}</td>
                                        <td colspan="2" class="text-center">Pagados: $.
                                            {{ number_format($sumaTotalDolar, 2) }} <br>Pendientes: $.
                                            {{ number_format($sumaTotalPendienteDolar, 2) }}</td>
                                        <td colspan="2" class="text-center">
                                            @if ($url)
                                                <a target="_blank" href="{{ $url }}"
                                                    class="btn btn-danger">
                                                    <i class="fa fa-file-pdf-o"></i> Reporte PDF
                                                </a>
                                            @endif
                                        </td>

                                    </tr>
                                    <tr>
                                        <td colspan="6">
                                            {{ $movimientos->links() }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div><!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->

        {{-- @include('livewire.socios.detalles.view_register') --}}

    </section>

    @livewire('socios.detalles.nuevo-registro', ['student' => $student], key($student->id))
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        window.livewire.on('error', msg => {
            Swal.fire({
                icon: 'error',
                title: 'Opss...!',
                text: msg,
            });
        });

        window.livewire.on('error_fecha', msg => {
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: msg,
            });
        });
        window.livewire.on('showDetail', msg => {
            $('#modalRegistro').modal('show');
        });
        window.livewire.on('close_modal', msg => {
            $('#modalRegistro').modal('hide');
        });

        window.livewire.on('eliminado', msg => {
            Swal.fire(
                'Eliminado!',
                'Tu registro se eliminó exitosamente.',
                'success'
            )
        });

        // $('#nuevoRegistro').modal('show');

    });

    function editar(id) {
        livewire.emit('editMovimiento', id);
    }

    function eliminar(id) {
        Swal.fire({
            title: 'Estas seguro de eliminar este registro?',
            text: "Recuerde que ya no podrá ser recuperado!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, Eliminar!'
        }).then((result) => {
            if (result.isConfirmed) {
                livewire.emit('eliminarDetalle', id);

            }
        })
    }
</script>
