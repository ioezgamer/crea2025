<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-transparent lg:text-3xl bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500">
                Nueva inscripción
            </h2>
            <x-boton-regresar onclick="window.location.href='{{ route('participante.index') }}'" />
        </div>
    </x-slot>

    <div class="py-12 font-sans bg-gray-100 dark:bg-slate-900">
        <div class="max-w-6xl px-4 mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden border shadow-2xl border-purple-400/50 dark:border-slate-700/50 bg-white/60 dark:bg-slate-800/60 backdrop-blur-xl rounded-3xl">
                <div class="px-6 py-5 bg-gradient-to-r from-indigo-600/80 via-purple-600/80 to-pink-500/80 sm:px-8 backdrop-blur-2xl backdrop-blur-xs">
                    <h1 class="text-3xl font-semibold text-center text-white">Formulario de Inscripción CREA</h1>
                    <p class="mt-1 text-sm text-center text-indigo-200">Complete todos los campos requeridos (*).</p>
                </div>

                <div class="p-6 sm:p-8 ">
                    @if ($errors->any())
                        <div class="p-4 mb-6 text-sm text-red-700 bg-red-100 border-l-4 border-red-500 shadow rounded-3xl" role="alert">
                            <p class="font-bold">Por favor corrige los siguientes errores:</p>
                            <ul class="mt-2 text-xs list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{--
                        MEJORA: Se añade x-data al formulario para manejar todo el estado de la UI.
                        - `esParticipanteAdulto`: Controla los campos relacionados a participantes adultos.
                        - `esOtro`: Controla el campo de texto para el tipo de participante "Otro".
                        - Los valores se inicializan con `old()` para mantener el estado tras errores de validación.
                    --}}
                    <form
                        action="{{ route('participante.store') }}"
                        method="POST"
                        accept-charset="UTF-8"
                        class="space-y-8"
                        id="inscripcionForm"
                        x-data="{
                            esParticipanteAdulto: '{{ old('participante', $participante->participante ?? '') }}' === 'Adulto',
                            esOtro: '{{ old('participante', $participante->participante ?? '') }}' === 'Otro'
                        }"
                    >
                        @csrf

                        {{-- === SECCIÓN 1: INFORMACIÓN GENERAL === --}}
                        <fieldset class="p-6 border border-purple-400 rounded-3xl hover:bg-slate-100/50 dark:hover:bg-slate-100">
                            <legend class="px-2 text-xl font-semibold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500">Información general de la inscripción</legend>
                            <div class="grid grid-cols-3 mt-2 gap-x-6 gap-y-4 ">
                                <div>
                                    <x-input-label for="fecha_de_inscripcion" >
                                        Fecha de inscripción <span class="text-red-500">*</span>
                                    </x-input-label>
                                    <x-date-picker id="fecha_de_inscripcion" name="fecha_de_inscripcion" :value="old('fecha_de_inscripcion', now()->format('Y-m-d'))" class="block w-full mt-1" />
                                    <x-input-error :messages="$errors->get('fecha_de_inscripcion')" class="mt-2" />
                                </div>
                                <div class="hidden ">
                                    <label for="ano_de_inscripcion" class="block mb-1 text-sm font-medium text-slate-700">Año de inscripción <span class="text-red-500">*</span></label>
                                    <input type="number" name="ano_de_inscripcion" id="ano_de_inscripcion" value="{{ old('ano_de_inscripcion', now()->year) }}"
                                           class="w-full px-3 py-2 text-sm bg-gray-100 border border-gray-300 shadow-sm rounded-3xl"
                                           readonly required>
                                    @error('ano_de_inscripcion') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <input type="hidden" name="activo" value="1">
                            </div>
                        </fieldset>

                        {{-- === SECCIÓN 2: DOCUMENTOS REQUERIDOS (CON LÓGICA DINÁMICA) === --}}
                        <!-- Bloque 3: Documentos Requeridos -->
<fieldset class="p-6 mt-8 border border-purple-400 rounded-3xl hover:bg-slate-100/50 dark:hover:bg-slate-800/50">
    <legend class="px-2 text-xl font-semibold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500">Documentos requeridos</legend>
    <div class="grid grid-cols-1 mt-3 sm:grid-cols-2 md:grid-cols-4 gap-x-6 gap-y-5 ">
        @php
        $documentos = [
            'partida_de_nacimiento' => 'Partida de nacimiento',
            'boletin_o_diploma_2024' => 'Boletín o diploma ('.now()->year.')',
            'cedula_tutor' => 'Cédula del tutor',
            'cedula_participante_adulto' => 'Cédula (participante adulto)',
        ];
        @endphp
        @foreach ($documentos as $fieldName => $label)
        <div
            @if ($fieldName === 'cedula_participante_adulto')
                x-show="$store.formState.esAdulto"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-end="opacity-0 transform -translate-y-2"
            @endif
        >
            <label class="block mb-1 text-sm font-medium text-slate-800 dark:text-slate-200">{{ $label }} <span class="text-red-500">*</span></label>
            <div x-data="{ selected: '{{ old($fieldName, $participante->$fieldName ?? null) ?? '0' }}' }" class="flex items-center mt-1 space-x-4">
                <label class="flex items-center justify-center w-8 h-8 text-lg font-bold border rounded-full cursor-pointer" :class="selected == '1' ? 'bg-green-100 text-green-700 border-green-500 ring-2 ring-green-300' : 'border-gray-300 text-green-600'">
                    <input type="radio" name="{{ $fieldName }}" value="1" class="hidden" @click="selected = '1'" :checked="selected === '1'" required>
                    ✓
                </label>
                <label class="flex items-center justify-center w-8 h-8 text-lg font-bold border rounded-full cursor-pointer" :class="selected == '0' ? 'bg-red-100 text-red-700 border-red-500 ring-2 ring-red-300' : 'border-gray-300 text-red-600'">
                    <input type="radio" name="{{ $fieldName }}" value="0" class="hidden" @click="selected = '0'" :checked="selected === '0'">
                    ✕
                </label>
            </div>
            @error($fieldName) <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        @endforeach
    </div>
</fieldset>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.store('formState', {
            // Inicializa con los valores antiguos de Laravel o null
            tipoParticipante: '{{ old('participante') }}' || null,
            grado: '{{ old('grado_p') }}' || null,

            // Determina si el participante es "Adulto"
            get esAdulto() {
                return this.tipoParticipante === 'Adulto';
            },

            // Determina si el campo 'Otro' debe mostrarse
            get esOtro() {
                return this.tipoParticipante === 'Otro';
            },

            // Calcula los grados disponibles según el tipo de participante
            get gradosDisponibles() {
                switch (this.tipoParticipante) {
                    case 'Primaria':
                        return [1, 2, 3, 4, 5, 6];
                    case 'Secundaria':
                        return [7, 8, 9, 10, 11];
                    case 'Preescolar o menos':
                        return [0];
                    case 'Adulto':
                        return [12];
                    default:
                        return []; // No hay grados para 'Otro' o si no se ha seleccionado nada
                }
            },

            // Devuelve la etiqueta correcta para cada valor de grado
            gradoLabel(g) {
                if (g === null || g === '') return 'Seleccione...';
                if (g == 0) return 'Preescolar';
                if (g == 12) return 'Adulto';
                return g;
            },

            // Función para manejar el cambio de tipo de participante
            seleccionarTipo(tipo) {
                // Si el tipo cambia, reseteamos el grado seleccionado
                if (this.tipoParticipante !== tipo) {
                    this.grado = null;
                }
                this.tipoParticipante = tipo;
            }
        });
    });
</script>
                        {{-- === SECCIÓN 3: INFORMACIÓN DEL PARTICIPANTE (CON LÓGICA DINÁMICA) === --}}
                        <fieldset class="p-6 border border-purple-400 rounded-3xl hover:bg-slate-100/50 dark:hover:bg-slate-100">
                            <legend class="px-2 text-xl font-semibold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500">Información del participante</legend>
                            <div class="grid grid-cols-1 mt-2 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-4">
                                {{-- Selector de participante que controla otros campos --}}

                                <!-- Selector de Tipo de Participante -->
    <div>
        <label for="participante" class="block mb-1 text-sm font-medium text-slate-800 dark:text-slate-200">Tipo de Participante <span class="text-red-500">*</span></label>
        <div x-data="{ open: false }" class="relative">
            <!-- Botón del Dropdown -->
            <button @click="open = !open" type="button" class="relative w-full px-3 py-2 text-left bg-white border border-gray-300 shadow-sm dark:bg-slate-700 dark:border-slate-600 rounded-3xl focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:text-sm">
                <span class="block truncate" x-text="$store.formState.tipoParticipante || 'Seleccione...'"></span>
                <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none"><svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a.75.75 0 01.53.22l3.5 3.5a.75.75 0 01-1.06 1.06L10 4.81 6.53 8.28a.75.75 0 01-1.06-1.06l3.5-3.5A.75.75 0 0110 3zm-3.72 9.53a.75.75 0 011.06 0L10 15.19l3.47-3.47a.75.75 0 111.06 1.06l-4 4a.75.75 0 01-1.06 0l-4-4a.75.75 0 010-1.06z" clip-rule="evenodd" /></svg></span>
            </button>

            <!-- Panel de Opciones -->
            <div x-show="open" @click.away="open = false" x-transition class="absolute z-50 w-full mt-1 bg-white shadow-lg rounded-xl dark:bg-slate-800 max-h-60 ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm" style="display: none;">
                @foreach ($tiposParticipante as $tipo)
                    <div @click="$store.formState.seleccionarTipo('{{ $tipo }}'); open = false" :class="{'bg-indigo-600 text-white': $store.formState.tipoParticipante === '{{ $tipo }}'}" class="relative py-2 pl-3 text-gray-900 cursor-pointer select-none pr-9 hover:bg-indigo-600 hover:text-white dark:text-white"><span class="block truncate">{{ $tipo }}</span></div>
                @endforeach
                <div @click="$store.formState.seleccionarTipo('Otro'); open = false" :class="{'bg-indigo-600 text-white': $store.formState.tipoParticipante === 'Otro'}" class="relative py-2 pl-3 text-gray-900 cursor-pointer select-none pr-9 hover:bg-indigo-600 hover:text-white dark:text-white"><span class="block truncate">Otro (especificar)</span></div>
            </div>

            <!-- Select oculto para el envío del formulario -->
            <select name="participante" id="participante_select" x-model="$store.formState.tipoParticipante" class="hidden" required>
                <option value="" disabled>Seleccione...</option>
                @foreach ($tiposParticipante as $tipo)
                    <option value="{{ $tipo }}">{{ $tipo }}</option>
                @endforeach
                <option value="Otro">Otro (especificar)</option>
            </select>
        </div>
    </div>

    <!-- Campo de texto para "Otro" (Condicional) -->
    <div x-show="$store.formState.esOtro" x-transition>
        <label for="participante_otro" class="block mb-1 text-sm font-medium text-slate-800 dark:text-slate-200">Especifique el tipo <span class="text-red-500">*</span></label>
        <input type="text" name="participante_otro" id="participante_otro"
               class="w-full px-3 py-2 bg-white border border-gray-300 shadow-sm dark:bg-slate-700 dark:border-slate-600 rounded-3xl focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:text-sm"
               :required="$store.formState.esOtro" placeholder="Ej: Docente, Padre de familia, etc.">
    </div>
                                <div>
                                    <label for="primer_nombre_p" class="block mb-1 text-sm font-medium text-slate-800">Primer nombre <span class="text-red-500">*</span></label>
                                    <input type="text" name="primer_nombre_p" id="primer_nombre_p" value="{{ old('primer_nombre_p') }}" class="w-full px-3 py-2 text-sm border border-gray-300 shadow-sm rounded-3xl" required>
                                    @error('primer_nombre_p') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="segundo_nombre_p" class="block mb-1 text-sm font-medium text-slate-800">Segundo nombre</label>
                                    <input type="text" name="segundo_nombre_p" id="segundo_nombre_p" value="{{ old('segundo_nombre_p') }}" class="w-full px-3 py-2 text-sm border border-gray-300 shadow-sm rounded-3xl">
                                </div>
                                <div>
                                    <label for="primer_apellido_p" class="block mb-1 text-sm font-medium text-slate-800">Primer apellido <span class="text-red-500">*</span></label>
                                    <input type="text" name="primer_apellido_p" id="primer_apellido_p" value="{{ old('primer_apellido_p') }}" class="w-full px-3 py-2 text-sm border border-gray-300 shadow-sm rounded-3xl" required>
                                    @error('primer_apellido_p') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="segundo_apellido_p" class="block mb-1 text-sm font-medium text-slate-800">Segundo apellido</label>
                                    <input type="text" name="segundo_apellido_p" id="segundo_apellido_p" value="{{ old('segundo_apellido_p') }}" class="w-full px-3 py-2 text-sm border border-gray-300 shadow-sm rounded-3xl">
                                </div>
                                 {{-- Campo de Cédula que se muestra condicionalmente --}}
                                <div x-show="esParticipanteAdulto" x-transition>
                                    <label for="cedula_participante_adulto_str" class="block mb-1 text-sm font-medium text-slate-800">Cédula (si es adulto)</label>
                                    <input type="text" name="cedula_participante_adulto_str" id="cedula_participante_adulto_str" value="{{ old('cedula_participante_adulto_str') }}" class="w-full px-3 py-2 text-sm border border-gray-300 shadow-sm rounded-3xl" :required="esParticipanteAdulto">
                                    @error('cedula_participante_adulto_str') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <x-input-label for="fecha_de_nacimiento_p">Fecha de nacimiento</x-input-label>
                                    <x-date-picker id="fecha_de_nacimiento_p" name="fecha_de_nacimiento_p" :value="old('fecha_de_nacimiento_p')" class="block w-full mt-1" />
                                    <x-input-error :messages="$errors->get('fecha_de_nacimiento_p')" class="mt-2" />
                                </div>
                                <div>
                                    <label for="edad_p" class="block mb-1 text-sm font-medium text-slate-800">Edad <span class="text-red-500">*</span></label>
                                    <input type="number" name="edad_p" id="edad_p" value="{{ old('edad_p') }}" class="w-full px-3 py-2 text-sm bg-gray-100 border border-gray-300 shadow-sm rounded-3xl" readonly required>
                                    @error('edad_p') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="genero" class="block mb-1 text-sm font-medium text-slate-800">Género <span class="text-red-500">*</span></label>
                                    <div x-data="{ open: false, selected: '{{ old('genero') }}' }" class="relative">
                                        <button @click="open = !open" type="button" class="relative w-full px-3 py-2 text-left bg-white border border-gray-300 shadow-sm rounded-3xl focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:text-sm">
                                            <span class="block truncate" x-text="selected || 'Seleccione...'"></span>
                                            <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none"><svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a.75.75 0 01.53.22l3.5 3.5a.75.75 0 01-1.06 1.06L10 4.81 6.53 8.28a.75.75 0 01-1.06-1.06l3.5-3.5A.75.75 0 0110 3zm-3.72 9.53a.75.75 0 011.06 0L10 15.19l3.47-3.47a.75.75 0 111.06 1.06l-4 4a.75.75 0 01-1.06 0l-4-4a.75.75 0 010-1.06z" clip-rule="evenodd" /></svg></span>
                                        </button>
                                        <div x-show="open" @click.away="open = false" x-transition class="absolute z-40 w-full mt-1 bg-white rounded-md shadow-lg dark:bg-slate-800 max-h-60 ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm " style="display: none;">
                                            <div @click="selected = 'Masculino'; open = false" :class="{'bg-indigo-600 text-white': selected === 'Masculino'}" class="relative py-2 pl-3 text-gray-900 cursor-default select-none pr-9 hover:bg-indigo-600 hover:text-white dark:text-white hover:rounded-md"><span class="block truncate">Masculino</span></div>
                                            <div @click="selected = 'Femenino'; open = false" :class="{'bg-indigo-600 text-white': selected === 'Femenino'}" class="relative py-2 pl-3 text-gray-900 cursor-default select-none pr-9 hover:bg-indigo-600 hover:text-white dark:text-white"><span class="block truncate">Femenino</span></div>
                                        </div>
                                        <select name="genero" id="genero" x-model="selected" class="hidden" required>
                                            <option value="" disabled>Seleccione...</option>
                                            <option value="Masculino">Masculino</option>
                                            <option value="Femenino">Femenino</option>
                                        </select>
                                    </div>
                                    @error('genero') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <x-input-label for="comunidad_p" required>Comunidad del Participante</x-input-label>
                                    <x-community-selector :comunidades="$comunidades" name="comunidad_p" id="comunidad_p" :value="old('comunidad_p')" required class="mt-1"/>
                                </div>
                                <div>
                                    <label for="ciudad_p" class="block mb-1 text-sm font-medium text-slate-800">Ciudad <span class="text-red-500">*</span></label>
                                    <input type="text" name="ciudad_p" id="ciudad_p" value="{{ old('ciudad_p', 'Tola') }}" class="w-full px-3 py-2 text-sm border border-gray-300 shadow-sm rounded-3xl" required>
                                </div>
                                <div>
                                    <label for="departamento_p" class="block mb-1 text-sm font-medium text-slate-800">Departamento <span class="text-red-500">*</span></label>
                                    <input type="text" name="departamento_p" id="departamento_p" value="{{ old('departamento_p', 'Rivas') }}" class="w-full px-3 py-2 text-sm border border-gray-300 shadow-sm rounded-3xl" required>
                                </div>
                            </div>
                        </fieldset>

                        {{-- ... EL RESTO DE LOS FIELDSETS (Educativa, Programa, Tutor, etc.) ... --}}
                        {{-- (El resto del formulario se mantiene igual, no es necesario modificarlo para esta funcionalidad) --}}
                        <fieldset class="p-6 border border-purple-400 rounded-3xl hover:bg-slate-100/50 dark:hover:bg-slate-100">
                            <legend class="px-2 text-xl font-semibold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500">Información educativa</legend>
                            <div class="grid grid-cols-1 mt-2 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-4">
                                <div>
                                    <label for="escuela_p" class="block mb-1 text-sm font-medium text-slate-800">Nombre de la escuela </label>
                                    <input type="text" name="escuela_p" id="escuela_p" value="{{ old('escuela_p') }}" class="w-full px-3 py-2 border border-gray-300 rounded-3xl shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm @error('escuela_p') border-red-500 @enderror">
                                    @error('escuela_p') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="comunidad_escuela" class="block mb-1 text-sm font-medium text-slate-800">Comunidad de la escuela </label>
                                    <div x-data="{ open: false, selected: '{{ old('comunidad_escuela') }}' }" class="relative">
                                        <button @click="open = !open" type="button" class="relative w-full px-3 py-2 text-left bg-white border border-gray-300 shadow-sm dark:bg-slate-700 dark:border-slate-600 rounded-3xl focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:text-sm">
                                            <span class="block truncate" x-text="selected || 'Seleccione...'"></span>
                                            <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none"><svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a.75.75 0 01.53.22l3.5 3.5a.75.75 0 01-1.06 1.06L10 4.81 6.53 8.28a.75.75 0 01-1.06-1.06l3.5-3.5A.75.75 0 0110 3zm-3.72 9.53a.75.75 0 011.06 0L10 15.19l3.47-3.47a.75.75 0 111.06 1.06l-4 4a.75.75 0 01-1.06 0l-4-4a.75.75 0 010-1.06z" clip-rule="evenodd" /></svg></span>
                                        </button>
                                        <div x-show="open" @click.away="open = false" x-transition class="absolute z-30 w-full mt-1 overflow-auto bg-white rounded-md shadow-lg dark:bg-slate-800 max-h-60 ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm" style="display: none;">
                                            @foreach ($comunidades as $comunidad)
                                                <div @click="selected = '{{ $comunidad }}'; open = false" :class="{'bg-indigo-600 text-white': selected === '{{ $comunidad }}'}" class="relative py-2 pl-3 text-gray-900 cursor-default select-none pr-9 hover:bg-indigo-600 hover:text-white dark:text-white"><span class="block truncate">{{ $comunidad }}</span></div>
                                            @endforeach
                                        </div>
                                        <select name="comunidad_escuela" id="comunidad_escuela" x-model="selected" class="hidden">
                                            <option value="" disabled>Seleccione...</option>
                                            @foreach ($comunidades as $comunidad)
                                                <option value="{{ $comunidad }}">{{ $comunidad }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('comunidad_escuela') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <!-- Selector de Grado (Condicional) -->
    <div x-show="$store.formState.gradosDisponibles.length > 0" x-transition>
        <label for="grado_p" class="block mb-1 text-sm font-medium text-slate-800 dark:text-slate-200">Grado <span class="text-red-500">*</span></label>
        <div x-data="{ open: false }" class="relative">
            <!-- Botón del Dropdown -->
            <button @click="open = !open" type="button" class="relative w-full px-3 py-2 text-left bg-white border border-gray-300 shadow-sm dark:bg-slate-700 dark:border-slate-600 rounded-3xl focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:text-sm">
                <span class="block truncate" x-text="$store.formState.gradoLabel($store.formState.grado)"></span>
                <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none"><svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a.75.75 0 01.53.22l3.5 3.5a.75.75 0 01-1.06 1.06L10 4.81 6.53 8.28a.75.75 0 01-1.06-1.06l3.5-3.5A.75.75 0 0110 3zm-3.72 9.53a.75.75 0 011.06 0L10 15.19l3.47-3.47a.75.75 0 111.06 1.06l-4 4a.75.75 0 01-1.06 0l-4-4a.75.75 0 010-1.06z" clip-rule="evenodd" /></svg></span>
            </button>

            <!-- Panel de Opciones (generado dinámicamente) -->
            <div x-show="open" @click.away="open = false" x-transition class="absolute z-30 w-full mt-1 overflow-auto bg-white shadow-lg rounded-xl dark:bg-slate-800 max-h-60 ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm" style="display: none;">
                <template x-for="g in $store.formState.gradosDisponibles" :key="g">
                    <div @click="$store.formState.grado = g; open = false" :class="{'bg-indigo-600 text-white': $store.formState.grado == g}" class="relative py-2 pl-3 text-gray-900 cursor-pointer select-none pr-9 hover:bg-indigo-600 hover:text-white dark:text-white">
                        <span class="block truncate" x-text="$store.formState.gradoLabel(g)"></span>
                    </div>
                </template>
            </div>

            <!-- Select oculto para el envío del formulario -->
            <select name="grado_p" id="grado_p" x-model="$store.formState.grado" class="hidden" :required="$store.formState.gradosDisponibles.length > 0">
                <option value="" disabled>Seleccione...</option>
                <template x-for="g in $store.formState.gradosDisponibles" :key="g">
                    <option :value="g" x-text="$store.formState.gradoLabel(g)"></option>
                </template>
            </select>
        </div>
        @error('grado_p') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
                                <div>
                                    <label for="turno" class="block mb-1 text-sm font-medium text-slate-800">Turno </label>
                                    <div x-data="{ open: false, selected: '{{ old('turno') }}' }" class="relative">
                                        <button @click="open = !open" type="button" class="relative w-full px-3 py-2 text-left bg-white border border-gray-300 shadow-sm dark:bg-slate-700 dark:border-slate-600 rounded-3xl focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:text-sm">
                                            <span class="block truncate" x-text="selected || 'Seleccione...'"></span>
                                            <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none"><svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a.75.75 0 01.53.22l3.5 3.5a.75.75 0 01-1.06 1.06L10 4.81 6.53 8.28a.75.75 0 01-1.06-1.06l3.5-3.5A.75.75 0 0110 3zm-3.72 9.53a.75.75 0 011.06 0L10 15.19l3.47-3.47a.75.75 0 111.06 1.06l-4 4a.75.75 0 01-1.06 0l-4-4a.75.75 0 010-1.06z" clip-rule="evenodd" /></svg></span>
                                        </button>
                                        <div x-show="open" @click.away="open = false" x-transition class="absolute z-30 w-full mt-1 bg-white rounded-md shadow-lg dark:bg-slate-800 max-h-60 ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm" style="display: none;">
                                            <div @click="selected = 'Matutino'; open = false" :class="{'bg-indigo-600 text-white': selected === 'Matutino'}" class="relative py-2 pl-3 text-gray-900 cursor-default select-none pr-9 hover:bg-indigo-600 hover:text-white dark:text-white"><span class="block truncate">Matutino</span></div>
                                            <div @click="selected = 'Vespertino'; open = false" :class="{'bg-indigo-600 text-white': selected === 'Vespertino'}" class="relative py-2 pl-3 text-gray-900 cursor-default select-none pr-9 hover:bg-indigo-600 hover:text-white dark:text-white"><span class="block truncate">Vespertino</span></div>
                                            <div @click="selected = 'Sabatino'; open = false" :class="{'bg-indigo-600 text-white': selected === 'Sabatino'}" class="relative py-2 pl-3 text-gray-900 cursor-default select-none pr-9 hover:bg-indigo-600 hover:text-white dark:text-white"><span class="block truncate">Sabatino</span></div>
                                            <div @click="selected = 'No Aplica'; open = false" :class="{'bg-indigo-600 text-white': selected === 'No Aplica'}" class="relative py-2 pl-3 text-gray-900 cursor-default select-none pr-9 hover:bg-indigo-600 hover:text-white dark:text-white"><span class="block truncate">No Aplica (ej. Adulto)</span></div>
                                        </div>
                                        <select name="turno" id="turno" x-model="selected" class="hidden">
                                            <option value="" disabled>Seleccione...</option>
                                            <option value="Matutino">Matutino</option>
                                            <option value="Vespertino">Vespertino</option>
                                            <option value="Sabatino">Sabatino</option>
                                            <option value="No Aplica">No Aplica (ej. Adulto)</option>
                                        </select>
                                    </div>
                                    @error('turno') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div class="md:col-span-2" x-data="{ repite: '{{ old('repite_grado', '0') }}' }">
                                    <label class="block pb-3 mb-1 text-sm font-medium text-slate-800 dark:text-slate-200">¿Repite grado?</label>
                                    <div class="flex items-center mt-1 space-x-4">
                                        <label class="flex items-center justify-center w-8 h-8 text-lg border rounded-full cursor-pointer" :class="repite == '1' ? 'bg-green-100 text-green-700 border-green-500 ring-2 ring-green-300' : 'border-gray-300 text-green-600'">
                                            <input type="radio" name="repite_grado" value="1" class="hidden" @click="repite = '1'">
                                            ✓
                                        </label>
                                        <label class="flex items-center justify-center w-8 h-8 text-lg border rounded-full cursor-pointer" :class="repite == '0' ? 'bg-red-100 text-red-700 border-red-500 ring-2 ring-red-300' : 'border-gray-300 text-red-600'">
                                            <input type="radio" name="repite_grado" value="0" class="hidden" @click="repite = '0'">
                                            ✕
                                        </label>
                                    </div>
                                    @error('repite_grado') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="p-6 border border-purple-400 rounded-3xl hover:bg-slate-100/50 dark:hover:bg-slate-100">
                            <legend class="px-2 text-xl font-semibold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500">Detalles programa</legend>
                            <div class="mt-4 space-y-8">

                                {{-- 1. Programa Principal --}}
                                <div>
                                    <x-input-label required>Programa principal</x-input-label>
                                    <p class="mt-1 mb-3 text-xs text-slate-500 dark:text-slate-400">Selecciona al menos un programa principal al que se inscribe.</p>
                                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-3">
                                        @php
                                            // Lógica para manejar `old()` y los datos existentes del participante en modo edición
                                            $programasSeleccionados = old('programa', isset($participante) && is_string($participante->programa) ? explode(',', $participante->programa) : []);
                                        @endphp
                                        @foreach ($programaOptionsList as $programaItem)
                                        @php
                                            $colorClases = match($programaItem) {
                                                'Exito Academico' => 'hover:bg-blue-100 border-blue-300 text-blue-800',
                                                'Desarrollo Juvenil' => 'hover:bg-green-100 border-green-300 text-green-800',
                                                'Biblioteca' => 'hover:bg-purple-100 border-purple-300 text-purple-800',
                                                default => 'hover:bg-slate-100 border-slate-300 text-slate-800',
                                            };
                                        @endphp

                                        <label class="flex items-center p-3 transition-colors border rounded-3xl cursor-pointer dark:border-slate-600 dark:hover:bg-slate-700/50 {{ $colorClases }}">
                                            <input type="checkbox" name="programa[]" value="{{ $programaItem }}"
                                                @if(in_array($programaItem, $programasSeleccionados)) checked @endif
                                                class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded dark:bg-slate-900 dark:border-slate-500 focus:ring-indigo-500 dark:focus:ring-offset-slate-800">
                                            <span class="ml-3 text-sm font-medium dark:text-slate-200">{{ $programaItem }}</span>
                                        </label>
                                    @endforeach

                                    </div>
                                    <x-input-error :messages="$errors->get('programa')" class="mt-2" />
                                </div>

                                {{-- 2. Subprogramas (Con opción para agregar uno nuevo) --}}
                                <div x-data="{ otroSubprograma: {{ is_array(old('programas')) && in_array('_OTROS_', old('programas', [])) ? 'true' : 'false' }} }">
                                    <x-input-label>Subprogramas</x-input-label>
                                    <p class="mt-1 mb-3 text-xs text-slate-500 dark:text-slate-400">Selecciona los subprogramas aplicables.</p>
                                    <div class="grid grid-cols-2 gap-x-4 gap-y-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
                                        @php
                                            $subProgramasSeleccionados = old('programas', isset($participante) && is_string($participante->programas) ? explode(',', $participante->programas) : []);
                                        @endphp
                                        @foreach ($subProgramaOptionsList ?? [] as $subProgramaItem)
                                            <label class="flex items-center text-sm cursor-pointer dark:text-slate-200">
                                                <input type="checkbox" name="programas[]" value="{{ $subProgramaItem }}"
                                                    @if(in_array($subProgramaItem, $subProgramasSeleccionados)) checked @endif
                                                    class="w-4 h-4 mr-2 text-indigo-600 bg-gray-100 border-gray-300 rounded dark:bg-slate-900 dark:border-slate-500 focus:ring-indigo-500 dark:focus:ring-offset-slate-800">
                                                {{ $subProgramaItem }}
                                            </label>
                                        @endforeach
                                        {{-- Checkbox para "Otro" --}}
                                        <label class="flex items-center text-sm font-semibold cursor-pointer text-sky-600 dark:text-sky-400">
                                            <input type="checkbox" name="programas[]" value="_OTROS_" x-model="otroSubprograma"
                                                class="w-4 h-4 mr-2 text-indigo-600 border-gray-300 rounded dark:border-slate-500 focus:ring-indigo-500 dark:focus:ring-offset-slate-800">
                                            Otro
                                        </label>
                                    </div>
                                    <x-input-error :messages="$errors->get('programas')" class="mt-2" />
                                    {{-- Campo de texto para el nuevo subprograma --}}
                                    <div x-show="otroSubprograma" x-transition class="mt-4">
                                        <x-input-label for="nuevo_subprograma">Nombre del nuevo subprograma</x-input-label>
                                        <x-text-input id="nuevo_subprograma" type="text" name="nuevo_subprograma"
                                                    :value="old('nuevo_subprograma')"
                                                    class="block w-full mt-1 sm:w-1/2"
                                                    placeholder="Escribe el nombre aquí..." />
                                        <x-input-error :messages="$errors->get('nuevo_subprograma')" class="mt-2" />
                                    </div>
                                </div>

                                {{-- 3. Lugar de Encuentro --}}
                                <div>
                                    <x-input-label for="lugar_de_encuentro_del_programa" required>Lugar de encuentro del programa</x-input-label>
                                    <div class="mt-2">
                                        <x-radio-group-with-other
                                            :options="$lugaresDeEncuentro"
                                            name="lugar_de_encuentro_del_programa"
                                            :value="old('lugar_de_encuentro_del_programa', $participante->lugar_de_encuentro_del_programa ?? '')"
                                            required
                                        />
                                    </div>
                                </div>

                                {{-- 4. Días de Asistencia --}}
                                <div>
                                    <x-input-label required>Días de asistencia esperados</x-input-label>
                                    <p class="mt-1 mb-3 text-xs text-slate-500 dark:text-slate-400">Marca los días que el participante asistirá al programa.</p>
                                    <div class="flex flex-wrap items-center justify-center gap-4">
                                        @php $diasSeleccionados = old('dias_de_asistencia_al_programa', isset($participante) && is_string($participante->dias_de_asistencia_al_programa) ? explode(',', $participante->dias_de_asistencia_al_programa) : []); @endphp
                                        @foreach (['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'] as $dia)
                                            <label class="flex items-center px-4 py-2 text-sm border cursor-pointer rounded-3xl dark:border-slate-600 dark:text-slate-200 hover:bg-indigo-50 dark:hover:bg-slate-700/50">
                                                <input type="checkbox" name="dias_de_asistencia_al_programa[]" value="{{ $dia }}"
                                                    @if(in_array($dia, $diasSeleccionados)) checked @endif
                                                    class="w-4 h-4 mr-3 text-indigo-600 border-gray-300 rounded dark:bg-slate-900 dark:border-slate-500 focus:ring-indigo-500 dark:focus:ring-offset-slate-800">
                                                {{ $dia }}
                                            </label>
                                        @endforeach
                                    </div>
                                    <x-input-error :messages="$errors->get('dias_de_asistencia_al_programa')" class="mt-2" />
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="p-6 border border-purple-400 rounded-3xl hover:bg-slate-100/50 dark:hover:bg-slate-100">
                            <legend class="px-2 text-xl font-semibold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500">Información del tutor principal</legend>
                            <div class="grid grid-cols-1 mt-2 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-4">
                                <div>
                                    <label for="tutor_principal" class="block mb-1 text-sm font-medium text-slate-800">Tutor </label>
                                    <div x-data="{ open: false, selected: '{{ old('tutor_principal') }}' }" class="relative">
                                        <button @click="open = !open" type="button" class="relative w-full px-3 py-2 text-left bg-white border border-gray-300 shadow-sm dark:bg-slate-700 dark:border-slate-600 rounded-3xl focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:text-sm">
                                            <span class="block truncate" x-text="selected || 'Seleccione...'"></span>
                                            <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none"><svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a.75.75 0 01.53.22l3.5 3.5a.75.75 0 01-1.06 1.06L10 4.81 6.53 8.28a.75.75 0 01-1.06-1.06l3.5-3.5A.75.75 0 0110 3zm-3.72 9.53a.75.75 0 011.06 0L10 15.19l3.47-3.47a.75.75 0 111.06 1.06l-4 4a.75.75 0 01-1.06 0l-4-4a.75.75 0 010-1.06z" clip-rule="evenodd" /></svg></span>
                                        </button>
                                        <div x-show="open" @click.away="open = false" x-transition class="absolute z-20 w-full mt-1 overflow-auto bg-white rounded-md shadow-lg dark:bg-slate-800 max-h-60 ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm" style="display: none;">
                                            @foreach (['No aplica'] as $opcion)
                                                <div @click="selected = '{{ $opcion }}'; open = false" :class="{'bg-indigo-600 text-white': selected === '{{ $opcion }}'}" class="relative py-2 pl-3 text-gray-900 cursor-default select-none pr-9 hover:bg-indigo-600 hover:text-white dark:text-white"><span class="block truncate">{{ $opcion }}</span></div>
                                            @endforeach
                                            @foreach ($tipos_tutor as $tipo)
                                                <div @click="selected = '{{ $tipo }}'; open = false" :class="{'bg-indigo-600 text-white': selected === '{{ $tipo }}'}" class="relative py-2 pl-3 text-gray-900 cursor-default select-none pr-9 hover:bg-indigo-600 hover:text-white dark:text-white"><span class="block truncate">{{ $tipo }}</span></div>
                                            @endforeach
                                        </div>
                                        <select name="tutor_principal" id="tutor_principal" x-model="selected" class="hidden">
                                            <option value="" disabled>Seleccione...</option>
                                            @foreach (['No aplica'] as $opcion)
                                                <option value="{{ $opcion }}">{{ $opcion }}</option>
                                            @endforeach
                                            @foreach ($tipos_tutor as $tipo)
                                                <option value="{{ $tipo }}">{{ $tipo }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('tutor_principal') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div class="lg:col-span-2">
                                    <label for="nombres_y_apellidos_tutor_principal" class="block mb-1 text-sm font-medium text-slate-800">Nombres y apellidos </label>
                                    <input type="text" name="nombres_y_apellidos_tutor_principal" id="nombres_y_apellidos_tutor_principal" value="{{ old('nombres_y_apellidos_tutor_principal') }}" class="w-full px-3 py-2 border border-gray-300 rounded-3xl shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm @error('nombres_y_apellidos_tutor_principal') border-red-500 @enderror">
                                    @error('nombres_y_apellidos_tutor_principal') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="numero_de_cedula_tutor" class="block mb-1 text-sm font-medium text-slate-800">Número de cédula </label>
                                    <input type="text" name="numero_de_cedula_tutor" id="numero_de_cedula_tutor" value="{{ old('numero_de_cedula_tutor') }}" class="w-full uppercase px-3 py-2 border border-gray-300 rounded-3xl shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm @error('numero_de_cedula_tutor') border-red-500 @enderror" >
                                    @error('numero_de_cedula_tutor') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                {{-- Sección de Comunidad del Tutor (Con opción para agregar una nueva) --}}
                                <div>
                                    <x-input-label for="comunidad_tutor" >Comunidad del Tutor</x-input-label>
                                    <x-community-selector
                                        :comunidades="$comunidades"  {{-- <-- Le pasas EXACTAMENTE LA MISMA lista maestra --}}
                                        name="comunidad_tutor"     {{-- <-- Pero con el nombre del campo para el tutor --}}
                                        id="comunidad_tutor"       {{-- <-- Y un ID diferente --}}
                                        :value="old('comunidad_tutor', $participante->comunidad_tutor ?? '')" {{-- El valor actual del tutor --}}

                                        class="mt-1"
                                    />
                                </div>
                                <div class="lg:col-span-3">
                                    <label for="direccion_tutor" class="block mb-1 text-sm font-medium text-slate-800">Dirección </label>
                                    <textarea name="direccion_tutor" id="direccion_tutor" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-3xl shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm @error('direccion_tutor') border-red-500 @enderror" >{{ old('direccion_tutor') }}</textarea>
                                    @error('direccion_tutor') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="telefono_tutor" class="block mb-1 text-sm font-medium text-slate-800">Teléfono </label>
                                    <input type="tel" name="telefono_tutor" id="telefono_tutor" value="{{ old('telefono_tutor') }}" class="w-full px-3 py-2 border border-gray-300 rounded-3xl shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm @error('telefono_tutor') border-red-500 @enderror" >
                                    @error('telefono_tutor') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="sector_economico_tutor" class="block mb-1 text-sm font-medium text-slate-800">Sector económico </label>
                                    <div x-data="{ open: false, selected: '{{ old('sector_economico_tutor') }}' }" class="relative">
                                        <button @click="open = !open" type="button" class="relative w-full px-3 py-2 text-left bg-white border border-gray-300 shadow-sm dark:bg-slate-700 dark:border-slate-600 rounded-3xl focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:text-sm">
                                            <span class="block truncate" x-text="selected || 'Seleccione...'"></span>
                                            <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none"><svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a.75.75 0 01.53.22l3.5 3.5a.75.75 0 01-1.06 1.06L10 4.81 6.53 8.28a.75.75 0 01-1.06-1.06l3.5-3.5A.75.75 0 0110 3zm-3.72 9.53a.75.75 0 011.06 0L10 15.19l3.47-3.47a.75.75 0 111.06 1.06l-4 4a.75.75 0 01-1.06 0l-4-4a.75.75 0 010-1.06z" clip-rule="evenodd" /></svg></span>
                                        </button>
                                        <div x-show="open" @click.away="open = false" x-transition class="absolute z-20 w-full mt-1 overflow-auto bg-white rounded-md shadow-lg dark:bg-slate-800 max-h-60 ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm" style="display: none;">
                                            @foreach ($sector_economico as $sector)
                                                <div @click="selected = '{{ $sector }}'; open = false" :class="{'bg-indigo-600 text-white': selected === '{{ $sector }}'}" class="relative py-2 pl-3 text-gray-900 cursor-default select-none pr-9 hover:bg-indigo-600 hover:text-white dark:text-white"><span class="block truncate">{{ $sector }}</span></div>
                                            @endforeach
                                        </div>
                                        <select name="sector_economico_tutor" id="sector_economico_tutor" x-model="selected" class="hidden">
                                            <option value="" disabled>Seleccione...</option>
                                            @foreach ($sector_economico as $sector)
                                                <option value="{{ $sector }}">{{ $sector }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('sector_economico_tutor') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="nivel_de_educacion_formal_adquirido_tutor" class="block mb-1 text-sm font-medium text-slate-800">Nivel de educación </label>
                                    <div x-data="{ open: false, selected: '{{ old('nivel_de_educacion_formal_adquirido_tutor') }}' }" class="relative ">
                                        <button @click="open = !open" type="button" class="relative w-full px-3 py-2 text-left bg-white border border-gray-300 shadow-sm dark:bg-slate-700 dark:border-slate-600 rounded-3xl focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:text-sm">
                                            <span class="block truncate" x-text="selected || 'Seleccione...'"></span>
                                            <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none"><svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a.75.75 0 01.53.22l3.5 3.5a.75.75 0 01-1.06 1.06L10 4.81 6.53 8.28a.75.75 0 01-1.06-1.06l3.5-3.5A.75.75 0 0110 3zm-3.72 9.53a.75.75 0 011.06 0L10 15.19l3.47-3.47a.75.75 0 111.06 1.06l-4 4a.75.75 0 01-1.06 0l-4-4a.75.75 0 010-1.06z" clip-rule="evenodd" /></svg></span>
                                        </button>
                                        <div x-show="open" @click.away="open = false" x-transition class="absolute z-20 w-full mt-1 overflow-auto bg-white rounded-md shadow-lg dark:bg-slate-800 max-h-60 ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm" style="display: none;">
                                            @foreach ($nivel_educacion as $nivel)
                                                <div @click="selected = '{{ $nivel }}'; open = false" :class="{'bg-indigo-600 text-white': selected === '{{ $nivel }}'}" class="relative py-2 pl-3 text-gray-900 cursor-default select-none pr-9 hover:bg-indigo-600 hover:text-white dark:text-white"><span class="block truncate">{{ $nivel }}</span></div>
                                            @endforeach
                                        </div>
                                        <select name="nivel_de_educacion_formal_adquirido_tutor" id="nivel_de_educacion_formal_adquirido_tutor" x-model="selected" class="hidden">
                                            <option value="" disabled>Seleccione...</option>
                                            @foreach ($nivel_educacion as $nivel)
                                                <option value="{{ $nivel }}">{{ $nivel }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('nivel_de_educacion_formal_adquirido_tutor') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div class="md:col-span-2 lg:col-span-3">
                                    <label for="expectativas_del_programa_tutor_principal" class="block mb-1 text-sm font-medium text-slate-800">Expectativas del programa </label>
                                    <textarea name="expectativas_del_programa_tutor_principal" id="expectativas_del_programa_tutor_principal" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-3xl shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm @error('expectativas_del_programa_tutor_principal') border-red-500 @enderror">{{ old('expectativas_del_programa_tutor_principal') }}</textarea>
                                    @error('expectativas_del_programa_tutor_principal') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="p-6 border border-purple-400 rounded-3xl hover:bg-slate-100/50 dark:hover:bg-slate-100">
                            <legend class="px-2 text-xl font-semibold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500">Información del tutor secundario (Opcional)</legend>
                            <div class="grid grid-cols-1 mt-2 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-4">
                                <div>
                                    <label for="tutor_secundario" class="block mb-1 text-sm font-medium text-slate-800">Tutor</label>
                                    <div x-data="{ open: false, selected: '{{ old('tutor_secundario') }}' }" class="relative">
                                        <button @click="open = !open" type="button" class="relative w-full px-3 py-2 text-left bg-white border border-gray-300 shadow-sm dark:bg-slate-700 dark:border-slate-600 rounded-3xl focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:text-sm">
                                            <span class="block truncate" x-text="selected || 'Seleccione (si aplica)...'"></span>
                                            <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none"><svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a.75.75 0 01.53.22l3.5 3.5a.75.75 0 01-1.06 1.06L10 4.81 6.53 8.28a.75.75 0 01-1.06-1.06l3.5-3.5A.75.75 0 0110 3zm-3.72 9.53a.75.75 0 011.06 0L10 15.19l3.47-3.47a.75.75 0 111.06 1.06l-4 4a.75.75 0 01-1.06 0l-4-4a.75.75 0 010-1.06z" clip-rule="evenodd" /></svg></span>
                                        </button>
                                        <div x-show="open" @click.away="open = false" x-transition class="absolute z-10 w-full mt-1 overflow-auto bg-white rounded-md shadow-lg dark:bg-slate-800 max-h-60 ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm" style="display: none;">
                                            @foreach ($tipos_tutor as $tipo)
                                                <div @click="selected = '{{ $tipo }}'; open = false" :class="{'bg-indigo-600 text-white': selected === '{{ $tipo }}'}" class="relative py-2 pl-3 text-gray-900 cursor-default select-none pr-9 hover:bg-indigo-600 hover:text-white dark:text-white"><span class="block truncate">{{ $tipo }}</span></div>
                                            @endforeach
                                        </div>
                                        <select name="tutor_secundario" id="tutor_secundario" x-model="selected" class="hidden">
                                            <option value="" selected>Seleccione (si aplica)...</option>
                                            @foreach ($tipos_tutor as $tipo)
                                                <option value="{{ $tipo }}">{{ $tipo }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('tutor_secundario') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div class="lg:col-span-2">
                                    <label for="nombres_y_apellidos_tutor_secundario" class="block mb-1 text-sm font-medium text-slate-800">Nombres y apellidos</label>
                                    <input type="text" name="nombres_y_apellidos_tutor_secundario" id="nombres_y_apellidos_tutor_secundario" value="{{ old('nombres_y_apellidos_tutor_secundario') }}" class="w-full px-3 py-2 border border-gray-300 rounded-3xl shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm @error('nombres_y_apellidos_tutor_secundario') border-red-500 @enderror">
                                    @error('nombres_y_apellidos_tutor_secundario') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="numero_de_cedula_tutor_secundario" class="block mb-1 text-sm font-medium text-slate-800">Número de cédula</label>
                                    <input type="text" name="numero_de_cedula_tutor_secundario" id="numero_de_cedula_tutor_secundario" value="{{ old('numero_de_cedula_tutor_secundario') }}" class="w-full px-3 py-2 border border-gray-300 rounded-3xl shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm @error('numero_de_cedula_tutor_secundario') border-red-500 @enderror">
                                    @error('numero_de_cedula_tutor_secundario') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="comunidad_tutor_secundario" class="block mb-1 text-sm font-medium text-slate-800">Comunidad del tutor secundario</label>
                                    <div x-data="{ open: false, selected: '{{ old('comunidad_tutor_secundario') }}' }" class="relative">
                                        <button @click="open = !open" type="button" class="relative w-full px-3 py-2 text-left bg-white border border-gray-300 shadow-sm dark:bg-slate-700 dark:border-slate-600 rounded-3xl focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:text-sm">
                                            <span class="block truncate" x-text="selected || 'Seleccione (si aplica)...'"></span>
                                            <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none"><svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a.75.75 0 01.53.22l3.5 3.5a.75.75 0 01-1.06 1.06L10 4.81 6.53 8.28a.75.75 0 01-1.06-1.06l3.5-3.5A.75.75 0 0110 3zm-3.72 9.53a.75.75 0 011.06 0L10 15.19l3.47-3.47a.75.75 0 111.06 1.06l-4 4a.75.75 0 01-1.06 0l-4-4a.75.75 0 010-1.06z" clip-rule="evenodd" /></svg></span>
                                        </button>
                                        <div x-show="open" @click.away="open = false" x-transition class="absolute z-10 w-full mt-1 overflow-auto bg-white rounded-md shadow-lg dark:bg-slate-800 max-h-60 ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm" style="display: none;">
                                            @foreach ($comunidades as $comunidad)
                                                <div @click="selected = '{{ $comunidad }}'; open = false" :class="{'bg-indigo-600 text-white': selected === '{{ $comunidad }}'}" class="relative py-2 pl-3 text-gray-900 cursor-default select-none pr-9 hover:bg-indigo-600 hover:text-white dark:text-white"><span class="block truncate">{{ $comunidad }}</span></div>
                                            @endforeach
                                        </div>
                                        <select name="comunidad_tutor_secundario" id="comunidad_tutor_secundario" x-model="selected" class="hidden">
                                            <option value="" selected>Seleccione (si aplica)...</option>
                                            @foreach ($comunidades as $comunidad)
                                                <option value="{{ $comunidad }}">{{ $comunidad }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                     @error('comunidad_tutor_secundario') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="telefono_tutor_secundario" class="block mb-1 text-sm font-medium text-slate-800">Teléfono</label>
                                    <input type="tel" name="telefono_tutor_secundario" id="telefono_tutor_secundario" value="{{ old('telefono_tutor_secundario') }}" class="w-full px-3 py-2 border border-gray-300 rounded-3xl shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm @error('telefono_tutor_secundario') border-red-500 @enderror">
                                    @error('telefono_tutor_secundario') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="p-6 border border-purple-400 rounded-3xl hover:bg-slate-100/50 dark:hover:bg-slate-100">
                            <legend class="px-2 text-xl font-semibold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500">Participación en otros programas</legend>
                            <div x-data="{ asiste: '{{ old('asiste_a_otros_programas', '') }}' }" class="grid grid-cols-1 mt-2 md:grid-cols-2 gap-x-6 gap-y-4">
    {{-- Pregunta principal con botones ✓ y ✕ --}}
                            <div>
                                <label class="block mb-1 text-sm font-medium text-slate-800 dark:text-slate-200">
                                    ¿Asiste a otros programas fuera de CREA?
                                </label>
                                <div class="flex items-center mt-1 space-x-4">
                                    <label
                                        class="flex items-center justify-center w-8 h-8 text-lg border rounded-full cursor-pointer"
                                        :class="asiste == '1' ? 'bg-green-100 text-green-700 border-green-500 ring-2 ring-green-300' : 'border-gray-300 text-green-600'"
                                    >
                                        <input
                                            type="radio"
                                            name="asiste_a_otros_programas"
                                            value="1"
                                            class="hidden"
                                            @click="asiste = '1'"

                                        >
                                        ✓
                                    </label>
                                    <label
                                        class="flex items-center justify-center w-8 h-8 text-lg border rounded-full cursor-pointer"
                                        :class="asiste == '0' ? 'bg-red-100 text-red-700 border-red-500 ring-2 ring-red-300' : 'border-gray-300 text-red-600'"
                                    >
                                        <input
                                            type="radio"
                                            name="asiste_a_otros_programas"
                                            value="0"
                                            class="hidden"
                                            @click="asiste = '0'"
                                        >
                                        ✕
                                    </label>
                                </div>
                                @error('asiste_a_otros_programas')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Campos adicionales si selecciona "Sí" --}}
                            <div x-show="asiste == '1'" class="space-y-4 md:col-span-2" x-transition>
                                {{-- ¿Cuáles programas? --}}
                                <div>
                                    <label for="otros_programas" class="block mb-1 text-sm font-medium text-slate-800 dark:text-slate-200">¿Cuáles programas?</label>
                                    <input type="text" name="otros_programas" id="otros_programas" value="{{ old('otros_programas') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-3xl shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm @error('otros_programas') border-red-500 @enderror">
                                    @error('otros_programas')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Días que asiste --}}
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-slate-800 dark:text-slate-200">Días que asiste a esos otros programas</label>
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
                                    @error('dias_asiste_a_otros_programas')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        </fieldset>
                        <div class="flex flex-col items-center justify-end pt-8 space-y-3 sm:flex-row sm:space-y-0 sm:space-x-4">
                             <x-secondary-button type="button" onclick="document.getElementById('inscripcionForm').reset();">
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

    {{-- MEJORA: Se elimina el script de variables globales, ya no es necesario. --}}
    @push('scripts')
        @vite(['resources/js/pages/participante-create.js'])
    @endpush
</x-app-layout>
