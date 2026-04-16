@extends('adminlte::page')
@section('title', 'Editar Cuenta')

@section('content')
<div class="container-fluid pt-4">
    <form action="{{ route('account.update', $account->id) }}" method="POST">
        @method('PUT')
        @csrf
        <div class="card card-maroon">
            <div class="card-header d-flex align-items-center">
                <i class="fas fa-university mr-2"></i>
                <h3 class="card-title mb-0">Editar Cuenta</h3>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-form-label-sm font-weight-bold text-uppercase text-muted">
                            Nombre de la cuenta <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" maxlength="200"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $account->account_name) }}"
                            placeholder="Ej: Banco BCP - Cuenta Corriente">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label class="col-form-label-sm font-weight-bold text-uppercase text-muted">
                            Número de cuenta
                        </label>
                        <input type="text" name="number" maxlength="50"
                            class="form-control @error('number') is-invalid @enderror"
                            value="{{ old('number', $account->account_number) }}"
                            placeholder="Número de cuenta bancaria (opcional)">
                        @error('number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label class="col-form-label-sm font-weight-bold text-uppercase text-muted">
                            Tipo de cuenta <span class="text-danger">*</span>
                        </label>
                        <select name="type" class="form-control @error('type') is-invalid @enderror">
                            <option value="corriente" {{ old('type', $account->account_type) === 'corriente' ? 'selected' : '' }}>Corriente</option>
                            <option value="ahorro"    {{ old('type', $account->account_type) === 'ahorro'    ? 'selected' : '' }}>Ahorro</option>
                            <option value="caja"      {{ old('type', $account->account_type) === 'caja'      ? 'selected' : '' }}>Caja</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-3">
                        <label class="col-form-label-sm font-weight-bold text-uppercase text-muted">
                            Serie de ingreso <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="add_serie" maxlength="4"
                            class="form-control text-uppercase @error('add_serie') is-invalid @enderror"
                            value="{{ old('add_serie', $account->add_serie) }}"
                            placeholder="Ej: RI01"
                            style="text-transform:uppercase;">
                        <small class="text-muted">Exactamente 4 caracteres alfanuméricos</small>
                        @error('add_serie')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-3">
                        <label class="col-form-label-sm font-weight-bold text-uppercase text-muted">
                            Serie de gasto <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="out_serie" maxlength="4"
                            class="form-control text-uppercase @error('out_serie') is-invalid @enderror"
                            value="{{ old('out_serie', $account->out_serie) }}"
                            placeholder="Ej: RG01"
                            style="text-transform:uppercase;">
                        <small class="text-muted">Exactamente 4 caracteres alfanuméricos</small>
                        @error('out_serie')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Info: series affect receipt numbering --}}
                <div class="alert alert-warning d-flex align-items-center mt-2 mb-0 py-2" style="font-size:.85rem;">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <span>
                        Las series determinan el prefijo de los recibos (ej: <strong>RI01</strong>-00000001).
                        Cambiarlas afectará los nuevos recibos generados con esta cuenta.
                    </span>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-between">
                <a href="{{ route('account.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left mr-1"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-warning px-4">
                    <i class="fa fa-save mr-1"></i> Actualizar
                </button>
            </div>
        </div>
    </form>
</div>
@stop
