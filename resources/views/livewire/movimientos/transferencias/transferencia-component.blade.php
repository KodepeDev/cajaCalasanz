<div class="pt-4">
    <div class="card card-warning">
        <div class="card-header with-border">
            <h3 class="card-title">Transferir entre cuentas <i class="fa fa-bar-chart"></i></h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <h4>Transferir dinero desde:</h4>
                        <hr>
                    </div>
                    <div class="form-group">
                        <label for="cuenta1">Seleccione Origen </label>
                        <select required autofocus class="form-control" wire:model.lazy='cuenta1' name="cuenta1" id="cuenta1">
                            <option value="-1" selected>==== origen ====</option>
                            @foreach($cuentas as $id => $account_name)
                                <option value="{{ $id }}">
                                    {{ $account_name }}
                                </option>
                            @endforeach
                        </select>

                        @error('cuenta1')
                            <span class="error">{{$message}}</span>
                        @enderror

                       @if ($cuenta1 >= 0)
                            @if ($saldoCuenta1 >= 0)
                            <div class="form-group ml-4 mt-1">S/.
                                <span class="badge badge-pill badge-success">{{number_format($saldoCuenta1, 2)}}</span>
                            </div>
                            @else
                            <div class="form-group ml-4 mt-1">S/.
                                <span class="badge badge-pill badge-danger">{{number_format($saldoCuenta1, 2)}}</span>
                            </div>
                            @endif
                       @endif

                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <h4>Transferir dinero para:</h4>
                        <hr>
                    </div>
                    <div class="form-group">
                        <label for="cuenta2">Seleccione Destino </label>

                        <select required id="cuenta2" class="form-control" wire:model.lazy='cuenta2' name="cuenta2">
                            <option value="-1" selected>==== destino ====</option>
                            @foreach($cuentas as $id => $account_name)
                                <option value="{{ $id }}">
                                    {{ $account_name }}
                                </option>
                            @endforeach
                        </select>

                        @error('cuenta2')
                            <span class="error">{{$message}}</span>
                        @enderror

                        @if ($cuenta2 >= 0)
                            @if ($saldoCuenta2 >= 0)
                            <div class="form-group ml-4 mt-1">S/.
                                <span class="badge badge-pill badge-success">{{number_format($saldoCuenta2, 2)}}</span>
                            </div>
                            @else
                            <div class="form-group ml-4 mt-1">S/.
                                <span class="badge badge-pill badge-danger">{{number_format($saldoCuenta2, 2)}}</span>
                            </div>
                            @endif
                        @endif

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-6">

                    <label for="amount">Monto</label>
                    <input wire:model.lazy='amount' required name="amount" type="number" class="form-control" placeholder="Monto">

                    @error('amount')
                        <span class="error">{{$message}}</span>
                    @enderror

                </div>


                <div class="form-group col-md-6">
                    <label for="date">Fecha</label>
                    <input required wire:model.defer='date' name="created_at" placeholder="fecha" type="date" class="form-control">

                    @error('date')
                        <span class="error">{{$message}}</span>
                    @enderror

                </div>
            </div>

        </div>

        <div class="card-footer">
            <button type="submit" wire:click.prevent='crearTransferencia()' class="btn btn-warning">Guardar</button>
            <button type="submit" onclick="window.history.back()" class="btn btn-info">Cancelar</button>
        </div>

    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        window.livewire.on('added', msg => {
            Swal.fire({
                icon: 'success',
                title: 'Buen Trabajo!',
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

    });
</script>
