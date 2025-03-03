<div class="container-fluid spark-screen">
    <div class="row">
        <div class="col-md-12 ">
        <div class="box-body">
            <div class="container-fluid spark-screen">
            <div class="row">
                <div class="col-md-12">
                <div class="box box-admin-border">

                    <a class="btn btn-primary " wire:click='create'> <i class="fa fa-plus"></i>Nuevo</a>
                    </div>

                    <div class="mt-3 table-responsive">
                        <div class="col-sm-12">
                            <table id="categories" class="table table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Tipo</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $categoriess)
                                    <tr>
                                    <td>{{ $categoriess->id }}</td>
                                    <td>{{ $categoriess->name }}</td>
                                    <td>{{ $categoriess->description }}</td>
                                    @if($categoriess->type=='add' )
                                        <td>Categoria de Ingreso</td>
                                    @else
                                        <td>Categoria de Retiro</td>
                                    @endif


                                        <td>



                                    @if($categoriess->id !==1 )
                                        <form role="form" action = "/categories/eliminar/{{ $categoriess->id }}" method="post"  enctype="multipart/form-data">
                                            {{method_field('DELETE')}}
                                            {{ csrf_field() }}
                                        <a class="btn btn-sm btn-default" href="/categories/edit/{{ $categoriess->id }}"><i class="fa fa-edit"></i></a>
                                        <button onclick='if(confirmDel() == false){return false;}' class="btn btn-sm btn-default" type="submit"><i class="fa fa-trash"></i></button></a>
                                        </form>
                                    @endif

                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    <!-- /.box-body -->
                    </div>
                </div>
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        console.log('secciones de desarrollo');
        $('#categories').DataTable({
                    "order":[[0,"desc"]],
                    "dom": "<'row'<'col-sm-10 'f><'col-sm-2  hidden-xs'B>>t<'bottom 'p>",
                    "lengthChange": true,
                    "responsive": true,
                    buttons: [
                        'pdf',
                        'excel',
                        'copy',
                    ]
        });
    });
</script>
