@include('common.modal.modalHeader')

<div class="modal-body p-0">

    <div wire:loading.class="overlay" class="d-none" wire:loading.class.remove="d-none">
        <i class="fas fa-2x fa-sync-alt fa-spin"></i>
    </div>

    <form wire:submit.prevent="{{ $selected_id == 0 ? 'save' : 'update' }}">

        {{-- ── Datos del estudiante ─────────────────────────────────── --}}
        <div class="card card-primary card-outline mb-0">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user-graduate mr-1"></i> Datos del Estudiante
                </h3>
            </div>
            <div class="card-body">
                <div class="form-row">

                    {{-- Documento --}}
                    <div class="form-group col-md-4">
                        <label>Tipo y Nº de Documento <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <select class="form-control @error('document_type') is-invalid @enderror"
                                        wire:model.lazy="document_type"
                                        wire:change="chageDocumentType()">
                                    <option value="1">DNI</option>
                                </select>
                            </div>
                            <input type="number"
                                   wire:model.defer="document"
                                   wire:loading.attr="readonly"
                                   class="form-control @error('document') is-invalid @enderror"
                                   placeholder="Número de documento">
                        </div>
                        @error('document_type')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                        @error('document')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Grado --}}
                    <div class="form-group col-md-4">
                        <label>Grado <span class="text-danger">*</span></label>
                        <select class="form-control @error('grade_id') is-invalid @enderror"
                                wire:model.defer="grade_id">
                            <option value="">— Seleccione un grado —</option>
                            @foreach ($grades as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('grade_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Docente tutor (solo en edición) --}}
                    @if($selected_id != 0)
                    <div class="form-group col-md-4">
                        <label>Docente Tutor</label>
                        <select class="form-control @error('teacher_id') is-invalid @enderror"
                                wire:model.defer="teacher_id">
                            <option value="">— Seleccione un docente —</option>
                            @foreach ($teachers as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('teacher_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    @endif

                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Apellidos <span class="text-danger">*</span></label>
                        <input type="text"
                               wire:model.lazy="last_name"
                               class="form-control @error('last_name') is-invalid @enderror"
                               placeholder="Apellidos">
                        @error('last_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label>Nombres <span class="text-danger">*</span></label>
                        <input type="text"
                               wire:model.lazy="first_name"
                               class="form-control @error('first_name') is-invalid @enderror"
                               placeholder="Nombres">
                        @error('first_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Correo electrónico</label>
                        <input type="email"
                               wire:model.defer="email"
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="correo@ejemplo.com">
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-3">
                        <label>Celular</label>
                        <input type="tel"
                               wire:model.defer="phone"
                               class="form-control @error('phone') is-invalid @enderror"
                               placeholder="Teléfono o celular">
                        @error('phone')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-3">
                        <label>Foto</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file"
                                       wire:model.lazy="photo"
                                       id="{{ $photoId }}customFileLang"
                                       class="custom-file-input @error('photo') is-invalid @enderror"
                                       accept="image/*">
                                <label class="custom-file-label" for="{{ $photoId }}customFileLang">
                                    Seleccionar...
                                </label>
                            </div>
                        </div>
                        @error('photo')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row align-items-start">
                    <div class="form-group col-md-8">
                        <label>Dirección</label>
                        <input type="text"
                               wire:model.lazy="address"
                               class="form-control @error('address') is-invalid @enderror"
                               placeholder="Dirección">
                        @error('address')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror

                        <label class="mt-2">Información adicional</label>
                        <textarea wire:model.defer="description"
                                  class="form-control @error('description') is-invalid @enderror"
                                  rows="2" placeholder="Observaciones..."></textarea>
                        @error('description')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4 text-center">
                        @if($photo)
                            <img src="{{ $photo->temporaryUrl() }}"
                                 class="img-fluid img-circle elevation-2"
                                 width="120" height="120" alt="Vista previa">
                        @else
                            <img src="{{ asset('imagenes/profile-default.png') }}"
                                 class="img-fluid img-circle elevation-1"
                                 width="120" height="120" alt="Sin foto">
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Datos del tutor ──────────────────────────────────────── --}}
        <div class="card card-warning card-outline mb-0">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user-friends mr-1"></i> Padre / Madre / Tutor
                </h3>
            </div>
            <div class="card-body">

                <div class="callout callout-warning p-2 mb-3">
                    <small><i class="fas fa-info-circle mr-1"></i>
                        El tutor o apoderado no puede ser menor de edad.
                    </small>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Tipo y Nº de Documento <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <select class="form-control @error('tutor_document_type') is-invalid @enderror"
                                        wire:model.lazy="tutor_document_type"
                                        wire:change="chageDocumentType()">
                                    <option value="1">DNI</option>
                                </select>
                            </div>
                            <input type="number"
                                   wire:model.defer="tutor_document"
                                   wire:change="clearDataApi()"
                                   wire:loading.attr="readonly"
                                   class="form-control @error('tutor_document') is-invalid @enderror"
                                   placeholder="Número de documento">
                            <div class="input-group-append">
                                <button type="button"
                                        wire:click="ConsutasApi()"
                                        wire:loading.attr="disabled"
                                        class="btn btn-info btn-sm">
                                    <i class="fas fa-search mr-1"></i> Buscar
                                </button>
                            </div>
                        </div>
                        @error('tutor_document_type')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                        @error('tutor_document')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label>Parentesco <span class="text-danger">*</span></label>
                        <select class="form-control @error('tutor_type') is-invalid @enderror"
                                wire:model.defer="tutor_type">
                            <option value="">— Seleccione —</option>
                            <option value="PADRE">Padre</option>
                            <option value="MADRE">Madre</option>
                            <option value="TUTOR">Tutor</option>
                            <option value="APODERADO">Apoderado</option>
                            <option value="OTROS">Otros</option>
                        </select>
                        @error('tutor_type')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Apellidos <span class="text-danger">*</span></label>
                        <input type="text"
                               wire:model.lazy="tutor_last_name"
                               class="form-control @error('tutor_last_name') is-invalid @enderror"
                               placeholder="Apellidos">
                        @error('tutor_last_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label>Nombres <span class="text-danger">*</span></label>
                        <input type="text"
                               wire:model.lazy="tutor_first_name"
                               class="form-control @error('tutor_first_name') is-invalid @enderror"
                               placeholder="Nombres">
                        @error('tutor_first_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Correo electrónico</label>
                        <input type="email"
                               wire:model.defer="tutor_email"
                               class="form-control @error('tutor_email') is-invalid @enderror"
                               placeholder="correo@ejemplo.com">
                        @error('tutor_email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6">
                        <label>Celular</label>
                        <input type="tel"
                               wire:model.defer="tutor_phone"
                               class="form-control @error('tutor_phone') is-invalid @enderror"
                               placeholder="Teléfono o celular">
                        @error('tutor_phone')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label>Dirección</label>
                        <input type="text"
                               wire:model.lazy="tutor_address"
                               class="form-control @error('tutor_address') is-invalid @enderror"
                               placeholder="Dirección">
                        @error('tutor_address')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-12">
                        <label>Información adicional</label>
                        <textarea wire:model.defer="tutor_description"
                                  class="form-control @error('tutor_description') is-invalid @enderror"
                                  rows="2" placeholder="Observaciones..."></textarea>
                        @error('tutor_description')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

            </div>
        </div>

        {{-- ── Footer del formulario ────────────────────────────────── --}}
        <div class="modal-footer">
            <button type="button"
                    wire:click.prevent="resetUI()"
                    wire:loading.attr="disabled"
                    wire:target="photo, resetUI"
                    class="btn btn-default"
                    data-dismiss="modal">
                <i class="fas fa-times mr-1"></i> Cancelar
            </button>
            <button type="submit"
                    wire:loading.attr="disabled"
                    wire:target="photo, save, update"
                    class="btn btn-{{ $selected_id == 0 ? 'primary' : 'warning' }}">
                <i class="fas fa-{{ $selected_id == 0 ? 'save' : 'sync-alt' }} mr-1"
                   wire:loading.class="fa-spin"
                   wire:target="photo, save, update"></i>
                {{ $selected_id == 0 ? 'Guardar' : 'Actualizar' }}
            </button>
        </div>

    </form>
</div>

</div>{{-- modal-content --}}
</div>{{-- modal-dialog --}}
</div>{{-- modal --}}
