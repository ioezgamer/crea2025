<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-gray-900">
                Ficha de Inscripción
            </h2>
            <a href="{{ route('participante.index') }}"
               class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-100 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                @if($participante)
                    <div class="p-6 space-y-6">
                        <!-- Encabezado -->
                        <div class="border-b pb-4">
                            <h3 class="text-lg font-semibold text-gray-800">Información del Participante</h3>
                        </div>

                        <!-- Datos Personales -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <span class="text-sm font-medium text-gray-600">Nombre Completo</span>
                                <p class="text-gray-800">{{ $participante->primer_nombre_p ?? 'N/A' }} {{ $participante->segundo_nombre_p ?? '' }} {{ $participante->primer_apellido_p ?? '' }} {{ $participante->segundo_apellido_p ?? '' }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-600">Fecha de Nacimiento</span>
                                @php
                                        \Carbon\Carbon::setLocale('es');
                                        setlocale(LC_TIME, 'es_ES.UTF-8'); // Si tu sistema lo permite
                                    @endphp
                                    <p class="text-gray-800">{{ \Carbon\Carbon::parse($participante->fecha_de_nacimiento_p)->translatedFormat('l j \\d\\e F \\d\\e Y') ?? 'N/A' }} (Edad: {{ $participante->edad_p ?? 'N/A' }})</p>
                               
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-600">Género</span>
                                <p class="text-gray-800">{{ $participante->genero ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-600">Cédula (Adulto)</span>
                                <p class="text-gray-800">{{ $participante->cedula_participante_adulto_str ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-600">Ciudad / Departamento</span>
                                <p class="text-gray-800">{{ $participante->ciudad_p ?? 'N/A' }} / {{ $participante->departamento_p ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-600">Comunidad</span>
                                <p class="text-gray-800">{{ $participante->comunidad_p ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <!-- Información Escolar -->
                        <div class="border-t pt-4">
                            <h3 class="text-lg font-semibold text-gray-800">Información Escolar</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-2">
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Escuela</span>
                                    <p class="text-gray-800">{{ $participante->escuela_p ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Comunidad (Escuela)</span>
                                    <p class="text-gray-800">{{ $participante->comunidad_escuela ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Grado</span>
                                    <p class="text-gray-800">{{ $participante->grado_p ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Turno</span>
                                    <p class="text-gray-800">{{ $participante->turno ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">¿Repite Grado?</span>
                                    <p class="text-gray-800 {{ $participante->repite_grado ? 'text-green-600' : 'text-red-600' }}">{{ $participante->repite_grado ? 'Sí' : 'No' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Documentos -->
                        <div class="border-t pt-4">
                            <h3 class="text-lg font-semibold text-gray-800">Documentos</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-2">
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Partida de Nacimiento</span>
                                    <p class="text-gray-800 {{ $participante->partida_de_nacimiento ? 'text-green-600' : 'text-red-600' }}">{{ $participante->partida_de_nacimiento ? 'Sí' : 'No' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Boletín o Diploma (2024)</span>
                                    <p class="text-gray-800 {{ $participante->boletin_o_diploma_2024 ? 'text-green-600' : 'text-red-600' }}">{{ $participante->boletin_o_diploma_2024 ? 'Sí' : 'No' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Cédula Tutor</span>
                                    <p class="text-gray-800 {{ $participante->cedula_tutor ? 'text-green-600' : 'text-red-600' }}">{{ $participante->cedula_tutor ? 'Sí' : 'No' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Cédula Participante Adulto</span>
                                    <p class="text-gray-800 {{ $participante->cedula_participante_adulto ? 'text-green-600' : 'text-red-600' }}">{{ $participante->cedula_participante_adulto ? 'Sí' : 'No' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Programa -->
                        <div class="border-t pt-4">
                            <h3 class="text-lg font-semibold text-gray-800">Inscripción al Programa</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-2">
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Fecha de Inscripción</span>
                                    @php
                                        \Carbon\Carbon::setLocale('es');
                                        setlocale(LC_TIME, 'es_ES.UTF-8'); // Si tu sistema lo permite
                                    @endphp
                                    <p class="text-gray-800">{{ \Carbon\Carbon::parse($participante->fecha_de_inscripcion)->translatedFormat('l j \\d\\e F \\d\\e Y') ?? 'N/A' }}</p>
                                    
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Año de Inscripción</span>
                                    <p class="text-gray-800">{{ $participante->ano_de_inscripcion ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Programa principal</span>
                                    <p class="text-gray-800">{{ $participante->programa ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Subprogramas</span>
                                    <p class="text-gray-800">{{ is_array($participante->programas) ? implode(', ', $participante->programas) : ($participante->programas ?? 'N/A') }}</p>
                                </div>
                                
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Lugar de Encuentro</span>
                                    <p class="text-gray-800">{{ $participante->lugar_de_encuentro_del_programa ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Días de Asistencia</span>
                                    <p class="text-gray-800">{{ is_array($participante->dias_de_asistencia_al_programa) ? implode(', ', $participante->dias_de_asistencia_al_programa) : ($participante->dias_de_asistencia_al_programa ?? 'N/A') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Tutor Principal -->
                        <div class="border-t pt-4">
                            <h3 class="text-lg font-semibold text-gray-800">Tutor Principal</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-2">
                                <div>
                                    <span class="text-sm font-medium cenário text-gray-600">Nombre Completo</span>
                                    <p class="text-gray-800">{{ $participante->nombres_y_apellidos_tutor_principal ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Cédula</span>
                                    <p class="text-gray-800">{{ $participante->numero_de_cedula_tutor ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Comunidad</span>
                                    <p class="text-gray-800">{{ $participante->comunidad_tutor ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Dirección</span>
                                    <p class="text-gray-800">{{ $participante->direccion_tutor ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Teléfono</span>
                                    <p class="text-gray-800">{{ $participante->telefono_tutor ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Sector Económico</span>
                                    <p class="text-gray-800">{{ $participante->sector_economico_tutor ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Nivel de Educación</span>
                                    <p class="text-gray-800">{{ $participante->nivel_de_educacion_formal_adquirido_tutor ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Expectativas del Programa</span>
                                    <p class="text-gray-800">{{ $participante->expectativas_del_programa_tutor_principal ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Tutor Secundario -->
                        <div class="border-t pt-4">
                            <h3 class="text-lg font-semibold text-gray-800">Tutor Secundario</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-2">
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Nombre Completo</span>
                                    <p class="text-gray-800">{{ $participante->nombres_y_apellidos_tutor_secundario ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Cédula</span>
                                    <p class="text-gray-800">{{ $participante->numero_de_cedula_tutor_secundario ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Comunidad</span>
                                    <p class="text-gray-800">{{ $participante->comunidad_tutor_secundario ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Teléfono</span>
                                    <p class="text-gray-800">{{ $participante->telefono_tutor_secundario ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Otros Programas -->
                        <div class="border-t pt-4">
                            <h3 class="text-lg font-semibold text-gray-800">Otros Programas</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-2">
                                <div>
                                    <span class="text-sm font-medium text-gray-600">¿Asiste a Otros Programas?</span>
                                    <p class="text-gray-800 {{ $participante->asiste_a_otros_programas ? 'text-green-600' : 'text-red-600' }}">{{ $participante->asiste_a_otros_programas ? 'Sí' : 'No' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Otros Programas</span>
                                    <p class="text-gray-800">{{ $participante->otros_programas ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-600">Días de Asistencia</span>
                                    <p class="text-gray-800">{{ is_array($participante->dias_asiste_a_otros_programas) ? implode(', ', $participante->dias_asiste_a_otros_programas) : ($participante->dias_asiste_a_otros_programas ?? '') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="p-6 text-center text-red-600 bg-red-50 rounded-b-lg">
                        No se encontraron datos para este participante.
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>