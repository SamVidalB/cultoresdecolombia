@extends('layout')

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('talleres.index') }}">Talleres</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Participantes Inscritos</li>
                        </ol>
                    </nav>
                    <h2 class="page-title">
                        Participantes Inscritos en: {{ $taller->nombre }}
                    </h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('talleres.index') }}" class="btn btn-outline-primary d-sm-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg>
                            Volver a Talleres
                        </a>
                        <button type="button" class="btn btn-success d-sm-inline-block" id="btn-export-excel" disabled>
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-spreadsheet" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M8 11h8v7h-8z" /><path d="M8 15h8" /><path d="M11 11v7" /></svg>
                            Descargar Excel
                        </button>
                        <button type="button" class="btn btn-danger d-sm-inline-block" id="btn-export-pdf" disabled>
                           <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-type-pdf" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M5 12v-7a2 2 0 0 1 2 -2h7l5 5v4" /><path d="M5 18h1.5a1.5 1.5 0 0 0 0 -3h-1.5v6" /><path d="M17 18h2" /><path d="M20 15h-3v6" /><path d="M11 15v6h1a2 2 0 0 0 2 -2v-2a2 2 0 0 0 -2 -2h-1z" /></svg>
                            Descargar PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-body">
                    @if($taller->participantes->isEmpty())
                        <div class="alert alert-info" role="alert">
                            No hay participantes inscritos en este taller todavía.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tipo Doc.</th>
                                        <th>Documento</th>
                                        <th>Nombre Completo</th>
                                        <th>Fec. Nacimiento</th>
                                        <th>Dirección</th>
                                        <th>Teléfono</th>
                                        <th>Email</th>
                                        <th>Barrio</th>
                                        <th>Comuna</th>
                                        <th>Fec. Inscripción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($taller->participantes as $participante)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $participante->tipo_documento }}</td>
                                            <td>{{ $participante->documento }}</td>
                                            <td>{{ $participante->nombre }}</td>
                                            <td>{{ \Carbon\Carbon::parse($participante->fecha_nacimiento)->format('d/m/Y') }}</td>
                                            <td>{{ $participante->direccion }}</td>
                                            <td>{{ $participante->telefono }}</td>
                                            <td>{{ $participante->email }}</td>
                                            <td>{{ $participante->barrio }}</td>
                                            <td>{{ $participante->comuna->nombre ?? 'N/A' }}</td>
                                            <td>{{ $participante->pivot->fecha_inscripcion ? \Carbon\Carbon::parse($participante->pivot->fecha_inscripcion)->format('d/m/Y H:i') : 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Los botones de exportación estarán habilitados cuando se implemente la funcionalidad
        // Por ahora, los dejamos deshabilitados como se definió en el HTML.
        // const btnExcel = document.getElementById('btn-export-excel');
        // const btnPdf = document.getElementById('btn-export-pdf');

        // if (btnExcel) {
        //     btnExcel.addEventListener('click', function() {
        //         window.location.href = "{{-- route('talleres.participantes.export.excel', $taller->id) --}}";
        //     });
        // }

        // if (btnPdf) {
        //     btnPdf.addEventListener('click', function() {
        //         window.location.href = "{{-- route('talleres.participantes.export.pdf', $taller->id) --}}";
        //     });
        // }
    });
</script>
@endsection
