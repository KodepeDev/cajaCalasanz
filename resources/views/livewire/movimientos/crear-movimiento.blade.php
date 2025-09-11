<div class="pt-4">
    <div class="card card-primary">

        <div wire:loading.class='overlay' class="d-none dark" wire:loading.class.remove='d-none'>
            <i class="fas fa-2x fa-sync-alt fa-spin"></i>
        </div>

        <div class="card-header">
            <i class="fa fa-bar-chart"></i>
            <div class="card-title">
                <h3>Nuevo Movimiento</h3>
                <i>Las provisiones en dólares estan sujetos al tipo de cambio de la fecha de cobro</i>
            </div>
            <div class="card-tools">
                <div class="form-group">
                    <label for="provision_code">PROVISION</label>
                    <div class="input-group">
                        <input required type="text" maxlength="200" class="form-control" placeholder="# DNI o codigo"
                            aria-label="search provisions" wire:model.lazy="provision_code"
                            aria-describedby="provision_code" id="provision_code">
                        <div class="input-group-append">
                            <button class="btn btn-info" wire:click="$emit('buscar_provision')"
                                wire:loading.attr='disabled' type="button"><i class="fa fa-search-plus"
                                    aria-hidden="true"></i> BUSCAR</button>
                        </div>
                    </div>
                    @csrf
                    @error('provision_code')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
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
                {{-- <div class="form-group col-md-3">
                    <label for="exampleInputPassword1">TIPO DE MOVIMIENTO</label>
                    <select class="form-control" wire:model.lazy="type" id="type_movimiento" name="type"
                        wire:change='categoryType()' disabled>
                        <option value="add">Ingreso</option>
                    </select>
                    @error('type')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div> --}}
                <div class="form-group col-md-6">
                    <label for="exampleInputPassword1">{{ $type == 'add' ? 'CLIENTE ' : 'PROVEEDOR ' }}
                        {{ $student_name ? ' | ESTUDIANTE: ' . $student_name : '' }}</label>
                    {{-- <a href="javascript:void(0)" class="font-weight-bold" data-toggle="modal" data-target="#globalModal">[+ Agregar Nuevo]</a> --}}
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <input type="number" wire:model.defer='documento' class="form-control" readonly
                                wire:change='clearDataApi()' wire:loading.attr='readonly' placeholder="N° documento">
                        </div>
                        <input type="text" wire:model.defer='customer_name' class="form-control"
                            wire:change='clearDataApi()' readonly placeholder="nombres">

                        <div class="input-group-append">
                            <button type="button" disabled class="btn btn-info"><i class="fas fa-search"></i></button>
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
                    <label for="numoperacion"># OPERACIÓN</label>
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
                        <table class="table table-bordered table-hover">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>MES</th>
                                    <th width="40%">DESCIPCION</th>
                                    <th>CATEGORIA</th>
                                    <th>SOLES</th>
                                    <th>DOLAR</th>
                                    <th width="90px" class="text-center">
                                        <div class="btn-group" role="group" aria-label="">
                                            <button type="button" wire:click='Add' class="btn btn-sm btn-warning"
                                                {{ $student_id ? '' : 'disabled' }}><i
                                                    class="fa fa-cart-plus" aria-hidden="true"></i> ADD</button>
                                            {{-- <button type="button" wire:click='Save' class="btn btn-sm btn-info" {{($documento != '99999999' || $provision_stand != null) ? '' : 'disabled'}} >Save</button> --}}
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($student_id)
                                    @if ($total_prov_cobrar > 0)
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

                                    @foreach ($provisions as $key => $provision)
                                        <tr>
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
                                            {{-- <td class="text-center">S/. {{number_format(250, 2)}}</td> --}}
                                            <td colspan="2" class="text-center">
                                                <input type="number" class="form-control form-control-sm"
                                                    wire:model.lazy="provisions.{{ $key }}.amount">
                                                @error('provisions.{{ $key }}.amount')
                                                    <span class="error">{{ $message }}</span>
                                                @enderror
                                            </td>
                                            <td class="text-center"><button type="button"
                                                    class="btn btn-sm btn-danger"
                                                    wire:click="removeProvision({{ $key }})"><i
                                                        class="fas fa-trash"></i></button></td>
                                        </tr>
                                    @endforeach
                                @else
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
                                        @error('amount')
                                            <br><span class="error">{{ $message }}</span>
                                        @enderror
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1">OBSERVACIONES</label>
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
            <button type="submit" wire:click.prevent="crearMovimiento()" class="btn btn-info float-right"><i
                    class="fa fa-check-circle" aria-hidden="true"></i> EMITIR
                RECIBO</button>
            <a href="{{ route('movimientos.listado') }}" class="btn btn-danger"><i class="fa fa-window-close"
                    aria-hidden="true"></i> CANCELAR OPERACIÓN</a>
        </div>
    </div>

    {{-- @include('livewire.movimientos.form-crear-customer.agregar-nuevo') --}}
    @include('livewire.movimientos.clase-movimientos.modal-select-detalles')

    @if ($showReceiptModal)
        <!-- Modal -->
        <div class="modal fade" id="receiptModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
            role="dialog" aria-labelledby="receiptModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="receiptModalLabel">RECIBO GENERADO</h5>
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
                        {{-- <a href="{{ route('movimientos.cc5.recibo', $summary_id) }}" wire:click='redireccionar'
                            target="_blank" class="btn btn-primary">LegacyPDF</a> --}}
                        <a href="{{ route('movimientos.a4.recibo', $summary_id) }}" wire:click='redireccionar'
                            target="_blank" class="btn btn-primary"><i class="fa fa-file-pdf-o"
                                aria-hidden="true"></i> Imprimir A4</a>
                        {{-- <a href="{{ route('movimientos.ticket.recibo', $summary_id) }}" wire:click='redireccionar' target="_blank" class="btn btn-secondary">Imprimir Ticket</a> --}}
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
        window.livewire.on('movimiento_added', (msg) => {
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

        window.livewire.on('mostrarModalProvision', msg => {
            $("#modalProvision").modal('show');
        });

        $('#globalModal').on('hidden.bs.modal', function() {
            livewire.emit('resetUiApi');
        });
        $('#receiptModal').on('hidden.bs.modal', function() {
            livewire.emit('redireccionar');
        });

    });


    function cerrarModalSub() {
        $('#modalSubcategorias').modal('hide');
    }

    const input = document.querySelector('#provision_code');
    input.addEventListener('keydown', event => {
        if (event.keyCode === 13) {
            livewire.emit('buscar_provision');
        }
    });
</script>
