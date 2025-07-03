@extends('layout')

@section('styles')
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Flatpickr para fecha -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">Editar Participante</h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('participantes.index') }}" class="btn btn-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M5 12l14 0" />
                                <path d="M5 12l6 6" />
                                <path d="M5 12l6 -6" />
                            </svg>
                            Volver al listado
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <form action="{{ route('participantes.update', $participante) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="card-header">
                                <h3 class="card-title">Datos del Participante</h3>
                            </div>
                            <div class="card-body">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Tipo Documento</label>
                                            <select name="tipo_documento" class="form-select @error('tipo_documento') is-invalid @enderror" required>
                                                <option value="">Seleccione</option>
                                                <option value="CC" {{ old('tipo_documento', $participante->tipo_documento) == 'CC' ? 'selected' : '' }}>Cédula</option>
                                                <option value="TI" {{ old('tipo_documento', $participante->tipo_documento) == 'TI' ? 'selected' : '' }}>Tarjeta Identidad</option>
                                                <option value="CE" {{ old('tipo_documento', $participante->tipo_documento) == 'CE' ? 'selected' : '' }}>Cédula Extranjería</option>
                                                <option value="PA" {{ old('tipo_documento', $participante->tipo_documento) == 'PA' ? 'selected' : '' }}>Pasaporte</option>
                                            </select>
                                            @error('tipo_documento')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Documento</label>
                                            <input type="number" class="form-control @error('documento') is-invalid @enderror" 
                                                   name="documento" value="{{ old('documento', $participante->documento) }}" required>
                                            @error('documento')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Nombre Completo</label>
                                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                                                   name="nombre" value="{{ old('nombre', $participante->nombre) }}" required>
                                            @error('nombre')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Fecha Nacimiento</label>
                                            <input type="text" class="form-control flatpickr-date @error('fecha_nacimiento') is-invalid @enderror" 
                                                   name="fecha_nacimiento" value="{{ old('fecha_nacimiento', $participante->fecha_nacimiento) }}" required>
                                            @error('fecha_nacimiento')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Teléfono</label>
                                            <input type="text" class="form-control @error('telefono') is-invalid @enderror" 
                                                   name="telefono" value="{{ old('telefono', $participante->telefono) }}" required>
                                            @error('telefono')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                                   name="email" value="{{ old('email', $participante->email) }}">
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Dirección</label>
                                            <input type="text" class="form-control @error('direccion') is-invalid @enderror" 
                                                   name="direccion" value="{{ old('direccion', $participante->direccion) }}" required>
                                            @error('direccion')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Barrio</label>
                                            <input type="text" class="form-control @error('barrio') is-invalid @enderror" 
                                                   name="barrio" value="{{ old('barrio', $participante->barrio) }}" required>
                                            @error('barrio')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Comuna</label>
                                            <select name="comuna_id" class="form-select @error('comuna_id') is-invalid @enderror" required>
                                                <option value="">Seleccione</option>
                                                @foreach ($comunas as $comuna)
                                                    <option value="{{ $comuna->id }}" {{ old('comuna_id', $participante->comuna_id) == $comuna->id ? 'selected' : '' }}>
                                                        {{ $comuna->nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('comuna_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-check">
                                        <input type="checkbox" class="form-check-input" name="estado" value="activo" 
                                            {{ old('estado', $participante->estado) == 'activo' ? 'checked' : '' }}>
                                        <span class="form-check-label">Activo</span>
                                    </label>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <a href="{{ route('participantes.index') }}" class="btn btn-link">Cancelar</a>
                                <button type="submit" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M5 12l5 5l10 -10" />
                                    </svg>
                                    Actualizar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>

    <script>
        $(document).ready(function() {
            // Inicializar Select2
            $('select').select2({
                dropdownParent: $('.card-body')
            });

            // Inicializar Flatpickr para fecha
            flatpickr(".flatpickr-date", {
                dateFormat: "Y-m-d",
                locale: "es",
                maxDate: new Date().fp_incr(-15 * 365), // Mínimo 15 años de edad
            });

            // Mostrar modal si hay errores
            @if ($errors->any())
                const errors = @json($errors->all());
                if(errors.length > 0) {
                    // Scroll al primer error
                    const firstError = document.querySelector('.is-invalid');
                    if(firstError) {
                        firstError.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }
                }
            @endif
        });
    </script>
@endsection