<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ficha de Inscripción - {{ $participante->primer_nombre_p ?? 'Participante' }} {{ $participante->primer_apellido_p ?? '' }}</title>
    <style>
        @page {
            margin: 2.5mm; /* Márgenes de la página */
        }
        body {
            font-family: 'Helvetica', Arial, sans-serif; /* Usar Helvetica si está disponible, sino Arial */
            font-size: 10pt;
            color: #333;
            line-height: 1.4;
        }
        .container {
            width: 100%;
        }
        .header {
            text-align: center;
            margin-bottom: 2px;
            border-bottom: 1px solid #0056b3; /* Azul oscuro para la línea */
            padding-bottom: 5px;
        }
        .header h1 {
            font-size: 8pt;
            color: #003366; /* Azul oscuro */
            margin: 0;
        }
        .header p {
            font-size: 9pt;
            color: #555;
            margin-top: 3px;
        }
        .section {
            margin-bottom: 8px; /* Más espacio entre secciones */
            padding: 5px;
            border: 1px solid #ddd; /* Borde sutil para cada sección */
            border-radius: 5px; /* Esquinas redondeadas */
            background-color: #f9f9f9; /* Fondo muy claro para las secciones */
        }
        .section-title {
            font-size: 11pt; /* Ligeramente más grande */
            font-weight: bold;
            color: #0056b3; /* Azul oscuro */
            margin-bottom: 7px;
            padding-bottom: 5px;
            border-bottom: 1px solid #eee; /* Línea sutil bajo el título de sección */
        }
        dl {
            margin: 0;
            padding: 0;
        }
        .info-grid {
            display: block; /* For mPDF, table-like structures are more reliable */
        }
        .info-item {
            padding: 2px 0;
            border-bottom: 1px dotted #eee; /* Líneas punteadas entre items */
            margin-bottom: 4px;
        }
        .info-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        dt {
            font-size: 7pt;
            font-weight: bold; /* Hacer etiquetas más notorias */
            color: #4A5568; /* Gris oscuro */
            display: block; /* Para que ocupe su propia línea */
            margin-bottom: 2px;
        }
        dd {
            font-size: 7pt;
            color: #1A202C; /* Negro suave */
            margin-left: 0; /* Resetear margen */
            margin-bottom: 5px; /* Espacio después del valor */
            padding-left: 5px; /* Pequeña indentación para el valor */
        }
        .text-green { color: #28a745; font-weight: bold; }
        .text-red { color: #dc3545; font-weight: bold; }
        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 8pt;
            font-weight: bold;
            border-radius: 4px;
            color: #fff;
        }
        .status-activo { background-color: #28a745; } /* Verde */
        .status-inactivo { background-color: #dc3545; } /* Rojo */
        .status-entregado { background-color: #28a745; }
        .status-pendiente { background-color: #ffc107; color: #333; } /* Amarillo con texto oscuro */

        .footer {
            text-align: center;
            font-size: 4pt;
            color: #777;
            position: fixed;
            bottom: 5mm;
            left: 10mm;
            right: 10mm;
        }
        /* Para simular columnas en mPDF, usaremos tablas o elementos flotantes si es necesario,
           pero para simplicidad, un diseño de una sola columna para items es más robusto.
           Si se necesitan dos columnas para pares dt/dd, se puede usar una tabla dentro de la sección. */
        .two-column-dl table { width: 100%; }
        .two-column-dl td { width: 50%; vertical-align: top; padding-right: 10px;}
        .two-column-dl td:last-child { padding-right: 0; }

    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Ficha de Inscripción del Participante</h1>
            @if($participante)
                <p>
                    {{ $participante->primer_nombre_p }} {{ $participante->segundo_nombre_p ?? '' }} {{ $participante->primer_apellido_p }} {{ $participante->segundo_apellido_p ?? '' }}
                    - ID: #{{ $participante->participante_id }}
                    <span class="status-badge {{ $participante->activo ? 'status-activo' : 'status-inactivo' }}">
                        {{ $participante->activo ? 'Activo' : 'Inactivo' }}
                    </span>
                </p>
            @endif
        </div>

        @if($participante)
            <div class="section">
                <h3 class="section-title">Información del Participante</h3>
                <dl class="two-column-dl">
                    <table><tr><td>
                        <div class="info-item"><dt>Nivel del Participante:</dt><dd>{{ $participante->participante ?? 'N/A' }}</dd></div>
                        <div class="info-item"><dt>Fecha de Nacimiento:</dt><dd>{{ $participante->fecha_de_nacimiento_p ? \Carbon\Carbon::parse($participante->fecha_de_nacimiento_p)->translatedFormat('j \\d\\e F \\d\\e Y') : 'N/A' }}</dd></div>
                        <div class="info-item"><dt>Edad:</dt><dd>{{ $participante->edad_p ?? 'N/A' }} años</dd></div>
                        <div class="info-item"><dt>Género:</dt><dd>{{ $participante->genero ?? 'N/A' }}</dd></div>
                    </td><td>
                        <div class="info-item"><dt>Cédula (Adulto):</dt><dd>{{ $participante->cedula_participante_adulto_str ?? 'N/A' }}</dd></div>
                        <div class="info-item"><dt>Comunidad:</dt><dd>{{ $participante->comunidad_p ?? 'N/A' }}</dd></div>
                        <div class="info-item"><dt>Ciudad:</dt><dd>{{ $participante->ciudad_p ?? 'N/A' }}</dd></div>
                        <div class="info-item"><dt>Departamento:</dt><dd>{{ $participante->departamento_p ?? 'N/A' }}</dd></div>
                    </td></tr></table>
                </dl>
            </div>

            <div class="section">
                <h3 class="section-title">Información Escolar</h3>
                <dl class="two-column-dl">
                     <table><tr><td>
                        <div class="info-item"><dt>Escuela:</dt><dd>{{ $participante->escuela_p ?? 'N/A' }}</dd></div>
                        <div class="info-item"><dt>Comunidad (Escuela):</dt><dd>{{ $participante->comunidad_escuela ?? 'N/A' }}</dd></div>
                        <div class="info-item"><dt>Grado:</dt><dd>{{ $participante->grado_p ?? 'N/A' }}</dd></div>
                    </td><td>
                        <div class="info-item"><dt>Turno:</dt><dd>{{ $participante->turno ?? 'N/A' }}</dd></div>
                        <div class="info-item">
                            <dt>¿Repite Grado?</dt>
                            <dd><span class="{{ $participante->repite_grado ? 'text-green' : 'text-red' }}">{{ $participante->repite_grado ? 'Sí' : 'No' }}</span></dd>
                        </div>
                    </td></tr></table>
                </dl>
            </div>

            <div class="section">
                <h3 class="section-title">Documentos Requeridos</h3>
                <dl class="two-column-dl">
                     <table><tr><td>
                        <div class="info-item"><dt>Partida de Nacimiento:</dt><dd><span class="{{ $participante->partida_de_nacimiento ? 'status-entregado' : 'status-pendiente' }}">{{ $participante->partida_de_nacimiento ? 'Entregada' : 'Pendiente' }}</span></dd></div>
                        <div class="info-item"><dt>Boletín o Diploma ({{ now()->year }}):</dt><dd><span class="{{ $participante->boletin_o_diploma_2024 ? 'status-entregado' : 'status-pendiente' }}">{{ $participante->boletin_o_diploma_2024 ? 'Entregado' : 'Pendiente' }}</span></dd></div>
                    </td><td>
                        <div class="info-item"><dt>Cédula del Tutor:</dt><dd><span class="{{ $participante->cedula_tutor ? 'status-entregado' : 'status-pendiente' }}">{{ $participante->cedula_tutor ? 'Entregada' : 'Pendiente' }}</span></dd></div>
                        <div class="info-item"><dt>Cédula Participante (Adulto):</dt><dd><span class="{{ $participante->cedula_participante_adulto ? 'status-entregado' : 'status-pendiente' }}">{{ $participante->cedula_participante_adulto ? 'Entregada' : 'Pendiente' }}</span></dd></div>
                    </td></tr></table>
                </dl>
            </div>

            <div class="section">
                <h3 class="section-title">Inscripción al Programa CREA</h3>
                <dl class="two-column-dl">
                     <table><tr><td>
                        <div class="info-item"><dt>Fecha de Inscripción:</dt><dd>{{ $participante->fecha_de_inscripcion ? \Carbon\Carbon::parse($participante->fecha_de_inscripcion)->translatedFormat('j \\d\\e F \\d\\e Y') : 'N/A' }}</dd></div>
                        <div class="info-item"><dt>Año de Inscripción:</dt><dd>{{ $participante->ano_de_inscripcion ?? 'N/A' }}</dd></div>
                        <div class="info-item"><dt>Lugar de Encuentro:</dt><dd>{{ $participante->lugar_de_encuentro_del_programa ?? 'N/A' }}</dd></div>
                    </td><td>
                        <div class="info-item"><dt>Programa(s) Principal(es):</dt><dd>{{ $participante->programa_array ? implode(', ', $participante->programa_array) : 'N/A' }}</dd></div>
                        <div class="info-item"><dt>Sub-Programa(s)/Código(s):</dt><dd>{{ $participante->programas_array ? implode(', ', $participante->programas_array) : 'N/A' }}</dd></div>
                        <div class="info-item"><dt>Días de Asistencia Esperados:</dt><dd>{{ $participante->dias_de_asistencia_al_programa_array ? implode(', ', $participante->dias_de_asistencia_al_programa_array) : 'N/A' }}</dd></div>
                    </td></tr></table>
                </dl>
            </div>

            <div class="section">
                <h3 class="section-title">Tutor Principal</h3>
                <dl>
                    <div class="two-column-dl">
                        <table><tr><td>
                            <div class="info-item"><dt>Parentesco:</dt><dd>{{ $participante->tutor_principal ?? 'N/A' }}</dd></div>
                            <div class="info-item"><dt>Nº de Cédula:</dt><dd>{{ $participante->numero_de_cedula_tutor ?? 'N/A' }}</dd></div>
                            <div class="info-item"><dt>Teléfono:</dt><dd>{{ $participante->telefono_tutor ?? 'N/A' }}</dd></div>
                        </td><td>
                            <div class="info-item"><dt>Nombres y Apellidos:</dt><dd>{{ $participante->nombres_y_apellidos_tutor_principal ?? 'N/A' }}</dd></div>
                            <div class="info-item"><dt>Comunidad:</dt><dd>{{ $participante->comunidad_tutor ?? 'N/A' }}</dd></div>
                            <div class="info-item"><dt>Sector Económico:</dt><dd>{{ $participante->sector_economico_tutor ?? 'N/A' }}</dd></div>
                        </td></tr></table>
                    </div>
                    <div class="info-item"><dt>Dirección:</dt><dd>{{ $participante->direccion_tutor ?? 'N/A' }}</dd></div>
                    <div class="info-item"><dt>Nivel de Educación:</dt><dd>{{ $participante->nivel_de_educacion_formal_adquirido_tutor ?? 'N/A' }}</dd></div>
                    <div class="info-item"><dt>Expectativas del Programa:</dt><dd>{{ $participante->expectativas_del_programa_tutor_principal ?? 'N/A' }}</dd></div>
                </dl>
            </div>

            @if($participante->nombres_y_apellidos_tutor_secundario || $participante->tutor_secundario)
            <div class="section">
                <h3 class="section-title">Tutor Secundario</h3>
                <dl class="two-column-dl">
                    <table><tr><td>
                        <div class="info-item"><dt>Parentesco:</dt><dd>{{ $participante->tutor_secundario ?? 'N/A' }}</dd></div>
                        <div class="info-item"><dt>Nombres y Apellidos:</dt><dd>{{ $participante->nombres_y_apellidos_tutor_secundario ?? 'N/A' }}</dd></div>
                    </td><td>
                        <div class="info-item"><dt>Nº de Cédula:</dt><dd>{{ $participante->numero_de_cedula_tutor_secundario ?? 'N/A' }}</dd></div>
                        <div class="info-item"><dt>Comunidad:</dt><dd>{{ $participante->comunidad_tutor_secundario ?? 'N/A' }}</dd></div>
                        <div class="info-item"><dt>Teléfono:</dt><dd>{{ $participante->telefono_tutor_secundario ?? 'N/A' }}</dd></div>
                    </td></tr></table>
                </dl>
            </div>
            @endif

            <div class="section">
                <h3 class="section-title">Participación en Otros Programas</h3>
                <dl class="two-column-dl">
                     <table><tr><td>
                        <div class="info-item">
                            <dt>¿Asiste a otros programas fuera de CREA?</dt>
                            <dd><span class="{{ $participante->asiste_a_otros_programas ? 'text-green' : 'text-red' }}">{{ $participante->asiste_a_otros_programas ? 'Sí' : 'No' }}</span></dd>
                        </div>
                        @if($participante->asiste_a_otros_programas)
                            <div class="info-item"><dt>¿Cuáles programas?</dt><dd>{{ $participante->otros_programas ?? 'N/A' }}</dd></div>
                        </td><td>
                            <div class="info-item"><dt>Días que asiste a esos otros programas:</dt><dd>{{ $participante->dias_asiste_a_otros_programas ? (is_array($participante->dias_asiste_a_otros_programas) ? implode(', ', $participante->dias_asiste_a_otros_programas) : $participante->dias_asiste_a_otros_programas) : 'N/A' }}</dd></div>
                        @else
                        </td><td>
                        @endif
                    </td></tr></table>
                </dl>
            </div>

        @else
            <p class="no-data">No se encontraron datos para este participante.</p>
        @endif

        <div class="footer">
            Ficha de Inscripción Generada el {{ \Carbon\Carbon::now()->translatedFormat('j \\d\\e F \\d\\e Y \\a \\l\\a\\s H:i') }}
        </div>
    </div>
</body>
</html>
