<div class="pt-4">
    <div class="container-fluid">

        <!-- Timelime example  -->
        <h1>Historial de Modificaciones por Usuario</h1>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <!-- The time line -->
                <div class="timeline">
                    <!-- timeline time label -->
                    @foreach ($bitacoras as $item)
                        <div>
                            @switch($item->type)
                                @case('add')
                                <i class="fas fa-plus bg-success"></i>
                                    @break
                                @case('update')
                                <i class="fas fa-pencil-alt bg-warning"></i>
                                    @break
                                @case('delete')
                                <i class="fas fa-trash-alt bg-danger"></i>
                                    @break
                                @case('transfer')
                                <i class="fas fa-random bg-info"></i>
                                    @break
                                @default
                                <i class="fas fa-envelope bg-blue"></i>
                            @endswitch
                            <div class="timeline-item">
                                <span class="time"><i class="fas fa-clock"></i>{{$item->created_at->format('d/m/Y h:i A')}}</span>
                                <h3 class="timeline-header"><a href="#">{{$item->user->first_name}} </a><span class="badge badge-pill badge-primary">{{$item->type}}</span></h3>

                                <div class="timeline-body">
                                    {{$item->activity}}
                                </div>
                                <div class="timeline-footer">
                                    {{-- <a class="btn btn-primary btn-sm">Read more</a>
                                    <a class="btn btn-danger btn-sm">Delete</a> --}}
                                </div>
                            </div>
                        </div>

                    @endforeach
                    <div>
                        <i class="fas fa-clock bg-gray"></i>
                    </div>
                </div>
            </div>
            {{$bitacoras->links()}}
            <!-- /.col -->
        </div>
    </div>
    <!-- /.timeline -->
</div>
