<div class="container-fluid spark-screen pt-4">
    <div class="card card-maroon">
        <div class="card-header">
            <h3 class="card-title">LISTA DE ESTUDIANTES</h3>
            <div class="card-tools">
                {{-- <a type="button" href="{{ route('students.deudores') }}" class="btn btn-warning">Ver
                    Deudores</a> --}}
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#globalModal"><i
                        class="fa fa-plus-circle" aria-hidden="true"></i> NUEVO</button>
            </div>
            <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <input type="search" class="form-control" wire:model="search"
                        placeholder="Buscar por N° de documento o Nombre o Stand">

                </div>
            </div>

            <div class="table-responsive">
                <table id="students" class="table table-bordered">
                    <thead class="bg-primary text-center">
                        <tr>
                            <th scope="col">DOCUMENTO</th>
                            <th scope="col">APELLIDOS Y NOMBRES</th>
                            <th scope="col">FOTO</th>
                            <th scope="col" width="15%" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($students->count() > 0)
                            @foreach ($students as $student)
                                <tr>
                                    <th scope="row">

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
                                    </th>

                                    <td>{{ $student->full_name }}</td>
                                    <td class="text-center">
                                        <span>
                                            <img src="{{ asset('storage/students/' . $student->Foto) }}"
                                                alt="imagen de ejemplo" height="30" width="30" class="rounded">
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-primary text-center"
                                            wire:click='edit({{ $student->id }})'><i class="fa fa-pen"></i></button>
                                        <a href="{{ route('students.detalle', $student->id) }}"
                                            class="btn btn-sm btn-info text-center"><i class="fa fa-eye"></i></a>
                                        <button type="button" class="btn btn-sm btn-danger text-center"
                                            onclick="anularStudent({{ $student->id }})"><i
                                                class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="text-center">No se encontró ningun registro</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            {{ $students->links() }}
        </div>
        <!-- /.card-footer -->
    </div>
    <!-- /.card -->
    @if ($selected_id == 0)
        @include('livewire.socios.form')
    @endif
    @if ($selected_id != 0)
        @include('livewire.socios.form-edit')
    @endif
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {

        // $('#socios').DataTable();

        window.livewire.on('error', msg => {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: msg,
            });
        });

        window.livewire.on('socio_added', msg => {
            Swal.fire({
                icon: 'success',
                title: 'Buen trabajo!',
                text: msg,
            });

            $('#globalModal').modal('hide');

            livewire.emit('resetUI');
        });
        window.livewire.on('socio_updated', msg => {
            Swal.fire({
                icon: 'success',
                title: 'Buen trabajo!',
                text: msg,
            });

            $('#globalModal').modal('hide');

            livewire.emit('resetUI');
        });

        window.livewire.on('show-modal', msg => {
            $('#globalModal').modal('show');
        });

    });

    function anularSocio(id) {
        Swal.fire({
            title: 'Estas seguro de anular este socio?',
            // text: "Recuerde que ya no podrá ser recuperado!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, Anular!'
        }).then((result) => {
            if (result.isConfirmed) {
                livewire.emit('anularSocio', id);

            }
        })
    }
</script>
