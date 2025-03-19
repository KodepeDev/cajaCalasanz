<div class="pt-4">
    <div class="card card-maroon">

        <div wire:loading.class='overlay' class="d-none dark" wire:loading.class.remove='d-none'>
            <i class="fas fa-2x fa-sync-alt fa-spin"></i>
        </div>

        <div class="card-header">
            <i class="fa fa-bar-chart"></i>
            <h3 class="card-title">NUEVO INGRESO POR CLIENTE</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->

        <div class="card-body">

            <div class="row">
                <div class="form-group col-md-6">
                    <label for="exampleInputPassword1">{{ $type == 'add' ? 'CLIENTE' : 'PROVEEDOR' }}</label>
                    <a href="javascript:void(0)" class="font-weight-bold" data-toggle="modal"
                        data-target="#globalModal">[+ Agregar Nuevo]</a>
                    {{-- @if ($customer_name)
                        <a href="javascript:void(0)" class="font-weight-bold text-success"
                            wire:click="searchStandProvision()">[Ver Provisiones]</a>
                    @endif --}}
                    <div class="input-group" wire:ignore>
                        <div class="input-group-prepend">
                            <input type="number" wire:model.defer='documento' id="customer_doc" class="form-control"
                                wire:change='clearDataApi()' wire:loading.attr='readonly' placeholder="N° documento">
                        </div>
                        {{-- <input type="text" wire:model.defer='customer_name' class="form-control"
                            wire:change='clearDataApi()' readonly placeholder="nombres"> --}}
                        <select class="form-control select2" id="customer-select" wire:model.defer='customer_id'
                            wire:change='selectSearch()'>
                            <option value="">Elige un cliente...</option>
                            @foreach ($customers as $full_name => $id)
                                <option value="{{ $id }}">{{ $full_name }}</option>
                            @endforeach
                        </select>

                        <div class="input-group-append">
                            <button id="consulta" type="button" wire:click="ConsutasCustomer()"
                                class="btn btn-info"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                    @error('documento')
                        <span class="error">{{ $message }}</span>
                    @enderror
                    @error('customer_name')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="paid_bay">PAGADO POR</label>
                    <input type="text" class="form-control" wire:model.defer="paid_by" id="paid_bay" placeholder="">
                </div>
                <div class="form-group col-md-3">
                    <div class="">
                        <label for="exampleInputPassword1">FECHA <span class="badge badge-pill badge-primary">TC:
                                {{ $tc }}</span></label>
                        <input maxlength="200" name="date" wire:model="date" type="date" required
                            class="form-control" placeholder="Fecha">
                    </div>
                    @error('date')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group col-md-3">
                    <label for="exampleInputPassword1">CUENTAS</label>
                    <select required id="account_id" class="form-control" wire:model="account_id" name="account_id">
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
                    <label for="payment_method">MEDIO DE PAGO</label>
                    <select required id="payment_method" class="form-control custom-select"
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
                    <label for="numoperacion"># OPERACION</label>
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
                                    <th>SOLES</th>
                                    <th>DOLAR</th>
                                    <th width="90px" class="text-center">
                                        <div class="btn-group" role="group" aria-label="">
                                            <button type="button" wire:click='Add' class="btn btn-sm btn-primary"
                                                {{ $documento != '' ? '' : 'disabled' }}><i class="fa fa-cart-plus"
                                                    aria-hidden="true"></i> ADD</button>
                                            {{-- <button type="button" wire:click='Save' class="btn btn-sm btn-info" {{($documento != '') ? '' : 'disabled'}} >Save</button> --}}
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($provisionsCobrar))
                                    @foreach ($provisionsCobrar as $item)
                                        <tr>
                                            <td scope="row">{{ $item->date->format('m-Y') }}</td>
                                            <td>{{ $item->description }}</td>
                                            <td>{{ $item->category->name }}</td>
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
                                            <td class="text-center"></td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if ($provisions)
                                    @foreach ($provisions as $key => $provision)
                                        <tr>

                                            {{-- {{dd($provisions)}} --}}
                                            <td scope="row"><input type="month"
                                                    class="form-control form-control-sm"
                                                    wire:model.defer="provisions.{{ $key }}.date"></td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm"
                                                    wire:model.defer="provisions.{{ $key }}.description">
                                                @error('provisions.{{ $key }}.description')
                                                    <span class="error">{{ $message }}</span>
                                                @enderror
                                            </td>
                                            <td>
                                                <select class="custom-select custom-select-sm"
                                                    wire:model.defer="provisions.{{ $key }}.category_id"
                                                    name="" id="">
                                                    <option value="Elegir" selected>Seleccione uno</option>
                                                    @foreach ($categorias as $name => $id)
                                                        <option value="{{ $id }}">{{ $name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('provisions.{{ $key }}.category_id')
                                                    <span class="error">{{ $message }}</span>
                                                @enderror
                                            </td>
                                            {{-- <td>
                                                <select class="custom-select custom-select-sm"
                                                    wire:model.defer='provisions.{{ $key }}.stand_id'
                                                    name="" id="">
                                                    <option value="" selected value="">Ninguno</option>
                                                    @foreach ($stands as $name => $id)
                                                        <option value="{{ $id }}">{{ $name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td> --}}
                                            {{-- <td class="text-center">S/. {{number_format(250, 2)}}</td> --}}
                                            <td class="text-center" colspan="2">
                                                <input type="number" class="form-control form-control-sm"
                                                    wire:model.lazy="provisions.{{ $key }}.amount"
                                                    wire:change="setDefaultAmount()">
                                                @error('provisions.{{ $key }}.amount')
                                                    <span class="error">{{ $message }}</span>
                                                @enderror
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-danger"
                                                    wire:click="removeProvision({{ $key }})"><i
                                                        class="fas fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                {{-- {{dd(empty($provision_detalles) && empty($provisions))}} --}}
                                @if (empty($provision_detalles) && empty($provisions))
                                    <tr>
                                        <td class="text-center" scope="row" colspan="7">No hay ningún registro
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-right">
                                        TOTAL A COBRAR EN SOLES
                                    </td>
                                    <td colspan="3" class="text-center">
                                        S/. {{ number_format($total_prov_cobrar + $total_new, 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">Observaciones</label>
                        <textarea class="form-control" wire:model.defer="observation" id="exampleFormControlTextarea1" rows="3"></textarea>
                    </div>
                </div>
                {{-- <div class="form-group col-md-2">
                    <label for="puesto">Stand</label>
                    <input required maxlength="200" type="text" name="puesto" wire:model.defer="puesto" class="form-control"
                        placeholder="# stand o codigo">
                    @error('puesto')
                    <span class="error">{{$message}}</span>
                    @enderror
                </div> --}}
                {{-- <div class="form-group col-md-3">
                    <label for="amount">Monto</label>
                    <input required name="amount" id="currency" wire:model.defer="amount" type="number" min="0" class="form-control currency" placeholder="Monto">
                    @error('amount')
                    <span class="error">{{$message}}</span>
                    @enderror
                </div> --}}

                {{-- <div class="form-group col-md-3">
                    <label for="exampleInputPassword1">Categorias</label>
                    <select required class="form-control" wire:model.defer="category_id" wire:change='changeCategory' name="categories_id" id="category_id">
                        <option class="" value="">Seleccione Categoria</option>
                        @foreach ($categorias as $categoria)
                            @if ($categoria->id !== 1)
                                <option class="attr-{{ $categoria->type }}" value="{{ $categoria->id }}">
                                    {{ $categoria->name }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                    @error('category_id')
                    <span class="error">{{$message}}</span>
                    @enderror
                </div> --}}


            </div>
        </div>
        <div class="card-footer">
            <button type="submit" wire:click.prevent="crearMovimiento()" class="btn btn-warning">Guardar</button>
            <a href="{{ route('movimientos.listado') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </div>

    @include('livewire.movimientos.form-crear-customer.agregar-nuevo')
    @include('livewire.movimientos.clase-movimientos.modal-select-detalles')

    @if ($showReceiptModal)
        <!-- Modal -->
        <div class="modal fade" id="receiptModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
            role="dialog" aria-labelledby="receiptModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="receiptModalLabel">Recibo generado</h5>
                        <button type="button" class="close" data-dismiss="modal" wire:click='redireccionar'
                            aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>El número de recibo generado es: <strong>{{ $receipt }}</strong></p>
                        <p>¿Qué desea hacer con el recibo?</p>
                    </div>
                    <div class="modal-footer">
                        {{-- <a href="{{ route('movimientos.a4.recibo', $summary_id) }}" wire:click='redireccionar' target="_blank" class="btn btn-primary">Imprimir A4</a> --}}
                        <a href="{{ route('movimientos.cc5.recibo', $summary_id) }}" wire:click='redireccionar'
                            target="_blank" class="btn btn-primary">LegacyPDF</a>
                        <a href="{{ route('movimientos.ticket.recibo', $summary_id) }}" wire:click='redireccionar'
                            target="_blank" class="btn btn-secondary">Imprimir Ticket</a>
                    </div>
                </div>
            </div>
        </div>


        <script>
            $(document).ready(function() {
                $('#receiptModal').modal('show');

                // $('#receiptModal').on('hidden.bs.modal', function () {
                //     livewire.emit('redireccionar');
                // });
            });
        </script>
    @endif

</div>

@push('js')
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
                $('#customer-select').select2();
                $("#customer-select").select2({
                    theme: 'bootstrap4',
                });
            });
            window.livewire.on('movimiento_added', msg => {
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

            window.livewire.on('mostrarModalProvision', msg => {
                $("#modalProvision").modal('show');
            });

            $('#globalModal').on('hidden.bs.modal', function() {
                livewire.emit('resetUiApi');
            });
            $('#receiptModal').on('hidden.bs.modal', function() {
                livewire.emit('redireccionar');
            });

            $('#customer-select').select2();
            $("#customer-select").select2({
                theme: 'bootstrap4',
            });

            $('#customer-select').on('change', function(e) {
                let socioID = $('#customer-select').select2('val');
                livewire.emit('selectSearch');
                @this.set('customer_id', socioID);
            });
            Livewire.on('updateSelect', function(customerId, fullName) {
                // Destruye el Select2 actual
                $('#customer-select').select2('destroy');

                // Vuelve a inicializar Select2
                $('#customer-select').select2();

                // Selecciona el nuevo cliente
                if (!$('#customer-select option[value="' + customerId + '"]').length) {
                    // Si no existe, agrega la nueva opción
                    let newOption = new Option(fullName, customerId, true, true);
                    $('#customer-select').append(newOption);
                }

                // Selecciona la nueva opción
                $('#customer-select').val(customerId).trigger('change');

                $("#customer-select").select2({
                    theme: 'bootstrap4',
                });
            });
            window.livewire.on('clearSelect', msg => {
                $("#customer-select").val('');
                $("#customer-select").select2();
                $("#customer-select").select2({
                    theme: 'bootstrap4',
                });
            });

        });


        function cerrarModalSub() {
            $('#modalSubcategorias').modal('hide');
        }
    </script>
@endpush
