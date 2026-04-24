<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
        .salto {
            page-break-after: always;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .cover {
            width: 100%;
            height: 100%;
        }

        /* Layout con tabla en lugar de flexbox */
        .cover-table {
            width: 100%;
        }

        .col-izquierda {
            width: 60%;
            vertical-align: top;
            padding-right: 1cm;
            text-align: right;
        }

        .col-derecha {
            width: 40%;
            vertical-align: top;
        }

        .cover-table img {
            width: 10cm;
            border-radius: 0.5cm;
        }

        h1 {
            font-size: 22px;
            margin-top: 1cm;
        }

        h3,
        h4 {
            margin: 0.3cm 0;
        }

        .col-derecha p {
            font-size: 20px;
        }

        .preguntasVulnerabilidad {
            width: 100%;
        }
    </style>
</head>

<body>

    <div class="cover">
        <table class="cover-table">
            <tr>
                <td class="col-izquierda">
                    <h3>Cartilla Guía Metodología Presencial</h3>
                    <img src="{{ public_path('assets/images/plan-familiar-ilustración.jpg') }}" alt="plan_familiar_img">
                    <h1>PLAN FAMILIAR DE EMERGENCIA</h1>
                    <h4>Defensa Civil Colombiana</h4>
                </td>

                <td class="col-derecha">
                    <div class="datos_iniciales">

                        <div class="familia">
                            <h2>Familia</h2>
                            <p>{{ $familyPlan->last_names }}</p>
                        </div>

                        <div class="fecha">
                            <h2>Fecha</h2>
                            {{-- <p>{{ $familyPlan->created_at }}</p> --}}

                            <p>
                                {{ \Carbon\Carbon::parse($familyPlan->create_at)->format('d/m/Y') }}
                            </p>
                        </div>

                        <div class="ciudad">
                            <h2>Ciudad</h2>
                            <p>{{ $familyPlan->city->name }}</p>
                        </div>
                    </div>

                    <div class="datos_autor">
                        <h2>
                            Autor
                        </h2>

                        <p>
                            Grupo de Conocimiento y Reducción del Riesgo
                        </p>

                        <p>
                            Quinta Edición <br>
                            Febrero - 2025
                        </p>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="salto"></div>

    <table style="width:100%; margin-bottom: 10px;">
        <tr>
            <td style="text-align:center; font-size:12px;">
                <strong>Formato Anexo N° 01 - <em>"TEST DE VULNERABILIDAD FAMILIAR"</em></strong>
            </td>
        </tr>
    </table>

    <table class="preguntasVulnerabilidad">
        <thead>
            <tr style="background-color:#1a5276; color:#ffffff;">
                <td style="width:8%; text-align:center; padding:6px; border:1px solid #ccc;">
                    <strong>N°</strong>
                </td>
                <td style="width:72%; text-align:center; padding:6px; border:1px solid #ccc;">
                    <strong>RESPONDA SÍ O NO SEGÚN SU APRECIACIÓN</strong>
                </td>
                <td style="width:10%; text-align:center; padding:6px; border:1px solid #ccc;">
                    <strong>SÍ</strong>
                </td>
                <td style="width:10%; text-align:center; padding:6px; border:1px solid #ccc;">
                    <strong>NO</strong>
                </td>
            </tr>
        </thead> {{-- ← cierra thead ANTES del tbody --}}

        <tbody>
            @php $i = 1; @endphp
            {{-- @foreach ($familyPlan->vulnerableTest as $index => $test) --}}
            @foreach ($familyPlan->vulnerableTest as $test)

                <tr>

                    <td style="text-align:center; padding:6px; border:1px solid #ccc;">
                        {{ str_pad($i++, 2, '0', STR_PAD_LEFT) }}
                    </td>

                    <td style="padding:6px; border:1px solid #ccc;">
                        {{ $test->vulnerableQuestion->description }}
                    </td>

                    <td style="text-align:center; padding:6px; border:1px solid #ccc;">
                        {{ $test->answer ? 'X' : '' }}
                    </td>

                    <td style="text-align:center; padding:6px; border:1px solid #ccc;">
                        {{ !$test->answer ? 'X' : '' }}
                    </td>

                </tr>

            @endforeach
        </tbody>

    </table>

    <table style="width:100%; margin-bottom: 10px;">
        <tr>
            <td style="text-align:center; font-size:12px;">
                <p>RESULTADO: Si existen al menos cinco (05) respuestas son afirmativas (SÍ), entre las preguntas 1 a
                    12, el hogar se considerará como vulnerable. "TEST DE VULNERABILIDAD FAMILIAR"</p>
            </td>
        </tr>
    </table>

    <div class="salto"></div>

    {{-- Encabezado --}}
    <table style="width:100%; margin-bottom:10px;">
        <tr>
            <td style="text-align:center; font-size:12px;">
                <strong>Formato Anexo N° 02 - <em>"IDENTIFICACIÓN DE LA FAMILIA"</em></strong>
            </td>
        </tr>
    </table>

    {{-- Tabla principal --}}
    <table style="width:100%; border-collapse:collapse; font-size:11px;">

        {{-- Título --}}
        <tr style="background-color:#1a5276; color:#ffffff;">
            <td colspan="2" style="text-align:center; padding:8px; border:1px solid #ccc;">
                <strong>ANEXO N° 02 IDENTIFICACIÓN DE LA FAMILIA</strong>
            </td>
        </tr>

        {{-- Fila oscura --}}
        <tr style="background-color:#1a5276; color:#ffffff;">
            <td style="width:40%; padding:8px; border:1px solid #ccc;">
                <strong>SECCIONAL</strong>
            </td>
            <td style="width:60%; padding:8px; border:1px solid #ccc;">
                <p>{{ $familyPlan->sectional->name }}</p>
            </td>
        </tr>

        {{-- Fila clara --}}
        <tr style="background-color:#aed6f1;">
            <td style="padding:8px; border:1px solid #ccc;">
                <strong>Familia Segura N°</strong>
            </td>
            <td style="padding:8px; border:1px solid #ccc;">
                <p>{{ $familyPlan->id }}</p>
            </td>
        </tr>

        <tr>
            <td style="padding:8px; border:1px solid #ccc;">
                <strong>NOMBRE DE LA FAMILIA</strong><br>
                <small>(Apellidos)</small>
            </td>
            <td style="padding:8px; border:1px solid #ccc;">
                <p>{{ $familyPlan->last_names }}</p>
            </td>
        </tr>

        <tr>
            <td style="padding:8px; border:1px solid #ccc;">
                <strong>DIRECCIÓN</strong>
            </td>
            <td style="padding:8px; border:1px solid #ccc;">
                <p>{{ $familyPlan->address . ', ' . $familyPlan->sector->name . ' ' . $familyPlan->sector_name . ', ' . $familyPlan->city->name . ', ' . $familyPlan->city->department->name}}
                </p>
            </td>
        </tr>

        <tr>
            <td style="padding:8px; border:1px solid #ccc;">
                <strong>BARRIO - COMUNA - LOCALIDAD</strong>
            </td>
            <td style="padding:8px; border:1px solid #ccc;">
                <p>{{ $familyPlan->sector->name }}</p>
            </td>
        </tr>

        <tr>
            <td style="padding:8px; border:1px solid #ccc;">
                <strong>TELÉFONO FIJO</strong>
            </td>
            <td style="padding:8px; border:1px solid #ccc;">
                <p>{{ $familyPlan->landline_phone }}</p>
            </td>
        </tr>

        <tr>
            <td style="padding:8px; border:1px solid #ccc;">
                <strong>CALIDAD DE LA VIVIENDA</strong><br>
                <small>(Arriendo – Propietario)</small>
            </td>
            <td style="padding:8px; border:1px solid #ccc;">
                <p>{{ $familyPlan->housingQuality->name }}</p>
            </td>
        </tr>

    </table>

    {{-- Georreferenciación --}}
    <p style="font-size:11px; margin-top:10px;">
        <strong>GEORREFERENCIACIÓN:</strong><br>
        Se debe identificar la ubicación de la vivienda, con dirección, barrio, vereda, finca, municipio
        y en lo posible tomar coordenadas, sexagesimales en grados minutos y segundos
        <em>(N 4°15'25,23" W 74°45'10,05")</em>, captura de <em>Google Earth</em>,
        Cartografía Social, Plano, bosquejo etc.
    </p>

    <div class="salto"></div>

    <table style="width:100%; margin-bottom:10px;">
        <tr>
            <td style="text-align:center; font-size:12px;">
                <strong>Formato Anexo N° 04 - <em>"MASCOTAS O ANIMALES DE COMPAÑÍA"</em></strong>
            </td>
        </tr>
    </table>

    {{-- Tabla --}}
    <table style="width:100%; border-collapse:collapse; font-size:11px;">

        {{-- Título --}}
        <tr style="background-color:#1a5276; color:#ffffff;">
            <td colspan="6" style="text-align:center; padding:8px; border:1px solid #ccc;">
                <p style="margin:0;">ANEXO N° 04 MASCOTAS O ANIMALES DE COMPAÑÍA</p>
            </td>
        </tr>

        {{-- Encabezados de columna --}}
        <tr style="background-color:#aed6f1;">
            <td style="padding:8px; border:1px solid #ccc;">
                <p style="margin:0;">ESPECIE</p>
            </td>
            <td style="padding:8px; border:1px solid #ccc;">
                <p style="margin:0;">NOMBRE</p>
            </td>
            <td style="padding:8px; border:1px solid #ccc;">
                <p style="margin:0;">RAZA</p>
            </td>
            <td style="padding:8px; border:1px solid #ccc;">
                <p style="margin:0;">GENERO</p>
            </td>
            <td style="padding:8px; border:1px solid #ccc;">
                <p style="margin:0;">EDAD</p>
            </td>
            <td style="padding:8px; border:1px solid #ccc;">
                <p style="margin:0;">VACUNAS</p>
            </td>
        </tr>

        {{-- Filas dinámicas --}}
        @foreach($familyPlan->pets as $pet)
            <tr>
                <td style="padding:8px; border:1px solid #ccc;">
                    <p style="margin:0;">{{ $pet->species->name }}</p>
                </td>
                <td style="padding:8px; border:1px solid #ccc;">
                    <p style="margin:0;">{{ $pet->name }}</p>
                </td>
                <td style="padding:8px; border:1px solid #ccc;">
                    <p style="margin:0;">{{ $pet->breed }}</p>
                </td>
                <td style="padding:8px; border:1px solid #ccc;">
                    <p style="margin:0;">{{ $pet->animalGender->name }}</p>
                </td>
                <td style="padding:8px; border:1px solid #ccc;">
                    <p style="margin:0;">{{ $pet->age }}</p>
                </td>
                <td style="padding:8px; border:1px solid #ccc;">
                    <p style="margin:0;">{{ $pet->petVaccine->pluck('name')->join(', ') }}</p>
                </td>
            </tr>
        @endforeach

    </table>

    <div class="salto"></div>

    {{-- Encabezado --}}
    <table style="width:100%; margin-bottom:10px;">
        <tr>
            <td style="text-align:center; font-size:12px;">
                <strong>Formato Anexo N° 03 - <em>"INTEGRANTES DE LA FAMILIA"</em></strong>
            </td>
        </tr>
    </table>

    {{-- Tabla --}}
    <table style="width:100%; border-collapse:collapse; font-size:10px;">

        {{-- Título --}}
        <tr style="background-color:#1a5276; color:#ffffff;">
            <td colspan="9" style="text-align:center; padding:8px; border:1px solid #ccc;">
                <p style="margin:0;">ANEXO N° 03 INTEGRANTES DE LA FAMILIA</p>
            </td>
        </tr>

        {{-- Encabezados de columna --}}
        <tr style="background-color:#1a5276; color:#ffffff;">
            <td style="padding:6px; border:1px solid #ccc;">
                <p style="margin:0;">APELLIDOS Y NOMBRES</p>
            </td>
            <td style="padding:6px; border:1px solid #ccc;">
                <p style="margin:0;">DOC. IDENTIDAD</p>
            </td>
            <td style="padding:6px; border:1px solid #ccc;">
                <p style="margin:0;">EDAD</p>
            </td>
            <td style="padding:6px; border:1px solid #ccc;">
                <p style="margin:0;">GRUPO SANGUÍNEO Y RH</p>
            </td>
            <td style="padding:6px; border:1px solid #ccc;">
                <p style="margin:0;">PARENTESCO</p>
            </td>
            <td style="padding:6px; border:1px solid #ccc;">
                <p style="margin:0;">EPS</p>
            </td>
            <td style="padding:6px; border:1px solid #ccc;">
                <p style="margin:0;">ENFERMEDAD DISCAPACIDA ALERGIAS</p>
            </td>
            <td style="padding:6px; border:1px solid #ccc;">
                <p style="margin:0;">MEDICINAS / DOSIS</p>
            </td>
            <td style="padding:6px; border:1px solid #ccc;">
                <p style="margin:0;">CELULAR</p>
            </td>
        </tr>

        {{-- Filas dinámicas --}}
        @foreach($familyPlan->familyMembers as $member)
            <tr>
                <td style="padding:6px; border:1px solid #ccc;">
                    <p style="margin:0;"> {{ $member->member->last_names . ' ' . $member->member->names}} </p>
                </td>
                <td style="padding:6px; border:1px solid #ccc;">
                    <p style="margin:0;"> {{ $member->member->documentType->acronym . ', ' .  }} </p>
                </td>
                <td style="padding:6px; border:1px solid #ccc;">
                    <p style="margin:0;">{{-- dato --}}</p>
                </td>
                <td style="padding:6px; border:1px solid #ccc;">
                    <p style="margin:0;">{{-- dato --}}</p>
                </td>
                <td style="padding:6px; border:1px solid #ccc;">
                    <p style="margin:0;">{{-- dato --}}</p>
                </td>
                <td style="padding:6px; border:1px solid #ccc;">
                    <p style="margin:0;">{{-- dato --}}</p>
                </td>
                <td style="padding:6px; border:1px solid #ccc;">
                    <p style="margin:0;">{{-- dato --}}</p>
                </td>
                <td style="padding:6px; border:1px solid #ccc;">
                    <p style="margin:0;">{{-- dato --}}</p>
                </td>
                <td style="padding:6px; border:1px solid #ccc;">
                    <p style="margin:0;">{{-- dato --}}</p>
                </td>
            </tr>
        @endforeach

    </table>
</body>

</html>