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
                    <h2 class="page-title">Talleres de {{ $comuna->nombre }}</h2>
                </div>
                
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('comunas.index') }}" class="btn btn-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z"/>
                                <path d="M5 12h14" />
                                <path d="M5 12l6 -6" />
                                <path d="M5 12l6 6" />
                            </svg>
                            Volver al listado
                        </a>
                        
                        <a href="#" class="btn btn-primary d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-create">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                            Nuevo Taller
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
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nombre</th>
                                            <th>Descripción</th>
                                            <th>Cultor</th>
                                            <th>Comuna</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($comuna->talleres as $taller)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $taller->nombre }}</td>
                                                <td>{{ $taller->descripcion }}</td>
                                                <td>{{ $taller->cultor ? $taller->cultor->nombres . ' ' . $taller->cultor->apellidos : 'Sin asignar' }}</td>
                                                <td>{{ $taller->comuna->nombre }}</td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('talleres.edit', $taller) }}" class="btn btn-default btn-sm" title="Editar">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z"/>
                                                                <path d="M16 3l4 4l-11 11h-4v-4z" />
                                                            </svg>
                                                        </a>
                                                        <form action="{{ route('talleres.destroy', $taller) }}" method="POST" onsubmit="return confirm('¿Eliminar este taller?')">
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
                                                <td colspan="6" class="text-center">No hay talleres registrados.</td>
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

    <!-- Modal para crear taller -->
    <div class="modal modal-blur fade" id="modal-create" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('comunas.storeTaller') }}" method="POST">
                @csrf

                <input type="hidden" name="comuna_id" value="{{ $comuna->id }}">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Nuevo taller para {{ $comuna->nombre }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nombre del taller</label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                            @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" name="descripcion">{{ old('descripcion') }}</textarea>
                            @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Cultor</label>
                            <select name="cultor_id" class="form-control @error('cultor_id') is-invalid @enderror" required>
                                <option value="">Seleccione un cultor</option>
                                @foreach ($cultores as $cultor)
                                    <option value="{{ $cultor->id }}" {{ old('cultor_id') == $cultor->id ? 'selected' : '' }}>
                                        {{ $cultor->nombres . ' ' . $cultor->apellidos }}
                                    </option>
                                @endforeach
                            </select>
                            @error('cultor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                            Registrar
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
                $('#nombre').trigger('focus');
                initSelect2();
            });

            @if ($errors->any())
                $('#modal-create').modal('show');
            @endif
        });
    </script>
@endsection
