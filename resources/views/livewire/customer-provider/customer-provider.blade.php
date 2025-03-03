<div class="container-fluid pt-4">
    <div wire:ignore  class="card card-danger">
        <div class="card-header">
            <h3 class="card-title">Listado de personas</h3>
            <div class="card-tools">
                @can('clientes.edit')
                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#globalModal">Nuevo <i class="fa fa-plus"></i></button>
                @endcan
            </div>
            <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table id="personas" class="table table-bordered table-hover">
                <thead class="bg-danger">
                    <tr>
                        <th>Nombres</th>
                        <th>Correo</th>
                        <th>DNI o RUC</th>
                        <th>Etapa</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
    @include('livewire.customer-provider.modalCustomer')
</div>

<script>
    document.addEventListener("DOMContentLoaded", function(){
        $(document).ready(function() {
            let table = $("#personas").DataTable({
                dom: 'Bftrip',
                "responsive": true, "lengthChange": true, "autoWidth": false,
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
                },
                "buttons": ["copy", "excel", "pdf", "print"],
                processing: true,
                serverSide: true,
                "ajax": "{{route('data.cliente-proveedor')}}",
                "dataType": "json",
                "type": "POST",
                "columns": [
                    {data: 'full_name'},
                    {data: 'email'},
                    {data: 'document'},
                    {data: 'etapa'}
                ],
            }).buttons().container().appendTo('#personas_wrapper .col-md-6:eq(0)');

            window.livewire.on('error', msg => {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: msg,
                });
                $("#globalModal").modal('hide');
            });
            window.livewire.on('customer_added', msg => {
                $("#globalModal").modal('hide');
                Swal.fire({
                    icon: 'error',
                    title: 'Buen Trabajo!',
                    text: msg,
                });
                table.ajax.reload(null, false);
            });

            $('#globalModal').on('shown.bs.modal', function () {
                livewire.emit('resetUI');
            });
        });
    });
</script>
