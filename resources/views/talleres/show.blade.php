@extends('layout')

@section('styles')
    <style>
        .schedule-calendar .bg-blue-lt {
            background-color: #e1f0ff;
            border-left: 3px solid #467fcf;
        }
        .schedule-calendar th {
            text-align: center;
        }
        .schedule-calendar td {
            vertical-align: top;
            height: 120px;
        }
    </style>
@endsection

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">Detalle del Taller</h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('talleres.index') }}" class="btn btn-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M5 12l14 0" />
                                <path d="M5 12l6 6" />
                                <path d="M5 12l6 -6" />
                            </svg>
                            Volver al listado
                        </a>
                        <a href="{{ route('talleres.edit', $taller) }}" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M16 3l4 4l-11 11h-4v-4z" />
                            </svg>
                            Editar Taller
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
                <div class="col-md-4">
                    <!-- Información básica del taller -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3 class="card-title">Información General</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="text-muted">Nombre</div>
                                <h4>{{ $taller->nombre }}</h4>
                            </div>
                            
                            <div class="mb-3">
                                <div class="text-muted">Descripción</div>
                                <p>{{ $taller->descripcion ?? 'Sin descripción' }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <div class="text-muted">Comuna</div>
                                <div>{{ $taller->comuna->nombre }}</div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="text-muted">Cultor asignado</div>
                                <div>
                                    @if($taller->cultor)
                                        {{ $taller->cultor->nombres }} {{ $taller->cultor->apellidos }}
                                        <a href="{{ route('cultores.show', $taller->cultor) }}" class="btn btn-sm btn-link">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-external-link" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M12 6h-6a2 2 0 0 0 -2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-6" />
                                                <path d="M11 13l9 -9" />
                                                <path d="M15 4h5v5" />
                                            </svg>
                                        </a>
                                    @else
                                        <span class="text-muted">Sin cultor asignado</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div>
                                <div class="text-muted">Participantes inscritos</div>
                                <div>{{ $taller->participantes->count() }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Estadísticas -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Estadísticas</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="text-muted">Total de sesiones</div>
                                <div>{{ $taller->horarios->count() }}</div>
                            </div>
                            <div class="mb-3">
                                <div class="text-muted">Días programados</div>
                                <div>{{ $taller->horarios->groupBy('dia_semana')->count() }}</div>
                            </div>
                            <div>
                                <div class="text-muted">Última actualización</div>
                                <div>{{ $taller->updated_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-8">
                    <!-- Horarios del taller -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center w-100"> <!-- w-100 para asegurar ancho completo -->
                                <h3 class="card-title mb-0">Horarios del Taller</h3>
                                <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modal-horario">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M12 5l0 14" />
                                        <path d="M5 12l14 0" />
                                    </svg>
                                    Agregar Horario
                                </a>
                            </div>
                        </div>
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
                            
                            @if($taller->horarios->isEmpty())
                                <div class="alert alert-info">Este taller no tiene horarios programados.</div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Día</th>
                                                <th>Hora Inicio</th>
                                                <th>Hora Fin</th>
                                                <th>Lugar</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($taller->horarios as $horario)
                                                <tr>
                                                    <td>{{ ucfirst($horario->dia_semana) }}</td>
                                                    <td>{{ date('g:i A', strtotime($horario->hora_inicio)) }}</td>
                                                    <td>{{ date('g:i A', strtotime($horario->hora_fin)) }}</td>
                                                    <td>{{ $horario->lugar }}</td>
                                                    <td>
                                                        <div class="d-flex gap-2">
                                                            <a href="#" class="btn btn-sm btn-icon" title="Editar" data-bs-toggle="modal" data-bs-target="#modal-edit-horario-{{ $horario->id }}">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                                    <path d="M16 3l4 4l-11 11h-4v-4z" />
                                                                </svg>
                                                            </a>
                                                            <form action="{{ route('horarios.destroy', $horario) }}" method="POST" onsubmit="return confirm('¿Eliminar este horario?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button class="btn btn-sm btn-icon" title="Eliminar">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
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
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Calendario semanal -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Vista Semanal</h3>
                        </div>
                        <div class="card-body">
                            <div class="schedule-calendar">
                                @php
                                    $dias = ['lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado', 'domingo'];
                                @endphp
                                
                                <div class="table-responsive">
                                    <table class="table table-bordered" style="width: 800px;">
                                        <thead>
                                            <tr>
                                                @foreach($dias as $dia)
                                                    <th>{{ ucfirst($dia) }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                @foreach($dias as $dia)
                                                    <td>
                                                        @php
                                                            $horariosDia = $taller->horarios->where('dia_semana', $dia);
                                                        @endphp
                                                        
                                                        @if($horariosDia->isEmpty())
                                                            <div class="text-center text-muted py-3">Sin actividades</div>
                                                        @else
                                                            @foreach($horariosDia as $horario)
                                                                <div class="mb-2 p-2 bg-blue-lt rounded">
                                                                    <strong>{{ date('g:i A', strtotime($horario->hora_inicio)) }} - {{ date('g:i A', strtotime($horario->hora_fin)) }}</strong><br>
                                                                    <small>{{ $horario->lugar }}</small>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </td>
                                                @endforeach
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para agregar horario -->
    <div class="modal modal-blur fade" id="modal-horario" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('horarios.store') }}" method="POST">
                @csrf
                <input type="hidden" name="taller_id" value="{{ $taller->id }}">
                
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Agregar Horario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Día de la semana</label>
                            <select name="dia_semana" class="form-select" required>
                                <option value="">Seleccione un día</option>
                                <option value="lunes">Lunes</option>
                                <option value="martes">Martes</option>
                                <option value="miércoles">Miércoles</option>
                                <option value="jueves">Jueves</option>
                                <option value="viernes">Viernes</option>
                                <option value="sábado">Sábado</option>
                                <option value="domingo">Domingo</option>
                            </select>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Hora de inicio</label>
                                    <input type="time" class="form-control" name="hora_inicio" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Hora de fin</label>
                                    <input type="time" class="form-control" name="hora_fin" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Lugar</label>
                            <input type="text" class="form-control" name="lugar" placeholder="Ej: Salón principal" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg>
                            Guardar Horario
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modales para editar horarios -->
    @foreach($taller->horarios as $horario)
        <div class="modal modal-blur fade" id="modal-edit-horario-{{ $horario->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{ route('horarios.update', $horario) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Editar Horario</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Día de la semana</label>
                                <select name="dia_semana" class="form-select" required>
                                    <option value="lunes" {{ $horario->dia_semana == 'lunes' ? 'selected' : '' }}>Lunes</option>
                                    <option value="martes" {{ $horario->dia_semana == 'martes' ? 'selected' : '' }}>Martes</option>
                                    <option value="miércoles" {{ $horario->dia_semana == 'miércoles' ? 'selected' : '' }}>Miércoles</option>
                                    <option value="jueves" {{ $horario->dia_semana == 'jueves' ? 'selected' : '' }}>Jueves</option>
                                    <option value="viernes" {{ $horario->dia_semana == 'viernes' ? 'selected' : '' }}>Viernes</option>
                                    <option value="sábado" {{ $horario->dia_semana == 'sábado' ? 'selected' : '' }}>Sábado</option>
                                    <option value="domingo" {{ $horario->dia_semana == 'domingo' ? 'selected' : '' }}>Domingo</option>
                                </select>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Hora de inicio</label>
                                        <input type="time" class="form-control" name="hora_inicio" value="{{ $horario->hora_inicio }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Hora de fin</label>
                                        <input type="time" class="form-control" name="hora_fin" value="{{ $horario->hora_fin }}" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Lugar</label>
                                <input type="text" class="form-control" name="lugar" value="{{ $horario->lugar }}" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-link" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M5 12l5 5l10 -10" />
                                </svg>
                                Actualizar Horario
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
@endsection
