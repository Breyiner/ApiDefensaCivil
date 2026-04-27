<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
        * {
            box-sizing: border-box;
        }

        @page {
            margin-left: 12px;
            margin-right: 12px;
            margin-bottom: 5px;
            margin-top: 120px;
        }

        .salto {
            page-break-after: always;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .titulo {
            width: 100%;
            text-align: center
        }

        .nota {
            margin-top: 20px;
            width: 90%;

            margin: 20px auto;
            /* text-align: center; */
        }

        .encabezado {
            height: 85px;
            width: 100%;
            position: fixed;
            top: -120px;
            padding-top: 15px;
            background-color: rgb(0, 111, 192);
            border-radius: 0 0 30px 30px;
        }

        .encabezado img {
            height: 70px;
            display: inline-block;
            float: left;
        }

        .encabezado p {
            float: left;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-top: 15px;
            width: auto;
            text-align: center;
        }

        .cover {
            width: 100%;
            padding-top: 100px;
        }

        .cover .izq {
            width: 55%;
            vertical-align: middle;
            text-align: center;
            padding-right: 15px;
            text-align: right;
        }

        .cover .der {
            width: 43%;
            vertical-align: middle;
            padding-left: 25px;
        }

        .cover .mid {
            width: 2px;
            background-color: rgb(254, 101, 0);
            border-radius: 100px;
        }

        /* Layout con tabla en lugar de flexbox */
        h1 {
            font-size: 30px;
            margin-top: 1cm;
        }

        .preguntasVulnerabilidad,
        .tablaFamilia,
        .tablaMascotas,
        .tablaIntegrante,
        .tablaVulnerabilidades,
        .tablaRecursos,
        .tablaAccion {

            width: 100%;
            border-collapse: collapse;
            height: fit-content;
            padding-top: 20px;
            font-size: 12px;
        }

        .preguntasVulnerabilidad {
            font-size: 15px;
        }

        .columnas {
            background-color: rgb(0, 111, 192);
            color: #ffffff;
        }

        .graficoCont {
            width: 100%;
            height: 900px;
            background-image: url('{{ public_path('assets/images/cuadricula.jpg') }}');
            background-repeat: no-repeat;
            background-position: center;
            text-align: center;
            background-size: cover;
            margin-top: 10px;
            /* border: #1a5276 solid 3px; */
        }

        .graficoCont img {
            height: 100%;
            max-height: 700px;
            max-width: 90%;
            object-fit: contain;
            display: inline-block;
            margin-top: 100px;
        }
    </style>
</head>

<body>

    <header class="encabezado">

        <img src="{{ public_path('assets/images/logos/defensa-civil-logo.png') }}" alt="">

        <p> PLAN FAMILIAR DE EMERGENCIA </p>

    </header>

    <div class="cover">

        <table>

            <tr>
                {{-- COLUMNA IZQUIERDA --}}
                <td class="izq">

                    <h3>Cartilla Guía Metodología Presencial</h3>

                    <img src="{{ public_path('assets/images/plan-familiar-ilustración.jpg') }}" alt="plan_familiar_img"
                        style="width:400px; border-radius: 10px;">

                    <h1>PLAN FAMILIAR <br> DE EMERGENCIA</h1>

                    <h4 style="font-size:20px; font-weight:normal;">Defensa Civil Colombiana</h4>
                </td>

                {{-- DIVISOR NARANJA --}}
                <td class="mid"></td>

                {{-- COLUMNA DERECHA --}}
                <td class="der">

                    <div class="datos_iniciales">

                        <h2>Familia</h2>
                        <p>{{ $familyPlan->last_names }}</p>

                        <h2>Fecha</h2>
                        <p>{{ \Carbon\Carbon::parse($familyPlan->created_at)->format('d/m/Y') }}</p>

                        <h2>Ciudad</h2>
                        <p>{{ $familyPlan->city->name }}</p>

                    </div>

                    <div class="datos_autor" style="margin-top:30px;">

                        <h2>Autor</h2>
                        <p>Grupo de Conocimiento y Reducción del Riesgo</p>
                        <p>Quinta Edición <br> Febrero - 2025</p>

                    </div>

                </td>

            </tr>

        </table>

    </div>

    <div class="salto"></div>

    <div class="titulo">
        <strong>Formato Anexo N° 01 - <em>"TEST DE VULNERABILIDAD FAMILIAR"</em></strong>
    </div>

    <table class="preguntasVulnerabilidad">

        <thead>

            <tr class="columnas">
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

        </thead>

        <tbody>

            @php $i = 1; @endphp

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

    <p class="nota">RESULTADO: Si existen al menos cinco (05) respuestas son afirmativas (SÍ), entre las preguntas 1 a
        12, el hogar se considerará como vulnerable. "TEST DE VULNERABILIDAD FAMILIAR"</p>


    <div class="salto"></div>

    {{-- IDENTIFICACIÓN DE LA FAMILIA
    ------------------------------------------------------------------------------------------ --}}

    <div class="titulo" style="width:100%; margin-bottom:10px;">
        <strong>Formato Anexo N° 02 - <em>"IDENTIFICACIÓN DE LA FAMILIA"</em></strong>
    </div>


    <table class="tablaFamilia">

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


    <p class="nota">
        <strong>GEORREFERENCIACIÓN:</strong><br>
        Se debe identificar la ubicación de la vivienda, con dirección, barrio, vereda, finca, municipio
        y en lo posible tomar coordenadas, sexagesimales en grados minutos y segundos
        <em>(N 4°15'25,23" W 74°45'10,05")</em>, captura de <em>Google Earth</em>,
        Cartografía Social, Plano, bosquejo etc.
    </p>

    <div class="salto"></div>


    {{-- TABLA MASCOTAS --------------------------------------------------------------------------------------------
    --}}

    <div class="titulo">
        <strong>Formato Anexo N° 04 - <em>"MASCOTAS O ANIMALES DE COMPAÑÍA"</em></strong>
    </div>

    <table class="tablaMascotas">

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

    {{-- TABLA INTEGRANTES --------------------------------------------------------------------------------------------
    --}}
    <div class="titulo">
        <strong>Formato Anexo N° 03 - <em>"INTEGRANTES DE LA FAMILIA"</em></strong>
    </div>

    <table class="tablaIntegrante">

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
                    <p style="margin:0;">
                        {{ $member->member->documentType->acronym . ' ' . $member->member->document_number }}
                    </p>
                </td>
                <td style="padding:6px; border:1px solid #ccc;">
                    <p style="margin:0;"> {{ $member->member->age }} </p>
                </td>
                <td style="padding:6px; border:1px solid #ccc;">
                    <p style="margin:0;"> {{ $member->member->bloodGroup->name }} </p>
                </td>
                <td style="padding:6px; border:1px solid #ccc;">
                    <p style="margin:0;"> {{ $member->member->kinship->name }} </p>
                </td>
                <td style="padding:6px; border:1px solid #ccc;">
                    <p style="margin:0;"> {{ $member->member->eps }} </p>
                </td>
                <td style="padding:6px; border:1px solid #ccc;">
                    <p style="margin:0;"> {{ $member->member->conditionMember->pluck('name')->join('<br>')}} </p>
                </td>
                <td style="padding:6px; border:1px solid #ccc;">
                    <p style="margin:0;"> {{ $member->member->conditionMember->pluck('dose')->join('<br>') }} </p>
                </td>
                <td style="padding:6px; border:1px solid #ccc;">
                    <p style="margin:0;"> {{ $member->member->phone }} </p>
                </td>
            </tr>
        @endforeach

    </table>

    <div class="salto"></div>


    {{-- TABLA VULNERABILIDADES
    -------------------------------------------------------------------------------------------- --}}

    <div class="titulo">
        <strong>Formato Anexo N° 05 - <em>"FACTORES DE RIESGO (AMENAZAS – VULNERABILIDADES)"</em></strong>
    </div>

    <table class="tablaVulnerabilidades">

        <tr style="background-color:#1a5276; color:#ffffff;">
            <td colspan="7" style="text-align:center; padding:8px; border:1px solid #ccc;">
                <p style="margin:0;">ANEXO N° 05 FACTORES DE RIESGO (AMENAZAS –VULNERABILIDADES)</p>
            </td>
        </tr>

        <tr style="background-color:#1a5276; color:#ffffff;">
            <td style="padding:6px; border:1px solid #ccc;">
                <p style="margin:0;">DESCRIPCIÓN</p>
            </td>
            <td style="padding:6px; border:1px solid #ccc;">
                <p style="margin:0;">UBICACIÓN</p>
            </td>
            <td style="padding:6px; border:1px solid #ccc;">
                <p style="margin:0;">TIPO DE AMENAZA</p>
            </td>
            <td style="padding:6px; border:1px solid #ccc;">
                <p style="margin:0;">VULNERABILIDAD</p>
            </td>
            <td style="padding:6px; border:1px solid #ccc;">
                <p style="margin:0;">ACCIONES DE REDUCCIÓN FAMILIARES</p>
            </td>
            <td style="padding:6px; border:1px solid #ccc;">
                <p style="margin:0;">RESPONSABLE</p>
            </td>
            <td style="padding:6px; border:1px solid #ccc;">
                <p style="margin:0;">TÉRMINO</p>
            </td>
        </tr>

        @foreach($familyPlan->riskFactors as $risk)
            <tr>
                <td style="padding:6px; border:1px solid #ccc;">
                    <p style="margin:0;">{{ $risk->description }}</p>
                </td>
                <td style="padding:6px; border:1px solid #ccc;">
                    <p style="margin:0;">{{ $risk->ubication }}</p>
                </td>
                <td style="padding:6px; border:1px solid #ccc;">
                    <p style="margin:0;">{{ $risk->threatType->name }}</p>
                </td>
                <td style="padding:6px; border:1px solid #ccc;">
                    <p style="margin:0;">{{ $risk->description }}</p>
                </td>
                <td style="padding:6px; border:1px solid #ccc;">
                    <p style="margin:0;">{{ $risk->riskReductionActions->pluck('action')->join(', ') }}</p>
                </td>
                <td style="padding:6px; border:1px solid #ccc;">
                    <p style="margin:0;">{{ $risk->riskReductionActions->pluck('member.names')->join(', ') }}</p>
                </td>
                <td style="padding:6px; border:1px solid #ccc;">
                    <p style="margin:0;">{{ $risk->riskReductionActions->pluck('end_date')->join(', ') }}</p>
                </td>
            </tr>
        @endforeach

    </table>

    <div class="salto"></div>


    {{-- TABLA RECURSOS DISPONIBLES
    --------------------------------------------------------------------------------------- --}}

    <div class="titulo">
        <strong>Formato Anexo N° 06 - <em>"RECURSOS DISPONIBLES"</em></strong>
    </div>

    <table class="tablaRecursos">

        <tr style="background-color:#1a5276; color:#ffffff;">
            <td colspan="6" style="text-align:center; padding:8px; border:1px solid #ccc;">
                <p style="margin:0;">ANEXO N° 06 RECURSOS DISPONIBLES</p>
            </td>
        </tr>

        <tr style="background-color:#1a5276; color:#ffffff;">
            <td style="padding:6px; border:1px solid #ccc;">
                <p style="margin:0;">RECURSO</p>
            </td>
            <td style="padding:6px; border:1px solid #ccc;">
                <p style="margin:0;">UBICACIÓN</p>
            </td>
            <td style="padding:6px; border:1px solid #ccc;">
                <p style="margin:0;">DISTANCIA</p>
            </td>
            <td style="padding:6px; border:1px solid #ccc;">
                <p style="margin:0;">SERVICIO</p>
            </td>
            <td style="padding:6px; border:1px solid #ccc;">
                <p style="margin:0;">DESCRIPCIÓN</p>
            </td>
            <td style="padding:6px; border:1px solid #ccc;">
                <p style="margin:0;">TELÉFONO</p>
            </td>
        </tr>

        @foreach($familyPlan->availableResources as $resource)
            <tr>
                <td style="padding:6px; border:1px solid #ccc;">
                    <p style="margin:0;">
                        {{ $resource->resource->name }}
                    </p>
                </td>
                <td style="padding:6px; border:1px solid #ccc;">
                    <p style="margin:0;">
                        {{ $resource->location }}
                    </p>
                </td>
                <td style="padding:6px; border:1px solid #ccc;">
                    <p style="margin:0;">
                        {{ $resource->distance . ' metros' }}
                    </p>
                </td>
                <td style="padding:6px; border:1px solid #ccc;">
                    <p style="margin:0;">
                        {{ $resource->resource->service }}
                    </p>
                </td>
                <td style="padding:6px; border:1px solid #ccc;">
                    <p style="margin:0;">
                        {{ $resource->description }}
                    </p>
                </td>
                <td style="padding:6px; border:1px solid #ccc;">
                    <p style="margin:0;">
                        {{ $resource->phone }}
                    </p>
                </td>
            </tr>
        @endforeach

    </table>

    <div class="salto"></div>


    {{-- GRAFICO VIVIENDA --------------------------------------------------------------------------------------- --}}

    <div class="titulo">
        <strong>Formato Anexo N° 07 - <em>"GRÁFICO DE LA VIVIENDA"</em></strong>
    </div>

    @foreach ($familyPlan->housingGraphic as $graphic)

        <div class="graficoCont">

            <img src="{{ storage_path('app/public/' . $graphic->path) }}">

            <p style="font-size:16px; margin-top:10px;">
                <strong>Descripción del Grafico:</strong> {{ $graphic->description }}
            </p>

        </div>

        <div class="salto"></div>

    @endforeach

    {{-- TABLA PLAN DE ACCIÓN FAMILIAR
    --------------------------------------------------------------------------------------- --}}

    <div class="titulo">
        <strong>Formato Anexo N° 08 - <em>"PLAN DE ACCIÓN FAMILIAR"</em></strong>
    </div>

    <table class="tablaAccion">

        <tr style="background-color:#1a5276; color:#ffffff;">
            <td colspan="3" style="text-align:center; padding:8px; border:1px solid #ccc;">
                <p style="margin:0;">ANEXO N° 08 PLAN DE ACCIÓN FAMILIAR</p>
            </td>
        </tr>

        <tr>
            <td style="padding:6px; border:1px solid #ccc; width:30%;"><strong>PLAN DE ACCION POR:</strong></td>
            <td colspan="2" style="padding:6px; border:1px solid #ccc;">
                @foreach($familyPlan->familyMembers as $familyMember)
                    @foreach ($familyMember->member->actionPlan as $plan)

                        <p style="margin:0;">{{$plan->member->names . ' ' . $plan->member->last_names}}</p>
                        <p>{{ $plan->actionPlanAction }}</p>

                    @endforeach
                @endforeach
            </td>
        </tr>

        <tr>
            <td style="padding:6px; border:1px solid #ccc;"><strong>COORDINADOR:</strong></td>
            <td colspan="2" style="padding:6px; border:1px solid #ccc;">
                <p style="margin:0;"></p>
            </td>
        </tr>

        <tr style="background-color:#1a5276; color:#ffffff;">
            <td colspan="2" style="padding:6px; border:1px solid #ccc;">
                <p style="margin:0;">ACCIONES A DESARROLLAR</p>
            </td>
            <td style="padding:6px; border:1px solid #ccc; width:30%;">
                <p style="margin:0;">RESPONSABLE</p>
            </td>
        </tr>

@php
    $acciones = collect();

    $acciones = collect();

    foreach ($familyPlan->familyMembers as $familyMember) {
        foreach ($familyMember->member->actionPlan as $plan) {
            // Aquí es donde recolectamos los objetos individuales
            $acciones->push($plan->actionPlanAction);
        }
    }

    // Ahora filtramos la colección que ya tiene los objetos
    $antes = $acciones->where('action_type_id', 1)->values();
    $durante = $acciones->where('action_type_id', 2)->values();
    $despues = $acciones->where('action_type_id', 3)->values();

@endphp

{{-- BLOQUE ANTES --}}
@if($antes->count() > 0)
    @foreach($antes as $index => $accion)
    <tr>
        @if($loop->first)
            <td rowspan="{{ $antes->count() }}" style="background-color:#1a5276; color:white; text-align:center;">
                ANTES
            </td>
        @endif
        <td style="border:1px solid #ccc; padding:5px;">
            {{ $index + 1 }}. {{ $accion->description }}
        </td>
        <td style="border:1px solid #ccc; padding:5px;">
            {{-- Ajusta esto según cómo se llame el nombre del miembro en tu modelo --}}
            {{ $accion->member->names ?? 'Sin asignar' }}
        </td>
    </tr>
    @endforeach
@else
    {{-- Esto asegura que se vea la fila aunque no haya datos --}}
    <tr>
        <td style="background-color:#1a5276; color:white; text-align:center;">ANTES</td>
        <td style="border:1px solid #ccc; height:30px;"></td>
        <td style="border:1px solid #ccc;"></td>
    </tr>
@endif

{{-- @forelse($antes as $accion)
    <tr>
        @if($loop->first)
            <td rowspan="{{ max($antes->count(), 1) }}">ANTES</td>
        @endif

        <td>
            {{ $loop->iteration }}. {{ $accion->description }}
        </td>

        <td>
            {{ $loop->iteration }}. {{ $accion->member->names ?? '' }}
        </td>
    </tr>
@empty
    <tr>
        <td>ANTES</td>
        <td></td>
        <td></td>
    </tr>
@endforelse --}}

        {{-- @for($i = 0; $i < max(count($durante), 1); $i++)
            <tr>
                @if($i == 0)
                    <td rowspan="{{ max(count($durante), 1) }}">DURANTE</td>
                @endif

                <td>
                    {{ isset($durante[$i]) ? ($i + 1) . '. ' . $durante[$i]->description : '' }}
                </td>

                <td>
                    {{ isset($durante[$i]) ? ($i + 1) . '. ' . $durante[$i]->member->names : '' }}
                </td>
            </tr>
        @endfor

        @for($i = 0; $i < max(count($despues), 1); $i++)
            <tr>
                @if($i == 0)
                    <td rowspan="{{ max(count($despues), 1) }}">DESPUES</td>
                @endif

                <td>
                    {{ isset($despues[$i]) ? ($i + 1) . '. ' . $despues[$i]->description : '' }}
                </td>

                <td>
                    {{ isset($despues[$i]) ? ($i + 1) . '. ' . $despues[$i]->member->names : '' }}
                </td>
            </tr>
        @endfor --}}

    </table>

    <div class="salto"> </div>

    {{-- GRAFICO ENTORNO --------------------------------------------------------------------------------------- --}}

    <div class="titulo">
        <strong>Formato Anexo N° 09 - <em>“GRÁFICO DEL ENTORNO”</em></strong>
    </div>

    <div class="graficoCont">

        <img src="{{ storage_path('app/public/' . $familyPlan->housingInfo->path) }}">
    </div>

</body>

</html>