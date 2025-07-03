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
                                                        <button type="button" class="btn btn-default btn-sm" data-bs-toggle="modal" data-bs-target="#modal-qr-{{ $taller->id }}" title="Código QR de Registro">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-qrcode" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                <rect x="4" y="4" width="6" height="6" rx="1" />
                                                                <line x1="7" y1="17" x2="7" y2="17.01" />
                                                                <rect x="14" y="4" width="6" height="6" rx="1" />
                                                                <line x1="7" y1="7" x2="7" y2="7.01" />
                                                                <rect x="4" y="14" width="6" height="6" rx="1" />
                                                                <line x1="17" y1="7" x2="17" y2="7.01" />
                                                                <line x1="14" y1="14" x2="17" y2="14" />
                                                                <line x1="20" y1="14" x2="20" y2="14.01" />
                                                                <line x1="14" y1="14" x2="14" y2="17" />
                                                                <line x1="14" y1="20" x2="17" y2="20" />
                                                                <line x1="17" y1="17" x2="20" y2="17" />
                                                                <line x1="20" y1="17" x2="20" y2="20" />
                                                            </svg>
                                                        </button>
                                                        <a href="{{ route('talleres.participantesInscritos', $taller) }}" class="btn btn-default btn-sm" title="Ver Participantes Inscritos">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-users" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                                                <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                                                <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
                                                            </svg>
                                                        </a>
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

    {{-- Modal para Crear Taller --}}
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

    {{-- Modales para Códigos QR --}}
    @foreach ($talleres as $taller)
    <div class="modal modal-blur fade" id="modal-qr-{{ $taller->id }}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">QR Registro: {{ $taller->nombre }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body text-center">
                    @if(isset($qrCodes[$taller->id]))
                        {!! $qrCodes[$taller->id] !!}
                        <p class="mt-2 small text-muted">Escanee este código para que los participantes se inscriban al taller.</p>
                        <a href="{{ route('registro.taller.form', $taller->id) }}" class="btn btn-outline-primary btn-sm mt-2" target="_blank" title="Abrir enlace de registro en nueva pestaña">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-link" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 14a3.5 3.5 0 0 0 5 0l4 -4a3.5 3.5 0 0 0 -5 -5l-.5 .5" /><path d="M14 10a3.5 3.5 0 0 0 -5 0l-4 4a3.5 3.5 0 0 0 5 5l.5 -.5" /></svg>
                            Ver enlace
                        </a>
                         <button type="button" class="btn btn-primary btn-sm mt-2" onclick="descargarQR('{{ $taller->id }}', '{{ Str::slug($taller->nombre) }}')">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-download" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" /><path d="M7 11l5 5l5 -5" /><path d="M12 4l0 12" /></svg>
                            Descargar QR
                        </button>
                    @else
                        <p class="text-danger">No se pudo generar el código QR para este taller.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach

@endsection

@section('scripts')

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        function descargarQR(tallerId, tallerNombreSlug) {
            const modal = document.getElementById('modal-qr-' + tallerId);
            const svgElement = modal.querySelector('svg:not(.icon)'); // Asegurarse de no seleccionar los iconos de los botones

            if (!svgElement) {
                console.error('No se encontró el elemento SVG del QR para el taller ID:', tallerId);
                alert('Error al encontrar el código QR para descargar.');
                return;
            }

            const serializer = new XMLSerializer();
            let source = serializer.serializeToString(svgElement);

            if(!source.match(/^<svg[^>]+xmlns="http:\/\/www\.w3\.org\/2000\/svg"/)){
                source = source.replace(/^<svg/, '<svg xmlns="http://www.w3.org/2000/svg"');
            }
            if(!source.match(/^<svg[^>]+"http:\/\/www\.w3\.org\/1999\/xlink"/)){
                source = source.replace(/^<svg/, '<svg xmlns:xlink="http://www.w3.org/1999/xlink"');
            }

            source = '<?xml version="1.0" standalone="no"?>\r\n' + source;

            const url = "data:image/svg+xml;charset=utf-8," + encodeURIComponent(source);

            const link = document.createElement('a');
            link.download = 'qr_registro_' + tallerNombreSlug + '.svg';
            link.href = url;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function initSelect2() {
            $('#modal-create select[name="cultor_id"], #modal-create select[name="comuna_id"]').select2({
                dropdownParent: $('#modal-create')
            });
            // Para los select de día de semana dentro del modal, si es necesario aplicarles Select2
            // $('.horario-item select[name*="[dia_semana]"]').select2({
            //     dropdownParent: $('#modal-create')
            // });
        }

        $(document).ready(function () {
            $('#modal-create').on('shown.bs.modal', function () {
                $('#modal-create input[name="nombre"]').trigger('focus');
                initSelect2();
            });
        });
    </script>


    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let horarioIndex = {{ old('horarios') ? count(old('horarios')) : 1 }};

            document.getElementById("add-horario").addEventListener("click", function () {
                const container = document.getElementById("horarios-container");
                const newHorario = container.children[0].cloneNode(true); // Clonar el primer item como plantilla

                // Limpiar valores y actualizar índices
                newHorario.querySelectorAll('input, select').forEach(el => {
                    el.name = el.name.replace(/\[0\]/, `[${horarioIndex}]`);
                    if (el.tagName === 'INPUT' && el.type !== 'time') el.value = '';
                    if (el.tagName === 'INPUT' && el.type === 'time') el.value = '';
                    if (el.tagName === 'SELECT') el.selectedIndex = 0;
                    el.classList.remove('is-invalid');
                });
                newHorario.querySelectorAll('.text-danger').forEach(el => el.remove());

                // Habilitar el botón de eliminar para el nuevo y anteriores (excepto si solo queda uno)
                newHorario.querySelector('.remove-horario').disabled = false;
                actualizarBotonesRemoveHorario();


                container.appendChild(newHorario);
                horarioIndex++;
                actualizarBotonesRemoveHorario();
            });

            document.getElementById("horarios-container").addEventListener("click", function (e) {
                if (e.target.classList.contains("remove-horario")) {
                    e.target.closest(".horario-item").remove();
                    actualizarBotonesRemoveHorario();
                }
            });

            function actualizarBotonesRemoveHorario() {
                const container = document.getElementById("horarios-container");
                const items = container.querySelectorAll('.horario-item');
                items.forEach((item, index) => {
                    const button = item.querySelector('.remove-horario');
                    if (button) {
                        button.disabled = items.length === 1;
                    }
                });
            }
            actualizarBotonesRemoveHorario(); // Llamada inicial

            // Si hay errores de validación y se recarga la página con old data
            @if ($errors->any() && old('horarios'))
                var modal = new bootstrap.Modal(document.getElementById('modal-create'));
                modal.show();
            @endif
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const form = document.querySelector('#modal-create form');
            if (form) {
                form.addEventListener('submit', function (e) {
                    let isValid = true;
                    let erroresHtml = "";

                    document.querySelectorAll('#modal-create .horario-item').forEach(function (item, index) {
                        const diaSelect = item.querySelector(`select[name^="horarios["][name$="[dia_semana]"]`);
                        const horaInicioInput = item.querySelector(`input[name^="horarios["][name$="[hora_inicio]"]`);
                        const horaFinInput = item.querySelector(`input[name^="horarios["][name$="[hora_fin]"]`);
                        const lugarInput = item.querySelector(`input[name^="horarios["][name$="[lugar]"]`);

                        // Limpiar errores previos
                        const limpiarError = (input) => {
                            input.classList.remove('is-invalid');
                            let errorDiv = input.closest('.col-md-2, .col-md-3, .col-md-4').querySelector('.invalid-feedback-custom');
                            if (errorDiv) errorDiv.remove();
                        };

                        [diaSelect, horaInicioInput, horaFinInput, lugarInput].forEach(limpiarError);

                        let erroresEnItem = [];

                        if (!diaSelect.value) {
                            erroresEnItem.push('Debe seleccionar un día.');
                            diaSelect.classList.add('is-invalid');
                        }
                        if (!horaInicioInput.value) {
                            erroresEnItem.push('Debe ingresar una hora de inicio.');
                            horaInicioInput.classList.add('is-invalid');
                        }
                        if (!horaFinInput.value) {
                            erroresEnItem.push('Debe ingresar una hora de fin.');
                            horaFinInput.classList.add('is-invalid');
                        }
                        if (horaInicioInput.value && horaFinInput.value && horaFinInput.value <= horaInicioInput.value) {
                            erroresEnItem.push('La hora de fin debe ser posterior a la hora de inicio.');
                            horaFinInput.classList.add('is-invalid');
                        }
                        if (!lugarInput.value.trim()) {
                            erroresEnItem.push('Debe ingresar un lugar.');
                            lugarInput.classList.add('is-invalid');
                        }

                        if (erroresEnItem.length > 0) {
                            isValid = false;
                            erroresHtml += `<li>Horario ${index + 1}:<ul>`;
                            erroresEnItem.forEach(err => {
                                erroresHtml += `<li>${err}</li>`;
                                // Mostrar error debajo del campo específico (opcional, mejora UX)
                                let elWithError = null;
                                if (err.includes('día')) elWithError = diaSelect;
                                else if (err.includes('inicio')) elWithError = horaInicioInput;
                                else if (err.includes('fin')) elWithError = horaFinInput;
                                else if (err.includes('lugar')) elWithError = lugarInput;

                                if(elWithError){
                                    let errorDiv = document.createElement('div');
                                    errorDiv.classList.add('invalid-feedback-custom', 'text-danger', 'mt-1');
                                    errorDiv.style.fontSize = '0.875em';
                                    errorDiv.textContent = err;
                                    elWithError.closest('.col-md-2, .col-md-3, .col-md-4').appendChild(errorDiv);
                                }
                            });
                            erroresHtml += `</ul></li>`;
                        }
                    });

                    // Validar campos principales del taller
                    const nombreTaller = document.querySelector('#modal-create input[name="nombre"]');
                    const comunaTaller = document.querySelector('#modal-create select[name="comuna_id"]');

                    if(!nombreTaller.value.trim()){
                        isValid = false;
                        erroresHtml += `<li>El nombre del taller es obligatorio.</li>`;
                        nombreTaller.classList.add('is-invalid');
                    } else {
                        nombreTaller.classList.remove('is-invalid');
                    }
                    if(!comunaTaller.value){
                         isValid = false;
                        erroresHtml += `<li>Debe seleccionar una comuna para el taller.</li>`;
                        // No se puede añadir is-invalid directamente a select2 de forma estándar,
                        // pero el mensaje global es suficiente.
                    }


                    if (!isValid) {
                        e.preventDefault();
                        const errorContainer = document.querySelector('#modal-create .alert-danger ul');
                        if (errorContainer) { // Si ya existe el contenedor de errores generales
                           // Podríamos añadir estos errores específicos ahí, o manejarlo de otra forma.
                           // Por ahora, la validación de backend es la principal para errores de formato.
                           // Esta validación JS es más para usabilidad.
                        }
                        // alert("Por favor, corrija los errores en los horarios:\n" + erroresHtml.replace(/<li>/g, '- ').replace(/<\/li>|<\/ul>|<ul>/g, '\n'));
                        // Podríamos mostrar los errores en un div específico dentro del modal.
                    }
                });
            }
        });
    </script>


@endsection
