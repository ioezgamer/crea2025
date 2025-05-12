<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ficha de Inscripción - {{ $participante->primer_nombre_p }} {{ $participante->primer_apellido_p }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10px; color: #374151; margin: 0; }
        .container { width: 100%; padding: 10px; box-sizing: border-box; }
        h1 { font-size: 16px; font-weight: bold; color: #111827; text-align: center; margin-bottom: 5px; }
        h3 { font-size: 12px; font-weight: 600; color: #1f2937; margin: 10px 0 5px; }
        .section { margin-top: 10px; padding-top: 10px; border-top: 1px solid #e5e7eb; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 5px; vertical-align: top; }
        .col { width: 50%; }
        .label { display: block; font-size: 9px; font-weight: 500; color: #4b5563; margin-bottom: 2px; }
        p { font-size: 10px; color: #1f2937; margin: 0; }
        .text-green-600 { color: #16a34a; }
        .text-red-600 { color: #dc2626; }
        .no-data { text-align: center; color: #dc2626; background-color: #fef2f2; padding: 10px; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Ficha de Inscripción</h1>

        @if($participante)
            <!-- Datos Personales -->
            <div class="section">
                <h3>Información del Participante</h3>
                <table>
                    <tr>
                        <td class="col">
                            <span class="label">Nombre Completo</span>
                            <p>{{ $participante->primer_nombre_p }} {{ $participante->segundo_nombre_p ?? '' }} {{ $participante->primer_apellido_p }} {{ $participante->segundo_apellido_p ?? '' }}</p>
                        </td>
                        <td class="col">
                            <span class="label">Fecha de Nacimiento</span>
                            <p>{{ \Carbon\Carbon::parse($participante->fecha_de_nacimiento_p)->translatedFormat('l j \\d\\e F \\d\\e Y') ?? 'N/A' }} (Edad: {{ $participante->edad_p ?? 'N/A' }})</p>
                        </td>
                    </tr>
                    <tr>
                        <td class="col">
                            <span class="label">Género</span>
                            <p>{{ $participante->genero ?? 'N/A' }}</p>
                        </td>
                        <td class="col">
                            <span class="label">Cédula (Adulto)</span>
                            <p>{{ $participante->cedula_participante_adulto_str ?? 'N/A' }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td class="col">
                            <span class="label">Ciudad / Departamento</span>
                            <p>{{ $participante->ciudad_p ?? 'N/A' }} / {{ $participante->departamento_p ?? 'N/A' }}</p>
                        </td>
                        <td class="col">
                            <span class="站在label">Comunidad</span>
                            <p>{{ $participante->comunidad_p ?? 'N/A' }}</p>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Información Escolar -->
            <div class="section">
                <h3>Información Escolar</h3>
                <table>
                    <tr>
                        <td class="col">
                            <span class="label">Escuela</span>
                            <p>{{ $participante->escuela_p ?? 'N/A' }}</p>
                        </td>
                        <td class="col">
                            <span class="label">Comunidad (Escuela)</span>
                            <p>{{ $participante->comunidad_escuela ?? 'N/A' }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td class="col">
                            <span class="label">Grado</span>
                            <p>{{ $participante->grado_p ?? 'N/A' }}</p>
                        </td>
                        <td class="col">
                            <span class="label">Turno</span>
                            <p>{{ $participante->turno ?? 'N/A' }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td class="col">
                            <span class="label">¿Repite Grado?</span>
                            <p class="{{ $participante->repite_grado ? 'text-green-600' : 'text-red-600' }}">{{ $participante->repite_grado ? 'Sí' : 'No' }}</p>
                        </td>
                        <td class="col"></td>
                    </tr>
                </table>
            </div>

            <!-- Documentos -->
            <div class="section">
                <h3>Documentos</h3>
                <table>
                    <tr>
                        <td class="col">
                            <span class="label">Partida de Nacimiento</span>
                            <p class="{{ $participante->partida_de_nacimiento ? 'text-green-600' : 'text-red-600' }}">{{ $participante->partida_de_nacimiento ? 'Sí' : 'No' }}</p>
                        </td>
                        <td class="col">
                            <span class="label">Boletín o Diploma (2024)</span>
                            <p class="{{ $participante->boletin_o_diploma_2024 ? 'text-green-600' : 'text-red-600' }}">{{ $participante->boletin_o_diploma_2024 ? 'Sí' : 'No' }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td class="col">
                            <span class="label">Cédula Tutor</span>
                            <p class="{{ $participante->cedula_tutor ? 'text-green-600' : 'text-red-600' }}">{{ $participante->cedula_tutor ? 'Sí' : 'No' }}</p>
                        </td>
                        <td class="col">
                            <span class="label">Cédula Participante Adulto</span>
                            <p class="{{ $participante->cedula_participante_adulto ? 'text-green-600' : 'text-red-600' }}">{{ $participante->cedula_participante_adulto ? 'Sí' : 'No' }}</p>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Inscripción al Programa -->
            <div class="section">
                <h3>Inscripción al Programa</h3>
                <table>
                    <tr>
                        <td class="col">
                            <span class="label">Fecha de Inscripción</span>
                            <p>{{ \Carbon\Carbon::parse($participante->fecha_de_inscripcion)->translatedFormat('l j \\d\\e F \\d\\e Y') ?? 'N/A' }}</p>
                        </td>
                        <td class="col">
                            <span class="label">Año de Inscripción</span>
                            <p>{{ $participante->ano_de_inscripcion ?? 'N/A' }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td class="col">
                            <span class="label">Programa principal</span>
                            <p>{{ $participante->programa ?? 'N/A' }}</p>
                        </td>
                        <td class="col">
                            <span class="label">Subprogramas</span>
                            <p>{{ is_array($participante->programas) ? implode(', ', $participante->programas) : ($participante->programas ?? 'N/A') }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td class="col">
                            <span class="label">Lugar de Encuentro</span>
                            <p>{{ $participante->lugar_de_encuentro_del_programa ?? 'N/A' }}</p>
                        </td>
                        <td class="col">
                            <span class="label">Días de Asistencia</span>
                            <p>{{ is_array($participante->dias_de_asistencia_al_programa) ? implode(', ', $participante->dias_de_asistencia_al_programa) : ($participante->dias_de_asistencia_al_programa ?? 'N/A') }}</p>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Tutor Principal -->
            <div class="section">
                <h3>Tutor Principal</h3>
                <table>
                    <tr>
                        <td class="col">
                            <span class="label">Nombre Completo</span>
                            <p>{{ $participante->nombres_y_apellidos_tutor_principal ?? 'N/A' }}</p>
                        </td>
                        <td class="col">
                            <span class="label">Cédula</span>
                            <p>{{ $participante->numero_de_cedula_tutor ?? 'N/A' }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td class="col">
                            <span class="label">Comunidad</span>
                            <p>{{ $participante->comunidad_tutor ?? 'N/A' }}</p>
                        </td>
                        <td class="col">
                            <span class="label">Dirección</span>
                            <p>{{ $participante->direccion_tutor ?? 'N/A' }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td class="col">
                            <span class="label">Teléfono</span>
                            <p>{{ $participante->telefono_tutor ?? 'N/A' }}</p>
                        </td>
                        <td class="col">
                            <span class="label">Sector Económico</span>
                            <p>{{ $participante->sector_economico_tutor ?? 'N/A' }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td class="col">
                            <span class="label">Nivel de Educación</span>
                            <p>{{ $participante->nivel_de_educacion_formal_adquirido_tutor ?? 'N/A' }}</p>
                        </td>
                        <td class="col">
                            <span class="label">Expectativas del Programa</span>
                            <p>{{ $participante->expectativas_del_programa_tutor_principal ?? 'N/A' }}</p>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Tutor Secundario -->
            <div class="section">
                <h3>Tutor Secundario</h3>
                <table>
                    <tr>
                        <td class="col">
                            <span class="label">Nombre Completo</span>
                            <p>{{ $participante->nombres_y_apellidos_tutor_secundario ?? 'N/A' }}</p>
                        </td>
                        <td class="col">
                            <span class="label">Cédula</span>
                            <p>{{ $participante->numero_de_cedula_tutor_secundario ?? 'N/A' }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td class="col">
                            <span class="label">Comunidad</span>
                            <p>{{ $participante->comunidad_tutor_secundario ?? 'N/A' }}</p>
                        </td>
                        <td class="col">
                            <span class="label">Teléfono</span>
                            <p>{{ $participante->telefono_tutor_secundario ?? 'N/A' }}</p>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Otros Programas -->
            <div class="section">
                <h3>Otros Programas</h3>
                <table>
                    <tr>
                        <td class="col">
                            <span class="label">¿Asiste a Otros Programas?</span>
                            <p class="{{ $participante->asiste_a_otros_programas ? 'text-green-600' : 'text-red-600' }}">{{ $participante->asiste_a_otros_programas ? 'Sí' : 'No' }}</p>
                        </td>
                        <td class="col">
                            <span class="label">Otros Programas</span>
                            <p>{{ $participante->otros_programas ?? 'N/A' }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td class="col">
                            <span class="label">Días de Asistencia</span>
                            <p>{{ is_array($participante->dias_asiste_a_otros_programas) ? implode(', ', $participante->dias_asiste_a_otros_programas) : ($participante->dias_asiste_a_otros_programas ?? 'N/A') }}</p>
                        </td>
                        <td class="col"></td>
                    </tr>
                </table>
            </div>
        @else
            <div class="no-data">
                No se encontraron datos para este participante.
            </div>
        @endif
    </div>
</body>
</html>