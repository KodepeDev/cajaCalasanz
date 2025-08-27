<div class="container-fluid spark-screen pt-4">
    <div class="card card-maroon">
        <div class="card-header">
            <h3 class="card-title">DOCENTES</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-warning" wire:click="newTeacher()">
                    <i class="fas fa-plus"></i> NUEVO
                </button> 
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="bg-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">APELLIDOS Y NOMBRES</th>
                        <th scope="col">ESTADO</th>
                        <th scope="col">GRADO</th>
                        <th scope="col" class="text-center">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($teachers) > 0)
                        @foreach ($teachers as $teacher)
                            <tr>
                                <th scope="row">{{ $teacher->document }}</th>
                                <td>{{ $teacher->full_name }}</td>
                                <td>
                                    @if ($teacher->is_active)
                                        <span class="badge badge-success">ACTIVO</span>
                                    @else
                                        <span class="badge badge-danger">INACTIVO</span>
                                    @endif
                                </td>
                                <td>{{ $teacher->grade ? $teacher->grade->name : '' }}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-primary" wire:click="edit({{ $teacher->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5">
                                <p class="text-center">
                                    No hay registros
                                </p>
                            </td>
                        </tr>
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5">
                            {{ $teachers->links() }}
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @include('livewire.teachers.form')
    @push('js')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                window.livewire.on('showModal', msg => {
                    $('#globalModal').modal('show');
                });
                window.livewire.on('teacher-added', msg => {
                    $('#globalModal').modal('hide');
                    noty(msg);
                });
                window.livewire.on('teacher-updated', msg => {
                    $('#globalModal').modal('hide');
                    noty(msg);
                });
                window.livewire.on('teacher-deleted', msg => {
                    noty(msg);
                });
            });
        </script>
    @endpush
</div>
