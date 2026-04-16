@extends('adminlte::page')
@section('title', 'Nueva Cuenta')

@section('content')
<div class="container-fluid pt-4">
    <form action="{{ route('account.save') }}" method="POST">
        @csrf
        <div class="card card-maroon">
            <div class="card-header d-flex align-items-center">
                <i class="fas fa-university mr-2"></i>
                <h3 class="card-title mb-0">Nueva Cuenta</h3>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="col-form-label-sm font-weight-bold text-uppercase text-muted">
                            Nombre de la cuenta <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" maxlength="200"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}"
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
                            value="{{ old('number') }}"
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
                            <option value="corriente" {{ old('type') === 'corriente' ? 'selected' : '' }}>Corriente</option>
                            <option value="ahorro"    {{ old('type') === 'ahorro'    ? 'selected' : '' }}>Ahorro</option>
                            <option value="caja"      {{ old('type') === 'caja'      ? 'selected' : '' }}>Caja</option>
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
                            value="{{ old('add_serie') }}"
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
                            value="{{ old('out_serie') }}"
                            placeholder="Ej: RG01"
                            style="text-transform:uppercase;">
                        <small class="text-muted">Exactamente 4 caracteres alfanuméricos</small>
                        @error('out_serie')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-between">
                <a href="{{ route('account.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left mr-1"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-warning px-4">
                    <i class="fa fa-save mr-1"></i> Guardar
                </button>
            </div>
        </div>
    </form>
</div>
@stop
