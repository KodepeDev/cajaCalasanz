@extends('adminlte::page')
@section('title', 'Agregar Subcategorías')

@section('content')
<div class="container-fluid pt-4">
    <form action="/admin/categories/save_attr/{{ $categorie->id }}" method="POST">
        @csrf
        <div class="card card-secondary">
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="fas fa-list mr-2"></i>
                    <div>
                        <h3 class="card-title mb-0">Subcategorías</h3>
                        <small class="text-light opacity-75">
                            Categoría: <strong>{{ $categorie->name }}</strong>
                        </small>
                    </div>
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
                            <tr>
                                <td>
                                    <input type="text" name="name_[]" maxlength="200"
                                        class="form-control form-control-sm"
                                        placeholder="Nombre de la subcategoría">
                                </td>
                                <td>
                                    <input type="text" name="value_[]" maxlength="200"
                                        class="form-control form-control-sm"
                                        placeholder="Descripción o valor">
                                </td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-between">
                <a href="{{ route('categorias') }}" class="btn btn-secondary">
                    <i class="fa fa-forward mr-1"></i> Omitir
                </a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fa fa-save mr-1"></i> Guardar subcategorías
                </button>
            </div>
        </div>
    </form>
</div>
@stop

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const attrList = document.getElementById('attr-list');

        document.getElementById('btn-add-attr').addEventListener('click', function () {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
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
                    <button type="button" class="btn btn-xs btn-outline-danger"
                        onclick="this.closest('tr').remove()" title="Quitar fila">
                        <i class="fas fa-times"></i>
                    </button>
                </td>`;
            attrList.appendChild(row);
        });
    });
</script>
@stop
