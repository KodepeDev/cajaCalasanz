<div class="pt-4">
    <div class="card card-warning">

        <div wire:loading.class='overlay' class="d-none dark" wire:loading.class.remove='d-none'>
            <i class="fas fa-2x fa-sync-alt fa-spin"></i>
        </div>

        <div class="card-header">
            <i class="fa fa-bar-chart"></i>
            <h3 class="card-title">Editando Movimiento</h3>
            {{-- <div class="card-tools">
                <div class="form-group">
                    <label for="provision_stand">ProvisionStand</label>
                    <input required maxlength="200" type="text" id="provision_stand" wire:model.lazy="provision_stand" class="form-control"
                        placeholder="# stand o codigo">
                        @csrf
                    @error('provision_stand')
                    <span class="error">{{$message}}</span>
                    @enderror
                </div>
            </div> --}}
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <div wire:ignore.self id="modalSubcategorias" class="modal fade">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <h4 class="modal-title">Subcategorias</h4>
                        <button type="button" id="closemodal" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        @if ($subcategorias)
                            @foreach ($subcategorias as $item)
                                <div class="form-group form-check">
                                    <input class="form-check-input" type="radio" wire:model='subcategoria_id'
                                        name="exampleRadios" id="radio{{ $item->id }}" value="{{ $item->id }}">
                                    <label class="form-check-label" for="radio{{ $item->id }}">
                                        {{ $item->name }}
                                    </label>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button style="margin: 15px;" type="button" id="modalSubcategorias" onclick="cerrarModalSub()"
                            class="btn btn-default" data-dismiss="modalSubcategorias">Ok</button>
                    </div>
                </div>

            </div>
        </div>

        <div class="card-body">

            <div class="row">
                @if ($type === 'add')
                    <div class="form-group col-md-3">
                        <label for="exampleInputPassword1">Tipo de Movimiento</label>
                        <select class="form-control" id="type_movimiento" name="type" disabled>
                            <option value="add">Ingreso</option>
                            <option value="out">Egreso</option>
                        </select>
                        {{-- <select class="form-control" wire:model.lazy="type" id="type_movimiento" name="type"
                            wire:change='categoryType()' disabled>
                            <option value="add">Ingreso</option>
                            <option value="out">Egreso</option>
                        </select> --}}
                        @error('type')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                @endif
                @if ($type === 'out')
                    <div class="form-group col-md-3">
                        <label for="area_movimiento">Gasto de:</label>
                        {{-- <select class="form-control" wire:model.defer="section_type" id="area_movimiento"
                            name="">
                            <option value="AD">Administracion</option>
                            <option value="1E">I Etapa</option>
                            <option value="2E">II Etapa</option>
                        </select> --}}
                        <select disabled class="form-control" id="area_movimiento" name="">
                            <option value="AD">Administracion</option>
                            <option value="1E">I Etapa</option>
                            <option value="2E">II Etapa</option>
                        </select>
                        @error('section_type')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>
                @endif
                <div class="form-group col-md-6">
                    <label for="exampleInputPassword1">{{ $type == 'add' ? 'Cliente' : 'Proveedor' }}</label>
                    {{-- <a href="javascript:void(0)" class="font-weight-bold" data-toggle="modal" data-target="#globalModal">[+ Agregar Nuevo]</a> --}}
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <input type="number" wire:model.defer='documento' disabled class="form-control"
                                wire:change='clearDataApi()' wire:loading.attr='readonly' placeholder="N° documento">
                        </div>
                        <input type="text" wire:model.defer='customer_name' class="form-control"
                            wire:change='clearDataApi()' readonly placeholder="nombres">

                        <div class="input-group-append">
                            <button type="button" disabled wire:click="" class="btn btn-info"><i
                                    class="fas fa-search"></i></button>
                        </div>
                    </div>
                    @error('documento')
                        <span class="error">{{ $message }}</span>
                    @enderror
                    @error('customer_name')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group col-md-3">
                    <label for="paid_bay">Pagado por</label>
                    <input disabled type="text" class="form-control" wire:model.defer="paid_by" id="paid_bay"
                        placeholder="">
                </div>
                <div class="form-group col-md-3">
                    <div class="">
                        <label for="exampleInputPassword1">Fecha</label>
                        <input maxlength="200" name="date" readonly wire:model.defer="date" type="date" required
                            class="form-control" placeholder="Fecha">
                    </div>
                    @error('date')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group col-md-3">
                    <label for="exampleInputPassword1">Cuentas</label>
                    <select required id="account_id" disabled class="form-control" wire:model="account_id"
                        name="account_id">
                        <option value="">Seleccione Cuenta</option>
                        @foreach ($cuentas as $cuenta)
                            <option value="{{ $cuenta->id }}">
                                {{ $cuenta->account_name }}
                            </option>
                        @endforeach
                    </select>

                    @error('account_id')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                {{-- metodos de pago --}}
                <div class="form-group col-md-3">
                    <label for="payment_method">Medio de Pago</label>
                    <select required id="payment_method" disabled class="form-control custom-select"
                        wire:model.defer="payment_method" name="payment_method">
                        @foreach ($paymentMethods as $method)
                            <option value="{{ $method->id }}">
                                {{ $method->name }}
                            </option>
                        @endforeach
                    </select>

                    @error('payment_method')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group col-md-3">
                    <label for="numoperacion"># Operacion</label>
                    <input type="text" class="form-control" wire:model.defer='numero_operacion'
                        placeholder="# de operacion (opcional)">

                    @error('numero_operacion')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group col-md-12">
                    {{-- <label for="exampleInputEmail1">Concepto</label>
                    <input required maxlength="200" type="text" name="concept" wire:model.defer="concept" class="form-control"
                        placeholder="concepto o descripción del movimiento">
                    @error('concept')
                    <span class="error">{{$message}}</span>
                    @enderror --}}
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-sm">
                            <thead class="thead-dark">
                                <tr>
                                    <th>MES</th>
                                    <th width="40%">DESCRIPCION</th>
                                    <th>CATEGORIA</th>
                                    <th>STAND</th>
                                    <th>SOLES</th>
                                    <th>DOLAR</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($detalles as $item)
                                    <tr>
                                        <td scope="row">{{ $item->date->format('m-Y') }}</td>
                                        <td>{{ $item->description }}</td>
                                        <td>{{ $item->category->name }}</td>
                                        @if ($item->stand)
                                            <td style="text-align: left" scope="row">{{ $item->stand->name }}</td>
                                        @else
                                            <td>S/N</td>
                                        @endif
                                        <td class="text-center">
                                            @if ($item->currency->id == 1 || $item->currency->id == null)
                                                S/. {{ number_format($item->amount, 2) }}
                                            @else
                                                S/. {{ number_format($item->amount * $tc, 2) }}
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($item->currency->id == 2)
                                                $. {{ number_format($item->amount, 2) }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-right">
                                        TOTAL
                                    </td>
                                    <td colspan="2" class="text-center">
                                        S/. {{ number_format($total, 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">Observaciones</label>
                        <textarea class="form-control" wire:model.defer="observation" id="exampleFormControlTextarea1" maxlength="255"
                            rows="3"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" wire:click.prevent="updateMovimiento()"
                class="btn btn-warning">Actualizar</button>
            <a href="{{ route('movimientos.ver', $summary_id) }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.livewire.on('error', msg => {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: msg,
                });
            });
            window.livewire.on('customer_added', msg => {
                $("#globalModal").modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Buen Trabajo!',
                    text: msg,
                });
            });
            window.livewire.on('registro-existente', msg => {
                $("#globalModal").modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Buen Trabajo!',
                    text: msg,
                });
            });
            window.livewire.on('movimiento_actualizado', msg => {
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

            window.livewire.on('tiene_subcategorias', msg => {
                $("#modalSubcategorias").modal('show');
            });

            $('#globalModal').on('hidden.bs.modal', function() {
                livewire.emit('resetUiApi');
            });

        });


        function cerrarModalSub() {
            $('#modalSubcategorias').modal('hide');
        }
    </script>
