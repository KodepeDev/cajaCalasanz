<div class="pt-4">
    <div class="container-fluid">
        <div class="card p-4">
            <div wire:loading.class='overlay' class="d-none dark" wire:loading.class.remove='d-none'>
                <i class="fas fa-2x fa-sync-alt fa-spin"></i>
            </div>
            <div class="card-header">
                <h3 class="card-title">Cierre de Movimientos</h3>
                <div class="card-tools">
                    <a href="{{ route('closes.create') }}" class="btn btn-primary"> <i class="fa fa-plus-circle"
                            aria-hidden="true"></i>
                        Agregar</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="form-group">
                        <label for="">Año de Consulta</label>
                        <select class="custom-select" name="" id="">
                            <option selected>2024</option>
                            <option value="">2023</option>
                            <option value=""></option>
                            <option value=""></option>
                        </select>
                        <small id="helpId" class="text-muted">Solo figuran los registros generados para este
                            año</small>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="bg-primary text-center">
                            <tr>
                                <th>DESDE</th>
                                <th>HASTA</th>
                                <th>TIPO</th>
                                <th>BALANCE</th>
                                <th>SALDO</th>
                                <th>ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($closes as $item)
                                <tr>
                                    <td scope="row">{{ $item->from_date }}</td>
                                    <td>{{ $item->to_date }}</td>
                                    <td>{{ $item->type }}</td>
                                    <td>{{ number_format($item->current_income_balance - $item->current_expense_balance, 2) }}
                                    </td>
                                    <td>{{ number_format($item->current_balance, 2) }}</td>
                                    <td></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
