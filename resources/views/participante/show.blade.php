<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col items-center justify-between w-full gap-3 px-4 py-4 mx-auto md:flex-row max-w-7xl sm:px-6 lg:px-8">
            <div class="flex items-center gap-3">
                <x-boton-regresar onclick="window.location.href='{{ route('participante.index') }}'" />
                <h1 class="text-2xl font-bold text-center text-transparent lg:text-3xl bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500">
                    Ficha del Participante
                </h1>
            </div>
            <div class="flex items-center gap-3">
                <x-secondary-button onclick="window.location.href='{{ route('participante.edit', $participante->participante_id) }}'">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Editar
                </x-secondary-button>
                <x-primary-button onclick="window.open('{{ route('participante.pdf', $participante->participante_id) }}', '_blank')">
                     <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Imprimir PDF
                </x-primary-button>
            </div>
        </div>
    </x-slot>

    <div class="py-8 font-sans bg-gradient-to-br from-slate-50 via-purple-50 to-pink-50 dark:from-slate-900 dark:via-purple-950 dark:to-pink-950">
        <div class="max-w-5xl px-4 mx-auto sm:px-6 lg:px-8">
            @if($participante)
                <div class="overflow-hidden bg-white shadow-2xl dark:bg-slate-800 rounded-2xl">
                    <div class="px-6 py-5 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 sm:px-8">
                        <div class="flex flex-col items-center justify-between gap-2 md:flex-row">
                            <h2 class="text-2xl font-semibold text-white">
                                {{ $participante->primer_nombre_p }} {{ $participante->segundo_nombre_p ?? '' }} {{ $participante->primer_apellido_p }} {{ $participante->segundo_apellido_p ?? '' }}
                            </h2>
                             <span class="px-3 py-1 text-xs font-medium rounded-full {{ $participante->activo ? 'bg-green-100 text-green-800 dark:bg-green-700/30 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-700/30 dark:text-red-200' }}">
                                {{ $participante->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>
                        <p class="mt-1 text-sm text-center text-indigo-200 md:text-left">Participante ID: #{{ $participante->participante_id }}</p>
                    </div>

                    <div class="p-6 space-y-8 md:p-8">

                        {{-- Sección de Información General --}}
                        <section class="p-5 border border-gray-200 dark:border-slate-700 rounded-xl bg-slate-50/50 dark:bg-slate-800/50">
                            <h3 class="mb-4 text-lg font-semibold text-indigo-700 dark:text-indigo-400">
                                <svg class="inline w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                Información del Participante
                            </h3>
                            <dl class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2 md:grid-cols-3">
                                <div class="info-item">
                                    <dt class="info-label">Tipo de participante:</dt>
                                    <dd class="info-value">{{ $participante->participante ?? 'N/A' }}</dd>
                                </div>
                                <div class="info-item">
                                    <dt class="info-label">Fecha de nacimiento:</dt>
                                    <dd class="info-value">{{ $participante->fecha_de_nacimiento_p ? \Carbon\Carbon::parse($participante->fecha_de_nacimiento_p)->translatedFormat('j \\d\\e F \\d\\e Y') : 'N/A' }}</dd>
                                </div>
                                <div class="info-item">
                                    <dt class="info-label">Edad:</dt>
                                    <dd class="info-value">{{ $participante->edad_p ?? 'N/A' }} años</dd>
                                </div>
                                <div class="info-item">
                                    <dt class="info-label">Género:</dt>
                                    <dd class="info-value">{{ $participante->genero ?? 'N/A' }}</dd>
                                </div>
                                @if(in_array('Adulto', explode(',', $participante->tipo_participante)))
                                <div class="info-item">
                                    <dt class="info-label">Cédula (Adulto):</dt>
                                    <dd class="info-value">{{ $participante->cedula_participante_adulto_str ?? 'N/A' }}</dd>
                                </div>
                                @endif

                                <div class="info-item">
                                    <dt class="info-label">Comunidad:</dt>
                                    <dd class="info-value">{{ $participante->comunidad_p ?? 'N/A' }}</dd>
                                </div>
                                <div class="info-item">
                                    <dt class="info-label">Ciudad:</dt>
                                    <dd class="info-value">{{ $participante->ciudad_p ?? 'N/A' }}</dd>
                                </div>
                                <div class="info-item">
                                    <dt class="info-label">Departamento:</dt>
                                    <dd class="info-value">{{ $participante->departamento_p ?? 'N/A' }}</dd>
                                </div>
                            </dl>
                        </section>

                        {{-- Sección de Información Escolar --}}
                         <section class="p-5 border border-gray-200 dark:border-slate-700 rounded-xl bg-slate-50/50 dark:bg-slate-800/50">
                            <h3 class="mb-4 text-lg font-semibold text-indigo-700 dark:text-indigo-400">
                                <svg class="inline w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v11.494m0 0A23.978 23.978 0 0112 6.253zM12 6.253c0-1.13.285-2.199.793-3.138A13.449 13.449 0 0112 3c-1.166 0-2.276.178-3.293.494A13.49 13.49 0 0012 6.253zm0 11.494c-2.954 0-5.707-.937-8-2.501M12 17.747c2.954 0 5.707-.937 8-2.501M4 15.246V8.754a13.444 13.444 0 0116 0v6.492m-16 0a13.425 13.425 0 0016 0"></path></svg>
                                Información Escolar
                            </h3>
                            <dl class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2 md:grid-cols-3">
                                <div class="info-item">
                                    <dt class="info-label">Escuela:</dt>
                                    <dd class="info-value">{{ $participante->escuela_p ?? 'N/A' }}</dd>
                                </div>
                                <div class="info-item">
                                    <dt class="info-label">Comunidad (Escuela):</dt>
                                    <dd class="info-value">{{ $participante->comunidad_escuela ?? 'N/A' }}</dd>
                                </div>
                                <div class="info-item">
                                    <dt class="info-label">Grado:</dt>
                                    <dd class="info-value">{{ $participante->grado_p ?? 'N/A' }}</dd>
                                </div>
                                <div class="info-item">
                                    <dt class="info-label">Turno:</dt>
                                    <dd class="info-value">{{ $participante->turno ?? 'N/A' }}</dd>
                                </div>
                                <div class="info-item">
                                    <dt class="info-label">¿Repite Grado?</dt>
                                    <dd class="info-value {{ $participante->repite_grado ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">{{ $participante->repite_grado ? 'Sí' : 'No' }}</dd>
                                </div>
                            </dl>
                        </section>

                        {{-- Sección de Documentos --}}
                        <section class="p-5 border border-gray-200 dark:border-slate-700 rounded-xl bg-slate-50/50 dark:bg-slate-800/50">
                            <h3 class="mb-4 text-lg font-semibold text-indigo-700 dark:text-indigo-400">
                                <svg class="inline w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Documentos Requeridos
                            </h3>
                            <dl class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2">
                                <div class="info-item">
                                    <dt class="info-label">Partida de Nacimiento:</dt>
                                    <dd class="info-value {{ $participante->partida_de_nacimiento ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">{{ $participante->partida_de_nacimiento ? 'Entregada' : 'Pendiente' }}</dd>
                                </div>
                                <div class="info-item">
                                    <dt class="info-label">Boletín o Diploma ({{ now()->year }}):</dt>
                                    <dd class="info-value {{ $participante->boletin_o_diploma_2024 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">{{ $participante->boletin_o_diploma_2024 ? 'Entregado' : 'Pendiente' }}</dd>
                                </div>
                                <div class="info-item">
                                    <dt class="info-label">Cédula del Tutor:</dt>
                                    <dd class="info-value {{ $participante->cedula_tutor ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">{{ $participante->cedula_tutor ? 'Entregada' : 'Pendiente' }}</dd>
                                </div>
                                @if(in_array('Adulto', explode(',', $participante->tipo_participante)))
                                <div class="info-item">
                                    <dt class="info-label">Cédula Participante (Adulto):</dt>
                                    <dd class="info-value {{ $participante->cedula_participante_adulto ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">{{ $participante->cedula_participante_adulto ? 'Entregada' : 'Pendiente' }}</dd>
                                </div>
                                @endif
                            </dl>
                        </section>

                        {{-- Inscripción al Programa CREA --}}
                        <section class="p-5 border border-gray-200 dark:border-slate-700 rounded-xl bg-slate-50/50 dark:bg-slate-800/50">
                            <h3 class="mb-4 text-lg font-semibold text-indigo-700 dark:text-indigo-400">
                                 <svg class="inline w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                Inscripción al Programa CREA
                            </h3>
                            <dl class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2">
                                <div class="info-item">
                                    <dt class="info-label">Fecha de inscripción:</dt>
                                    <dd class="info-value">{{ $participante->fecha_de_inscripcion ? \Carbon\Carbon::parse($participante->fecha_de_inscripcion)->translatedFormat('j \\d\\e F \\d\\e Y') : 'N/A' }}</dd>
                                </div>
                                <div class="info-item">
                                    <dt class="info-label">Año de inscripción:</dt>
                                    <dd class="info-value">{{ $participante->ano_de_inscripcion ?? 'N/A' }}</dd>
                                </div>
                                <div class="info-item sm:col-span-2">
                                    <dt class="info-label">Programa principal:</dt>
                                    <dd class="info-value">{{ $participante->programa_array ? implode(', ', $participante->programa_array) : 'N/A' }}</dd>
                                </div>
                                <div class="info-item sm:col-span-2">
                                    <dt class="info-label">Subprograma:</dt>
                                    <dd class="info-value">{{ $participante->programas_array ? implode(', ', $participante->programas_array) : 'N/A' }}</dd>
                                </div>
                                <div class="info-item">
                                    <dt class="info-label">Lugar de encuentro:</dt>
                                    <dd class="info-value">{{ $participante->lugar_de_encuentro_del_programa ?? 'N/A' }}</dd>
                                </div>
                                <div class="info-item">
                                    <dt class="info-label">Días de asistencia esperados:</dt>
                                    <dd class="info-value">{{ $participante->dias_de_asistencia_al_programa_array ? implode(', ', $participante->dias_de_asistencia_al_programa_array) : 'N/A' }}</dd>
                                </div>
                            </dl>
                        </section>

                        {{-- Tutor Principal --}}
                        <section class="p-5 border border-gray-200 dark:border-slate-700 rounded-xl bg-slate-50/50 dark:bg-slate-800/50">
                            <h3 class="mb-4 text-lg font-semibold text-indigo-700 dark:text-indigo-400">
                                <svg class="inline w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                                Tutor Principal
                            </h3>
                            <dl class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2 md:grid-cols-3">
                                <div class="info-item sm:col-span-1 md:col-span-1">
                                    <dt class="info-label">Tutor:</dt>
                                    <dd class="info-value">{{ $participante->tutor_principal ?? 'N/A' }}</dd>
                                </div>
                                <div class="info-item sm:col-span-2 md:col-span-2">
                                    <dt class="info-label">Nombres y apellidos:</dt>
                                    <dd class="info-value">{{ $participante->nombres_y_apellidos_tutor_principal ?? 'N/A' }}</dd>
                                </div>
                                <div class="info-item">
                                    <dt class="info-label">Nº de cédula:</dt>
                                    <dd class="info-value">{{ $participante->numero_de_cedula_tutor ?? 'N/A' }}</dd>
                                </div>
                                <div class="info-item">
                                    <dt class="info-label">Comunidad:</dt>
                                    <dd class="info-value">{{ $participante->comunidad_tutor ?? 'N/A' }}</dd>
                                </div>
                                <div class="info-item">
                                    <dt class="info-label">Teléfono:</dt>
                                    <dd class="info-value">{{ $participante->telefono_tutor ?? 'N/A' }}</dd>
                                </div>
                                <div class="info-item sm:col-span-2 md:col-span-3">
                                    <dt class="info-label">Dirección:</dt>
                                    <dd class="info-value">{{ $participante->direccion_tutor ?? 'N/A' }}</dd>
                                </div>
                                <div class="info-item">
                                    <dt class="info-label">Sector económico:</dt>
                                    <dd class="info-value">{{ $participante->sector_economico_tutor ?? 'N/A' }}</dd>
                                </div>
                                <div class="info-item">
                                    <dt class="info-label">Nivel de educación:</dt>
                                    <dd class="info-value">{{ $participante->nivel_de_educacion_formal_adquirido_tutor ?? 'N/A' }}</dd>
                                </div>
                                <div class="info-item sm:col-span-2 md:col-span-3">
                                    <dt class="info-label">Expectativas del programa:</dt>
                                    <dd class="info-value">{{ $participante->expectativas_del_programa_tutor_principal ?? 'N/A' }}</dd>
                                </div>
                            </dl>
                        </section>

                        {{-- Tutor Secundario --}}
                        @if($participante->nombres_y_apellidos_tutor_secundario || $participante->tutor_secundario)
                        <section class="p-5 border border-gray-200 dark:border-slate-700 rounded-xl bg-slate-50/50 dark:bg-slate-800/50">
                            <h3 class="mb-4 text-lg font-semibold text-indigo-700 dark:text-indigo-400">
                                <svg class="inline w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm-9 3a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                Tutor Secundario
                            </h3>
                            <dl class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2">
                                <div class="info-item">
                                    <dt class="info-label">Tutor:</dt>
                                    <dd class="info-value">{{ $participante->tutor_secundario ?? 'N/A' }}</dd>
                                </div>
                                <div class="info-item">
                                    <dt class="info-label">Nombres y apellidos:</dt>
                                    <dd class="info-value">{{ $participante->nombres_y_apellidos_tutor_secundario ?? 'N/A' }}</dd>
                                </div>
                                <div class="info-item">
                                    <dt class="info-label">Nº de cédula:</dt>
                                    <dd class="info-value">{{ $participante->numero_de_cedula_tutor_secundario ?? 'N/A' }}</dd>
                                </div>
                                <div class="info-item">
                                    <dt class="info-label">Comunidad:</dt>
                                    <dd class="info-value">{{ $participante->comunidad_tutor_secundario ?? 'N/A' }}</dd>
                                </div>
                                <div class="info-item">
                                    <dt class="info-label">Teléfono:</dt>
                                    <dd class="info-value">{{ $participante->telefono_tutor_secundario ?? 'N/A' }}</dd>
                                </div>
                            </dl>
                        </section>
                        @endif

                        {{-- Participación en Otros Programas --}}
                        <section class="p-5 border border-gray-200 dark:border-slate-700 rounded-xl bg-slate-50/50 dark:bg-slate-800/50">
                            <h3 class="mb-4 text-lg font-semibold text-indigo-700 dark:text-indigo-400">
                                <svg class="inline w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 14.88a2 2 0 00-1.022.547m14.356 0a2 2 0 01-.022 2.473l-2.387.477a6 6 0 01-3.86-.517l-.318-.158a6 6 0 00-3.86-.517l-2.387.477a2 2 0 01-.022-2.473m14.356 0l-.002-.001z"></path></svg>
                                Participación en Otros Programas
                            </h3>
                            <dl class="grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-2">
                                <div class="info-item">
                                    <dt class="info-label">¿Asiste a otros programas fuera de CREA?</dt>
                                    <dd class="info-value {{ $participante->asiste_a_otros_programas ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">{{ $participante->asiste_a_otros_programas ? 'Sí' : 'No' }}</dd>
                                </div>
                                @if($participante->asiste_a_otros_programas)
                                    <div class="info-item">
                                        <dt class="info-label">¿Cuáles programas?</dt>
                                        <dd class="info-value">{{ $participante->otros_programas ?? 'N/A' }}</dd>
                                    </div>
                                    <div class="info-item">
                                        <dt class="info-label">Días que asiste a esos otros programas:</dt>
                                        <dd class="info-value">{{ $participante->dias_asiste_a_otros_programas ? (is_array($participante->dias_asiste_a_otros_programas) ? implode(', ', $participante->dias_asiste_a_otros_programas) : $participante->dias_asiste_a_otros_programas) : 'N/A' }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </section>
                    </div>
                </div>
            @else
                <div class="p-6 text-center text-red-600 bg-red-100 rounded-b-lg dark:bg-red-700/20 dark:text-red-300">
                    No se encontraron datos para este participante.
                </div>
            @endif
        </div>
    </div>

    {{-- Estilos para los items de información --}}
    <style>
        .info-item {
            @apply break-words;
        }
        .info-label {
            @apply block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-0.5;
        }
        .info-value {
            @apply text-sm text-slate-800 dark:text-slate-200;
        }
    </style>
</x-app-layout>
