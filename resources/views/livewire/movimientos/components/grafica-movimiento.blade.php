<div>
    <div class="row">
        <div class="col-md-12 ">

            <div class="card">
                <div class="card-header bg-dark">
                    <i class="fa fa-bar-chart"></i>
                    <h3 class="card-title">Grafica de movimientos</h3>
                </div>
                {{-- <div class="col-sm-12 add_top_10">
                    <div class="form-group col-sm-5">
                        <input type="date" name="start" id="start" class="form-control">
                    </div>
                    <div class="form-group col-sm-5">
                        <input type="date" name="finish" id="finish" class="form-control">
                    </div>
                    <div class="form-group col-sm-2">
                        <a href="javascript:void(0)" id="filter_btn" class="btn btn-sm btn-default form-control"><i
                                class="fa fa-filter"></i> Filtrar</a>
                    </div>
                </div> --}}
                <div class="card-body">
                    {!! $chartData->container() !!}

                    <script src="{{ $chartData->cdn() }}"></script>

                    {{ $chartData->script() }}
                </div>
            </div>
        </div>
    </div>
</div>
