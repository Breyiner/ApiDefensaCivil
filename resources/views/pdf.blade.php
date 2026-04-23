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
</body>

</html>