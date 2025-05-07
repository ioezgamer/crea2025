<x-app-layout>
    <div class="bg-gray-100 font-sans py-12">
        <div class="max-w-4xl mx-auto p-8 bg-white rounded-xl shadow-xl">
            <h1 class="text-3xl font-semibold text-gray-800 text-center mb-8">Formulario de inscripción CREA</h1>

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-800 p-4 rounded-md mb-6" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-800 p-4 rounded-md mb-6" role="alert">
                    <p class="font-bold mb-2">Por favor corrige los siguientes errores:</p>
                    <ul class="list-disc list-inside ml-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('participante.store') }}" method="POST" accept-charset="UTF-8">
                @csrf
                
                <div class="space-y-8">
                    <!-- Fecha de Inscripción y Año -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Fecha de Inscripción</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Fecha de Inscripción -->
                            <div>
                                <label for="fecha_de_inscripcion" class="block text-xs font-medium text-gray-800 mb-1">Fecha de inscripción</label>
                                <input type="date" name="fecha_de_inscripcion" id="fecha_de_inscripcion" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" required>
                            </div>

                            <!-- Año de Inscripción -->
                            <div>
                                <label for="ano_de_inscripcion" class="block text-xs font-medium text-gray-800 mb-1">Año de inscripción</label>
                                <input type="number" name="ano_de_inscripcion" id="ano_de_inscripcion" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm bg-gray-100 text-sm transition duration-150 ease-in-out" readonly required>
                            </div>
                        </div>
                    </div>

                    <!-- Documentos Requeridos -->
                    <div class="border-t border-gray-200 pt-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Documentos Requeridos</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-2">
                            <!-- Partida de Nacimiento -->
                            <div>
                                <label class="block text-xs font-medium text-gray-800 mb-1">Copia de partida de nacimiento</label>
                                <div class="flex items-center space-x-3">
                                    <label class="flex items-center">
                                        <input type="radio" name="partida_de_nacimiento" value="1" class="h-4 w-4 text-indigo-600 border-gray-200 focus:ring-indigo-600" required>
                                        <span class="ml-1.5 text-xs text-gray-600">Sí</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="partida_de_nacimiento" value="0" class="h-4 w-4 text-indigo-600 border-gray-200 focus:ring-indigo-600">
                                        <span class="ml-1.5 text-xs text-gray-600">No</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Boletín o Diploma (2024) -->
                            <div>
                                <label class="block text-xs font-medium text-gray-800 mb-1">Copia boletín o diploma (2024)</label>
                                <div class="flex items-center space-x-3">
                                    <label class="flex items-center">
                                        <input type="radio" name="boletin_o_diploma_2024" value="1" class="h-4 w-4 text-indigo-600 border-gray-200 focus:ring-indigo-600" required>
                                        <span class="ml-1.5 text-xs text-gray-600">Sí</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="boletin_o_diploma_2024" value="0" class="h-4 w-4 text-indigo-600 border-gray-200 focus:ring-indigo-600">
                                        <span class="ml-1.5 text-xs text-gray-600">No</span>
                                    </label>
                                </div>
                            </div>
                            <!-- Cédula Tutor (Sí/No) -->
                            <div>
                                <label class="block text-xs font-medium text-gray-800 mb-1">Copia de cédula del tutor</label>
                                <div class="flex items-center space-x-3">
                                    <label class="flex items-center">
                                        <input type="radio" name="cedula_tutor" value="1" class="h-4 w-4 text-indigo-600 border-gray-200 focus:ring-indigo-600" required>
                                        <span class="ml-1.5 text-xs text-gray-600">Sí</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="cedula_tutor" value="0" class="h-4 w-4 text-indigo-600 border-gray-200 focus:ring-indigo-600">
                                        <span class="ml-1.5 text-xs text-gray-600">No</span>
                                    </label>
                                </div>
                            </div>
                            <!-- Cédula Participante Adulto (Sí/No) -->
                            <div>
                                <label class="block text-xs font-medium text-gray-800 mb-1">Copia de cédula (participante adulto)</label>
                                <div class="flex items-center space-x-3">
                                    <label class="flex items-center">
                                        <input type="radio" name="cedula_participante_adulto" value="1" class="h-4 w-4 text-indigo-600 border-gray-200 focus:ring-indigo-600" required>
                                        <span class="ml-1.5 text-xs text-gray-600">Sí</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="cedula_participante_adulto" value="0" class="h-4 w-4 text-indigo-600 border-gray-200 focus:ring-indigo-600">
                                        <span class="ml-1.5 text-xs text-gray-600">No</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información del Participante -->
                    <div class="border-t border-gray-200 pt-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Información del Participante</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <!-- Tipo de Participante -->
                            <div>
                                <label for="participante" class="block text-xs font-medium text-gray-800 mb-1">Participante</label>
                                <select name="participante" id="participante" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" required>
                                    <option value="" disabled selected>Seleccione</option>
                                    <option value="Primaria">Primaria</option>
                                    <option value="Secundaria">Secundaria</option>
                                    <option value="Preescolar (o menos)">Preescolar (o menos)</option>
                                    <option value="Adulto">Adulto</option>
                                </select>
                            </div>

                            <!-- Primer Nombre (P) -->
                            <div>
                                <label for="primer_nombre_p" class="block text-xs font-medium text-gray-800 mb-1">Primer Nombre</label>
                                <input type="text" name="primer_nombre_p" id="primer_nombre_p" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" required>
                            </div>

                            <!-- Segundo Nombre (P) -->
                            <div>
                                <label for="segundo_nombre_p" class="block text-xs font-medium text-gray-800 mb-1">Segundo Nombre</label>
                                <input type="text" name="segundo_nombre_p" id="segundo_nombre_p" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out">
                            </div>

                            <!-- Primer Apellido (P) -->
                            <div>
                                <label for="primer_apellido_p" class="block text-xs font-medium text-gray-800 mb-1">Primer Apellido</label>
                                <input type="text" name="primer_apellido_p" id="primer_apellido_p" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" required>
                            </div>

                            <!-- Segundo Apellido (P) -->
                            <div>
                                <label for="segundo_apellido_p" class="block text-xs font-medium text-gray-800 mb-1">Segundo Apellido</label>
                                <input type="text" name="segundo_apellido_p" id="segundo_apellido_p" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out">
                            </div>
                            <!-- Cédula Participante Adulto -->
                            <div>
                                <label for="cedula_participante_adulto_str" class="block text-xs font-medium text-gray-800 mb-1">Cédula Participante Adulto</label>
                                <input type="text" name="cedula_participante_adulto_str" id="cedula_participante_adulto_str" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out">
                            </div>
                            <!-- Fecha de Nacimiento (P) -->
                            <div>
                                <label for="fecha_de_nacimiento_p" class="block text-xs font-medium text-gray-800 mb-1">Fecha de Nacimiento</label>
                                <input type="date" name="fecha_de_nacimiento_p" id="fecha_de_nacimiento_p" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" required>
                            </div>

                            <!-- Edad (P) -->
                            <div>
                                <label for="edad_p" class="block text-xs font-medium text-gray-800 mb-1">Edad</label>
                                <input type="number" name="edad_p" id="edad_p" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm bg-gray-100 text-sm transition duration-150 ease-in-out" readonly required>
                            </div>

                            <!-- Género -->
                            <div>
                                <label for="genero" class="block text-xs font-medium text-gray-800 mb-1">Género</label>
                                <select name="genero" id="genero" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" required>
                                    <option value="" disabled selected>Seleccione</option>
                                    <option value="Masculino">Masculino</option>
                                    <option value="Femenino">Femenino</option>
                                </select>
                            </div>

                            <!-- Comunidad (P) -->
                            <div>
                                <label for="comunidad_p" class="block text-xs font-medium text-gray-800 mb-1">Comunidad</label>
                                <select name="comunidad_p" id="comunidad_p" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" required>
                                    <option value="" disabled selected>Seleccione</option>
                                    <option value="Asentamiento">Asentamiento</option>
                                    <option value="Barrio Nuevo">Barrio Nuevo</option>
                                    <option value="Cuascoto">Cuascoto</option>
                                    <option value="Higueral">Higueral</option>
                                    <option value="Juan Dávila">Juan Dávila</option>
                                    <option value="Las Mercedes">Las Mercedes</option>
                                    <option value="Las Salinas">Las Salinas</option>
                                    <option value="Limón 1">Limón 1</option>
                                    <option value="Limón 2">Limón 2</option>
                                    <option value="Ojochal">Ojochal</option>
                                    <option value="San Ignacio">San Ignacio</option>
                                    <option value="Santa Juana">Santa Juana</option>
                                    <option value="Virgen Morena">Virgen Morena</option>
                                </select>
                            </div>

                            <!-- Ciudad (P) -->
                            <div>
                                <label for="ciudad_p" class="block text-xs font-medium text-gray-800 mb-1">Ciudad</label>
                                <input type="text" name="ciudad_p" id="ciudad_p" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" required>
                            </div>

                            <!-- Departamento (P) -->
                            <div>
                                <label for="departamento_p" class="block text-xs font-medium text-gray-800 mb-1">Departamento</label>
                                <input type="text" name="departamento_p" id="departamento_p" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" required>
                            </div>
                        </div>
                    </div>

                    <!-- Información Educativa -->
                    <div class="border-t border-gray-200 pt-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Información Educativa</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <!-- Escuela (P) -->
                            <div>
                                <label for="escuela_p" class="block text-xs font-medium text-gray-800 mb-1">Escuela</label>
                                <input type="text" name="escuela_p" id="escuela_p" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" required>
                            </div>

                            <!-- Comunidad (Escuela) -->
                            <div>
                                <label for="comunidad_escuela" class="block text-xs font-medium text-gray-800 mb-1">Comunidad (Escuela)</label>
                                <select name="comunidad_escuela" id="comunidad_escuela" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" required>
                                    <option value="" disabled selected>Seleccione</option>
                                    <option value="Asentamiento">Asentamiento</option>
                                    <option value="Barrio Nuevo">Barrio Nuevo</option>
                                    <option value="Cuascoto">Cuascoto</option>
                                    <option value="Higueral">Higueral</option>
                                    <option value="Juan Dávila">Juan Dávila</option>
                                    <option value="Las Mercedes">Las Mercedes</option>
                                    <option value="Las Salinas">Las Salinas</option>
                                    <option value="Limón 1">Limón 1</option>
                                    <option value="Limón 2">Limón 2</option>
                                    <option value="Ojochal">Ojochal</option>
                                    <option value="San Ignacio">San Ignacio</option>
                                    <option value="Santa Juana">Santa Juana</option>
                                    <option value="Virgen Morena">Virgen Morena</option>
                                </select>
                            </div>

                            <!-- Grado (P) -->
                            <div>
                                <label for="grado_p" class="block text-xs font-medium text-gray-800 mb-1">Grado</label>
                                <select name="grado_p" id="grado_p" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" required>
                                    <option value="" disabled selected>Seleccione</option>
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                </select>
                            </div>

                            <!-- Turno -->
                            <div>
                                <label for="turno" class="block text-xs font-medium text-gray-800 mb-1">Turno</label>
                                <select name="turno" id="turno" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" required>
                                    <option value="" disabled selected>Seleccione</option>
                                    <option value="Matutino">Matutino</option>
                                    <option value="Vespertino">Vespertino</option>
                                    <option value="Sabatino">Sabatino</option>
                                </select>
                            </div>

                            <!-- ¿Repite Grado? -->
                            <div>
                                <label class="block text-xs font-medium text-gray-800 mb-1">¿Repite Grado?</label>
                                <div class="flex items-center space-x-3">
                                    <label class="flex items-center">
                                        <input type="radio" name="repite_grado" value="1" class="h-4 w-4 text-indigo-600 border-gray-200 focus:ring-indigo-600" required>
                                        <span class="ml-1.5 text-xs text-gray-600">Sí</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="repite_grado" value="0" class="h-4 w-4 text-indigo-600 border-gray-200 focus:ring-indigo-600">
                                        <span class="ml-1.5 text-xs text-gray-600">No</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detalles del Programa -->
                    <div class="border-t border-gray-200 pt-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Detalles del Programa</h3>
                        
                        <!-- Sección de Programas Principales -->
                        <div class="mb-4">
                            <label class="block text-xs font-medium text-gray-800 mb-2">Programa <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                @php
                                    $programaSeleccionados = old('programa', []);
                                @endphp

                                @foreach (['Exito Academico', 'Desarrollo Juvenil', 'Biblioteca'] as $programa)
                                    <label class="flex items-center text-sm text-gray-600">
                                        <input
                                            type="checkbox"
                                            name="programa[]"
                                            value="{{ $programa }}"
                                            @if(in_array($programa, (array)$programaSeleccionados)) checked @endif
                                            class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 mr-2"
                                        >
                                        {{ $programa }}
                                    </label>
                                @endforeach
                            </div>
                            @error('programa')
                                <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sección de Subprogramas -->
                        <div class="mb-4">
                            <label class="block text-xs font-medium text-gray-800 mb-2">Subprogramas <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-2 sm:grid-cols-7 gap-8 px-3 py-1.5">
                                @php
                                    $programasSeleccionados = old('programas', []);
                                @endphp

                                @foreach (['RAC', 'RACREA', 'CLC', 'CLCREA', 'DJ', 'BM', 'CLM'] as $programas)
                                    <label class="flex items-center text-sm text-gray-600">
                                        <input
                                            type="checkbox"
                                            name="programas[]"
                                            value="{{ $programas }}"
                                            @if(in_array($programas, (array)$programasSeleccionados)) checked @endif
                                            class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 mr-2"
                                        >
                                        {{ $programas }}
                                    </label>
                                @endforeach
                            </div>
                            @error('programas')
                                <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sección de Lugar de Encuentro-->
                        <div class="mb-4 w-64">
                            <label class="block text-xs font-medium text-gray-800 mb-1">Lugar de encuentro <span class="text-red-500">*</span></label>
                            <select name="lugar_de_encuentro_del_programa" id="lugar_de_encuentro_del_programa" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600">
                                <option value="">Seleccione...</option>
                                <option value="Virgen Morena" {{ old('lugar_de_encuentro_del_programa') == 'Virgen Morena' ? 'selected' : '' }}>Virgen Morena</option>
                                <option value="Las Salinas" {{ old('lugar_de_encuentro_del_programa') == 'Las Salinas' ? 'selected' : '' }}>Las Salinas</option>
                                <option value="CREA" {{ old('lugar_de_encuentro_del_programa') == 'CREA' ? 'selected' : '' }}>CREA</option>
                                <option value="Ojochal" {{ old('lugar_de_encuentro_del_programa') == 'Ojochal' ? 'selected' : '' }}>Ojochal</option>
                                <option value="Las Mercedes" {{ old('lugar_de_encuentro_del_programa') == 'Las Mercedes' ? 'selected' : '' }}>Las Mercedes</option>
                                <option value="Limón 1" {{ old('lugar_de_encuentro_del_programa') == 'Limón 1' ? 'selected' : '' }}>Limón 1</option>
                                <option value="Asentamiento" {{ old('lugar_de_encuentro_del_programa') == 'Asentamiento' ? 'selected' : '' }}>Asentamiento</option>
                            </select>
                            @error('lugar_de_encuentro_del_programa')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Días de Asistencia al Programa -->
                        <div class="lg:col-span-2">
                            <label class="block text-xs font-medium text-gray-800 mb-2">Días de Asistencia Esperados <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
                                @php $diasSeleccionados = old('dias_de_asistencia_al_programa', []); @endphp
                                @foreach (['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'] as $dia)
                                    <label class="flex items-center text-sm text-gray-600">
                                        <input type="checkbox" name="dias_de_asistencia_al_programa[]" value="{{ $dia }}" @if(in_array($dia, $diasSeleccionados)) checked @endif class="dias-asistencia h-4 w-4 text-indigo-600 border-gray-300 rounded-full focus:ring-indigo-500 mr-2"> {{ $dia }}
                                    </label>
                                @endforeach
                            </div>
                            <input type="hidden" name="dias_de_asistencia_al_programa_count" id="dias_de_asistencia_al_programa" value="0">
                            <p class="text-sm text-gray-500 mt-1">Total días: <span id="total-dias-asistencia">0</span></p>
                            @error('dias_de_asistencia_al_programa')
                                <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Tutor Principal Section -->
                    <div class="border-t border-gray-200 pt-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Tutor Principal</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

                            <!-- Tutor Principal -->
                            <div>
                                <label for="tutor_principal" class="block text-xs font-medium text-gray-800 mb-1">Tipo de Tutor</label>
                                <select name="tutor_principal" id="tutor_principal" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" {{ $errors->has('tutor_principal') ? 'aria-invalid=true' : '' }}>
                                    <option value="" disabled {{ old('tutor_principal', isset($participante) ? $participante->tutor_principal : '') == '' ? 'selected' : '' }}>Seleccione</option>
                                    @foreach ($tipos_tutor as $tipo)
                                        <option value="{{ $tipo }}" {{ old('tutor_principal', isset($participante) ? $participante->tutor_principal : '') == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                                    @endforeach
                                </select>
                                @error('tutor_principal')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Nombres y Apellidos (Tutor Principal) -->
                            <div>
                                <label for="nombres_y_apellidos_tutor_principal" class="block text-xs font-medium text-gray-800 mb-1">Nombres y Apellidos</label>
                                <input type="text" name="nombres_y_apellidos_tutor_principal" id="nombres_y_apellidos_tutor_principal" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" required>
                            </div>

                            <!-- Número de Cédula (Tutor) -->
                            <div>
                                <label for="numero_de_cedula_tutor" class="block text-xs font-medium text-gray-800 mb-1">Número de Cédula</label>
                                <input type="text" name="numero_de_cedula_tutor" id="numero_de_cedula_tutor" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" required>
                            </div>

                            <!-- Comunidad (Tutor) -->
                            <div>
                                <label for="comunidad_tutor" class="block text-xs font-medium text-gray-800 mb-1">Comunidad</label>
                                <select name="comunidad_tutor" id="comunidad_tutor" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" {{ $errors->has('comunidad_tutor') ? 'aria-invalid=true' : '' }}>
                                    <option value="" disabled {{ old('comunidad_tutor', isset($participante) ? $participante->comunidad_tutor : '') == '' ? 'selected' : '' }}>Seleccione</option>
                                    @foreach ($comunidades as $comunidad)
                                        <option value="{{ $comunidad }}" {{ old('comunidad_tutor', isset($participante) ? $participante->comunidad_tutor : '') == $comunidad ? 'selected' : '' }}>{{ $comunidad }}</option>
                                    @endforeach
                                </select>
                                @error('comunidad_tutor')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Dirección (Tutor) -->
                            <div>
                                <label for="direccion_tutor" class="block text-xs font-medium text-gray-800 mb-1">Dirección</label>
                                <textarea type="text" name="direccion_tutor" id="direccion_tutor" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" ></textarea>
                            </div>

                            <!-- Teléfono (Tutor) -->
                            <div>
                                <label for="telefono_tutor" class="block text-xs font-medium text-gray-800 mb-1">Teléfono</label>
                                <input type="text" name="telefono_tutor" id="telefono_tutor" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" >
                            </div>

                            <!-- Sector Económico (Tutor) -->
                            <div>
                                <label for="sector_economico_tutor" class="block text-xs font-medium text-gray-800 mb-1">Sector económico</label>
                                <select name="sector_economico_tutor" id="sector_economico_tutor" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" {{ $errors->has('sector_economico_tutor') ? 'aria-invalid=true' : '' }}>
                                    <option value="" disabled {{ old('sector_economico_tutor', isset($participante) ? $participante->sector_economico : '') == '' ? 'selected' : '' }}>Seleccione</option>
                                    @foreach ($sector_economico as $sector)
                                        <option value="{{ $sector }}" {{ old('tutor_principal', isset($participante) ? $participante->sector_economico : '') == $sector ? 'selected' : '' }}>{{ $sector }}</option>
                                    @endforeach
                                </select>
                                @error('sector_economico_tutor')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Nivel de Educación Formal Adquirido (Tutor) -->
                            <div>
                                <label for="nivel_de_educacion_formal_adquirido_tutor" class="block text-xs font-medium text-gray-800 mb-1">Nivel de Educación</label>
                                <select name="nivel_de_educacion_formal_adquirido_tutor" id="nivel_de_educacion_formal_adquirido_tutor" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out"  {{ $errors->has('nivel_de_educacion_formal_adquirido_tutor') ? 'aria-invalid=true' : '' }}>
                                    <option value="" disabled {{ old('nivel_de_educacion_formal_adquirido_tutor', isset($participante) ? $participante->nivel_educacion : '') == '' ? 'selected' : '' }}>Seleccione</option>
                                    @foreach ($nivel_educacion as $nivel)
                                        <option value="{{ $nivel }}" {{ old('tutor_principal', isset($participante) ? $participante->nivel_educacion : '') == $nivel ? 'selected' : '' }}>{{ $nivel }}</option>
                                    @endforeach
                                </select>
                                @error('nivel_de_educacion_formal_adquirido_tutor')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Expectativas del Programa (Tutor Principal) -->
                            <div>
                                <label for="expectativas_del_programa_tutor_principal" class="block text-xs font-medium text-gray-800 mb-1">Expectativas del Programa</label>
                                <textarea name="expectativas_del_programa_tutor_principal" id="expectativas_del_programa_tutor_principal" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" ></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Tutor Secundario Section -->
                    <div class="border-t border-gray-200 pt-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Tutor Secundario</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <!-- Tutor Secundario -->
                            <div>
                                <label for="tutor_secundario" class="block text-xs font-medium text-gray-800 mb-1">Tipo de Tutor</label>
                                <select name="tutor_secundario" id="tutor_secundario" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out">
                                    <option value="" selected>Seleccione</option>
                                    <option value="Madre">Madre</option>
                                    <option value="Padre">Padre</option>
                                    <option value="Abuelo">Abuelo</option>
                                    <option value="Abuela">Abuela</option>
                                    <option value="Tío">Tío</option>
                                    <option value="Tía">Tía</option>
                                    <option value="Hermano">Hermano</option>
                                    <option value="Hermana">Hermana</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>

                            <!-- Nombres y Apellidos (Tutor Secundario) -->
                            <div>
                                <label for="nombres_y_apellidos_tutor_secundario" class="block text-xs font-medium text-gray-800 mb-1">Nombres y Apellidos</label>
                                <input type="text" name="nombres_y_apellidos_tutor_secundario" id="nombres_y_apellidos_tutor_secundario" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out">
                            </div>

                            <!-- Número de Cédula (Tutor Secundario) -->
                            <div>
                                <label for="numero_de_cedula_tutor_secundario" class="block text-xs font-medium text-gray-800 mb-1">Número de Cédula</label>
                                <input type="text" name="numero_de_cedula_tutor_secundario" id="numero_de_cedula_tutor_secundario" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out">
                            </div>

                            <!-- Comunidad (Tutor Secundario) -->
                            <div>
                                <label for="comunidad_tutor_secundario" class="block text-xs font-medium text-gray-800 mb-1">Comunidad</label>
                                <select name="comunidad_tutor_secundario" id="comunidad_tutor_secundario" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" {{ $errors->has('comunidad_tutor_secundario') ? 'aria-invalid=true' : '' }}>
                                    <option value="" disabled {{ old('comunidad_tutor_secundario', isset($participante) ? $participante->comunidad_tutor : '') == '' ? 'selected' : '' }}>Seleccione</option>
                                    @foreach ($comunidades as $comunidad)
                                        <option value="{{ $comunidad }}" {{ old('comunidad_tutor_secundario', isset($participante) ? $participante->comunidad_tutor : '') == $comunidad ? 'selected' : '' }}>{{ $comunidad }}</option>
                                    @endforeach
                                </select>
                                @error('comunidad_tutor_secundario')
                                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Teléfono (Tutor Secundario) -->
                            <div>
                                <label for="telefono_tutor_secundario" class="block text-xs font-medium text-gray-800 mb-1">Teléfono</label>
                                <input type="text" name="telefono_tutor_secundario" id="telefono_tutor_secundario" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out">
                            </div>
                        </div>
                    </div>

                    <!-- Otros Programas Section -->
                    <div class="border-t border-gray-200 pt-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Otros Programas</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <!-- ¿Asiste a Otros Programas? -->
                            <div>
                                <label class="block text-xs font-medium text-gray-800 mb-1">¿Asiste a Otros Programas?</label>
                                <div class="flex items-center space-x-3">
                                    <label class="flex items-center">
                                        <input type="radio" name="asiste_a_otros_programas" value="1" class="asiste-otros-radio h-4 w-4 text-indigo-600 border-gray-200 focus:ring-indigo-600" required>
                                        <span class="ml-1.5 text-xs text-gray-600">Sí</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="asiste_a_otros_programas" value="0" class="asiste-otros-radio h-4 w-4 text-indigo-600 border-gray-200 focus:ring-indigo-600" checked>
                                        <span class="ml-1.5 text-xs text-gray-600">No</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Otros Programas -->
                            <div id="otros-programas-section" class="hidden">
                                <label for="otros_programas" class="block text-xs font-medium text-gray-800 mb-1">Otros Programas</label>
                                <input type="text" name="otros_programas" id="otros_programas" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out">
                            </div>

                            <!-- Días que Asiste a Otros Programas -->
                            <div id="dias-otros-section" class="hidden lg:col-span-2">
                                <label class="block text-xs font-medium text-gray-800 mb-1">Días que Asiste a Otros Programas</label>
                                <div class="flex flex-wrap gap-3">
                                    <label class="flex items-center">
                                        <input type="checkbox" value="Lunes" class="dias-otros h-4 w-4 text-indigo-600 border-gray-200 focus:ring-indigo-600">
                                        <span class="ml-1.5 text-xs text-gray-600">Lunes</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" value="Martes" class="dias-otros h-4 w-4 text-indigo-600 border-gray-200 focus:ring-indigo-600">
                                        <span class="ml-1.5 text-xs text-gray-600">Martes</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" value="Miércoles" class="dias-otros h-4 w-4 text-indigo-600 border-gray-200 focus:ring-indigo-600">
                                        <span class="ml-1.5 text-xs text-gray-600">Miércoles</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" value="Jueves" class="dias-otros h-4 w-4 text-indigo-600 border-gray-200 focus:ring-indigo-600">
                                        <span class="ml-1.5 text-xs text-gray-600">Jueves</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" value="Viernes" class="dias-otros h-4 w-4 text-indigo-600 border-gray-200 focus:ring-indigo-600">
                                        <span class="ml-1.5 text-xs text-gray-600">Viernes</span>
                                    </label>
                                </div>
                                <input type="hidden" name="dias_asiste_a_otros lottos_programas" id="dias_asiste_a_otros_programas" value="0">
                                <p class="text-xs text-gray-500 mt-1">Total días: <span id="total-dias-otros">0</span></p>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="pt-8 flex justify-between items-center">
                        <x-boton-regresar onclick="window.location.href='{{ route('participante.index') }}'">Volver</x-boton-regresar>
                        <x-boton-flotante type="submit">Registrar Participante</x-boton-flotante>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript for Functionality -->
    <script>
        // Calculate Año de Inscripción based on Fecha de Inscripción
        document.getElementById('fecha_de_inscripcion').addEventListener('change', function () {
            const fechaInscripcion = new Date(this.value);
            const anoInscripcion = fechaInscripcion.getFullYear();
            document.getElementById('ano_de_inscripcion').value = anoInscripcion;
        });

        // Calculate Edad based on Fecha de Nacimiento
        document.getElementById('fecha_de_nacimiento_p').addEventListener('change', function () {
            const birthDate = new Date(this.value);
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            document.getElementById('edad_p').value = age >= 0 ? age : 0;
        });

        // Días de Asistencia al Programa
        document.querySelectorAll('.dias-asistencia').forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                const checkedCount = document.querySelectorAll('.dias-asistencia:checked').length;
                document.getElementById('dias_de_asistencia_al_programa').value = checkedCount;
                document.getElementById('total-dias-asistencia').textContent = checkedCount;
            });
        });

        // Toggle visibility of Otros Programas and Días que Asiste a Otros Programas
        document.querySelectorAll('.asiste-otros-radio').forEach(radio => {
            radio.addEventListener('change', function () {
                const otrosProgramasSection = document.getElementById('otros-programas-section');
                const diasOtrosSection = document.getElementById('dias-otros-section');
                if (this.value === '1') {
                    otrosProgramasSection.classList.remove('hidden');
                    diasOtrosSection.classList.remove('hidden');
                } else {
                    otrosProgramasSection.classList.add('hidden');
                    diasOtrosSection.classList.add('hidden');
                    document.getElementById('otros_programas').value = '';
                    document.querySelectorAll('.dias-otros').forEach(checkbox => checkbox.checked = false);
                    document.getElementById('dias_asiste_a_otros_programas').value = '0';
                    document.getElementById('total-dias-otros').textContent = '0';
                }
            });
        });

        // Días que Asiste a Otros Programas
        document.querySelectorAll('.dias-otros').forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                const checkedCount = document.querySelectorAll('.dias-otros:checked').length;
                document.getElementById('dias_asiste_a_otros_programas').value = checkedCount;
                document.getElementById('total-dias-otros').textContent = checkedCount;
            });
        });
    </script>
</x-app-layout>