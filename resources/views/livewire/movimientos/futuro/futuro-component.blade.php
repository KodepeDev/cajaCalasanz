<div class="pt-4">
    <div class="container-fluid">
         <div class="card card-warning">
             <div class="card-header">
                 <i class="fa fa-bar-chart"></i>
                 <h3 class="card-title"><i class="fas fa-fast-forward"></i> <b>Movimientos Futuros</b></h3>
                 <div class="card-tools">
                     {{-- <a class="btn btn-warning" href="{{route('movimientos.crear')}}"> <i class="fa fa-plus"></i> Nuevo</a> --}}
                 </div>
             </div>

             <div class="card-body">
                     <div class="row">
                         <div class="row col-sm-6">
                             <div class="form-group col-sm-6">
                                <input type="date" wire:model.lazy='start1' name="start" placeholder="Fecha Inicio" class="form-control form-control-sm">
                                @error('start1')
                                    <span class="error">{{$message}}</span>
                                @enderror
                             </div>
                             <div class="form-group col-sm-6">
                                <input type="date" wire:model.lazy="finish1" name="finish" placeholder="Fecha Final" class="form-control form-control-sm">
                                @error('finish1')
                                    <span class="error">{{$message}}</span>
                                @enderror
                             </div>
                         </div>
                         <div class="row col-md-6">
                             <div class="col-sm-4">
                                <select class="form-control form-control-sm" type="text" wire:model.lazy="tipo1" name="tipo">
                                    <option value="">Tipo de movimiento</option>
                                    <option value="add">Abonos</option>
                                    <option value="out">Retiros</option>
                                </select>
                                @error('tipo1')
                                    <span class="error">{{$message}}</span>
                                @enderror
                             </div>
                             <div class="col-sm-4">
                                <select class="form-control form-control-sm" type="text" wire:model.lazy="cuenta_id1" name="cuentas">
                                    <option value="">Cuentas</option>
                                    @foreach ($accounts as $datas)
                                        <option value="{{ $datas->id }}">{{ $datas->account_name }}</option>
                                    @endforeach
                                </select>
                                @error('cuenta_id1')
                                    <span class="error">{{$message}}</span>
                                @enderror
                             </div>

                             <div class="col-sm-4">
                                 <div class="btn-group btn-block" role="group" aria-label="">
                                    <button type="button" {{(($start1 != "" && $finish1 != "") || $tipo1 != "" || $cuenta_id1 != "") ? '' : 'disabled'}} class="btn btn-sm btn-primary" wire:click.prevent='Filter'><i class="fas fa-filter"></i></button>
                                    <button type="button" class="btn btn-sm btn-warning" wire:click.prevent='clearFilter'><i class="fas fa-broom"></i></button>
                                 </div>
                             </div>
                         </div>
                     </div>

                     <div class="row mt-2">
                         <div class="col-sm-12 table-responsive">
                             <table id="summary" class="table table-bordered table-sm table-hover" style="width:100%">
                                 <thead class="thead-dark">
                                 <tr>
                                     <th>Numero</th>
                                     <th>Fecha</th>
                                     <th>Tipo</th>
                                     <th>Monto</th>
                                     <th>Impuesto</th>
                                     <th>Motivo</th>
                                     <th>Cuenta</th>
                                     <th>Categorias</th>
                                     <th>Acción</th>
                                 </tr>
                                 </thead>
                                 <tbody>
                                     @if($summaries->count() > 0)
                                         @foreach ($summaries as $summarys)
                                             <tr>
                                                 <td>{{ $summarys->id }}</td>
                                                 @if( $summarys->date )
                                                     <?php  $datef = date_create($summarys->date);
                                                     $fecha = date_format($datef, 'd-m-Y ');
                                                     ?>
                                                 @endif
                                                 <td>{{ $fecha }}</td>
                                                 @if($summarys->type=="add")
                                                     <td>Abono
                                                         <small class="badge badge-success bordered float-right mt-1">
                                                             @if($summarys->id_transfer!="")
                                                                 <i class="fas fa-exchange-alt"></i>
                                                             @else
                                                                 <i class="fa fa-sort-up"></i>
                                                             @endif
                                                         </small>
                                                     </td>
                                                 @elseif($summarys->type=="out")
                                                     <td>Retiro
                                                         <small class="badge badge-danger bordered float-right mt-1">
                                                             @if($summarys->id_transfer!="")
                                                                 <i class="fas fa-exchange-alt"></i>
                                                             @else
                                                                 <i class="fa fa-sort-down"></i>
                                                             @endif
                                                         </small>
                                                     </td>
                                                 @endif
                                                 <td>{{ number_format($summarys->amount, 2, '.', ',') }}</td>
                                                 <td>{{ number_format( $summarys->tax, 2, '.', ',') }}</td>
                                                 <td class="text-truncate " style="max-width: 200px;">{{ $summarys->concept }}</td>
                                                 <td>{{ $summarys->account->account_name }}</td>
                                                 <td>{{ $summarys->category->name }}</td>
                                                 <td class="text-center">
                                                    <a class="btn btn-sm btn-success" href="{{route('movimientos.ver', $summarys->id)}}"><i class="fa fa fa-eye"></i></a>
                                                 </td>
                                             </tr>
                                         @endforeach
                                     @else
                                         <tr>
                                             <td colspan="9" class="text-center">No se encontraron registros</td>
                                         </tr>
                                     @endif
                                 </tbody>
                             </table>
                             {{$summaries->links()}}
                         </div>
                     </div>
             </div>
         </div>

         <div class="row">
             <div class="col-md-3 col-sm-4 col-xs-12 ">
                 <div class="small-box bg-info">
                     <div class="inner">
                         <h3>{{ number_format($totalFinal, 2, '.', ',') }}</h3>
                         <p>{{$divisa}}</p>
                     </div>
                     <div class="icon">
                         <i class="fa fa-money-bill"></i>
                     </div>
                     <a href="javascript:void(0)" class="small-box-footer">Balance Actual <i class="fas fa-arrow-circle-right"></i></a>
                 </div>
             </div>

             <div class="col-md-5 col-sm-2">

             </div>

             <div class="col-md-4 col-sm-6 col-xs-12  float-right ">
                 <div class="info-box ">
                     <span class="info-box-icon"><i class="fa fa-credit-card"></i></span>
                     <div class="info-box-content">
                        <span class="info-box-text">Balance de Impuestos</span>
                        <span class="info-box-number"
                             style="color: darkgreen;">+ {{ number_format($totalEgresosTx, 2, '.', ',') }}</span>

                        <div class="progress">
                            <div class="progress-bar" style="width: 0%">

                            </div>
                        </div>
                        <span class="progress-description">No deducibles:
                            <span style="color: red;"> {{ number_format($totalIngresosTx, 2, '.', ',') }}</span>
                        </span>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>

