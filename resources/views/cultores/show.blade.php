@extends('layout')

@section('styles')
    <style>
        .schedule-calendar .bg-blue-lt {
            background-color: #e1f0ff;
            border-left: 3px solid #467fcf;
        }
        .avatar-xl {
            width: 6rem;
            height: 6rem;
        }
    </style>
@endsection

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">Detalle del Cultor</h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('cultores.index') }}" class="btn btn-secondary">
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
                <div class="col-md-4">
                    <!-- Información básica del cultor -->
                    <div class="card mb-4">
                        <div class="card-body text-center">
                            @if($cultor->foto)
                                <img src="{{ asset('storage/' . $cultor->foto) }}" class="avatar avatar-xl mb-3 rounded" alt="Foto del cultor">
                            @else
                                <div class="avatar avatar-xl mb-3 rounded bg-blue-lt">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                    </svg>
                                </div>
                            @endif
                            
                            <h3 class="mb-1">{{ $cultor->nombres }} {{ $cultor->apellidos }}</h3>
                            <div class="text-muted">{{ $cultor->tipo_documento }}: {{ $cultor->documento }}</div>
                            
                            <div class="mt-3">
                                <span class="badge bg-blue-lt">{{ $cultor->talleres->count() }} Taller(es)</span>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-6">
                                    <div class="text-muted">Email</div>
                                    <div class="text-truncate">{{ $cultor->email }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-muted">Teléfono</div>
                                    <div>{{ $cultor->telefono }}</div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <div class="text-muted">Comuna</div>
                                <div>{{ $cultor->comuna->nombre ?? 'Sin comuna asignada' }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Información bancaria -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Información Bancaria</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="text-muted">RUT</div>
                                <div>{{ $cultor->rut ?? 'No especificado' }}</div>
                            </div>
                            <div class="mb-3">
                                <div class="text-muted">Banco</div>
                                <div>{{ $cultor->banco ?? 'No especificado' }}</div>
                            </div>
                            <div class="mb-3">
                                <div class="text-muted">Tipo de Cuenta</div>
                                <div>{{ $cultor->tipo_cuenta ?? 'No especificado' }}</div>
                            </div>
                            <div>
                                <div class="text-muted">Número de Cuenta</div>
                                <div>{{ $cultor->numero_cuenta ?? 'No especificado' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-8">
                    <!-- Talleres asignados -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3 class="card-title">Talleres Asignados</h3>
                        </div>
                        <div class="card-body">
                            @if($cultor->talleres->isEmpty())
                                <div class="alert alert-info">Este cultor no tiene talleres asignados.</div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Taller</th>
                                                <th>Comuna</th>
                                                <th>Horarios</th>
                                                <th>Participantes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($cultor->talleres as $taller)
                                                <tr>
                                                    <td>{{ $taller->nombre }}</td>
                                                    <td>{{ $taller->comuna->nombre }}</td>
                                                    <td>
                                                        @if($taller->horarios->isEmpty())
                                                            <span class="text-muted">Sin horarios</span>
                                                        @else
                                                            <ul class="list-unstyled">
                                                                @foreach($taller->horarios as $horario)
                                                                    <li>
                                                                        {{ ucfirst($horario->dia_semana) }}: 
                                                                        {{ date('g:i A', strtotime($horario->hora_inicio)) }} - 
                                                                        {{ date('g:i A', strtotime($horario->hora_fin)) }}
                                                                        <small class="text-muted">({{ $horario->lugar }})</small>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </td>
                                                    <td>{{ $taller->participantes->count() }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Calendario de horarios -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Horarios Semanales</h3>
                        </div>
                        <div class="card-body">
                            @if($cultor->talleres->isEmpty())
                                <div class="alert alert-info">No hay horarios para mostrar.</div>
                            @else
                                <div class="schedule-calendar">
                                    @php
                                        $dias = ['lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado', 'domingo'];
                                    @endphp
                                    
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="width: 14.28%">Lunes</th>
                                                    <th style="width: 14.28%">Martes</th>
                                                    <th style="width: 14.28%">Miércoles</th>
                                                    <th style="width: 14.28%">Jueves</th>
                                                    <th style="width: 14.28%">Viernes</th>
                                                    <th style="width: 14.28%">Sábado</th>
                                                    <th style="width: 14.28%">Domingo</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    @foreach($dias as $dia)
                                                        <td>
                                                            @php
                                                                $horariosDia = $cultor->talleres->flatMap(function($taller) use ($dia) {
                                                                    return $taller->horarios->where('dia_semana', $dia);
                                                                });
                                                            @endphp
                                                            
                                                            @if($horariosDia->isEmpty())
                                                                <div class="text-center text-muted py-3">Sin actividades</div>
                                                            @else
                                                                @foreach($horariosDia as $horario)
                                                                    <div class="mb-2 p-2 bg-blue-lt rounded">
                                                                        <strong>{{ $horario->taller->nombre }}</strong><br>
                                                                        {{ date('g:i A', strtotime($horario->hora_inicio)) }} - {{ date('g:i A', strtotime($horario->hora_fin)) }}<br>
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
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
