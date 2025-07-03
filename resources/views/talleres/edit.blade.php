@extends('layout')

@section('title', 'Editar Taller')

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">Editar Taller</h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('talleres.index') }}" class="btn btn-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z"/>
                                <path d="M5 12h14" />
                                <path d="M5 12l6 -6" />
                                <path d="M5 12l6 6" />
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
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <form action="{{ route('talleres.update', $taller) }}" method="POST">
                            @csrf
                            @method('PUT')

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
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="nombre" class="form-label">Nombre del taller</label>
                                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $taller->nombre) }}" required autofocus>
                                            @error('nombre')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="comuna_id" class="form-label">Comuna</label>
                                            <select class="form-select @error('comuna_id') is-invalid @enderror" id="comuna_id" name="comuna_id" required>
                                                <option value="">Seleccione una comuna</option>
                                                @foreach($comunas as $comuna)
                                                    <option value="{{ $comuna->id }}" {{ old('comuna_id', $taller->comuna_id) == $comuna->id ? 'selected' : '' }}>
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
                                    <label for="descripcion" class="form-label">Descripción</label>
                                    <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion" rows="3">{{ old('descripcion', $taller->descripcion) }}</textarea>
                                    @error('descripcion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="cultor_id" class="form-label">Cultor asignado</label>
                                    <select class="form-select @error('cultor_id') is-invalid @enderror" id="cultor_id" name="cultor_id">
                                        <option value="">Sin cultor asignado</option>
                                        @foreach($cultores as $cultor)
                                            <option value="{{ $cultor->id }}" {{ old('cultor_id', $taller->cultor_id) == $cultor->id ? 'selected' : '' }}>
                                                {{ $cultor->nombres }} {{ $cultor->apellidos }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('cultor_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <a href="{{ route('talleres.index') }}" class="btn btn-link">Cancelar</a>
                                <button type="submit" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z"/>
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