<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Participantes - {{ $taller->nombre }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
        }
        .container {
            width: 100%;
            margin: 0 auto;
        }
        h1 {
            text-align: center;
            font-size: 16px;
            margin-bottom: 5px;
        }
        h2 {
            text-align: center;
            font-size: 14px;
            margin-top: 0;
            margin-bottom: 15px;
            font-weight: normal;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            text-align: center;
            font-size: 9px;
            color: #777;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
        @page {
            margin: 20mm 15mm; /* top, right, bottom, left */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Listado de Participantes Inscritos</h1>
        <h2>Taller: {{ $taller->nombre }}</h2>

        @if($taller->participantes->isEmpty())
            <p class="text-center">No hay participantes inscritos en este taller todavía.</p>
        @else
            <table>
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
        @endif
    </div>

    <div class="footer">
        Generado el {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }} - Sistema de Gestión de Cultores
    </div>
</body>
</html>
