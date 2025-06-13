<x-app-layout>
   <x-slot name="header">
        <div class="flex items-center justify-between w-full px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-transparent lg:text-3xl bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500">
                {{ __('Edición de Participante') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8 font-sans bg-gradient-to-br from-slate-50 via-purple-50 to-pink-50 dark:from-slate-800 dark:via-purple-900 dark:to-pink-900">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white rounded-lg shadow-lg">
                <div class="p-6">

                    @if ($errors->any())
                        <div class="p-4 mb-6 text-red-800 bg-red-100 border-l-4 border-red-500 rounded-md" role="alert">
                            <p class="mb-2 font-bold">Por favor corrige los siguientes errores:</p>
                            <ul class="ml-4 list-disc list-inside">
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
                            <div>
                                <h3 class="mb-3 text-lg font-semibold text-gray-800">Fecha de Inscripción</h3>
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                    <div>
                                        <label for="fecha_de_inscripcion" class="block mb-1 text-xs font-medium text-gray-800">Fecha de inscripción</label>
                                        <input type="date" name="fecha_de_inscripcion" id="fecha_de_inscripcion" value="{{ old('fecha_de_inscripcion', $participante->fecha_de_inscripcion instanceof \Carbon\Carbon ? $participante->fecha_de_inscripcion->format('Y-m-d') : $participante->fecha_de_inscripcion) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" required>
                                        @error('fecha_de_inscripcion')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="ano_de_inscripcion" class="block mb-1 text-xs font-medium text-gray-800">Año de inscripción</label>
                                        <input type="number" name="ano_de_inscripcion" id="ano_de_inscripcion" value="{{ old('ano_de_inscripcion', $participante->ano_de_inscripcion) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm bg-gray-100 text-sm transition duration-150 ease-in-out" readonly required>
                                        @error('ano_de_inscripcion')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-gray-200">
                                <h3 class="mb-3 text-lg font-semibold text-gray-800">Documentos Requeridos</h3>
                                <div class="grid grid-cols-1 gap-2 md:grid-cols-2 lg:grid-cols-4">
                                    <div>
                                        <label class="block mb-1 text-xs font-medium text-gray-800">Copia de partida de nacimiento</label>
                                        <div class="flex items-center space-x-3">
                                            <label class="flex items-center">
                                                <input type="radio" name="partida_de_nacimiento" value="1" class="w-4 h-4 text-indigo-600 border-gray-200 focus:ring-indigo-600" {{ old('partida_de_nacimiento', $participante->partida_de_nacimiento) == 1 ? 'checked' : '' }} required>
                                                <span class="ml-1.5 text-xs text-gray-600">Sí</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="radio" name="partida_de_nacimiento" value="0" class="w-4 h-4 text-indigo-600 border-gray-200 focus:ring-indigo-600" {{ old('partida_de_nacimiento', $participante->partida_de_nacimiento) == 0 ? 'checked' : '' }}>
                                                <span class="ml-1.5 text-xs text-gray-600">No</span>
                                            </label>
                                        </div>
                                        @error('partida_de_nacimiento')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block mb-1 text-xs font-medium text-gray-800">Copia boletín o diploma (2024)</label>
                                        <div class="flex items-center space-x-3">
                                            <label class="flex items-center">
                                                <input type="radio" name="boletin_o_diploma_2024" value="1" class="w-4 h-4 text-indigo-600 border-gray-200 focus:ring-indigo-600" {{ old('boletin_o_diploma_2024', $participante->boletin_o_diploma_2024) == 1 ? 'checked' : '' }} required>
                                                <span class="ml-1.5 text-xs text-gray-600">Sí</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="radio" name="boletin_o_diploma_2024" value="0" class="w-4 h-4 text-indigo-600 border-gray-200 focus:ring-indigo-600" {{ old('boletin_o_diploma_2024', $participante->boletin_o_diploma_2024) == 0 ? 'checked' : '' }}>
                                                <span class="ml-1.5 text-xs text-gray-600">No</span>
                                            </label>
                                        </div>
                                        @error('boletin_o_diploma_2024')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block mb-1 text-xs font-medium text-gray-800">Copia de cédula del tutor</label>
                                        <div class="flex items-center space-x-3">
                                            <label class="flex items-center">
                                                <input type="radio" name="cedula_tutor" value="1" class="w-4 h-4 text-indigo-600 border-gray-200 focus:ring-indigo-600" {{ old('cedula_tutor', $participante->cedula_tutor) == 1 ? 'checked' : '' }} required>
                                                <span class="ml-1.5 text-xs text-gray-600">Sí</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="radio" name="cedula_tutor" value="0" class="w-4 h-4 text-indigo-600 border-gray-200 focus:ring-indigo-600" {{ old('cedula_tutor', $participante->cedula_tutor) == 0 ? 'checked' : '' }}>
                                                <span class="ml-1.5 text-xs text-gray-600">No</span>
                                            </label>
                                        </div>
                                        @error('cedula_tutor')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block mb-1 text-xs font-medium text-gray-800">Copia de cédula (participante adulto)</label>
                                        <div class="flex items-center space-x-3">
                                            <label class="flex items-center">
                                                <input type="radio" name="cedula_participante_adulto" value="1" class="w-4 h-4 text-indigo-600 border-gray-200 focus:ring-indigo-600" {{ old('cedula_participante_adulto', $participante->cedula_participante_adulto) == 1 ? 'checked' : '' }} required>
                                                <span class="ml-1.5 text-xs text-gray-600">Sí</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="radio" name="cedula_participante_adulto" value="0" class="w-4 h-4 text-indigo-600 border-gray-200 focus:ring-indigo-600" {{ old('cedula_participante_adulto', $participante->cedula_participante_adulto) == 0 ? 'checked' : '' }}>
                                                <span class="ml-1.5 text-xs text-gray-600">No</span>
                                            </label>
                                        </div>
                                        @error('cedula_participante_adulto')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-gray-200">
                                <h3 class="mb-3 text-lg font-semibold text-gray-800">Información del Participante</h3>
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                                    <div>
                                        <label for="participante_tipo" class="block mb-1 text-xs font-medium text-gray-800">Nivel del Participante</label> {{-- Cambiado el id y name a participante_tipo para evitar conflicto con el objeto $participante --}}
                                        <select name="participante" id="participante_tipo" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" required>
                                            <option value="" disabled>Seleccione</option>
                                            <option value="Primaria" {{ old('participante', $participante->participante) == 'Primaria' ? 'selected' : '' }}>Primaria</option>
                                            <option value="Secundaria" {{ old('participante', $participante->participante) == 'Secundaria' ? 'selected' : '' }}>Secundaria</option>
                                            <option value="Preescolar" {{ old('participante', $participante->participante) == 'Preescolar' ? 'selected' : '' }}>Preescolar (o menos)</option>
                                            <option value="Adulto" {{ old('participante', $participante->participante) == 'Adulto' ? 'selected' : '' }}>Adulto</option>
                                        </select>
                                        @error('participante')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="primer_nombre_p" class="block mb-1 text-xs font-medium text-gray-800">Primer Nombre</label>
                                        <input type="text" name="primer_nombre_p" id="primer_nombre_p" value="{{ old('primer_nombre_p', $participante->primer_nombre_p) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" required>
                                        @error('primer_nombre_p')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="segundo_nombre_p" class="block mb-1 text-xs font-medium text-gray-800">Segundo Nombre</label>
                                        <input type="text" name="segundo_nombre_p" id="segundo_nombre_p" value="{{ old('segundo_nombre_p', $participante->segundo_nombre_p) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out">
                                        @error('segundo_nombre_p')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="primer_apellido_p" class="block mb-1 text-xs font-medium text-gray-800">Primer Apellido</label>
                                        <input type="text" name="primer_apellido_p" id="primer_apellido_p" value="{{ old('primer_apellido_p', $participante->primer_apellido_p) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" required>
                                        @error('primer_apellido_p')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="segundo_apellido_p" class="block mb-1 text-xs font-medium text-gray-800">Segundo Apellido</label>
                                        <input type="text" name="segundo_apellido_p" id="segundo_apellido_p" value="{{ old('segundo_apellido_p', $participante->segundo_apellido_p) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out">
                                        @error('segundo_apellido_p')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="cedula_participante_adulto_str" class="block mb-1 text-xs font-medium text-gray-800">Cédula Participante Adulto</label>
                                        <input type="text" name="cedula_participante_adulto_str" id="cedula_participante_adulto_str" value="{{ old('cedula_participante_adulto_str', $participante->cedula_participante_adulto_str) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out">
                                        @error('cedula_participante_adulto_str')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="fecha_de_nacimiento_p" class="block mb-1 text-xs font-medium text-gray-800">Fecha de Nacimiento</label>
                                        <input type="date" name="fecha_de_nacimiento_p" id="fecha_de_nacimiento_p" value="{{ old('fecha_de_nacimiento_p', $participante->fecha_de_nacimiento_p instanceof \Carbon\Carbon ? $participante->fecha_de_nacimiento_p->format('Y-m-d') : $participante->fecha_de_nacimiento_p) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" required>
                                        @error('fecha_de_nacimiento_p')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="edad_p" class="block mb-1 text-xs font-medium text-gray-800">Edad</label>
                                        <input type="number" name="edad_p" id="edad_p" value="{{ old('edad_p', $participante->edad_p) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm bg-gray-100 text-sm transition duration-150 ease-in-out" readonly required>
                                        @error('edad_p')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="genero" class="block mb-1 text-xs font-medium text-gray-800">Género</label>
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
                                        <x-input-label for="comunidad_p">Comunidad del Participante <span class="text-red-500">*</span></x-input-label>

                                        {{-- Asegúrate de pasar la colección y el valor actual --}}
                                        <x-community-selector
                                            :comunidades="$comunidades"
                                            name="comunidad_p"
                                            id="comunidad_p"
                                            :value="$participante->comunidad_p"
                                            required
                                        />
                                    </div>
                                    <div>
                                        <label for="ciudad_p" class="block mb-1 text-xs font-medium text-gray-800">Ciudad</label>
                                        <input type="text" name="ciudad_p" id="ciudad_p" value="{{ old('ciudad_p', $participante->ciudad_p) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" required>
                                        @error('ciudad_p')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="departamento_p" class="block mb-1 text-xs font-medium text-gray-800">Departamento</label>
                                        <input type="text" name="departamento_p" id="departamento_p" value="{{ old('departamento_p', $participante->departamento_p) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" required>
                                        @error('departamento_p')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-gray-200">
                                <h3 class="mb-3 text-lg font-semibold text-gray-800">Información Educativa</h3>
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                                    <div>
                                        <label for="escuela_p" class="block mb-1 text-xs font-medium text-gray-800">Escuela</label>
                                        <input type="text" name="escuela_p" id="escuela_p" value="{{ old('escuela_p', $participante->escuela_p) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" required>
                                        @error('escuela_p')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="comunidad_escuela" class="block mb-1 text-xs font-medium text-gray-800">Comunidad (Escuela)</label>
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
                                        <label for="grado_p" class="block mb-1 text-xs font-medium text-gray-800">Grado</label>
                                        <select name="grado_p" id="grado_p" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" required>
                                            <option value="" disabled>Seleccione</option>
                                            @foreach (range(0, 11) as $grado)
                                                <option value="{{ $grado }}" {{ old('grado_p', $participante->grado_p) == $grado ? 'selected' : '' }}>{{ $grado == 0 ? 'Preescolar' : $grado }}</option>
                                            @endforeach
                                        </select>
                                        @error('grado_p')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="turno" class="block mb-1 text-xs font-medium text-gray-800">Turno</label>
                                        <select name="turno" id="turno" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out">
                                            <option value="" {{ old('turno', $participante->turno) == '' ? 'selected' : '' }}>Seleccione (Opcional)</option>
                                            <option value="Matutino" {{ old('turno', $participante->turno) == 'Matutino' ? 'selected' : '' }}>Matutino</option>
                                            <option value="Vespertino" {{ old('turno', $participante->turno) == 'Vespertino' ? 'selected' : '' }}>Vespertino</option>
                                            <option value="Sabatino" {{ old('turno', $participante->turno) == 'Sabatino' ? 'selected' : '' }}>Sabatino</option>
                                            <option value="Dominical" {{ old('turno', $participante->turno) == 'Dominical' ? 'selected' : '' }}>Dominical</option>
                                        </select>
                                        @error('turno')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block mb-1 text-xs font-medium text-gray-800">¿Repite Grado?</label>
                                        <div class="flex items-center space-x-3">
                                            <label class="flex items-center">
                                                <input type="radio" name="repite_grado" value="1" class="w-4 h-4 text-indigo-600 border-gray-200 focus:ring-indigo-600" {{ old('repite_grado', $participante->repite_grado) == 1 ? 'checked' : '' }}>
                                                <span class="ml-1.5 text-xs text-gray-600">Sí</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="radio" name="repite_grado" value="0" class="w-4 h-4 text-indigo-600 border-gray-200 focus:ring-indigo-600" {{ old('repite_grado', $participante->repite_grado) == 0 ? 'checked' : '' }}>
                                                <span class="ml-1.5 text-xs text-gray-600">No</span>
                                            </label>
                                        </div>
                                        @error('repite_grado')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-gray-200">
                                <h3 class="mb-3 text-lg font-semibold text-gray-800">Detalles del Programa</h3>
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                    <div>
                                        <label class="block mb-2 text-xs font-medium text-gray-800">Programa Principal <span class="text-red-500">*</span></label>
                                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                            @php
                                                // $participante->programa ya es un array debido al controlador
                                                $programaSeleccionados = old('programa', $participante->programa);
                                            @endphp
                                            @foreach ($programaOptionsList as $programaOption)
                                                <label class="flex items-center text-sm text-gray-600">
                                                    <input type="checkbox" name="programa[]" value="{{ $programaOption }}"
                                                           @if(is_array($programaSeleccionados) && in_array($programaOption, $programaSeleccionados)) checked @endif
                                                           class="w-4 h-4 mr-2 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                                    {{ $programaOption }}
                                                </label>
                                            @endforeach
                                        </div>
                                        @error('programa')
                                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                        @enderror
                                        @error('programa.*')
                                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block mb-2 text-xs font-medium text-gray-800">Subprogramas/Códigos <span class="text-red-500">*</span></label>
                                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                                            @php
                                                // $participante->programas ya es un array debido al controlador
                                                $programasCodigosSeleccionados = old('programas', $participante->programas);
                                            @endphp
                                            @foreach ($subProgramaOptionsList as $subprogramaOption)
                                                <label class="flex items-center text-sm text-gray-600">
                                                    <input type="checkbox" name="programas[]" value="{{ $subprogramaOption }}"
                                                           @if(is_array($programasCodigosSeleccionados) && in_array($subprogramaOption, $programasCodigosSeleccionados)) checked @endif
                                                           class="w-4 h-4 mr-2 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                                    {{ $subprogramaOption }}
                                                </label>
                                            @endforeach
                                        </div>
                                        @error('programas')
                                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                        @enderror
                                        @error('programas.*')
                                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <x-input-label for="lugar_de_encuentro_del_programa" required>Lugar de encuentro del programa</x-input-label>
                                        <div class="mt-2">
                                            <x-radio-group-with-other
                                                :options="$lugaresDeEncuentro"
                                                name="lugar_de_encuentro_del_programa"
                                                :value="$participante->lugar_de_encuentro_del_programa"
                                                required
                                            />
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block mb-2 text-xs font-medium text-gray-800">Días de Asistencia Esperados <span class="text-red-500">*</span></label>
                                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                                            @php
                                                // $participante->dias_de_asistencia_al_programa ya es un array debido al controlador
                                                $diasSeleccionados = old('dias_de_asistencia_al_programa', $participante->dias_de_asistencia_al_programa);
                                            @endphp
                                            @foreach ($diasOptionsList as $diaOption)
                                                <label class="flex items-center text-sm text-gray-600">
                                                    <input type="checkbox" name="dias_de_asistencia_al_programa[]" value="{{ $diaOption }}"
                                                           @if(is_array($diasSeleccionados) && in_array($diaOption, $diasSeleccionados)) checked @endif
                                                           class="w-4 h-4 mr-2 text-indigo-600 border-gray-300 rounded dias-asistencia focus:ring-indigo-500">
                                                    {{ $diaOption }}
                                                </label>
                                            @endforeach
                                        </div>
                                        <input type="hidden" name="dias_de_asistencia_al_programa_count" id="dias_de_asistencia_al_programa_count_input" value="{{ is_array($diasSeleccionados) ? count($diasSeleccionados) : 0 }}">
                                        <p class="mt-1 text-sm text-gray-500">Total días: <span id="total-dias-asistencia">{{ is_array($diasSeleccionados) ? count($diasSeleccionados) : 0 }}</span></p>
                                        @error('dias_de_asistencia_al_programa')
                                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                        @enderror
                                        @error('dias_de_asistencia_al_programa.*')
                                            <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-gray-200">
                                <h3 class="mb-3 text-lg font-semibold text-gray-800">Tutor Principal</h3>
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                                    <div>
                                        <label for="tutor_principal" class="block mb-1 text-xs font-medium text-gray-800">Tipo de Tutor</label>
                                        <select name="tutor_principal" id="tutor_principal" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" required>
                                            <option value="" disabled>Seleccione</option>
                                            @foreach($tipos_tutor as $tipo)
                                            <option value="{{ $tipo }}" {{ old('tutor_principal', $participante->tutor_principal) == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                                            @endforeach
                                        </select>
                                        @error('tutor_principal')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="nombres_y_apellidos_tutor_principal" class="block mb-1 text-xs font-medium text-gray-800">Nombres y Apellidos</label>
                                        <input type="text" name="nombres_y_apellidos_tutor_principal" id="nombres_y_apellidos_tutor_principal" value="{{ old('nombres_y_apellidos_tutor_principal', $participante->nombres_y_apellidos_tutor_principal) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" required>
                                        @error('nombres_y_apellidos_tutor_principal')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="numero_de_cedula_tutor" class="block mb-1 text-xs font-medium text-gray-800">Número de Cédula</label>
                                        <input type="text" name="numero_de_cedula_tutor" id="numero_de_cedula_tutor" value="{{ old('numero_de_cedula_tutor', $participante->numero_de_cedula_tutor) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out">
                                        @error('numero_de_cedula_tutor')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    {{-- Sección de Comunidad del Tutor (Adaptada para Edición) --}}
                                    @php
                                        // Determinar el estado inicial. Se muestra el campo 'nueva_comunidad' si:
                                        // 1. Hubo un error de validación y se había seleccionado 'Otra'.
                                        // 2. El valor actual del participante NO está en la lista de opciones y no está vacío.
                                        $showNuevaComunidad = old('comunidad_tutor') === '_OTRA_' ||
                                                            (!in_array(old('comunidad_tutor', $participante->comunidad_tutor), $comunidades->all()) &&
                                                            !empty(old('comunidad_tutor', $participante->comunidad_tutor)));

                                        // Determinar qué valor debe estar seleccionado en el dropdown.
                                        // Si la comunidad actual no está en la lista, se selecciona '_OTRA_'.
                                        $selectedComunidad = in_array(old('comunidad_tutor', $participante->comunidad_tutor), $comunidades->all())
                                                            ? old('comunidad_tutor', $participante->comunidad_tutor)
                                                            : '_OTRA_';
                                    @endphp

                                    <div x-data="{ esOtraComunidad: {{ $showNuevaComunidad ? 'true' : 'false' }} }">
                                        <label for="comunidad_tutor" class="block mb-1 text-xs font-medium text-gray-700 dark:text-slate-300">
                                            Comunidad del tutor
                                            {{-- Si el campo no es obligatorio en la edición, puedes quitar el asterisco --}}
                                            <span class="text-red-500">*</span>
                                        </label>

                                        {{-- Menú desplegable con las comunidades existentes y la opción "Otra" --}}
                                        <select name="comunidad_tutor" id="comunidad_tutor"
                                                @change="esOtraComunidad = ($event.target.value === '_OTRA_')"
                                                class="block w-full text-sm border-gray-300 shadow-sm rounded-xl dark:border-slate-600 dark:bg-slate-900/50 dark:text-slate-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-600">
                                            <option value="" disabled>Seleccione...</option>
                                            @foreach ($comunidades as $comunidad)
                                                <option value="{{ $comunidad }}" {{ $selectedComunidad == $comunidad ? 'selected' : '' }}>
                                                    {{ $comunidad }}
                                                </option>
                                            @endforeach
                                            <option value="_OTRA_" {{ $selectedComunidad == '_OTRA_' ? 'selected' : '' }}>
                                                Otra... (especificar)
                                            </option>
                                        </select>
                                        <x-input-error :messages="$errors->get('comunidad_tutor')" class="mt-2" />

                                        {{-- Campo de texto que aparece si se selecciona "Otra" o si el valor existente es personalizado --}}
                                        <div x-show="esOtraComunidad" x-transition class="mt-2">
                                            <label for="nueva_comunidad_tutor" class="block mb-1 text-xs font-medium text-gray-700 dark:text-slate-400">
                                                Nombre de la comunidad <span class="text-red-500">*</span>
                                            </label>
                                            {{--
                                                El valor se llena con:
                                                1. El valor antiguo de 'nueva_comunidad_tutor' si existe (por error de validación).
                                                2. O, si el valor del participante no estaba en la lista, se muestra aquí.
                                            --}}
                                            <x-text-input id="nueva_comunidad_tutor" type="text" name="nueva_comunidad_tutor"
                                                        :value="old('nueva_comunidad_tutor', $showNuevaComunidad ? $participante->comunidad_tutor : '')"
                                                        class="block w-full mt-1"
                                                        placeholder="Escriba el nombre aquí..."
                                                        x-bind:required="esOtraComunidad" />
                                            <x-input-error :messages="$errors->get('nueva_comunidad_tutor')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div>
                                        <label for="direccion_tutor" class="block mb-1 text-xs font-medium text-gray-800">Dirección</label>
                                        <input type="text" name="direccion_tutor" id="direccion_tutor" value="{{ old('direccion_tutor', $participante->direccion_tutor) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out">
                                        @error('direccion_tutor')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="telefono_tutor" class="block mb-1 text-xs font-medium text-gray-800">Teléfono</label>
                                        <input type="text" name="telefono_tutor" id="telefono_tutor" value="{{ old('telefono_tutor', $participante->telefono_tutor) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out">
                                        @error('telefono_tutor')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="sector_economico_tutor" class="block mb-1 text-xs font-medium text-gray-800">Sector Económico</label>
                                        <select name="sector_economico_tutor" id="sector_economico_tutor" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out">
                                            <option value="" {{ old('sector_economico_tutor', $participante->sector_economico_tutor) == '' ? 'selected' : '' }}>Seleccione (Opcional)</option>
                                            @foreach($sector_economico as $sector)
                                            <option value="{{ $sector }}" {{ old('sector_economico_tutor', $participante->sector_economico_tutor) == $sector ? 'selected' : '' }}>{{ $sector }}</option>
                                            @endforeach
                                        </select>
                                        @error('sector_economico_tutor')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="nivel_de_educacion_formal_adquirido_tutor" class="block mb-1 text-xs font-medium text-gray-800">Nivel de Educación</label>
                                        <select name="nivel_de_educacion_formal_adquirido_tutor" id="nivel_de_educacion_formal_adquirido_tutor" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out">
                                            <option value="" {{ old('nivel_de_educacion_formal_adquirido_tutor', $participante->nivel_de_educacion_formal_adquirido_tutor) == '' ? 'selected' : '' }}>Seleccione (Opcional)</option>
                                            @foreach($nivel_educacion as $nivel)
                                            <option value="{{ $nivel }}" {{ old('nivel_de_educacion_formal_adquirido_tutor', $participante->nivel_de_educacion_formal_adquirido_tutor) == $nivel ? 'selected' : '' }}>{{ $nivel }}</option>
                                            @endforeach
                                        </select>
                                        @error('nivel_de_educacion_formal_adquirido_tutor')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="lg:col-span-3">
                                        <label for="expectativas_del_programa_tutor_principal" class="block mb-1 text-xs font-medium text-gray-800">Expectativas del Programa</label>
                                        <textarea name="expectativas_del_programa_tutor_principal" id="expectativas_del_programa_tutor_principal" rows="2" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out" >{{ old('expectativas_del_programa_tutor_principal', $participante->expectativas_del_programa_tutor_principal) }}</textarea>
                                        @error('expectativas_del_programa_tutor_principal')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-gray-200">
                                <h3 class="mb-3 text-lg font-semibold text-gray-800">Tutor Secundario (Opcional)</h3>
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                                    <div>
                                        <label for="tutor_secundario" class="block mb-1 text-xs font-medium text-gray-800">Tipo de Tutor</label>
                                        <select name="tutor_secundario" id="tutor_secundario" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out">
                                            <option value="" {{ old('tutor_secundario', $participante->tutor_secundario) == '' ? 'selected' : '' }}>Seleccione</option>
                                            @foreach($tipos_tutor as $tipo)
                                            <option value="{{ $tipo }}" {{ old('tutor_secundario', $participante->tutor_secundario) == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                                            @endforeach
                                        </select>
                                        @error('tutor_secundario')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="nombres_y_apellidos_tutor_secundario" class="block mb-1 text-xs font-medium text-gray-800">Nombres y Apellidos</label>
                                        <input type="text" name="nombres_y_apellidos_tutor_secundario" id="nombres_y_apellidos_tutor_secundario" value="{{ old('nombres_y_apellidos_tutor_secundario', $participante->nombres_y_apellidos_tutor_secundario) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out">
                                        @error('nombres_y_apellidos_tutor_secundario')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="numero_de_cedula_tutor_secundario" class="block mb-1 text-xs font-medium text-gray-800">Número de Cédula</label>
                                        <input type="text" name="numero_de_cedula_tutor_secundario" id="numero_de_cedula_tutor_secundario" value="{{ old('numero_de_cedula_tutor_secundario', $participante->numero_de_cedula_tutor_secundario) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out">
                                        @error('numero_de_cedula_tutor_secundario')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="comunidad_tutor_secundario" class="block mb-1 text-xs font-medium text-gray-800">Comunidad</label>
                                        <select name="comunidad_tutor_secundario" id="comunidad_tutor_secundario" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out">
                                            <option value="" {{ old('comunidad_tutor_secundario', $participante->comunidad_tutor_secundario) == '' ? 'selected' : '' }}>Seleccione</option>
                                             @foreach($comunidades as $comunidad)
                                            <option value="{{ $comunidad }}" {{ old('comunidad_tutor_secundario', $participante->comunidad_tutor_secundario) == $comunidad ? 'selected' : '' }}>{{ $comunidad }}</option>
                                            @endforeach
                                        </select>
                                        @error('comunidad_tutor_secundario')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="telefono_tutor_secundario" class="block mb-1 text-xs font-medium text-gray-800">Teléfono</label>
                                        <input type="text" name="telefono_tutor_secundario" id="telefono_tutor_secundario" value="{{ old('telefono_tutor_secundario', $participante->telefono_tutor_secundario) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out">
                                        @error('telefono_tutor_secundario')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-gray-200">
                                <h3 class="mb-3 text-lg font-semibold text-gray-800">Otros Programas (Opcional)</h3>
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                                    <div>
                                        <label class="block mb-1 text-xs font-medium text-gray-800">¿Asiste a Otros Programas?</label>
                                        <div class="flex items-center space-x-3">
                                            <label class="flex items-center">
                                                <input type="radio" name="asiste_a_otros_programas" value="1" class="w-4 h-4 text-indigo-600 border-gray-200 asiste-otros-radio focus:ring-indigo-600" {{ old('asiste_a_otros_programas', $participante->asiste_a_otros_programas) == 1 ? 'checked' : '' }}>
                                                <span class="ml-1.5 text-xs text-gray-600">Sí</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="radio" name="asiste_a_otros_programas" value="0" class="w-4 h-4 text-indigo-600 border-gray-200 asiste-otros-radio focus:ring-indigo-600" {{ old('asiste_a_otros_programas', $participante->asiste_a_otros_programas) == 0 || is_null(old('asiste_a_otros_programas', $participante->asiste_a_otros_programas)) ? 'checked' : '' }}>
                                                <span class="ml-1.5 text-xs text-gray-600">No</span>
                                            </label>
                                        </div>
                                        @error('asiste_a_otros_programas')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div id="otros-programas-section" class="{{ old('asiste_a_otros_programas', $participante->asiste_a_otros_programas) == 1 ? '' : 'hidden' }}">
                                        <label for="otros_programas" class="block mb-1 text-xs font-medium text-gray-800">Nombres Otros Programas</label>
                                        <input type="text" name="otros_programas" id="otros_programas" value="{{ old('otros_programas', $participante->otros_programas) }}" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out">
                                        @error('otros_programas')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div id="dias-otros-section" class="{{ old('asiste_a_otros_programas', $participante->asiste_a_otros_programas) == 1 ? '' : 'hidden' }} lg:col-span-2">
                                        <label class="block mb-1 text-xs font-medium text-gray-800">Días que Asiste a Otros Programas</label>
                                        <input type="number" name="dias_asiste_a_otros_programas" id="dias_asiste_a_otros_programas_input" value="{{ old('dias_asiste_a_otros_programas', $participante->dias_asiste_a_otros_programas) }}" min="0" max="7" class="w-full px-3 py-1.5 border border-gray-200 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 text-sm transition duration-150 ease-in-out">
                                        @error('dias_asiste_a_otros_programas')
                                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                             <div class="pt-4 border-t border-gray-200">
                                <h3 class="mb-3 text-lg font-semibold text-gray-800">Estado</h3>
                                <div>
                                    <label class="block mb-1 text-xs font-medium text-gray-800">Activo</label>
                                    <div class="flex items-center space-x-3">
                                        <label class="flex items-center">
                                            <input type="radio" name="activo" value="1" class="w-4 h-4 text-indigo-600 border-gray-200 focus:ring-indigo-600" {{ old('activo', $participante->activo) == 1 ? 'checked' : '' }} required>
                                            <span class="ml-1.5 text-xs text-gray-600">Sí</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="activo" value="0" class="w-4 h-4 text-indigo-600 border-gray-200 focus:ring-indigo-600" {{ old('activo', $participante->activo) == 0 ? 'checked' : '' }}>
                                            <span class="ml-1.5 text-xs text-gray-600">No</span>
                                        </label>
                                    </div>
                                    @error('activo')
                                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>


                            <div class="flex items-center justify-between pt-8">
                                <x-boton-regresar onclick="window.location.href='{{ route('participante.index') }}'">Volver</x-boton-regresar>
                                <x-boton-flotante type="submit">Actualizar Participante</x-boton-flotante>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Script para pasar datos 'old' de Laravel a JS para la lógica de edición --}}
    <script>
        // Estas variables globales serán leídas por participante-edit.js
        const _oldAsisteOtrosProgramas = {{ old('asiste_a_otros_programas', $participante->asiste_a_otros_programas) ? 'true' : 'false' }};
        const _oldParticipanteNivel = @json(old('participante', $participante->participante));
        const _oldParticipanteNivelOtro = @json(old('participante_otro', $participante->participante_otro));

    </script>
    @vite(['resources/js/pages/participante-edit.js'])
</x-app-layout>
