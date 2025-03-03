<div>
    <div wire:ignore.self class="modal fade" id="nuevoRegistro" data-backdrop="static" data-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="nuevoRegistroLabel">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" id="nuevoRegistroLabel">NUEVA CUOTA</h5>
                    <button type="button" class="close" data-dismiss="modal" wire:click='resetUI' aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-8">
                            <label for="exampleInputPassword1">PADRE/MADRE O TUTOR</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <input type="number" wire:model.defer='documento' class="form-control" readonly
                                        placeholder="N° documento">
                                </div>
                                <input type="text" wire:model.defer='customer_name' class="form-control" readonly
                                    placeholder="nombres">
                            </div>
                            @error('documento')
                                <span class="error">{{ $message }}</span>
                            @enderror
                            @error('customer_name')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <div class="">
                                <label for="exampleInputPassword1">FECHA O PERIODO</label>
                                <input maxlength="200" name="date" wire:model.defer="date" type="date" required
                                    class="form-control" placeholder="Fecha">
                            </div>
                            @error('date')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-8">
                            <label for="exampleInputEmail1">DESCRIPCION</label>
                            <input required maxlength="200" type="text" name="concept" wire:model.defer="concept"
                                class="form-control" placeholder="concepto o descripción del movimiento">
                            @error('concept')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="amount">MONTO</label>
                            <div class="input-group mb-3">
                                <input required name="amount" id="currency" wire:model.defer="amount" type="number"
                                    min="0" class="form-control currency" placeholder="Monto">
                                <div class="input-group-append">
                                    <select name="" id="" wire:model.defer="currency_id"
                                        class="form-control">
                                        @foreach ($currency as $name => $id)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @error('amount')
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label for="exampleInputPassword1">CONCEPTO O CATEGORIA</label>
                            <select required class="form-control" wire:model.defer="category_id" name="categories_id"
                                id="category_id">
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
                                <span class="error">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- <div class="form-group col-md-4">
                            <label for="exampleInputPassword1">Cuentas</label>
                            <select required id="account_id" class="form-control" wire:model="account_id" name="account_id">
                                <option value="">Seleccione Cuenta</option>
                                @foreach ($cuentas as $cuenta)
                                    <option value="{{ $cuenta->id }}">
                                        {{ $cuenta->account_name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('account_id')
                            <span class="error">{{$message}}</span>
                            @enderror
                        </div> --}}
                        {{-- metodos de pago --}}
                        {{-- <div class="form-group col-md-4">
                            <label for="payment_method">Medio de Pago</label>
                            <select required id="payment_method" class="form-control custom-select" wire:model.defer="payment_method" name="payment_method">
                                @foreach ($paymentMethods as $method)
                                    <option value="{{ $method->id }}">
                                        {{ $method->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('payment_method')
                            <span class="error">{{$message}}</span>
                            @enderror
                        </div> --}}
                        {{-- <div class="form-group col-md-4">
                            <label for="numoperacion"># Operacion</label>
                            <input type="text" class="form-control" wire:model.defer='numero_operacion' placeholder="# de operacion (opcional)">

                            @error('numero_operacion')
                            <span class="error">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="form-group col-md-4">
                            <label for="status">Estado</label>
                            <select required id="status" class="form-control custom-select" wire:model.lazy="status" name="status">
                                <option value="PAID">Pagado</option>
                                <option value="PENDING">Pendiente</option>
                            </select>

                            @error('status')
                            <span class="error">{{$message}}</span>
                            @enderror
                        </div> --}}
                    </div>
                </div>
                <div class="modal-footer">
                    <button wire:click='resetUI' wire:loading.attr='disabled'
                        wire:target='crearMovimiento, updateMovimiento' class="btn btn-secondary">Cerrar</button>
                    @if ($selected_id == 0)
                        <button type="submit" wire:click.prevent="crearMovimiento()" wire:loading.attr='disabled'
                            wire:target='crearMovimiento' class="btn btn-warning">Guardar</button>
                    @else
                        <button type="submit" wire:click.prevent="updateMovimiento()" wire:loading.attr='disabled'
                            wire:target='updateMovimiento' class="btn btn-warning">Actualizar</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        window.livewire.on('movimiento_added', msg => {
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
        window.livewire.on('close_modal', msg => {
            $('#nuevoRegistro').modal('hide');
        });
        window.livewire.on('show_modal', msg => {
            $('#nuevoRegistro').modal('show');
        });

        $('#nuevoRegistro').on('hidden.bs.modal', function() {
            livewire.emit('resetUI');
        });

    });
</script>
