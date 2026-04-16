@extends('adminlte::page')
@section('title', 'Editar Categoría')

@section('content')
<div class="container-fluid pt-4">

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <form action="/admin/categories/edit/{{ $category->id }}" method="POST">
        @method('PUT')
        @csrf

        {{-- ── Datos de la categoría ── --}}
        <div class="card card-primary">
            <div class="card-header d-flex align-items-center">
                <i class="fas fa-tag mr-2"></i>
                <h3 class="card-title mb-0">Editar Categoría</h3>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label class="col-form-label-sm font-weight-bold text-uppercase text-muted">
                            Nombre <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" maxlength="200"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $category->name) }}"
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
                            <option value="add" {{ old('type', $category->type) === 'add' ? 'selected' : '' }}>
                                Categoría de Ingreso
                            </option>
                            <option value="out" {{ old('type', $category->type) === 'out' ? 'selected' : '' }}>
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
                            placeholder="Descripción (opcional)">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Subcategorías ── --}}
        <div class="card card-secondary">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="fas fa-list mr-2"></i>
                    <h3 class="card-title mb-0">Subcategorías</h3>
                    <small class="text-light ml-2 opacity-75">Opcional</small>
                </div>
                <button type="button" id="btn-add-attr" class="btn btn-warning btn-sm">
                    <i class="fa fa-plus mr-1"></i> Agregar fila
                </button>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm mb-0">
                        <thead class="bg-secondary text-white">
                            <tr>
                                <th>Nombre</th>
                                <th>Descripción / Valor</th>
                                <th style="width:60px" class="text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody id="attr-list">
                            @foreach ($attributes as $attr)
                                <tr>
                                    <td>
                                        <input type="hidden" name="id[]" value="{{ $attr->id }}">
                                        <input type="text" name="name_[]" maxlength="200"
                                            class="form-control form-control-sm"
                                            value="{{ $attr->name }}"
                                            placeholder="Nombre de la subcategoría">
                                    </td>
                                    <td>
                                        <input type="text" name="value_[]" maxlength="200"
                                            class="form-control form-control-sm"
                                            value="{{ $attr->value }}"
                                            placeholder="Descripción o valor">
                                    </td>
                                    <td class="text-center">
                                        <a href="/admin/categories/eliminarattr/{{ $attr->id }}"
                                            class="btn btn-xs btn-outline-danger btn-delete-attr"
                                            title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($attributes->isEmpty())
                    <div id="empty-msg" class="text-center py-4 text-muted">
                        <i class="fas fa-layer-group fa-2x d-block mb-2" style="opacity:.25;"></i>
                        Sin subcategorías. Puede agregarlas con el botón <strong>Agregar fila</strong>.
                    </div>
                @endif
            </div>
        </div>

        {{-- Footer --}}
        <div class="d-flex justify-content-between mb-4">
            <a href="{{ route('categorias') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left mr-1"></i> Cancelar
            </a>
            <button type="submit" class="btn btn-warning px-4">
                <i class="fa fa-save mr-1"></i> Actualizar
            </button>
        </div>

    </form>
</div>
@stop

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const attrList = document.getElementById('attr-list');
        const emptyMsg = document.getElementById('empty-msg');

        document.getElementById('btn-add-attr').addEventListener('click', function () {
            if (emptyMsg) emptyMsg.style.display = 'none';

            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <input type="hidden" name="id[]" value="0">
                    <input type="text" name="name_[]" maxlength="200"
                        class="form-control form-control-sm"
                        placeholder="Nombre de la subcategoría">
                </td>
                <td>
                    <input type="text" name="value_[]" maxlength="200"
                        class="form-control form-control-sm"
                        placeholder="Descripción o valor">
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-xs btn-outline-danger btn-remove-row"
                        title="Quitar fila">
                        <i class="fas fa-times"></i>
                    </button>
                </td>`;

            attrList.appendChild(row);

            row.querySelector('.btn-remove-row').addEventListener('click', function () {
                row.remove();
            });
        });

        // Delete existing attr with confirm
        document.querySelectorAll('.btn-delete-attr').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const href = this.getAttribute('href');

                Swal.fire({
                    title: '¿Eliminar subcategoría?',
                    text: 'Esta acción no se puede deshacer.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                }).then(function (result) {
                    if (result.isConfirmed) {
                        window.location.href = href;
                    }
                });
            });
        });

    });
</script>
@stop
