@extends('layout')

@section('content')

    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">Comunas</h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="#" class="btn btn-primary d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#modal-create">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                            Nueva Comuna
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
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

                            <div class="row">
                                @forelse ($comunas as $comuna)
                                    <div class="col-md-6 col-lg-3 mb-4">
                                        <div class="card position-relative h-100 shadow-sm border rounded" style="overflow: hidden;">
                                            
                                            <div class="card-body" style="position: relative; z-index: 1;">
                                                <h3 class="card-title">{{ $comuna->nombre }}</h3>
                                                <p class="text-muted mb-0">
                                                    <small>Creado el {{ $comuna->created_at->format('d/m/Y') }}</small>
                                                </p>
                                            </div>

                                            <div class="card-footer bg-transparent border-0 d-flex justify-content-between" style="position: relative; z-index: 1;">
                                                <a href="{{ route('comunas.talleres', $comuna->id) }}" class="btn btn-default" title="Ver talleres">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-list" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z"/>
                                                        <path d="M9 6h11" />
                                                        <path d="M9 12h11" />
                                                        <path d="M9 18h11" />
                                                        <path d="M5 6v.01" />
                                                        <path d="M5 12v.01" />
                                                        <path d="M5 18v.01" />
                                                    </svg>
                                                </a>

                                                <a href="{{ route('comunas.edit', $comuna) }}" class="btn btn-default" title="Editar">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                        <path stroke="none" d="M0 0h24v24H0z"/>
                                                        <path d="M16 3l4 4l-11 11h-4v-4z" />
                                                    </svg>
                                                </a>
                                                <form action="{{ route('comunas.destroy', $comuna) }}" method="POST" onsubmit="return confirm('¿Eliminar esta comuna?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-default" title="Eliminar">
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
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="alert alert-warning">No hay comunas registradas.</div>
                                    </div>
                                @endforelse
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal modal-blur fade" id="modal-create" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('comunas.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title">Nueva Comuna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Nombre de la comuna</label>
                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" placeholder="Ej: Comuna 1" value="{{ old('nombre') }}" required>
                    @error('nombre')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                </div>
                <div class="modal-footer text-end">
                <button type="button" class="btn btn-link " data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary ">
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
    
    <script>
        $(document).ready(function () {
            
            $('#modal-create').on('shown.bs.modal', function () {
                $('#nombre').trigger('focus');
            });

            @if ($errors->any())
                $('#modal-create').modal('show');
            @endif

        });
    </script>

@endsection