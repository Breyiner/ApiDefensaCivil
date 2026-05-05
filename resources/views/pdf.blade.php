<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
        /* =========================
        RESET / BASE
        ========================= */
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        /* =========================
        PDF CONFIG
        ========================= */
        @page {
            margin-left: 12px;
            margin-right: 12px;
            margin-bottom: 5px;
            margin-top: 120px;
        }

        /* =========================
        UTILIDADES
        ========================= */
        .salto {
            page-break-after: always;
        }

        .titulo {
            width: 100%;
            text-align: center;
        }

        .nota {
            width: 90%;
            margin: 20px auto;
            margin-top: 20px;
        }

        .margin_null {
            margin: 0;
        }

        /* =========================
        ENCABEZADO PDF
        ========================= */
        .encabezadoDoc {
            height: 85px;
            width: 100%;
            position: fixed;
            top: -120px;
            padding-top: 15px;
            background-color: rgb(0, 111, 192);
            border-radius: 0 0 30px 30px;
        }

        .encabezadoDoc img {
            height: 70px;
            float: left;
            display: inline-block;
        }

        .encabezadoDoc p {
            float: left;
            color: white;
            font-weight: bold;
            padding: 10px;
            border-radius: 5px;
            margin-top: 15px;
            text-align: center;
        }

        /* =========================
        COVER / PORTADA
        ========================= */
        .cover {
            width: 100%;
            padding-top: 100px;
        }

        .cover .izq {
            width: 55%;
            text-align: right;
            vertical-align: middle;
            padding-right: 15px;
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

        h1 {
            font-size: 30px;
            margin-top: 1cm;
        }

        /* =========================
        TABLAS BASE
        ========================= */
        .preguntasVulnerabilidad,
        .tablaFamilia,
        .tablaMascotas,
        .tablaIntegrante,
        .tablaVulnerabilidades,
        .tablaRecursos,
        .tablaAccion {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            height: fit-content;
            padding-top: 20px;
        }

        /* =========================
        TABLA PREGUNTAS
        ========================= */
        .preguntasVulnerabilidad {
            font-size: 15px;
        }

        .columnas {
            background-color: rgb(0, 111, 192);
            color: #fff;
        }

        .encabezado_preguntas {
            text-align: center;
            padding: 6px;
            border: 1px solid #000;
        }

        /* =========================
        TABLAS CELDAS
        ========================= */
        .fila_normal {
            padding: 6px;
            border: 1px solid #000;
        }

        .fila_centrada {
            text-align: center;
            padding: 6px;
            border: 1px solid #000;
        }

        .encabezado_tabla {
            text-align: center;
            padding: 8px;
            border: 1px solid #000;
        }

        .secciones_tabla_fila {
            padding: 8px;
            border: 1px solid #000;
        }

        /* =========================
        COLORES TABLAS
        ========================= */
        .color_fila_oscuro {
            background-color: #1a5276;
            color: #fff;
        }

        .background_claro {
            background-color: #aed6f1;
        }

        /* =========================
        ACCIONES (PLAN FAMILIAR)
        ========================= */
        .seccion_accion {
            padding: 6px;
            border: 1px solid #000;
            background-color: #1a5276;
            color: #fff;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
        }

        /* =========================
        GRÁFICOS
        ========================= */
        .graficoCont {
            width: 100%;
            height: 900px;
            background-image: url('{{ public_path('assets/images/cuadricula.jpg') }}');
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
            text-align: center;
            margin-top: 10px;
        }

        .graficoCont img {
            height: 100%;
            max-height: 700px;
            max-width: 90%;
            object-fit: contain;
            display: inline-block;
            margin-top: 100px;
        }

        /* =========================
        GEOREFERENCIA
        ========================= */
        .GeoreferenciaCont {
            width: 100%;
            height: 320px;
            text-align: center;
        }

        .GeoreferenciaCont img {
            height: 100%;
            display: inline-block;
        }
    </style>

</head>

<body>

    <header class="encabezadoDoc">

        <img src="{{ public_path('assets/images/logos/defensa-civil-logo.png') }}" alt='null'>

        <p> PLAN FAMILIAR DE EMERGENCIA </p>

    </header>

    {{-- PORTADA ------------------------------------------------------------------------------------------ --}}

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
                        <p>{{ $familyPlan->city?->name }}</p>

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

    {{-- PREGUNTAS DE VULNERABILIDAD
    ------------------------------------------------------------------------------------------ --}}

    <div class="titulo">
        <strong>Formato Anexo N° 01 - <em>"TEST DE VULNERABILIDAD FAMILIAR"</em></strong>
    </div>

    <table class="preguntasVulnerabilidad">

        <thead>

            <tr class="columnas">
                <td style="width:8%;" class="encabezado_preguntas">
                    <strong>N°</strong>
                </td>
                <td style="width:72%;" class="encabezado_preguntas">
                    <strong>RESPONDA SÍ O NO SEGÚN SU APRECIACIÓN</strong>
                </td>
                <td style="width:10%;" class="encabezado_preguntas">
                    <strong>SÍ</strong>
                </td>
                <td style="width:10%;" class="encabezado_preguntas">
                    <strong>NO</strong>
                </td>
            </tr>

        </thead>

        <tbody>

            @php $i = 1; @endphp

            @foreach ($familyPlan->vulnerableTest as $test)

                <tr>

                    <td class="fila_centrada">
                        {{ str_pad($i++, 2, '0', STR_PAD_LEFT) ?? 'null' }}
                    </td>

                    <td class="fila_normal">
                        {{ $test->vulnerableQuestion->description ?? 'null' }}
                    </td>

                    <td class="fila_centrada">
                        {{ $test->answer ? 'X' : '' }}
                    </td>

                    <td class="fila_centrada">
                        {{ !$test->answer ? 'X' : '' }}
                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>

    <p class="nota"><strong>RESULTADO:</strong> Si al menos cinco (5) de las primeras doce (12) preguntas son respondidas afirmativamente (SÍ), el hogar se 
        clasificará como familia vulnerable; de lo contrario, se considerará familia no vulnerable.</p>

    <div class="salto"></div>

    {{-- IDENTIFICACIÓN DE LA FAMILIA
    ------------------------------------------------------------------------------------------ --}}

    @php
        $georeferencia = $familyPlan->housingInfo->where('housing_info_type_id', 1)->first();
    @endphp

    <div class="titulo" style="width:100%; margin-bottom:10px;">
        <strong>Formato Anexo N° 02 - <em>"IDENTIFICACIÓN DE LA FAMILIA"</em></strong>
    </div>


    <table class="tablaFamilia">

        {{-- Título --}}
        <tr class="color_fila_oscuro">
            <td colspan="2" class="encabezado_tabla">
                <strong>ANEXO N° 02 IDENTIFICACIÓN DE LA FAMILIA</strong>
            </td>
        </tr>

        {{-- Fila oscura --}}
        <tr class="color_fila_oscuro">
            <td class="secciones_tabla_fila" style="width:40%;">
                <strong>SECCIONAL</strong>
            </td>
            <td class="secciones_tabla_fila" style="width:60%;">
                <p>{{ $familyPlan->sectional->name ?? 'null' }}</p>
            </td>
        </tr>

        {{-- Fila clara --}}
        <tr class="background_claro">
            <td class="secciones_tabla_fila">
                <strong>Familia Segura N°</strong>
            </td>
            <td class="secciones_tabla_fila">
                <p>{{ $familyPlan->id ?? 'null' }}</p>
            </td>
        </tr>

        <tr>
            <td class="secciones_tabla_fila">
                <strong>NOMBRE DE LA FAMILIA</strong><br>
                <small>(Apellidos)</small>
            </td>
            <td class="secciones_tabla_fila">
                <p>{{ $familyPlan->last_names ?? 'null' }}</p>
            </td>
        </tr>

        <tr>
            <td class="secciones_tabla_fila">
                <strong>TIPO DE FAMILIA</strong><br>
                <small>(En base al test de vulnerabilidad se clasifica a la familia como vulnerable o no vulnerable)</small>

            </td>

            <td class="secciones_tabla_fila">

                <p>{{ $familyPlan->familyType?->name ?? 'Por definir' }}</p>
            </td>
        </tr>

        <tr>
            <td class="secciones_tabla_fila">
                <strong>DIRECCIÓN</strong>
            </td>
            <td class="secciones_tabla_fila">
                <p>{{ ($familyPlan->address ?? 'null') . ', ' . ($familyPlan->sector?->name ?? 'null') . ' ' . ($familyPlan->sector_name ?? 'null') . ', ' . ($familyPlan->city?->name ?? 'null') . ', ' . ($familyPlan->city?->department?->name ?? 'null') }}
                </p>
            </td>
        </tr>

        <tr>
            <td class="secciones_tabla_fila">
                <strong>BARRIO - COMUNA - LOCALIDAD</strong>
            </td>
            <td class="secciones_tabla_fila">
                <p>{{ $familyPlan->sector->name ?? 'null' }}</p>
            </td>
        </tr>

        <tr>
            <td class="secciones_tabla_fila">
                <strong>TELÉFONO FIJO</strong>
            </td>
            <td class="secciones_tabla_fila">
                <p>{{ $familyPlan->landline_phone ?? 'null' }}</p>
            </td>
        </tr>

        <tr>
            <td class="secciones_tabla_fila">
                <strong>CALIDAD DE LA VIVIENDA</strong><br>
                <small>(Arriendo – Propietario)</small>
            </td>
            <td class="secciones_tabla_fila">
                <p>{{ $familyPlan->housingQuality->name ?? 'null' }}</p>
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

    <div class="GeoreferenciaCont">
        @if ($georeferencia && !is_null($georeferencia->path))
            <img src="{{ public_path('storage/' . $georeferencia->path) }}" alt='null'>
        @endif
    </div>


    <div class="salto"></div>


    {{-- TABLA INTEGRANTES --------------------------------------------------------------------------------------------
    --}}

    <div class="titulo">
        <strong>Formato Anexo N° 03 - <em>"INTEGRANTES DE LA FAMILIA"</em></strong>
    </div>

    <table class="tablaIntegrante">

        <tr class="color_fila_oscuro">
            <td colspan="9" class="encabezado_tabla">
                <p class="margin_null">ANEXO N° 03 INTEGRANTES DE LA FAMILIA</p>
            </td>
        </tr>

        {{-- Encabezados de columna --}}
        <tr class="color_fila_oscuro">
            <td class="fila_normal">
                <p class="margin_null">APELLIDOS Y NOMBRES</p>
            </td>
            <td class="fila_normal">
                <p class="margin_null">DOC. IDENTIDAD</p>
            </td>
            <td class="fila_normal">
                <p class="margin_null">EDAD</p>
            </td>
            <td class="fila_normal">
                <p class="margin_null">GRUPO SANGUÍNEO Y RH</p>
            </td>
            <td class="fila_normal">
                <p class="margin_null">PARENTESCO</p>
            </td>
            <td class="fila_normal">
                <p class="margin_null">EPS</p>
            </td>
            <td class="fila_normal">
                <p class="margin_null">ENFERMEDAD DISCAPACIDA ALERGIAS</p>
            </td>
            <td class="fila_normal">
                <p class="margin_null">MEDICINAS / DOSIS</p>
            </td>
            <td class="fila_normal">
                <p class="margin_null">CELULAR</p>
            </td>
        </tr>

        {{-- Filas dinámicas --}}
        @foreach($familyPlan->familyMembers as $member)
            <tr>
                <td class="fila_normal">
                    <p class="margin_null"> {{ ($member->member->last_names ?? 'null') . ' ' . ($member->member->names ?? 'null') }} </p>
                </td>
                <td class="fila_normal">
                    <p class="margin_null">
                        {{ ($member->member->documentType->acronym ?? 'null') . ' ' . ($member->member->document_number ?? 'null') }}
                    </p>
                </td>
                <td class="fila_normal">
                    <p class="margin_null"> {{ $member->member->age ?? 'null' }} </p>
                </td>
                <td class="fila_normal">
                    <p class="margin_null"> {{ $member->member->bloodGroup->name ?? 'null' }} </p>
                </td>
                <td class="fila_normal">
                    <p class="margin_null"> {{ $member->member->kinship->name ?? 'null' }} </p>
                </td>
                <td class="fila_normal">
                    <p class="margin_null"> {{ $member->member->eps ?? 'null' }} </p>
                </td>
                <td class="fila_normal">
                    <p class="margin_null"> {{ $member->member->conditionMember->pluck('name')->join('<br>') ?? 'null' }} </p>
                </td>
                <td class="fila_normal">
                    <p class="margin_null"> {{ $member->member->conditionMember->pluck('dose')->join('<br>') ?? 'null' }} </p>
                </td>
                <td class="fila_normal">
                    <p class="margin_null"> {{ $member->member->phone ?? 'null' }} </p>
                </td>
            </tr>
        @endforeach

    </table>

    <div class="salto"></div>


    {{-- TABLA MASCOTAS --------------------------------------------------------------------------------------------
    --}}

    <div class="titulo">
        <strong>Formato Anexo N° 04 - <em>"MASCOTAS O ANIMALES DE COMPAÑÍA"</em></strong>
    </div>

    <table class="tablaMascotas">

        <tr class="color_fila_oscuro">
            <td colspan="6" class="encabezado_tabla">
                <p class="margin_null">ANEXO N° 04 MASCOTAS O ANIMALES DE COMPAÑÍA</p>
            </td>
        </tr>

        {{-- Encabezados de columna --}}
        <tr class="background_claro">
            <td class="secciones_tabla_fila">
                <p class="margin_null">ESPECIE</p>
            </td>
            <td class="secciones_tabla_fila">
                <p class="margin_null">NOMBRE</p>
            </td>
            <td class="secciones_tabla_fila">
                <p class="margin_null">RAZA</p>
            </td>
            <td class="secciones_tabla_fila">
                <p class="margin_null">GENERO</p>
            </td>
            <td class="secciones_tabla_fila">
                <p class="margin_null">EDAD</p>
            </td>
            <td class="secciones_tabla_fila">
                <p class="margin_null">VACUNAS</p>
            </td>
        </tr>

        {{-- Filas dinámicas --}}
        @foreach($familyPlan->pets as $pet)
            <tr>
                <td class="secciones_tabla_fila">
                    <p class="margin_null">{{ $pet->species->name ?? 'null' }}</p>
                </td>
                <td class="secciones_tabla_fila">
                    <p class="margin_null">{{ $pet->name ?? 'null' }}</p>
                </td>
                <td class="secciones_tabla_fila">
                    <p class="margin_null">{{ $pet->breed ?? 'null' }}</p>
                </td>
                <td class="secciones_tabla_fila">
                    <p class="margin_null">{{ $pet->animalGender->name ?? 'null' }}</p>
                </td>
                <td class="secciones_tabla_fila">
                    <p class="margin_null">{{ $pet->age ?? 'null' }}</p>
                </td>
                <td class="secciones_tabla_fila">
                    <p class="margin_null">{{ $pet->petVaccine->pluck('name')->join(', ') ?? 'null' }}</p>
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

        <tr class="color_fila_oscuro">
            <td colspan="7" class="encabezado_tabla">
                <p class="margin_null">ANEXO N° 05 FACTORES DE RIESGO (AMENAZAS –VULNERABILIDADES)</p>
            </td>
        </tr>

        <tr class="color_fila_oscuro">
            <td class="fila_normal">
                <p class="margin_null">DESCRIPCIÓN</p>
            </td>
            <td class="fila_normal">
                <p class="margin_null">UBICACIÓN</p>
            </td>
            <td class="fila_normal">
                <p class="margin_null">TIPO DE AMENAZA</p>
            </td>
            <td class="fila_normal">
                <p class="margin_null">VULNERABILIDAD</p>
            </td>
            <td class="fila_normal">
                <p class="margin_null">ACCIONES DE REDUCCIÓN FAMILIARES</p>
            </td>
            <td class="fila_normal">
                <p class="margin_null">RESPONSABLE</p>
            </td>
            <td class="fila_normal">
                <p class="margin_null">TÉRMINO</p>
            </td>
        </tr>

        @foreach($familyPlan->riskFactors as $risk)
            <tr>
                <td class="fila_normal">
                    <p class="margin_null">{{ $risk->description ?? 'null' }}</p>
                </td>

                <td class="fila_normal">
                    <p class="margin_null">{{ $risk->ubication ?? 'null' }}</p>
                </td>

                <td class="fila_normal">
                    <p class="margin_null">{{ $risk->threatType->name ?? 'null' }}</p>
                </td>

                <td class="fila_normal">
                    <p class="margin_null">{{ $risk->description ?? 'null' }}</p>
                </td>

                <td class="fila_normal">
                    {{-- <p class="margin_null">{{ $risk->riskReductionActions->pluck('action')->join(', ') }}</p> --}}
                    @foreach($risk->riskReductionActions as $i => $action)
                        <p class="margin_null">
                            {{ $i + 1 }}. {{ $action->action ?? 'null' }}
                        </p>
                        <p></p>
                    @endforeach
                </td>

                <td class="fila_normal">
                    {{-- <p class="margin_null">{{ $risk->riskReductionActions->pluck('member.names')->join(', ') }}</p>
                    --}}
                    @foreach($risk->riskReductionActions as $i => $action)
                        <p class="margin_null">
                            {{ $i + 1 }}. {{ $action->member->names ?? '-' }}
                        </p>
                        <p></p>
                    @endforeach
                </td>

                <td class="fila_normal">
                    {{-- <p class="margin_null">{{ $risk->riskReductionActions->pluck('end_date')->join(', ') }}</p> --}}
                    @foreach($risk->riskReductionActions as $i => $action)
                        <p class="margin_null">
                            {{-- {{ $i + 1 }}. {{ $action->end_date->format('d/m/Y') }} --}}
                            {{ $i + 1 }}.{{ \Carbon\Carbon::parse($action->end_date)->format('d/m/Y') ?? '' }}
                        </p>
                        <p></p>
                    @endforeach
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

        <tr class="color_fila_oscuro">
            <td colspan="6" class="encabezado_tabla">
                <p class="margin_null">ANEXO N° 06 RECURSOS DISPONIBLES</p>
            </td>
        </tr>

        <tr class="color_fila_oscuro">
            <td class="fila_normal">
                <p class="margin_null">RECURSO</p>
            </td>
            <td class="fila_normal">
                <p class="margin_null">UBICACIÓN</p>
            </td>
            <td class="fila_normal">
                <p class="margin_null">DISTANCIA</p>
            </td>
            <td class="fila_normal">
                <p class="margin_null">SERVICIO</p>
            </td>
            <td class="fila_normal">
                <p class="margin_null">DESCRIPCIÓN</p>
            </td>
            <td class="fila_normal">
                <p class="margin_null">TELÉFONO</p>
            </td>
        </tr>

        @foreach($familyPlan->availableResources as $resource)
            <tr>
                <td class="fila_normal">
                    <p class="margin_null">
                        {{ $resource->resource->name ?? 'null' }}
                    </p>
                </td>
                <td class="fila_normal">
                    <p class="margin_null">
                        {{ $resource->location ?? 'null' }}
                    </p>
                </td>
                <td class="fila_normal">
                    <p class="margin_null">
                        {{ ($resource->distance ?? 'null') . ' metros' }}
                    </p>
                </td>
                <td class="fila_normal">
                    <p class="margin_null">
                        {{ $resource->resource->service ?? 'null' }}
                    </p>
                </td>
                <td class="fila_normal">
                    <p class="margin_null">
                        {{ $resource->description ?? 'null' }}
                    </p>
                </td>
                <td class="fila_normal">
                    <p class="margin_null">
                        {{ $resource->phone ?? 'null' }}
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

            @if (!is_null($graphic->path))
                <img src="{{ public_path('storage/' . $graphic->path) }}">
            @endif

            <p style="font-size:16px; margin-top:10px;">
                <strong>Descripción del Grafico:</strong> {{ $graphic->description ?? '' }}
            </p>
        </div>

        <div class="salto"></div>

    @endforeach

    @if ($familyPlan->housingGraphic->isEmpty())
        <div class="salto"></div>
    @endif

    {{-- TABLA PLAN DE ACCIÓN FAMILIAR --------------------------------------------------------------------------------------- --}}

    <div class="titulo">
        <strong>Formato Anexo N° 08 - <em>"PLAN DE ACCIÓN FAMILIAR"</em></strong>
    </div>

    <table class="tablaAccion">

        <tr class="color_fila_oscuro">
            <td colspan="3" class="encabezado_tabla">
                <p class="margin_null">ANEXO N° 08 PLAN DE ACCIÓN FAMILIAR</p>
            </td>
        </tr>

        <tr>
            <td style="width:30%;" class="fila_normal"><strong>PLAN DE ACCION POR:</strong></td>
            <td colspan="2" class="fila_normal">
                @foreach($familyPlan->familyMembers as $familyMember)
                    @foreach ($familyMember->member->actionPlan as $plan)

                        <p class="margin_null">{{ ($plan->member->names ?? 'null') . ' ' . ($plan->member->last_names ?? 'null') }}</p>
                        {{-- <p>{{ $plan->actionPlanAction }}</p> --}}
                    @endforeach
                @endforeach
            </td>
        </tr>

        <tr>
            <td class="fila_normal"><strong>COORDINADOR:</strong></td>
            <td colspan="2" class="fila_normal">
                <p class="margin_null">
                    {{ ($familyPlan->user->profile->names ?? 'null') . ' ' . ($familyPlan->user->profile->last_names ?? 'null') }}
                </p>
            </td>
        </tr>

        <tr class="color_fila_oscuro">
            <td colspan="2" class="fila_normal">
                <p class="margin_null">ACCIONES A DESARROLLAR</p>
            </td>
            <td style="width:30%;" class="fila_normal">
                <p class="margin_null">RESPONSABLE</p>
            </td>
        </tr>

        @php

            $acciones = collect();

            foreach ($familyPlan->familyMembers as $familyMember) {
                foreach ($familyMember->member->actionPlan as $plan) {
                    $acciones->push($plan->actionPlanAction);
                }
            }

            $acciones = $acciones->flatten();

            $antes = $acciones->where('action_type_id', 1)->values();
            $durante = $acciones->where('action_type_id', 2)->values();
            $despues = $acciones->where('action_type_id', 3)->values();

        @endphp

        {{-- @dd($despues) --}}

        @foreach($antes as $action)
            <tr>
                @if($loop->first)
                    <td rowspan="{{ $antes->count() }}" class="seccion_accion">
                        ANTES
                    </td>
                @endif

                <td class="fila_normal">
                    <p class="margin_null">{{ $action->description ?? 'null' }}</p>
                </td>

                <td class="fila_normal">
                    <p class="margin_null">{{ ($action->member->names ?? 'null') . ' ' . ($action->member->last_names ?? 'null') }}</p>
                </td>
            </tr>
        @endforeach

        @foreach($durante as $action)
            <tr>
                @if($loop->first)
                    <td rowspan="{{ $durante->count() }}" class="seccion_accion">
                        DURANTE
                    </td>
                @endif

                <td class="fila_normal">
                    <p class="margin_null">{{ $action->description ?? 'null' }}</p>
                </td>

                <td class="fila_normal">
                    <p class="margin_null">{{ ($action->member->names ?? 'null') . ' ' . ($action->member->last_names ?? 'null') }}</p>
                </td>
            </tr>
        @endforeach

        @foreach($despues as $action)
            <tr>
                @if($loop->first)
                    <td rowspan="{{ $despues->count() }}" class="seccion_accion">
                        DESPUÉS
                    </td>
                @endif

                <td class="fila_normal">
                    <p class="margin_null">{{ $action->description ?? 'null' }}</p>
                </td>

                <td class="fila_normal">
                    <p class="margin_null">{{ ($action->member->names ?? 'null') . ' ' . ($action->member->last_names ?? 'null') }}</p>
                </td>
            </tr>
        @endforeach

    </table>

    <div class="salto"> </div>

    {{-- GRAFICO ENTORNO --------------------------------------------------------------------------------------- --}}

    @php
        $graficoEntorno = $familyPlan->housingInfo->where('housing_info_type_id', 2)->first();
    @endphp

    <div class="titulo">
        <strong>Formato Anexo N° 09 - <em>“GRÁFICO DEL ENTORNO”</em></strong>
    </div>

    <div class="graficoCont">
        @if ($graficoEntorno && !is_null($graficoEntorno->path))
            <img src="{{ public_path('storage/' . $graficoEntorno->path) }}">
        @endif
    </div>

</body>

</html>