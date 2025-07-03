@extends('layout')

@section('styles')
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">Cultores</h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="#" class="btn btn-primary d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-create">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg>
                            Nuevo Cultor
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
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Documento</th>
                                            <th>Nombre Completo</th>
                                            <th>Email</th>
                                            <th>Teléfono</th>
                                            <th>Comuna</th>
                                            <th>Talleres</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($cultores as $cultor)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $cultor->tipo_documento }} {{ $cultor->documento }}</td>
                                                <td>{{ $cultor->nombres }} {{ $cultor->apellidos }}</td>
                                                <td>{{ $cultor->email }}</td>
                                                <td>{{ $cultor->telefono }}</td>
                                                <td>{{ $cultor->comuna->nombre ?? 'Sin comuna' }}</td>
                                                <td>{{ $cultor->talleres->count() }}</td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('cultores.show', $cultor) }}" class="btn btn-default btn-sm" title="Ver detalles">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                <path d="M12 12m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                                                <path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" />
                                                            </svg>
                                                        </a>
                                                        <a href="{{ route('cultores.edit', $cultor) }}" class="btn btn-default btn-sm" title="Editar">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z"/>
                                                                <path d="M16 3l4 4l-11 11h-4v-4z" />
                                                            </svg>
                                                        </a>
                                                        <form action="{{ route('cultores.destroy', $cultor) }}" method="POST" onsubmit="return confirm('¿Eliminar este cultor?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-default btn-sm" title="Eliminar">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                    <path stroke="none" d="M0 0h24v24H0z"/>
                                                                    <path d="M4 7h16" />
                                                                    <path d="M10 11v6" />
                                                                    <path d="M14 11v6" />
                                                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                                    <path d="M9 7v-3h6v3" />
                                                                </svg>
                                                            </button>
                                                        </form>
                                                        
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">No hay cultores registrados.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para crear nuevo cultor -->
    <div class="modal modal-blur fade" id="modal-create" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form action="{{ route('cultores.store') }}" method="POST" enctype="multipart/form-data">
                
                @csrf
                
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Nuevo Cultor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Tipo de Documento</label>
                                    <select name="tipo_documento" class="form-control @error('tipo_documento') is-invalid @enderror" required>
                                        <option value="">Seleccione</option>
                                        <option value="CC" {{ old('tipo_documento') == 'CC' ? 'selected' : '' }}>Cédula de Ciudadanía</option>
                                        <option value="CE" {{ old('tipo_documento') == 'CE' ? 'selected' : '' }}>Cédula de Extranjería</option>
                                        <option value="TI" {{ old('tipo_documento') == 'TI' ? 'selected' : '' }}>Tarjeta de Identidad</option>
                                        <option value="PA" {{ old('tipo_documento') == 'PA' ? 'selected' : '' }}>Pasaporte</option>
                                    </select>
                                    @error('tipo_documento')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Número de Documento</label>
                                    <input type="number" class="form-control @error('documento') is-invalid @enderror" name="documento" value="{{ old('documento') }}" required>
                                    @error('documento')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nombres</label>
                                    <input type="text" class="form-control @error('nombres') is-invalid @enderror" name="nombres" value="{{ old('nombres') }}" required>
                                    @error('nombres')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Apellidos</label>
                                    <input type="text" class="form-control @error('apellidos') is-invalid @enderror" name="apellidos" value="{{ old('apellidos') }}" required>
                                    @error('apellidos')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Fecha de Nacimiento</label>
                                    <input type="date" class="form-control @error('fecha_nacimiento') is-invalid @enderror" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" required>
                                    @error('fecha_nacimiento')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Teléfono</label>
                                    <input type="text" class="form-control @error('telefono') is-invalid @enderror" name="telefono" value="{{ old('telefono') }}" required>
                                    @error('telefono')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Dirección</label>
                            <input type="text" class="form-control @error('direccion') is-invalid @enderror" name="direccion" value="{{ old('direccion') }}" required>
                            @error('direccion')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Comuna</label>
                                    <select name="comuna_id" class="form-control @error('comuna_id') is-invalid @enderror" required>
                                        <option value="">Seleccione una comuna</option>
                                        @foreach ($comunas as $comuna)
                                            <option value="{{ $comuna->id }}" {{ old('comuna_id') == $comuna->id ? 'selected' : '' }}>{{ $comuna->nombre }}</option>
                                        @endforeach
                                    </select>
                                    @error('comuna_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Contraseña</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                                    @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Confirmar Contraseña</label>
                                    <input type="password" class="form-control" name="password_confirmation" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Foto de Perfil</label>
                            <input type="file" class="form-control @error('foto') is-invalid @enderror" name="foto" accept="image/*">
                            @error('foto')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">RUT</label>
                                    <input type="text" class="form-control @error('rut') is-invalid @enderror" name="rut" value="{{ old('rut') }}">
                                    @error('rut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Banco</label>
                                    <input type="text" class="form-control @error('banco') is-invalid @enderror" name="banco" value="{{ old('banco') }}">
                                    @error('banco')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Tipo de Cuenta</label>
                                    <select name="tipo_cuenta" class="form-control @error('tipo_cuenta') is-invalid @enderror">
                                        <option value="">Seleccione</option>
                                        <option value="Ahorros" {{ old('tipo_cuenta') == 'Ahorros' ? 'selected' : '' }}>Ahorros</option>
                                        <option value="Corriente" {{ old('tipo_cuenta') == 'Corriente' ? 'selected' : '' }}>Corriente</option>
                                    </select>
                                    @error('tipo_cuenta')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Número de Cuenta</label>
                                    <input type="text" class="form-control @error('numero_cuenta') is-invalid @enderror" name="numero_cuenta" value="{{ old('numero_cuenta') }}">
                                    @error('numero_cuenta')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer text-end">
                        <button type="button" class="btn btn-link" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M12 5v14" />
                                <path d="M5 12h14" />
                            </svg>
                            Registrar Cultor
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        function initSelect2() {
            $('select').select2({
                dropdownParent: $('#modal-create')
            });
        }

        $(document).ready(function () {
            $('#modal-create').on('shown.bs.modal', function () {
                $('#tipo_documento').trigger('focus');
                initSelect2();
            });

            @if ($errors->any())
                $('#modal-create').modal('show');
            @endif
        });
    </script>
@endsection