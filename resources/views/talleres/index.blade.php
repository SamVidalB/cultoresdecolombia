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
                    <h2 class="page-title">Talleres</h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
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
                                            <th>Nombre</th>
                                            <th>Descripción</th>
                                            <th>Cultor</th>
                                            <th>Comuna</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($talleres as $taller)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $taller->nombre }}</td>
                                                <td>{{ $taller->descripcion }}</td>
                                                <td>{{ $taller->cultor ? $taller->cultor->nombres . ' ' . $taller->cultor->apellidos : 'Sin asignar' }}</td>
                                                <td>{{ $taller->comuna->nombre }}</td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('talleres.show', $taller) }}" class="btn btn-default btn-sm" title="Ver detalles">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                <path d="M12 12m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                                                <path d="M22 12c-2.667 4.667 -6 7 -10 7s-7.333 -2.333 -10 -7c2.667 -4.667 6 -7 10 -7s7.333 2.333 10 7" />
                                                            </svg>
                                                        </a>
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

    <div class="modal modal-blur fade" id="modal-create" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document"> {{-- Ampliamos el tamaño del modal --}}
            <form action="{{ route('talleres.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Nuevo Taller</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        
                        {{-- Errores globales --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong>Ups!</strong> Corrige los siguientes errores:
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Datos del taller --}}
                        <div class="mb-3">
                            <label class="form-label">Nombre del taller</label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" name="nombre" value="{{ old('nombre') }}" required>
                            @error('nombre')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" name="descripcion">{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Cultor</label>
                                <select name="cultor_id" class="form-control @error('cultor_id') is-invalid @enderror">
                                    <option value="">Seleccione un cultor</option>
                                    @foreach ($cultores as $cultor)
                                        <option value="{{ $cultor->id }}" {{ old('cultor_id') == $cultor->id ? 'selected' : '' }}>
                                            {{ $cultor->nombres . ' ' . $cultor->apellidos }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('cultor_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Comuna</label>
                                <select name="comuna_id" class="form-control @error('comuna_id') is-invalid @enderror" required>
                                    <option value="">Seleccione una comuna</option>
                                    @foreach ($comunas as $comuna)
                                        <option value="{{ $comuna->id }}" {{ old('comuna_id') == $comuna->id ? 'selected' : '' }}>
                                            {{ $comuna->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('comuna_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <hr>
                        <h3>Horarios del taller</h3>

                        {{-- Contenedor de horarios --}}
                        <div id="horarios-container">
                            <div class="row horario-item mb-2">
                                <div class="col-md-3">
                                    <select name="horarios[0][dia_semana]" class="form-control @error('horarios.0.dia_semana') is-invalid @enderror" required>
                                        <option value="">Día</option>
                                        @foreach (['lunes','martes','miércoles','jueves','viernes','sábado','domingo'] as $dia)
                                            <option value="{{ $dia }}" {{ old('horarios.0.dia_semana') == $dia ? 'selected' : '' }}>
                                                {{ ucfirst($dia) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('horarios.0.dia_semana')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-2">
                                    <input type="time" name="horarios[0][hora_inicio]" class="form-control @error('horarios.0.hora_inicio') is-invalid @enderror" value="{{ old('horarios.0.hora_inicio') }}" required>
                                    @error('horarios.0.hora_inicio')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-2">
                                    <input type="time" name="horarios[0][hora_fin]" class="form-control @error('horarios.0.hora_fin') is-invalid @enderror" value="{{ old('horarios.0.hora_fin') }}" required>
                                    @error('horarios.0.hora_fin')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <input type="text" name="horarios[0][lugar]" class="form-control @error('horarios.0.lugar') is-invalid @enderror" placeholder="Lugar" value="{{ old('horarios.0.lugar') }}" required>
                                    @error('horarios.0.lugar')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-1 d-flex align-items-center">
                                    <button type="button" class="btn btn-danger btn-sm remove-horario" disabled>&times;</button>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="button" class="btn btn-outline-primary btn-sm" id="add-horario">Agregar otro horario</button>
                        </div>
                    </div>

                    <div class="modal-footer text-end">
                        <button type="button" class="btn btn-link" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Registrar</button>
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
                dropdownParent: $('#modal-create') // Usa el ID de tu modal aquí
            });
        }

        $(document).ready(function () {
     
            $('#modal-create').on('shown.bs.modal', function () {
                $('#nombre').trigger('focus');
                initSelect2();
            });

        });

    </script>


    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let horarioIndex = 1;

            document.getElementById("add-horario").addEventListener("click", function () {
                const container = document.getElementById("horarios-container");

                const row = document.createElement("div");
                row.classList.add("row", "horario-item", "mb-2");

                row.innerHTML = `
                    <div class="col-md-3">
                        <select name="horarios[${horarioIndex}][dia_semana]" class="form-control" required>
                            <option value="">Día</option>
                            @foreach (['lunes','martes','miércoles','jueves','viernes','sábado','domingo'] as $dia)
                                <option value="{{ $dia }}">{{ ucfirst($dia) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="time" name="horarios[${horarioIndex}][hora_inicio]" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <input type="time" name="horarios[${horarioIndex}][hora_fin]" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="horarios[${horarioIndex}][lugar]" class="form-control" placeholder="Lugar" required>
                    </div>
                    <div class="col-md-1 d-flex align-items-center">
                        <button type="button" class="btn btn-danger btn-sm remove-horario">&times;</button>
                    </div>
                `;

                container.appendChild(row);
                horarioIndex++;
            });

            document.getElementById("horarios-container").addEventListener("click", function (e) {
                if (e.target.classList.contains("remove-horario")) {
                    e.target.closest(".horario-item").remove();
                }
            });

            // Si hay errores, reabrir modal (ya incluido si usaste la sección previa)
            @if ($errors->any())
                var modal = new bootstrap.Modal(document.getElementById('modal-create'));
                modal.show();
            @endif
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const form = document.querySelector('#modal-create form');

            form.addEventListener('submit', function (e) {
                let isValid = true;
                let mensajeError = "";

                document.querySelectorAll('.horario-item').forEach(function (item, index) {
                    const horaInicio = item.querySelector(`[name^="horarios["][name$="[hora_inicio]"]`).value;
                    const horaFin = item.querySelector(`[name^="horarios["][name$="[hora_fin]"]`).value;

                    if (horaInicio && horaFin && horaFin <= horaInicio) {
                        isValid = false;
                        mensajeError += `- En el horario ${index + 1}, la hora de fin debe ser posterior a la hora de inicio.\n`;
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    alert(mensajeError);
                }
            });
        });
    </script>


@endsection
