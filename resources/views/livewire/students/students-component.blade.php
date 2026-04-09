<div class="container-fluid pt-3">

    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-user-graduate mr-1"></i> Estudiantes
                @if($schoolYear)
                    <span class="badge badge-primary ml-1">{{ $schoolYear->year }}</span>
                @endif
            </h3>
            <div class="card-tools d-flex align-items-center">
                @can('socios.deudores')
                    <a href="{{ route('students.deudores') }}" class="btn btn-warning btn-sm ml-2">
                        <i class="fas fa-exclamation-triangle mr-1"></i> Deudores
                    </a>
                @endcan
                <button type="button"
                        class="btn btn-primary btn-sm ml-2"
                        data-toggle="modal" data-target="#globalModal">
                    <i class="fas fa-plus mr-1"></i> Nuevo
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <input type="search"
                               wire:model="search"
                               class="form-control"
                               placeholder="Buscar por documento o nombre...">
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Documento</th>
                            <th>Apellidos y Nombres</th>
                            <th class="text-center" style="width:70px">Foto</th>
                            <th class="text-center" style="width:130px">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td class="align-middle">
                                    <span class="badge badge-secondary mr-1">
                                        @switch($student->document_type)
                                            @case(1) DNI @break
                                            @case(6) RUC @break
                                            @default  OTRO
                                        @endswitch
                                    </span>
                                    {{ $student->document }}
                                </td>
                                <td class="align-middle">{{ $student->full_name }}</td>
                                <td class="text-center align-middle">
                                    <img src="{{ asset($student->Foto) }}"
                                         alt="{{ $student->full_name }}"
                                         height="32" width="32"
                                         class="img-circle elevation-1">
                                </td>
                                <td class="text-center align-middle">
                                    <div class="btn-group btn-group-sm">
                                        <button wire:click="edit({{ $student->id }})"
                                                class="btn btn-primary btn-sm"
                                                title="Editar">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                        <a href="{{ route('students.detalle', $student->id) }}"
                                           class="btn btn-info btn-sm"
                                           title="Ver detalle">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button onclick="anularStudent({{ $student->id }})"
                                                class="btn btn-danger btn-sm"
                                                title="Anular">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted p-4">
                                    <i class="fas fa-inbox fa-2x d-block mb-2"></i>
                                    No se encontró ningún estudiante.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer clearfix">
            {{ $students->links() }}
        </div>
    </div>

    @include('livewire.socios.form')

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        window.livewire.on('error', msg => {
            Swal.fire({ icon: 'error', title: 'Oops...', text: msg });
        });

        window.livewire.on('socio_added', msg => {
            Swal.fire({ icon: 'success', title: '¡Registrado!', text: msg });
            $('#globalModal').modal('hide');
            livewire.emit('resetUI');
        });

        window.livewire.on('socio_updated', msg => {
            Swal.fire({ icon: 'success', title: '¡Actualizado!', text: msg });
            $('#globalModal').modal('hide');
            livewire.emit('resetUI');
        });

        window.livewire.on('show-modal', () => {
            $('#globalModal').modal('show');
        });

    });

    function anularStudent(id) {
        Swal.fire({
            title: '¿Anular este estudiante?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, anular',
            cancelButtonText: 'Cancelar',
        }).then(result => {
            if (result.isConfirmed) {
                livewire.emit('anularSocio', id);
            }
        });
    }
</script>
