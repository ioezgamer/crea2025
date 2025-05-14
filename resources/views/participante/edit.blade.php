<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Editar Participante
            </h2>
            <a href="{{ route('participante.index') }}" class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="p-6">
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

                    <form action="{{ route('participante.update', $participante) }}" method="POST" accept-charset="UTF-8">
                        @csrf
                        @method('PUT')

                        <div class="space-y-8">
                            <!-- Fecha de Inscripción y Año -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-3">Fecha de Inscripción</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="fecha_de_inscripcion" class="block text-xs font-medium text-gray-800 mb-1">Fecha de inscripción</label>
                                        <input type="date" name="fecha_de_inscripcion" id="fecha_de_inscripcion" value="{{ old('fecha_de_inscripcion', $participante->fecha_de_inscripcion) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" required>
                                        @error('fecha_de_inscripcion')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="ano_de_inscripcion" class="block text-xs font-medium text-gray-800 mb-1">Año de inscripción</label>
                                        <input type="number" name="ano_de_inscripcion" id="ano_de_inscripcion" value="{{ old('ano_de_inscripcion', $participante->ano_de_inscripcion) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm bg-gray-100 text-sm transition duration-150 ease-in-out" readonly required>
                                        @error('ano_de_inscripcion')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Documentos Requeridos -->
                            <div class="border-t border-gray-200 pt-4">
                                <h3 class="text-lg font-semibold text-gray-800 mb-3">Documentos Requeridos</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-2">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-800 mb-1">Copia de partida de nacimiento</label>
                                        <div class="flex items-center space-x-3">
                                            <label class="flex items-center">
                                                <input type="radio" name="partida_de_nacimiento" value="1" class="h-4 w-4 text-indigo-600 border-gray-200 focus:ring-indigo-600" {{ old('partida_de_nacimiento', $participante->partida_de_nacimiento) ? 'checked' : '' }} required>
                                                <span class="ml-1.5 text-xs text-gray-600">Sí</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="radio" name="partida_de_nacimiento" value="0" class="h-4 w-4 text-indigo-600 border-gray-200 focus:ring-indigo-600" {{ old('partida_de_nacimiento', $participante->partida_de_nacimiento) ? '' : 'checked' }}>
                                                <span class="ml-1.5 text-xs text-gray-600">No</span>
                                            </label>
                                        </div>
                                        @error('partida_de_nacimiento')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-800 mb-1">Copia boletín o diploma (2024)</label>
                                        <div class="flex items-center space-x-3">
                                            <label class="flex items-center">
                                                <input type="radio" name="boletin_o_diploma_2024" value="1" class="h-4 w-4 text-indigo-600 border-gray-200 focus:ring-indigo-600" {{ old('boletin_o_diploma_2024', $participante->boletin_o_diploma_2024) ? 'checked' : '' }} required>
                                                <span class="ml-1.5 text-xs text-gray-600">Sí</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="radio" name="boletin_o_diploma_2024" value="0" class="h-4 w-4 text-indigo-600 border-gray-200 focus:ring-indigo-600" {{ old('boletin_o_diploma_2024', $participante->boletin_o_diploma_2024) ? '' : 'checked' }}>
                                                <span class="ml-1.5 text-xs text-gray-600">No</span>
                                            </label>
                                        </div>
                                        @error('boletin_o_diploma_2024')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-800 mb-1">Copia de cédula del tutor</label>
                                        <div class="flex items-center space-x-3">
                                            <label class="flex items-center">
                                                <input type="radio" name="cedula_tutor" value="1" class="h-4 w-4 text-indigo-600 border-gray-200 focus:ring-indigo-600" {{ old('cedula_tutor', $participante->cedula_tutor) ? 'checked' : '' }} required>
                                                <span class="ml-1.5 text-xs text-gray-600">Sí</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="radio" name="cedula_tutor" value="0" class="h-4 w-4 text-indigo-600 border-gray-200 focus:ring-indigo-600" {{ old('cedula_tutor', $participante->cedula_tutor) ? '' : 'checked' }}>
                                                <span class="ml-1.5 text-xs text-gray-600">No</span>
                                            </label>
                                        </div>
                                        @error('cedula_tutor')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-800 mb-1">Copia de cédula (participante adulto)</label>
                                        <div class="flex items-center space-x-3">
                                            <label class="flex items-center">
                                                <input type="radio" name="cedula_participante_adulto" value="1" class="h-4 w-4 text-indigo-600 border-gray-200 focus:ring-indigo-600" {{ old('cedula_participante_adulto', $participante->cedula_participante_adulto) ? 'checked' : '' }} required>
                                                <span class="ml-1.5 text-xs text-gray-600">Sí</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="radio" name="cedula_participante_adulto" value="0" class="h-4 w-4 text-indigo-600 border-gray-200 focus:ring-indigo-600" {{ old('cedula_participante_adulto', $participante->cedula_participante_adulto) ? '' : 'checked' }}>
                                                <span class="ml-1.5 text-xs text-gray-600">No</span>
                                            </label>
                                        </div>
                                        @error('cedula_participante_adulto')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Información del Participante -->
                            <div class="border-t border-gray-200 pt-4">
                                <h3 class="text-lg font-semibold text-gray-800 mb-3">Información del Participante</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    <div>
                                        <label for="participante" class="block text-xs font-medium text-gray-800 mb-1">Participante</label>
                                        <select name="participante" id="participante" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" required>
                                            <option value="" disabled>Seleccione</option>
                                            <option value="primaria" {{ old('participante', $participante->participante) == 'Primaria' ? 'selected' : '' }}>Primaria</option>
                                            <option value="secundaria" {{ old('participante', $participante->participante) == 'Secundaria' ? 'selected' : '' }}>Secundaria</option>
                                            <option value="preescolar" {{ old('participante', $participante->participante) == 'Preescolar' ? 'selected' : '' }}>Preescolar (o menos)</option>
                                            <option value="adulto" {{ old('participante', $participante->participante) == 'Adulto' ? 'selected' : '' }}>Adulto</option>
                                        </select>
                                        @error('participante')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="primer_nombre_p" class="block text-xs font-medium text-gray-800 mb-1">Primer Nombre</label>
                                        <input type="text" name="primer_nombre_p" id="primer_nombre_p" value="{{ old('primer_nombre_p', $participante->primer_nombre_p) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" required>
                                        @error('primer_nombre_p')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="segundo_nombre_p" class="block text-xs font-medium text-gray-800 mb-1">Segundo Nombre</label>
                                        <input type="text" name="segundo_nombre_p" id="segundo_nombre_p" value="{{ old('segundo_nombre_p', $participante->segundo_nombre_p) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out">
                                        @error('segundo_nombre_p')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="primer_apellido_p" class="block text-xs font-medium text-gray-800 mb-1">Primer Apellido</label>
                                        <input type="text" name="primer_apellido_p" id="primer_apellido_p" value="{{ old('primer_apellido_p', $participante->primer_apellido_p) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" required>
                                        @error('primer_apellido_p')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="segundo_apellido_p" class="block text-xs font-medium text-gray-800 mb-1">Segundo Apellido</label>
                                        <input type="text" name="segundo_apellido_p" id="segundo_apellido_p" value="{{ old('segundo_apellido_p', $participante->segundo_apellido_p) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out">
                                        @error('segundo_apellido_p')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="cedula_participante_adulto_str" class="block text-xs font-medium text-gray-800 mb-1">Cédula Participante Adulto</label>
                                        <input type="text" name="cedula_participante_adulto_str" id="cedula_participante_adulto_str" value="{{ old('cedula_participante_adulto_str', $participante->cedula_participante_adulto_str) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out">
                                        @error('cedula_participante_adulto_str')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="fecha_de_nacimiento_p" class="block text-xs font-medium text-gray-800 mb-1">Fecha de Nacimiento</label>
                                        <input type="date" name="fecha_de_nacimiento_p" id="fecha_de_nacimiento_p" value="{{ old('fecha_de_nacimiento_p', $participante->fecha_de_nacimiento_p) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" required>
                                        @error('fecha_de_nacimiento_p')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="edad_p" class="block text-xs font-medium text-gray-800 mb-1">Edad</label>
                                        <input type="number" name="edad_p" id="edad_p" value="{{ old('edad_p', $participante->edad_p) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm bg-gray-100 text-sm transition duration-150 ease-in-out" readonly required>
                                        @error('edad_p')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="genero" class="block text-xs font-medium text-gray-800 mb-1">Género</label>
                                        <select name="genero" id="genero" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" required>
                                            <option value="" disabled>Seleccione</option>
                                            <option value="Masculino" {{ old('genero', $participante->genero) == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                            <option value="Femenino" {{ old('genero', $participante->genero) == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                                        </select>
                                        @error('genero')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="comunidad_p" class="block text-xs font-medium text-gray-800 mb-1">Comunidad</label>
                                        <select name="comunidad_p" id="comunidad_p" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" required>
                                            <option value="" disabled>Seleccione</option>
                                            <option value="Asentamiento" {{ old('comunidad_p', $participante->comunidad_p) == 'Asentamiento' ? 'selected' : '' }}>Asentamiento</option>
                                            <option value="Barrio Nuevo" {{ old('comunidad_p', $participante->comunidad_p) == 'Barrio Nuevo' ? 'selected' : '' }}>Barrio Nuevo</option>
                                            <option value="Cuascoto" {{ old('comunidad_p', $participante->comunidad_p) == 'Cuascoto' ? 'selected' : '' }}>Cuascoto</option>
                                            <option value="Higueral" {{ old('comunidad_p', $participante->comunidad_p) == 'Higueral' ? 'selected' : '' }}>Higueral</option>
                                            <option value="Juan Dávila" {{ old('comunidad_p', $participante->comunidad_p) == 'Juan Dávila' ? 'selected' : '' }}>Juan Dávila</option>
                                            <option value="Las Mercedes" {{ old('comunidad_p', $participante->comunidad_p) == 'Las Mercedes' ? 'selected' : '' }}>Las Mercedes</option>
                                            <option value="Las Salinas" {{ old('comunidad_p', $participante->comunidad_p) == 'Las Salinas' ? 'selected' : '' }}>Las Salinas</option>
                                            <option value="Limón 1" {{ old('comunidad_p', $participante->comunidad_p) == 'Limón 1' ? 'selected' : '' }}>Limón 1</option>
                                            <option value="Limón 2" {{ old('comunidad_p', $participante->comunidad_p) == 'Limón 2' ? 'selected' : '' }}>Limón 2</option>
                                            <option value="Ojochal" {{ old('comunidad_p', $participante->comunidad_p) == 'Ojochal' ? 'selected' : '' }}>Ojochal</option>
                                            <option value="San Ignacio" {{ old('comunidad_p', $participante->comunidad_p) == 'San Ignacio' ? 'selected' : '' }}>San Ignacio</option>
                                            <option value="Santa Juana" {{ old('comunidad_p', $participante->comunidad_p) == 'Santa Juana' ? 'selected' : '' }}>Santa Juana</option>
                                            <option value="Virgen Morena" {{ old('comunidad_p', $participante->comunidad_p) == 'Virgen Morena' ? 'selected' : '' }}>Virgen Morena</option>
                                        </select>
                                        @error('comunidad_p')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="ciudad_p" class="block text-xs font-medium text-gray-800 mb-1">Ciudad</label>
                                        <input type="text" name="ciudad_p" id="ciudad_p" value="{{ old('ciudad_p', $participante->ciudad_p) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" required>
                                        @error('ciudad_p')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="departamento_p" class="block text-xs font-medium text-gray-800 mb-1">Departamento</label>
                                        <input type="text" name="departamento_p" id="departamento_p" value="{{ old('departamento_p', $participante->departamento_p) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" required>
                                        @error('departamento_p')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Información Educativa -->
                            <div class="border-t border-gray-200 pt-4">
                                <h3 class="text-lg font-semibold text-gray-800 mb-3">Información Educativa</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    <div>
                                        <label for="escuela_p" class="block text-xs font-medium text-gray-800 mb-1">Escuela</label>
                                        <input type="text" name="escuela_p" id="escuela_p" value="{{ old('escuela_p', $participante->escuela_p) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" required>
                                        @error('escuela_p')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="comunidad_escuela" class="block text-xs font-medium text-gray-800 mb-1">Comunidad (Escuela)</label>
                                        <select name="comunidad_escuela" id="comunidad_escuela" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" required>
                                            <option value="" disabled>Seleccione</option>
                                            <option value="Asentamiento" {{ old('comunidad_escuela', $participante->comunidad_escuela) == 'Asentamiento' ? 'selected' : '' }}>Asentamiento</option>
                                            <option value="Barrio Nuevo" {{ old('comunidad_escuela', $participante->comunidad_escuela) == 'Barrio Nuevo' ? 'selected' : '' }}>Barrio Nuevo</option>
                                            <option value="Cuascoto" {{ old('comunidad_escuela', $participante->comunidad_escuela) == 'Cuascoto' ? 'selected' : '' }}>Cuascoto</option>
                                            <option value="Higueral" {{ old('comunidad_escuela', $participante->comunidad_escuela) == 'Higueral' ? 'selected' : '' }}>Higueral</option>
                                            <option value="Juan Dávila" {{ old('comunidad_escuela', $participante->comunidad_escuela) == 'Juan Dávila' ? 'selected' : '' }}>Juan Dávila</option>
                                            <option value="Las Mercedes" {{ old('comunidad_escuela', $participante->comunidad_escuela) == 'Las Mercedes' ? 'selected' : '' }}>Las Mercedes</option>
                                            <option value="Las Salinas" {{ old('comunidad_escuela', $participante->comunidad_escuela) == 'Las Salinas' ? 'selected' : '' }}>Las Salinas</option>
                                            <option value="Limón 1" {{ old('comunidad_escuela', $participante->comunidad_escuela) == 'Limón 1' ? 'selected' : '' }}>Limón 1</option>
                                            <option value="Limón 2" {{ old('comunidad_escuela', $participante->comunidad_escuela) == 'Limón 2' ? 'selected' : '' }}>Limón 2</option>
                                            <option value="Ojochal" {{ old('comunidad_escuela', $participante->comunidad_escuela) == 'Ojochal' ? 'selected' : '' }}>Ojochal</option>
                                            <option value="San Ignacio" {{ old('comunidad_escuela', $participante->comunidad_escuela) == 'San Ignacio' ? 'selected' : '' }}>San Ignacio</option>
                                            <option value="Santa Juana" {{ old('comunidad_escuela', $participante->comunidad_escuela) == 'Santa Juana' ? 'selected' : '' }}>Santa Juana</option>
                                            <option value="Virgen Morena" {{ old('comunidad_escuela', $participante->comunidad_escuela) == 'Virgen Morena' ? 'selected' : '' }}>Virgen Morena</option>
                                        </select>
                                        @error('comunidad_escuela')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="grado_p" class="block text-xs font-medium text-gray-800 mb-1">Grado</label>
                                        <select name="grado_p" id="grado_p" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" required>
                                            <option value="" disabled>Seleccione</option>
                                            @foreach (range(0, 11) as $grado)
                                                <option value="{{ $grado }}" {{ old('grado_p', $participante->grado_p) == $grado ? 'selected' : '' }}>{{ $grado }}</option>
                                            @endforeach
                                        </select>
                                        @error('grado_p')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="turno" class="block text-xs font-medium text-gray-800 mb-1">Turno</label>
                                        <select name="turno" id="turno" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" required>
                                            <option value="" disabled>Seleccione</option>
                                            <option value="Mañana" {{ old('turno', $participante->turno) == 'Mañana' ? 'selected' : '' }}>Matutino</option>
                                            <option value="Tarde" {{ old('turno', $participante->turno) == 'Tarde' ? 'selected' : '' }}>Vespertino</option>
                                            <option value="Noche" {{ old('turno', $participante->turno) == 'Noche' ? 'selected' : '' }}>Sabatino</option>
                                        </select>
                                        @error('turno')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-800 mb-1">¿Repite Grado?</label>
                                        <div class="flex items-center space-x-3">
                                            <label class="flex items-center">
                                                <input type="radio" name="repite_grado" value="1" class="h-4 w-4 text-indigo-600 border-gray-200 focus:ring-indigo-600" {{ old('repite_grado', $participante->repite_grado) ? 'checked' : '' }} required>
                                                <span class="ml-1.5 text-xs text-gray-600">Sí</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="radio" name="repite_grado" value="0" class="h-4 w-4 text-indigo-600 border-gray-200 focus:ring-indigo-600" {{ old('repite_grado', $participante->repite_grado) ? '' : 'checked' }}>
                                                <span class="ml-1.5 text-xs text-gray-600">No</span>
                                            </label>
                                        </div>
                                        @error('repite_grado')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Detalles del Programa -->
                            <div class="border-t border-gray-200 pt-4">
                                <h3 class="text-lg font-semibold text-gray-800 mb-3">Detalles del Programa</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-800 mb-2">Programa <span class="text-red-500">*</span></label>
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                            @php
                                                $programaSeleccionados = old('programa', explode(',', $participante->programa));
                                            @endphp
                                            @foreach (['Exito Academico', 'Desarrollo Juvenil', 'Biblioteca'] as $programa)
                                                <label class="flex items-center text-sm text-gray-600">
                                                    <input type="checkbox" name="programa[]" value="{{ $programa }}" @if(in_array($programa, $programaSeleccionados)) checked @endif class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 mr-2">
                                                    {{ $programa }}
                                                </label>
                                            @endforeach
                                        </div>
                                        @error('programa')
                                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-800 mb-2">Subprogramas <span class="text-red-500">*</span></label>
                                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                            @php
                                                $programasSeleccionados = old('programas', explode(',', $participante->programas));
                                            @endphp
                                            @foreach (['RAC', 'RACREA', 'CLC', 'CLCREA', 'DJ', 'BM', 'CLM'] as $subprograma)
                                                <label class="flex items-center text-sm text-gray-600">
                                                    <input type="checkbox" name="programas[]" value="{{ $subprograma }}" @if(in_array($subprograma, $programasSeleccionados)) checked @endif class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 mr-2">
                                                    {{ $subprograma }}
                                                </label>
                                            @endforeach
                                        </div>
                                        @error('programas')
                                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="lugar_de_encuentro_del_programa" class="block text-xs font-medium text-gray-800 mb-1">Lugar de encuentro <span class="text-red-500">*</span></label>
                                        <select name="lugar_de_encuentro_del_programa" id="lugar_de_encuentro_del_programa" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" required>
                                            <option value="" disabled>Seleccione...</option>
                                            <option value="Virgen Morena" {{ old('lugar_de_encuentro_del_programa', $participante->lugar_de_encuentro_del_programa) == 'Virgen Morena' ? 'selected' : '' }}>Virgen Morena</option>
                                            <option value="Las Salinas" {{ old('lugar_de_encuentro_del_programa', $participante->lugar_de_encuentro_del_programa) == 'Las Salinas' ? 'selected' : '' }}>Las Salinas</option>
                                            <option value="CREA" {{ old('lugar_de_encuentro_del_programa', $participante->lugar_de_encuentro_del_programa) == 'CREA' ? 'selected' : '' }}>CREA</option>
                                            <option value="Ojochal" {{ old('lugar_de_encuentro_del_programa', $participante->lugar_de_encuentro_del_programa) == 'Ojochal' ? 'selected' : '' }}>Ojochal</option>
                                            <option value="Las Mercedes" {{ old('lugar_de_encuentro_del_programa', $participante->lugar_de_encuentro_del_programa) == 'Las Mercedes' ? 'selected' : '' }}>Las Mercedes</option>
                                            <option value="Limón 1" {{ old('lugar_de_encuentro_del_programa', $participante->lugar_de_encuentro_del_programa) == 'Limón 1' ? 'selected' : '' }}>Limón 1</option>
                                            <option value="Asentamiento" {{ old('lugar_de_encuentro_del_programa', $participante->lugar_de_encuentro_del_programa) == 'Asentamiento' ? 'selected' : '' }}>Asentamiento</option>
                                        </select>
                                        @error('lugar_de_encuentro_del_programa')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-800 mb-2">Días de Asistencia Esperados <span class="text-red-500">*</span></label>
                                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                            @php
                                                $diasSeleccionados = old('dias_de_asistencia_al_programa', explode(',', $participante->dias_de_asistencia_al_programa));
                                            @endphp
                                            @foreach (['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'] as $dia)
                                                <label class="flex items-center text-sm text-gray-600">
                                                    <input type="checkbox" name="dias_de_asistencia_al_programa[]" value="{{ $dia }}" @if(in_array($dia, $diasSeleccionados)) checked @endif class="dias-asistencia h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 mr-2">
                                                    {{ $dia }}
                                                </label>
                                            @endforeach
                                        </div>
                                        <input type="hidden" name="dias_de_asistencia_al_programa_count" id="dias_de_asistencia_al_programa" value="{{ count($diasSeleccionados) }}" required>
                                        <p class="text-sm text-gray-500 mt-1">Total días: <span id="total-dias-asistencia">{{ count($diasSeleccionados) }}</span></p>
                                        @error('dias_de_asistencia_al_programa')
                                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Tutor Principal -->
                            <div class="border-t border-gray-200 pt-4">
                                <h3 class="text-lg font-semibold text-gray-800 mb-3">Tutor Principal</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    <div>
                                        <label for="tutor_principal" class="block text-xs font-medium text-gray-800 mb-1">Tipo de Tutor</label>
                                        <select name="tutor_principal" id="tutor_principal" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" required>
                                            <option value="" disabled>Seleccione</option>
                                            <option value="Madre" {{ old('tutor_principal', $participante->tutor_principal) == 'Madre' ? 'selected' : '' }}>Madre</option>
                                            <option value="Padre" {{ old('tutor_principal', $participante->tutor_principal) == 'Padre' ? 'selected' : '' }}>Padre</option>
                                            <option value="Abuelo" {{ old('tutor_principal', $participante->tutor_principal) == 'Abuelo' ? 'selected' : '' }}>Abuelo</option>
                                            <option value="Abuela" {{ old('tutor_principal', $participante->tutor_principal) == 'Abuela' ? 'selected' : '' }}>Abuela</option>
                                            <option value="Tío" {{ old('tutor_principal', $participante->tutor_principal) == 'Tío' ? 'selected' : '' }}>Tío</option>
                                            <option value="Tía" {{ old('tutor_principal', $participante->tutor_principal) == 'Tía' ? 'selected' : '' }}>Tía</option>
                                            <option value="Hermano" {{ old('tutor_principal', $participante->tutor_principal) == 'Hermano' ? 'selected' : '' }}>Hermano</option>
                                            <option value="Hermana" {{ old('tutor_principal', $participante->tutor_principal) == 'Hermana' ? 'selected' : '' }}>Hermana</option>
                                            <option value="Otro" {{ old('tutor_principal', $participante->tutor_principal) == 'Otro' ? 'selected' : '' }}>Otro</option>
                                        </select>
                                        @error('tutor_principal')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="nombres_y_apellidos_tutor_principal" class="block text-xs font-medium text-gray-800 mb-1">Nombres y Apellidos</label>
                                        <input type="text" name="nombres_y_apellidos_tutor_principal" id="nombres_y_apellidos_tutor_principal" value="{{ old('nombres_y_apellidos_tutor_principal', $participante->nombres_y_apellidos_tutor_principal) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" required>
                                        @error('nombres_y_apellidos_tutor_principal')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="numero_de_cedula_tutor" class="block text-xs font-medium text-gray-800 mb-1">Número de Cédula</label>
                                        <input type="text" name="numero_de_cedula_tutor" id="numero_de_cedula_tutor" value="{{ old('numero_de_cedula_tutor', $participante->numero_de_cedula_tutor) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" required>
                                        @error('numero_de_cedula_tutor')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="comunidad_tutor" class="block text-xs font-medium text-gray-800 mb-1">Comunidad</label>
                                        <select name="comunidad_tutor" id="comunidad_tutor" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" required>
                                            <option value="" disabled>Seleccione</option>
                                            <option value="Centro" {{ old('comunidad_tutor', $participante->comunidad_tutor) == 'Centro' ? 'selected' : '' }}>Centro</option>
                                            <option value="Norte" {{ old('comunidad_tutor', $participante->comunidad_tutor) == 'Norte' ? 'selected' : '' }}>Norte</option>
                                            <option value="Sur" {{ old('comunidad_tutor', $participante->comunidad_tutor) == 'Sur' ? 'selected' : '' }}>Sur</option>
                                            <option value="Este" {{ old('comunidad_tutor', $participante->comunidad_tutor) == 'Este' ? 'selected' : '' }}>Este</option>
                                            <option value="Oeste" {{ old('comunidad_tutor', $participante->comunidad_tutor) == 'Oeste' ? 'selected' : '' }}>Oeste</option>
                                        </select>
                                        @error('comunidad_tutor')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="direccion_tutor" class="block text-xs font-medium text-gray-800 mb-1">Dirección</label>
                                        <input type="text" name="direccion_tutor" id="direccion_tutor" value="{{ old('direccion_tutor', $participante->direccion_tutor) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" required>
                                        @error('direccion_tutor')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="telefono_tutor" class="block text-xs font-medium text-gray-800 mb-1">Teléfono</label>
                                        <input type="text" name="telefono_tutor" id="telefono_tutor" value="{{ old('telefono_tutor', $participante->telefono_tutor) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out">
                                        @error('telefono_tutor')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="sector_economico_tutor" class="block text-xs font-medium text-gray-800 mb-1">Sector Económico</label>
                                        <select name="sector_economico_tutor" id="sector_economico_tutor" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" required>
                                            <option value="" disabled>Seleccione</option>
                                            <option value="Agricultura" {{ old('sector_economico_tutor', $participante->sector_economico_tutor) == 'Agricultura' ? 'selected' : '' }}>Agricultura</option>
                                            <option value="Comercio" {{ old('sector_economico_tutor', $participante->sector_economico_tutor) == 'Comercio' ? 'selected' : '' }}>Comercio</option>
                                            <option value="Educación" {{ old('sector_economico_tutor', $participante->sector_economico_tutor) == 'Educación' ? 'selected' : '' }}>Educación</option>
                                            <option value="Salud" {{ old('sector_economico_tutor', $participante->sector_economico_tutor) == 'Salud' ? 'selected' : '' }}>Salud</option>
                                            <option value="Tecnología" {{ old('sector_economico_tutor', $participante->sector_economico_tutor) == 'Tecnología' ? 'selected' : '' }}>Tecnología</option>
                                            <option value="Otro" {{ old('sector_economico_tutor', $participante->sector_economico_tutor) == 'Otro' ? 'selected' : '' }}>Otro</option>
                                        </select>
                                        @error('sector_economico_tutor')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="nivel_de_educacion_formal_adquirido_tutor" class="block text-xs font-medium text-gray-800 mb-1">Nivel de Educación</label>
                                        <select name="nivel_de_educacion_formal_adquirido_tutor" id="nivel_de_educacion_formal_adquirido_tutor" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" required>
                                            <option value="" disabled>Seleccione</option>
                                            <option value="Primaria" {{ old('nivel_de_educacion_formal_adquirido_tutor', $participante->nivel_de_educacion_formal_adquirido_tutor) == 'Primaria' ? 'selected' : '' }}>Primaria</option>
                                            <option value="Secundaria" {{ old('nivel_de_educacion_formal_adquirido_tutor', $participante->nivel_de_educacion_formal_adquirido_tutor) == 'Secundaria' ? 'selected' : '' }}>Secundaria</option>
                                            <option value="Técnico" {{ old('nivel_de_educacion_formal_adquirido_tutor', $participante->nivel_de_educacion_formal_adquirido_tutor) == 'Técnico' ? 'selected' : '' }}>Técnico</option>
                                            <option value="Universidad" {{ old('nivel_de_educacion_formal_adquirido_tutor', $participante->nivel_de_educacion_formal_adquirido_tutor) == 'Universidad' ? 'selected' : '' }}>Universidad</option>
                                            <option value="Ninguno" {{ old('nivel_de_educacion_formal_adquirido_tutor', $participante->nivel_de_educacion_formal_adquirido_tutor) == 'Ninguno' ? 'selected' : '' }}>Ninguno</option>
                                        </select>
                                        @error('nivel_de_educacion_formal_adquirido_tutor')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="lg:col-span-3">
                                        <label for="expectativas_del_programa_tutor_principal" class="block text-xs font-medium text-gray-800 mb-1">Expectativas del Programa</label>
                                        <textarea name="expectativas_del_programa_tutor_principal" id="expectativas_del_programa_tutor_principal" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" >{{ old('expectativas_del_programa_tutor_principal', $participante->expectativas_del_programa_tutor_principal) }}</textarea>
                                        @error('expectativas_del_programa_tutor_principal')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Tutor Secundario -->
                            <div class="border-t border-gray-200 pt-4">
                                <h3 class="text-lg font-semibold text-gray-800 mb-3">Tutor Secundario</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    <div>
                                        <label for="tutor_secundario" class="block text-xs font-medium text-gray-800 mb-1">Tipo de Tutor</label>
                                        <select name="tutor_secundario" id="tutor_secundario" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out">
                                            <option value="" {{ old('tutor_secundario', $participante->tutor_secundario) == '' ? 'selected' : '' }}>Seleccione</option>
                                            <option value="Madre" {{ old('tutor_secundario', $participante->tutor_secundario) == 'Madre' ? 'selected' : '' }}>Madre</option>
                                            <option value="Padre" {{ old('tutor_secundario', $participante->tutor_secundario) == 'Padre' ? 'selected' : '' }}>Padre</option>
                                            <option value="Abuelo" {{ old('tutor_secundario', $participante->tutor_secundario) == 'Abuelo' ? 'selected' : '' }}>Abuelo</option>
                                            <option value="Abuela" {{ old('tutor_secundario', $participante->tutor_secundario) == 'Abuela' ? 'selected' : '' }}>Abuela</option>
                                            <option value="Tío" {{ old('tutor_secundario', $participante->tutor_secundario) == 'Tío' ? 'selected' : '' }}>Tío</option>
                                            <option value="Tía" {{ old('tutor_secundario', $participante->tutor_secundario) == 'Tía' ? 'selected' : '' }}>Tía</option>
                                            <option value="Hermano" {{ old('tutor_secundario', $participante->tutor_secundario) == 'Hermano' ? 'selected' : '' }}>Hermano</option>
                                            <option value="Hermana" {{ old('tutor_secundario', $participante->tutor_secundario) == 'Hermana' ? 'selected' : '' }}>Hermana</option>
                                            <option value="Otro" {{ old('tutor_secundario', $participante->tutor_secundario) == 'Otro' ? 'selected' : '' }}>Otro</option>
                                        </select>
                                        @error('tutor_secundario')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="nombres_y_apellidos_tutor_secundario" class="block text-xs font-medium text-gray-800 mb-1">Nombres y Apellidos</label>
                                        <input type="text" name="nombres_y_apellidos_tutor_secundario" id="nombres_y_apellidos_tutor_secundario" value="{{ old('nombres_y_apellidos_tutor_secundario', $participante->nombres_y_apellidos_tutor_secundario) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out">
                                        @error('nombres_y_apellidos_tutor_secundario')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="numero_de_cedula_tutor_secundario" class="block text-xs font-medium text-gray-800 mb-1">Número de Cédula</label>
                                        <input type="text" name="numero_de_cedula_tutor_secundario" id="numero_de_cedula_tutor_secundario" value="{{ old('numero_de_cedula_tutor_secundario', $participante->numero_de_cedula_tutor_secundario) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out">
                                        @error('numero_de_cedula_tutor_secundario')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="comunidad_tutor_secundario" class="block text-xs font-medium text-gray-800 mb-1">Comunidad</label>
                                        <select name="comunidad_tutor_secundario" id="comunidad_tutor_secundario" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out">
                                            <option value="" {{ old('comunidad_tutor_secundario', $participante->comunidad_tutor_secundario) == '' ? 'selected' : '' }}>Seleccione</option>
                                            <option value="Centro" {{ old('comunidad_tutor_secundario', $participante->comunidad_tutor_secundario) == 'Centro' ? 'selected' : '' }}>Centro</option>
                                            <option value="Norte" {{ old('comunidad_tutor_secundario', $participante->comunidad_tutor_secundario) == 'Norte' ? 'selected' : '' }}>Norte</option>
                                            <option value="Sur" {{ old('comunidad_tutor_secundario', $participante->comunidad_tutor_secundario) == 'Sur' ? 'selected' : '' }}>Sur</option>
                                            <option value="Este" {{ old('comunidad_tutor_secundario', $participante->comunidad_tutor_secundario) == 'Este' ? 'selected' : '' }}>Este</option>
                                            <option value="Oeste" {{ old('comunidad_tutor_secundario', $participante->comunidad_tutor_secundario) == 'Oeste' ? 'selected' : '' }}>Oeste</option>
                                        </select>
                                        @error('comunidad_tutor_secundario')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="telefono_tutor_secundario" class="block text-xs font-medium text-gray-800 mb-1">Teléfono</label>
                                        <input type="text" name="telefono_tutor_secundario" id="telefono_tutor_secundario" value="{{ old('telefono_tutor_secundario', $participante->telefono_tutor_secundario) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out">
                                        @error('telefono_tutor_secundario')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Otros Programas -->
                            <div class="border-t border-gray-200 pt-4">
                                <h3 class="text-lg font-semibold text-gray-800 mb-3">Otros Programas</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-800 mb-1">¿Asiste a Otros Programas?</label>
                                        <div class="flex items-center space-x-3">
                                            <label class="flex items-center">
                                                <input type="radio" name="asiste_a_otros_programas" value="1" class="asiste-otros-radio h-4 w-4 text-indigo-600 border-gray-200 focus:ring-indigo-600" {{ old('asiste_a_otros_programas', $participante->asiste_a_otros_programas) ? 'checked' : '' }} required>
                                                <span class="ml-1.5 text-xs text-gray-600">Sí</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="radio" name="asiste_a_otros_programas" value="0" class="asiste-otros-radio h-4 w-4 text-indigo-600 border-gray-200 focus:ring-indigo-600" {{ old('asiste_a_otros_programas', $participante->asiste_a_otros_programas) ? '' : 'checked' }}>
                                                <span class="ml-1.5 text-xs text-gray-600">No</span>
                                            </label>
                                        </div>
                                        @error('asiste_a_otros_programas')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div id="otros-programas-section" class="{{ old('asiste_a_otros_programas', $participante->asiste_a_otros_programas) ? '' : 'hidden' }}">
                                        <label for="otros_programas" class="block text-xs font-medium text-gray-800 mb-1">Otros Programas</label>
                                        <input type="text" name="otros_programas" id="otros_programas" value="{{ old('otros_programas', $participante->otros_programas) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out">
                                        @error('otros_programas')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div id="dias-otros-section" class="{{ old('asiste_a_otros_programas', $participante->asiste_a_otros_programas) ? '' : 'hidden' }} lg:col-span-2">
    <label class="block text-xs font-medium text-gray-800 mb-1">Días que Asiste a Otros Programas</label>
    <div class="flex flex-wrap gap-3">
        @php
            // Priorizar los valores de old() si existen, de lo contrario usar los datos del participante
            $diasOtrosSeleccionados = old('dias_asiste_a_otros_programas', $participante->dias_asiste_a_otros_programas ?? []);
        @endphp
        @foreach (['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'] as $dia)
            <label class="flex items-center">
                <input type="checkbox" value="{{ $dia }}" name="dias_asiste_a_otros_programas[]" class="dias-otros h-4 w-4 text-indigo-600 border-gray-200 focus:ring-indigo-600" {{ in_array($dia, (array)$diasOtrosSeleccionados) ? 'checked' : '' }}>
                <span class="ml-1.5 text-xs text-gray-600">{{ $dia }}</span>
            </label>
        @endforeach
    </div>
    <input type="hidden" name="total_dias_asiste_a_otros_programas" id="dias_asiste_a_otros_programas" value="{{ count((array)$diasOtrosSeleccionados) }}">
    <p class="text-xs text-gray-500 mt-1">Total días: <span id="total-dias-otros">{{ count((array)$diasOtrosSeleccionados) }}</span></p>
    @error('dias_asiste_a_otros_programas')
        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
    @enderror
</div>
                                </div>
                            </div>

                            <!-- Botones -->
                            <div class="pt-8 flex justify-between items-center">
                                <x-boton-regresar onclick="window.location.href='{{ route('participante.index') }}'">Volver</x-boton-regresar>
                                <x-boton-flotante type="submit">Actualizar Participante</x-boton-flotante>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript para funcionalidad -->
    <script>
        // Calcular Año de Inscripción basado en Fecha de Inscripción
        document.getElementById('fecha_de_inscripcion').addEventListener('change', function () {
            const fechaInscripcion = new Date(this.value);
            const anoInscripcion = fechaInscripcion.getFullYear();
            document.getElementById('ano_de_inscripcion').value = anoInscripcion;
        });

        // Calcular Edad basado en Fecha de Nacimiento
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

        // Alternar visibilidad de Otros Programas y Días que Asiste a Otros Programas
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