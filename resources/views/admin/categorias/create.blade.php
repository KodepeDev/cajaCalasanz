@extends('adminlte::page')
@section('title', 'Nueva Categoría')

@section('content')
<div class="container-fluid pt-4">
    <form action="/admin/categories/store" method="POST">
        @csrf
        <div class="card card-primary">
            <div class="card-header d-flex align-items-center">
                <i class="fas fa-tag mr-2"></i>
                <h3 class="card-title mb-0">Nueva Categoría / Concepto</h3>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label class="col-form-label-sm font-weight-bold text-uppercase text-muted">
                            Nombre <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" maxlength="200"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}"
                            placeholder="Nombre de la categoría">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label class="col-form-label-sm font-weight-bold text-uppercase text-muted">
                            Tipo <span class="text-danger">*</span>
                        </label>
                        <select name="type" class="form-control @error('type') is-invalid @enderror">
                            <option value="add" {{ old('type') === 'add' ? 'selected' : '' }}>
                                Categoría de Ingreso
                            </option>
                            <option value="out" {{ old('type') === 'out' ? 'selected' : '' }}>
                                Categoría de Gasto
                            </option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-12">
                        <label class="col-form-label-sm font-weight-bold text-uppercase text-muted">
                            Descripción
                        </label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                            rows="3" maxlength="200"
                            placeholder="Descripción de la categoría (opcional)">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-between">
                <a href="{{ route('categorias') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left mr-1"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-warning px-4">
                    <i class="fa fa-save mr-1"></i> Guardar y continuar
                </button>
            </div>
        </div>
    </form>
</div>
@stop
