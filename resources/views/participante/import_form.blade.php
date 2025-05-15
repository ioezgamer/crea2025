<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Importar Participantes
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if (session('success'))
                        <div class="mb-4 font-medium text-sm text-green-600 bg-green-100 border border-green-400 p-3 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 font-medium text-sm text-red-600 bg-red-100 border border-red-400 p-3 rounded-md">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if (session('warning'))
                        <div class="mb-4 font-medium text-sm text-yellow-700 bg-yellow-100 border border-yellow-400 p-3 rounded-md">
                            {{ session('warning') }}
                        </div>
                    @endif

                    @if (session('import_errors'))
                        <div class="mb-4 p-3 bg-red-100 border border-red-400 rounded-md">
                            <p class="font-medium text-sm text-red-700">Errores durante la importación:</p>
                            <ul class="list-disc list-inside text-xs text-red-600 mt-1 max-h-60 overflow-y-auto">
                                @foreach (session('import_errors') as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('participantes.import.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <label for="participantes_file" class="block text-sm font-medium text-gray-700">
                                Seleccionar archivo (Excel: .xlsx, .xls o CSV: .csv)
                            </label>
                            <div class="mt-1">
                                <input type="file" name="participantes_file" id="participantes_file" required
                                       class="block w-full text-sm text-gray-500
                                              file:mr-4 file:py-2 file:px-4
                                              file:rounded-full file:border-0
                                              file:text-sm file:font-semibold
                                              file:bg-blue-50 file:text-blue-700
                                              hover:file:bg-blue-100
                                              shadow-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            @error('participantes_file')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-6">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                Importar Participantes
                            </button>
                        </div>
                    </form>

                    <div class="mt-8 border-t pt-6">
                        <h3 class="text-lg font-medium text-gray-900">Instrucciones y Columnas Esperadas para Importación</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            Asegúrate de que tu archivo (Excel o CSV) tenga los siguientes encabezados en la primera fila.
                            Los nombres de las columnas en tu archivo deben coincidir EXACTAMENTE con los nombres listados abajo (sensible a mayúsculas/minúsculas y espacios).
                            Los campos marcados con (*) son generalmente requeridos o importantes.
                        </p>
                        <ul class="list-disc list-inside text-sm text-gray-500 mt-2 space-y-1 columns-2 md:columns-3">
                            <li><code>fecha_inscripcion</code> (YYYY-MM-DD)</li>
                            <li><code>ano_inscripcion</code> (*)</li>
                            <li><code>tipo_participante</code> (*)</li>
                            <li><code>tiene_partida_nacimiento_10</code> (1/0)</li>
                            <li><code>tiene_boletindiploma_2024_10</code> (1/0)</li>
                            <li><code>tutor_presento_cedula_10</code> (1/0)</li>
                            <li><code>participante_adulto_presento_cedula_10</code> (1/0)</li>
                            <li><code>programa_principal_csv</code> (*) (CSV, ej: Exito Academico,Biblioteca)</li>
                            <li><code>sub_programascodigos_csv</code> (CSV, ej: RAC,CLC)</li>
                            <li><code>lugar_encuentro_programa</code> (*)</li>
                            <li><code>primer_nombre</code> (*)</li>
                            <li><code>segundo_nombre</code></li>
                            <li><code>primer_apellido</code> (*)</li>
                            <li><code>segundo_apellido</code></li>
                            <li><code>ciudad_nacimiento</code></li>
                            <li><code>departamento_nacimiento</code></li>
                            <li><code>fecha_nacimiento</code> (YYYY-MM-DD, *)</li>
                            <li><code>edad</code> (Si se omite, se podría calcular)</li>
                            <li><code>cedula_participante_adulto_numero</code></li>
                            <li><code>genero</code> (*)</li>
                            <li><code>comunidad_residencia_participante</code> (*)</li>
                            <li><code>escuela</code> (*)</li>
                            <li><code>comunidad_escuela</code> (*)</li>
                            <li><code>grado_escolar</code> (*)</li>
                            <li><code>turno_escolar</code></li>
                            <li><code>repite_grado_10</code> (1/0)</li>
                            <li><code>dias_asistencia_programa_csv</code> (*) (CSV, ej: Lunes,Martes)</li>
                            <li><code>relacion_tutor_principal</code> (*)</li>
                            <li><code>nombres_y_apellidos_tutor_principal</code> (*)</li>
                            <li><code>numero_cedula_tutor_principal</code></li>
                            <li><code>comunidad_tutor_principal</code></li>
                            <li><code>direccion_tutor_principal</code></li>
                            <li><code>telefono_tutor_principal</code></li>
                            <li><code>sector_economico_tutor_principal</code></li>
                            <li><code>nivel_educacion_tutor_principal</code></li>
                            <li><code>expectativas_tutor_principal</code></li>
                            <li><code>relacion_tutor_secundario</code></li>
                            <li><code>nombres_y_apellidos_tutor_secundario</code></li>
                            <li><code>numero_cedula_tutor_secundario</code></li>
                            <li><code>comunidad_tutor_secundario</code></li>
                            <li><code>telefono_tutor_secundario</code></li>
                            <li><code>asiste_otros_programas_10</code> (1/0)</li>
                            <li><code>nombres_otros_programas</code></li>
                            <li><code>dias_asiste_otros_programas</code></li>
                            <li><code>activo_10</code> (1/0, default a 1 si se omite)</li>
                        </ul>
                        <p class="mt-3 text-sm text-gray-600">
                            Para campos booleanos (ej: <code>activo_10</code>), usa 1 para verdadero/sí y 0 para falso/no. También se aceptan 'true', 'false', 'si', 'no'.
                            Para campos con múltiples valores (ej: <code>programa_principal_csv</code>), sepáralos con comas (ej: Lunes,Martes,Miércoles).
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>