<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-transparent lg:text-3xl bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500">
                Nueva inscripción
            </h2>
            <x-boton-regresar onclick="window.location.href='{{ route('participante.index') }}'" />
        </div>
    </x-slot>

    <div class="py-8 font-sans bg-gradient-to-br from-slate-50 via-purple-50 to-pink-50 dark:from-slate-800 dark:via-purple-900 dark:to-pink-900">
        <div class="max-w-5xl px-4 mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-xl rounded-xl">
                <div class="px-6 py-5 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 sm:px-8">
                    <h1 class="text-2xl font-semibold text-center text-white">Formulario de Inscripción CREA</h1>
                    <p class="mt-1 text-sm text-center text-indigo-200">Complete todos los campos requeridos (*).</p>
                </div>

                <div class="p-6 sm:p-8">
                    {{-- Los mensajes de sesión (success, error) serán manejados por app.js globalmente --}}
                    {{-- Mostrar errores de validación específicos del formulario --}}
                    @if ($errors->any())
                        <div class="p-4 mb-6 text-sm text-red-700 bg-red-100 border-l-4 border-red-500 rounded-md shadow" role="alert">
                            <p class="font-bold">Por favor corrige los siguientes errores:</p>
                            <ul class="mt-2 text-xs list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('participante.store') }}" method="POST" accept-charset="UTF-8" class="space-y-8" id="inscripcionForm">
                        @csrf

                        <fieldset class="p-4 border border-gray-300 rounded-lg">
                            <legend class="px-2 text-base font-semibold text-indigo-700">Información General de Inscripción</legend>
                            <div class="grid grid-cols-1 mt-2 md:grid-cols-2 gap-x-6 gap-y-4">
                                <div>
                                    <label for="fecha_de_inscripcion" class="block mb-1 text-xs font-medium text-gray-700">Fecha de inscripción <span class="text-red-500">*</span></label>
                                    <input type="date" name="fecha_de_inscripcion" id="fecha_de_inscripcion" value="{{ old('fecha_de_inscripcion', now()->format('Y-m-d')) }}"
                                           class="w-full px-3 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm transition duration-150 ease-in-out @error('fecha_de_inscripcion') border-red-500 @enderror"
                                           required aria-describedby="fecha_de_inscripcion-error">
                                    @error('fecha_de_inscripcion') <p class="mt-1 text-xs text-red-600" id="fecha_de_inscripcion-error">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="ano_de_inscripcion" class="block mb-1 text-xs font-medium text-gray-700">Año de inscripción <span class="text-red-500">*</span></label>
                                    <input type="number" name="ano_de_inscripcion" id="ano_de_inscripcion" value="{{ old('ano_de_inscripcion', now()->year) }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-gray-100 text-sm transition duration-150 ease-in-out @error('ano_de_inscripcion') border-red-500 @enderror"
                                           readonly required aria-describedby="ano_de_inscripcion-error">
                                    @error('ano_de_inscripcion') <p class="mt-1 text-xs text-red-600" id="ano_de_inscripcion-error">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div class="mt-4" style="display: none;">
                                <label class="block mb-1 text-xs font-medium text-gray-700">Estado al Inscribir</label>
                                <div class="flex items-center" >
                                    {{-- Usamos un input hidden para manejar el estado activo --}}
                                    <input type="hidden" name="activo" value="1">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="activo" id="activo_toggle" value="1" class="sr-only peer" {{ old('activo', true) ? 'checked' : '' }}>
                                        <div class="w-9 h-5 bg-gray-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-indigo-500 dark:peer-focus:ring-indigo-600 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border dark:border-gray-500 after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
                                        <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">Activo</span>
                                    </label>
                                </div>
                                @error('activo') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </fieldset>

                        <fieldset class="p-4 border border-gray-300 rounded-lg">
                            <legend class="px-2 text-base font-semibold text-indigo-700">Documentos Requeridos</legend>
                            <div class="grid grid-cols-1 mt-3 sm:grid-cols-2 md:grid-cols-4 gap-x-6 gap-y-5">
                                @php
                                $documentos = [
                                    'partida_de_nacimiento' => 'Copia de partida de nacimiento',
                                    'boletin_o_diploma_2024' => 'Copia boletín o diploma ('.now()->year.')',
                                    'cedula_tutor' => 'Copia de cédula del tutor',
                                    'cedula_participante_adulto' => 'Copia de cédula (participante adulto)',
                                ];
                                @endphp
                                @foreach ($documentos as $fieldName => $label)
                                <div>
                                    <label class="block mb-1 text-xs font-medium text-gray-700">{{ $label }} <span class="text-red-500">*</span></label>
                                    <div class="flex items-center mt-1 space-x-4">
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" name="{{ $fieldName }}" value="1" class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" {{ old($fieldName) == '1' ? 'checked' : '' }} required>
                                            <span class="ml-2 text-xs text-gray-600">Sí</span>
                                        </label>
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" name="{{ $fieldName }}" value="0" class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" {{ old($fieldName, '0') == '0' ? 'checked' : '' }}>
                                            <span class="ml-2 text-xs text-gray-600">No</span>
                                        </label>
                                    </div>
                                    @error($fieldName) <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                @endforeach
                            </div>
                        </fieldset>

                        <fieldset class="p-4 border border-gray-300 rounded-lg">
                            <legend class="px-2 text-base font-semibold text-indigo-700">Información del Participante</legend>
                            <div class="grid grid-cols-1 mt-2 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-4">
                                <div>
                                    <label for="participante_select" class="block mb-1 text-xs font-medium text-gray-700">Nivel del Participante <span class="text-red-500">*</span></label>
                                    <select name="participante" id="participante_select" class="w-full px-3 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm @error('participante') border-red-500 @enderror" required>
                                        <option value="" disabled {{ !old('participante') ? 'selected' : '' }}>Seleccione...</option>
                                        @foreach ($tiposParticipante as $tipo)
                                            <option value="{{ $tipo }}" {{ old('participante') == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                                        @endforeach
                                        <option value="Otro" {{ old('participante') == 'Otro' ? 'selected' : '' }}>Otro (especificar)</option>
                                    </select>
                                    <input type="text" name="participante_otro" id="participante_otro_input"
                                           class="mt-2 w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm {{ (old('participante') == 'Otro' || old('participante_otro')) ? '' : 'hidden' }} @error('participante_otro') border-red-500 @enderror"
                                           value="{{ old('participante_otro') }}"
                                           placeholder="Especificar otro nivel">
                                    @error('participante') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                    @error('participante_otro') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="primer_nombre_p" class="block mb-1 text-xs font-medium text-gray-700">Primer Nombre <span class="text-red-500">*</span></label>
                                    <input type="text" name="primer_nombre_p" id="primer_nombre_p" value="{{ old('primer_nombre_p') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm @error('primer_nombre_p') @enderror" required>
                                    @error('primer_nombre_p') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="segundo_nombre_p" class="block mb-1 text-xs font-medium text-gray-700">Segundo Nombre</label>
                                    <input type="text" name="segundo_nombre_p" id="segundo_nombre_p" value="{{ old('segundo_nombre_p') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm @error('segundo_nombre_p') border-red-500 @enderror">
                                    @error('segundo_nombre_p') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="primer_apellido_p" class="block mb-1 text-xs font-medium text-gray-700">Primer Apellido <span class="text-red-500">*</span></label>
                                    <input type="text" name="primer_apellido_p" id="primer_apellido_p" value="{{ old('primer_apellido_p') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm @error('primer_apellido_p') border-red-500 @enderror" required>
                                    @error('primer_apellido_p') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="segundo_apellido_p" class="block mb-1 text-xs font-medium text-gray-700">Segundo Apellido</label>
                                    <input type="text" name="segundo_apellido_p" id="segundo_apellido_p" value="{{ old('segundo_apellido_p') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm @error('segundo_apellido_p') border-red-500 @enderror">
                                    @error('segundo_apellido_p') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                 <div>
                                    <label for="cedula_participante_adulto_str" class="block mb-1 text-xs font-medium text-gray-700">Cédula (si es adulto)</label>
                                    <input type="text" name="cedula_participante_adulto_str" id="cedula_participante_adulto_str" value="{{ old('cedula_participante_adulto_str') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm @error('cedula_participante_adulto_str') border-red-500 @enderror">
                                    @error('cedula_participante_adulto_str') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="fecha_de_nacimiento_p" class="block mb-1 text-xs font-medium text-gray-700">Fecha de Nacimiento <span class="text-red-500">*</span></label>
                                    <input type="date" name="fecha_de_nacimiento_p" id="fecha_de_nacimiento_p" value="{{ old('fecha_de_nacimiento_p') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm @error('fecha_de_nacimiento_p') border-red-500 @enderror" required>
                                    @error('fecha_de_nacimiento_p') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="edad_p" class="block mb-1 text-xs font-medium text-gray-700">Edad <span class="text-red-500">*</span></label>
                                    <input type="number" name="edad_p" id="edad_p" value="{{ old('edad_p') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm bg-gray-100 text-sm @error('edad_p') border-red-500 @enderror" readonly required>
                                    @error('edad_p') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="genero" class="block mb-1 text-xs font-medium text-gray-700">Género <span class="text-red-500">*</span></label>
                                    <select name="genero" id="genero" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm @error('genero') border-red-500 @enderror" required>
                                        <option value="" disabled {{ old('genero') ? '' : 'selected' }}>Seleccione...</option>
                                        <option value="Masculino" {{ old('genero') == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                        <option value="Femenino" {{ old('genero') == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                                    </select>
                                    @error('genero') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="comunidad_p" class="block mb-1 text-xs font-medium text-gray-700">Comunidad del Participante <span class="text-red-500">*</span></label>
                                    <select name="comunidad_p" id="comunidad_p" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm @error('comunidad_p') border-red-500 @enderror" required>
                                        <option value="" disabled {{ old('comunidad_p') ? '' : 'selected' }}>Seleccione...</option>
                                        @foreach ($comunidades as $comunidad)
                                            <option value="{{ $comunidad }}" {{ old('comunidad_p') == $comunidad ? 'selected' : '' }}>{{ $comunidad }}</option>
                                        @endforeach
                                    </select>
                                    @error('comunidad_p') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="ciudad_p" class="block mb-1 text-xs font-medium text-gray-700">Ciudad <span class="text-red-500">*</span></label>
                                    <input type="text" name="ciudad_p" id="ciudad_p" value="{{ old('ciudad_p', 'Tola') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm @error('ciudad_p') border-red-500 @enderror" required>
                                    @error('ciudad_p') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="departamento_p" class="block mb-1 text-xs font-medium text-gray-700">Departamento <span class="text-red-500">*</span></label>
                                    <input type="text" name="departamento_p" id="departamento_p" value="{{ old('departamento_p', 'Rivas') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm @error('departamento_p') border-red-500 @enderror" required>
                                    @error('departamento_p') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="p-4 border border-gray-300 rounded-lg">
                            <legend class="px-2 text-base font-semibold text-indigo-700">Información Educativa</legend>
                            <div class="grid grid-cols-1 mt-2 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-4">
                                <div>
                                    <label for="escuela_p" class="block mb-1 text-xs font-medium text-gray-700">Nombre de la Escuela <span class="text-red-500">*</span></label>
                                    <input type="text" name="escuela_p" id="escuela_p" value="{{ old('escuela_p') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm @error('escuela_p') border-red-500 @enderror" required>
                                    @error('escuela_p') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="comunidad_escuela" class="block mb-1 text-xs font-medium text-gray-700">Comunidad de la Escuela <span class="text-red-500">*</span></label>
                                    <select name="comunidad_escuela" id="comunidad_escuela" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm @error('comunidad_escuela') border-red-500 @enderror" required>
                                        <option value="" disabled {{ old('comunidad_escuela') ? '' : 'selected' }}>Seleccione...</option>
                                         @foreach ($comunidades as $comunidad)
                                            <option value="{{ $comunidad }}" {{ old('comunidad_escuela') == $comunidad ? 'selected' : '' }}>{{ $comunidad }}</option>
                                        @endforeach
                                    </select>
                                    @error('comunidad_escuela') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="grado_p" class="block mb-1 text-xs font-medium text-gray-700">Grado <span class="text-red-500">*</span></label>
                                    <select name="grado_p" id="grado_p" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm @error('grado_p') border-red-500 @enderror" required>
                                        <option value="" disabled {{ old('grado_p') ? '' : 'selected' }}>Seleccione...</option>
                                        @for ($i = 0; $i <= 12; $i++)
                                            <option value="{{ $i }}" {{ old('grado_p') == $i ? 'selected' : '' }}>{{ $i == 0 ? 'Preescolar' : $i }}</option>
                                        @endfor
                                    </select>
                                    @error('grado_p') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="turno" class="block mb-1 text-xs font-medium text-gray-700">Turno <span class="text-red-500">*</span></label>
                                    <select name="turno" id="turno" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm @error('turno') border-red-500 @enderror" required>
                                        <option value="" disabled {{ old('turno') ? '' : 'selected' }}>Seleccione...</option>
                                        <option value="Matutino" {{ old('turno') == 'Matutino' ? 'selected' : '' }}>Matutino</option>
                                        <option value="Vespertino" {{ old('turno') == 'Vespertino' ? 'selected' : '' }}>Vespertino</option>
                                        <option value="Sabatino" {{ old('turno') == 'Sabatino' ? 'selected' : '' }}>Sabatino</option>
                                        <option value="No Aplica" {{ old('turno') == 'No Aplica' ? 'selected' : '' }}>No Aplica (ej. Adulto)</option>
                                    </select>
                                    @error('turno') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block mb-1 text-xs font-medium text-gray-700">¿Repite Grado? <span class="text-red-500">*</span></label>
                                    <div class="flex items-center mt-1 space-x-4">
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" name="repite_grado" value="1" class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" {{ old('repite_grado') == '1' ? 'checked' : '' }} required>
                                            <span class="ml-2 text-xs text-gray-600">Sí</span>
                                        </label>
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" name="repite_grado" value="0" class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" {{ old('repite_grado', '0') == '0' ? 'checked' : '' }}>
                                            <span class="ml-2 text-xs text-gray-600">No</span>
                                        </label>
                                    </div>
                                    @error('repite_grado') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="p-4 border border-gray-300 rounded-lg">
                            <legend class="px-2 text-base font-semibold text-indigo-700">Detalles del Programa CREA</legend>
                            <div class="mt-2 space-y-4">
                                <div>
                                    <label class="block mb-2 text-xs font-medium text-gray-700">Programa(s) Principal(es) <span class="text-red-500">*</span></label>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-x-6 gap-y-3">
                                        @foreach ($programaOptionsList ?? [] as $programaItem)
                                            <label class="flex items-center text-sm text-gray-600 cursor-pointer">
                                                <input type="checkbox" name="programa[]" value="{{ $programaItem }}"
                                                       @if(is_array(old('programa')) && in_array($programaItem, old('programa', []))) checked @endif
                                                       class="w-4 h-4 mr-2 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                                {{ $programaItem }}
                                            </label>
                                        @endforeach
                                    </div>
                                    @error('programa') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block mb-2 text-xs font-medium text-gray-700">Sub-Programa(s) <span class="text-red-500">*</span></label>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7 gap-x-4 gap-y-3">
                                        @foreach ($subProgramaOptionsList ?? [] as $subProgramaItem)
                                            <label class="flex items-center text-sm text-gray-600 cursor-pointer">
                                                <input type="checkbox" name="programas[]" value="{{ $subProgramaItem }}"
                                                       @if(is_array(old('programas')) && in_array($subProgramaItem, old('programas', []))) checked @endif
                                                       class="w-4 h-4 mr-2 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                                {{ $subProgramaItem }}
                                            </label>
                                        @endforeach
                                    </div>
                                    @error('programas') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="lugar_de_encuentro_del_programa" class="block mb-1 text-xs font-medium text-gray-700">Lugar de encuentro del programa <span class="text-red-500">*</span></label>
                                    <select name="lugar_de_encuentro_del_programa" id="lugar_de_encuentro_del_programa" class="w-full md:w-1/2 lg:w-1/3 px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm @error('lugar_de_encuentro_del_programa') border-red-500 @enderror" required>
                                        <option value="" disabled {{ old('lugar_de_encuentro_del_programa') ? '' : 'selected' }}>Seleccione...</option>
                                        <option value="Asentamiento" {{ old('lugar_de_encuentro_del_programa') == 'Asentamiento' ? 'selected' : '' }}>Asentamiento</option>
                                        <option value="CREA" {{ old('lugar_de_encuentro_del_programa') == 'CREA' ? 'selected' : '' }}>CREA</option>
                                        <option value="Las Salinas" {{ old('lugar_de_encuentro_del_programa') == 'Las Salinas' ? 'selected' : '' }}>Las Salinas</option>
                                        <option value="Limón 1" {{ old('lugar_de_encuentro_del_programa') == 'Limón 1' ? 'selected' : '' }}>Limón 1</option>
                                        <option value="Las Mercedes" {{ old('lugar_de_encuentro_del_programa') == 'Las Mercedes' ? 'selected' : '' }}>Las Mercedes</option>
                                        <option value="Virgen Morena" {{ old('lugar_de_encuentro_del_programa') == 'Virgen Morena' ? 'selected' : '' }}>Virgen Morena</option>
                                        <option value="Ojochal" {{ old('lugar_de_encuentro_del_programa') == 'Ojochal' ? 'selected' : '' }}>Ojochal</option>
                                    </select>
                                    @error('lugar_de_encuentro_del_programa') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block mb-2 text-xs font-medium text-gray-700">Días de Asistencia Esperados al Programa CREA <span class="text-red-500">*</span></label>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-x-6 gap-y-3">
                                        @php $diasSeleccionados = old('dias_de_asistencia_al_programa', []); @endphp
                                        @foreach ($diasOptionsList ?? ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'] as $dia)
                                            <label class="flex items-center text-sm text-gray-600 cursor-pointer">
                                                <input type="checkbox" name="dias_de_asistencia_al_programa[]" value="{{ $dia }}"
                                                       @if(is_array($diasSeleccionados) && in_array($dia, $diasSeleccionados)) checked @endif
                                                       class="w-4 h-4 mr-2 text-indigo-600 border-gray-300 rounded dias-asistencia focus:ring-indigo-500">
                                                {{ $dia }}
                                            </label>
                                        @endforeach
                                    </div>
                                    @error('dias_de_asistencia_al_programa') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="p-4 border border-gray-300 rounded-lg">
                            <legend class="px-2 text-base font-semibold text-indigo-700">Información del Tutor Principal</legend>
                            <div class="grid grid-cols-1 mt-2 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-4">
                                <div>
                                    <label for="tutor_principal" class="block mb-1 text-xs font-medium text-gray-700">Parentesco <span class="text-red-500">*</span></label>
                                    <select name="tutor_principal" id="tutor_principal" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm @error('tutor_principal') border-red-500 @enderror" required>
                                        <option value="" disabled {{ old('tutor_principal') ? '' : 'selected' }}>Seleccione...</option>
                                        @foreach ($tipos_tutor as $tipo)
                                            <option value="{{ $tipo }}" {{ old('tutor_principal') == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                                        @endforeach
                                    </select>
                                    @error('tutor_principal') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div class="lg:col-span-2">
                                    <label for="nombres_y_apellidos_tutor_principal" class="block mb-1 text-xs font-medium text-gray-700">Nombres y Apellidos <span class="text-red-500">*</span></label>
                                    <input type="text" name="nombres_y_apellidos_tutor_principal" id="nombres_y_apellidos_tutor_principal" value="{{ old('nombres_y_apellidos_tutor_principal') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm @error('nombres_y_apellidos_tutor_principal') border-red-500 @enderror" required>
                                    @error('nombres_y_apellidos_tutor_principal') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="numero_de_cedula_tutor" class="block mb-1 text-xs font-medium text-gray-700">Número de Cédula <span class="text-red-500">*</span></label>
                                    <input type="text" name="numero_de_cedula_tutor" id="numero_de_cedula_tutor" value="{{ old('numero_de_cedula_tutor') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm @error('numero_de_cedula_tutor') border-red-500 @enderror" required>
                                    @error('numero_de_cedula_tutor') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="comunidad_tutor" class="block mb-1 text-xs font-medium text-gray-700">Comunidad del Tutor <span class="text-red-500">*</span></label>
                                    <select name="comunidad_tutor" id="comunidad_tutor" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm @error('comunidad_tutor') border-red-500 @enderror" required>
                                        <option value="" disabled {{ old('comunidad_tutor') ? '' : 'selected' }}>Seleccione...</option>
                                        @foreach ($comunidades as $comunidad)
                                            <option value="{{ $comunidad }}" {{ old('comunidad_tutor') == $comunidad ? 'selected' : '' }}>{{ $comunidad }}</option>
                                        @endforeach
                                    </select>
                                    @error('comunidad_tutor') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div class="lg:col-span-3">
                                    <label for="direccion_tutor" class="block mb-1 text-xs font-medium text-gray-700">Dirección <span class="text-red-500">*</span></label>
                                    <textarea name="direccion_tutor" id="direccion_tutor" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm @error('direccion_tutor') border-red-500 @enderror" required>{{ old('direccion_tutor') }}</textarea>
                                    @error('direccion_tutor') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="telefono_tutor" class="block mb-1 text-xs font-medium text-gray-700">Teléfono <span class="text-red-500">*</span></label>
                                    <input type="tel" name="telefono_tutor" id="telefono_tutor" value="{{ old('telefono_tutor') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm @error('telefono_tutor') border-red-500 @enderror" required>
                                    @error('telefono_tutor') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="sector_economico_tutor" class="block mb-1 text-xs font-medium text-gray-700">Sector Económico <span class="text-red-500">*</span></label>
                                    <select name="sector_economico_tutor" id="sector_economico_tutor" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm @error('sector_economico_tutor') border-red-500 @enderror" required>
                                        <option value="" disabled {{ old('sector_economico_tutor') ? '' : 'selected' }}>Seleccione...</option>
                                        @foreach ($sector_economico as $sector)
                                            <option value="{{ $sector }}" {{ old('sector_economico_tutor') == $sector ? 'selected' : '' }}>{{ $sector }}</option>
                                        @endforeach
                                    </select>
                                    @error('sector_economico_tutor') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="nivel_de_educacion_formal_adquirido_tutor" class="block mb-1 text-xs font-medium text-gray-700">Nivel de Educación <span class="text-red-500">*</span></label>
                                    <select name="nivel_de_educacion_formal_adquirido_tutor" id="nivel_de_educacion_formal_adquirido_tutor" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm @error('nivel_de_educacion_formal_adquirido_tutor') border-red-500 @enderror" required>
                                        <option value="" disabled {{ old('nivel_de_educacion_formal_adquirido_tutor') ? '' : 'selected' }}>Seleccione...</option>
                                        @foreach ($nivel_educacion as $nivel)
                                            <option value="{{ $nivel }}" {{ old('nivel_de_educacion_formal_adquirido_tutor') == $nivel ? 'selected' : '' }}>{{ $nivel }}</option>
                                        @endforeach
                                    </select>
                                    @error('nivel_de_educacion_formal_adquirido_tutor') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div class="md:col-span-2 lg:col-span-3">
                                    <label for="expectativas_del_programa_tutor_principal" class="block mb-1 text-xs font-medium text-gray-700">Expectativas del Programa <span class="text-red-500">*</span></label>
                                    <textarea name="expectativas_del_programa_tutor_principal" id="expectativas_del_programa_tutor_principal" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm @error('expectativas_del_programa_tutor_principal') border-red-500 @enderror" required>{{ old('expectativas_del_programa_tutor_principal') }}</textarea>
                                    @error('expectativas_del_programa_tutor_principal') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="p-4 border border-gray-300 rounded-lg">
                            <legend class="px-2 text-base font-semibold text-indigo-700">Información del Tutor Secundario (Opcional)</legend>
                            <div class="grid grid-cols-1 mt-2 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-4">
                                <div>
                                    <label for="tutor_secundario" class="block mb-1 text-xs font-medium text-gray-700">Parentesco</label>
                                    <select name="tutor_secundario" id="tutor_secundario" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm @error('tutor_secundario') border-red-500 @enderror">
                                        <option value="" selected>Seleccione (si aplica)...</option>
                                        @foreach ($tipos_tutor as $tipo) <option value="{{ $tipo }}" {{ old('tutor_secundario') == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                                        @endforeach
                                    </select>
                                    @error('tutor_secundario') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div class="lg:col-span-2">
                                    <label for="nombres_y_apellidos_tutor_secundario" class="block mb-1 text-xs font-medium text-gray-700">Nombres y Apellidos</label>
                                    <input type="text" name="nombres_y_apellidos_tutor_secundario" id="nombres_y_apellidos_tutor_secundario" value="{{ old('nombres_y_apellidos_tutor_secundario') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm @error('nombres_y_apellidos_tutor_secundario') border-red-500 @enderror">
                                    @error('nombres_y_apellidos_tutor_secundario') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="numero_de_cedula_tutor_secundario" class="block mb-1 text-xs font-medium text-gray-700">Número de Cédula</label>
                                    <input type="text" name="numero_de_cedula_tutor_secundario" id="numero_de_cedula_tutor_secundario" value="{{ old('numero_de_cedula_tutor_secundario') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm @error('numero_de_cedula_tutor_secundario') border-red-500 @enderror">
                                    @error('numero_de_cedula_tutor_secundario') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="comunidad_tutor_secundario" class="block mb-1 text-xs font-medium text-gray-700">Comunidad del Tutor Secundario</label>
                                    <select name="comunidad_tutor_secundario" id="comunidad_tutor_secundario" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm @error('comunidad_tutor_secundario') border-red-500 @enderror">
                                        <option value="" selected>Seleccione (si aplica)...</option>
                                        @foreach ($comunidades as $comunidad)
                                            <option value="{{ $comunidad }}" {{ old('comunidad_tutor_secundario') == $comunidad ? 'selected' : '' }}>{{ $comunidad }}</option>
                                        @endforeach
                                    </select>
                                     @error('comunidad_tutor_secundario') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="telefono_tutor_secundario" class="block mb-1 text-xs font-medium text-gray-700">Teléfono</label>
                                    <input type="tel" name="telefono_tutor_secundario" id="telefono_tutor_secundario" value="{{ old('telefono_tutor_secundario') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm @error('telefono_tutor_secundario') border-red-500 @enderror">
                                    @error('telefono_tutor_secundario') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="p-4 border border-gray-300 rounded-lg">
                            <legend class="px-2 text-base font-semibold text-indigo-700">Participación en Otros Programas</legend>
                            <div class="grid grid-cols-1 mt-2 md:grid-cols-2 gap-x-6 gap-y-4">
                                <div>
                                    <label class="block mb-1 text-xs font-medium text-gray-700">¿Asiste a otros programas fuera de CREA? <span class="text-red-500">*</span></label>
                                    <div class="flex items-center mt-1 space-x-4">
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" name="asiste_a_otros_programas" value="1" class="w-4 h-4 text-indigo-600 border-gray-300 asiste-otros-radio focus:ring-indigo-500" {{ old('asiste_a_otros_programas') == '1' ? 'checked' : '' }} required>
                                            <span class="ml-2 text-xs text-gray-600">Sí</span>
                                        </label>
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" name="asiste_a_otros_programas" value="0" class="w-4 h-4 text-indigo-600 border-gray-300 asiste-otros-radio focus:ring-indigo-500" {{ old('asiste_a_otros_programas', '0') == '0' ? 'checked' : '' }}>
                                            <span class="ml-2 text-xs text-gray-600">No</span>
                                        </label>
                                    </div>
                                     @error('asiste_a_otros_programas') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div id="otros-programas-detalles-section" class="{{ old('asiste_a_otros_programas') == '1' ? '' : 'hidden' }} space-y-4 md:col-span-2">
                                    <div>
                                        <label for="otros_programas" class="block mb-1 text-xs font-medium text-gray-700">¿Cuáles programas?</label>
                                        <input type="text" name="otros_programas" id="otros_programas" value="{{ old('otros_programas') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm @error('otros_programas') border-red-500 @enderror">
                                        @error('otros_programas') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label class="block mb-2 text-xs font-medium text-gray-700">Días que asiste a esos otros programas</label>
                                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-x-6 gap-y-3">
                                            @php $diasOtrosSeleccionados = old('dias_asiste_a_otros_programas', []); @endphp
                                            @foreach ($diasOptionsList ?? ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'] as $dia)
                                                <label class="flex items-center text-sm text-gray-600 cursor-pointer">
                                                    <input type="checkbox" name="dias_asiste_a_otros_programas[]" value="{{ $dia }}"
                                                           @if(is_array($diasOtrosSeleccionados) && in_array($dia, $diasOtrosSeleccionados)) checked @endif
                                                           class="w-4 h-4 mr-2 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                                    {{ $dia }}
                                                </label>
                                            @endforeach
                                        </div>
                                        @error('dias_asiste_a_otros_programas') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <div class="flex flex-col items-center justify-end pt-8 space-y-3 sm:flex-row sm:space-y-0 sm:space-x-4">
                             <x-secondary-button type="button"> {{-- El onclick se asignará por JS --}}
                                Limpiar Formulario
                            </x-secondary-button>
                            <x-primary-button type="submit">
                                Registrar Participante
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Script para pasar datos 'old' de Laravel a JS. Esto es mejor que generarlos directamente en el JS. --}}
    <script>
        // Estas variables globales serán leídas por participante-create.js
        const _oldActivo = {{ old('activo', true) ? 'true' : 'false' }}; // Asegurar que sea booleano JS
        const _oldParticipante = @json(old('participante'));
        const _oldParticipanteOtro = @json(old('participante_otro'));
    </script>
    @vite(['resources/js/pages/participante-create.js'])
</x-app-layout>
