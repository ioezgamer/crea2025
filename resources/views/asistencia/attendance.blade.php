<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-800">Registro de Asistencias</h2>
            <x-boton-regresar onclick="window.location.href='{{ route('asistencia.reporte', [
                'programa' => $programa,
                'fecha_inicio' => $fechaInicio,
                'lugar_de_encuentro_del_programa' => $lugar_encuentro,
                'grado_p' => $grado
            ]) }}'" />
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Formulario de filtros -->
            <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
                <form method="GET" action="{{ route('asistencia.create') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Programa <span class="text-red-500">*</span></label>
                        <select name="programa" id="programa" class="mt-1 block w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" onchange="this.form.submit()">
                            <option value="">Seleccione...</option>
                            @foreach (['Exito Academico', 'Desarrollo Juvenil', 'Biblioteca'] as $prog)
                                <option value="{{ $prog }}" {{ $programa == $prog ? 'selected' : '' }}>{{ $prog }}</option>
                            @endforeach
                        </select>
                        @error('programa') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Lugar</label>
                        <select name="lugar_de_encuentro_del_programa" id="lugar_de_encuentro_del_programa" class="mt-1 block w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" onchange="this.form.submit()">
                            <option value="">Todos...</option>
                            @foreach ($lugares_encuentro ?? [] as $lugar)
                                <option value="{{ $lugar }}" {{ $lugar_encuentro == $lugar ? 'selected' : '' }}>{{ $lugar }}</option>
                            @endforeach
                        </select>
                        @error('lugar_de_encuentro_del_programa') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Semana (Lunes) <span class="text-red-500">*</span></label>
                        <input type="date" name="fecha_inicio" id="fecha_inicio" value="{{ $fechaInicio ?? now()->startOfWeek()->format('Y-m-d') }}" class="mt-1 block w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" onchange="this.form.submit()">
                        @error('fecha_inicio') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Grado</label>
                        <select name="grado_p" id="grado_p" class="mt-1 block w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500" onchange="this.form.submit()">
                            <option value="">Seleccione...</option>
                            @foreach ($grados ?? [] as $grado_item)
                                <option value="{{ $grado_item }}" {{ $grado == $grado_item ? 'selected' : '' }}>{{ $grado_item }}</option>
                            @endforeach
                        </select>
                        @error('grado_p') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </form>
            </div>

            <!-- Mensajes -->
            @if (session('success'))
                <div class="bg-green-50 border-l-4 border-green-400 text-green-700 p-4 mb-6 rounded-md">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-400 text-red-700 p-4 mb-6 rounded-md">
                    <p class="font-bold">Errores:</p>
                    <ul class="list-disc ml-4">
                        @foreach ($errors->all() as $error)
                            <li class="text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Tabla de asistencias -->
            @if (isset($programa) && $programa && $participantes->isNotEmpty())
                <div class="bg-transparent p-6">
                    <div class="overflow-x-auto rounded-2xl border-x-2 border-y-2 shadow-lg">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-200">
                                <tr class="text-xs font-medium text-gray-600">
                                    <th class="px-4 py-3 text-left">Nombres y apellidos</th>
                                    <th class="px-4 py-3 text-left">Género</th>
                                    <th class="px-4 py-3 text-left">Grado</th>
                                    <th class="px-4 py-3 text-left">Programa</th>
                                    @foreach ($diasSemana as $dia => $fecha)
                                        <th class="px-4 py-3 text-center">
                                            @php
                                                $abreviaturas = [
                                                    'Lunes' => 'Lun',
                                                    'Martes' => 'Mar',
                                                    'Miércoles' => 'Mié',
                                                    'Jueves' => 'Jue',
                                                    'Viernes' => 'Vie',
                                                    'Sábado' => 'Sáb',
                                                    'Domingo' => 'Dom',
                                                ];
                                                $diaAbreviado = $abreviaturas[$dia] ?? mb_substr($dia, 0, 3, 'UTF-8');
                                            @endphp
                                            {{ $diaAbreviado }} {{ \Carbon\Carbon::parse($fecha)->format('d') }}
                                        </th>
                                    @endforeach
                                    <th class="px-4 py-3 text-center">Total</th>
                                    <th class="px-4 py-3 text-center">%</th>
                                    <th class="px-4 py-3 text-center">Acción</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 rounded-2xl">
                                @foreach ($participantes as $participante)
                                    <tr class="hover:bg-sky-100">
                                        <td class="px-4 py-3 text-gray-900">
                                            {{ $participante->primer_nombre_p }} {{ $participante->segundo_nombre_p ?? '' }} {{ $participante->primer_apellido_p }} {{ $participante->segundo_apellido_p ?? '' }}
                                        </td>
                                        <td class="px-4 py-3 text-gray-600">{{ $participante->genero }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ $participante->grado_p ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ $participante->programa ?? 'N/A' }}</td>
                                        @foreach ($diasSemana as $dia => $fecha)
                                            <td class="px-4 py-3 text-center">
                                                @php
                                                    $estado = $asistencias[$participante->participante_id][$dia];
                                                    $color = match ($estado) {
                                                        'Presente' => 'text-green-600',
                                                        'Ausente' => 'text-red-600',
                                                        'Justificado' => 'text-yellow-600',
                                                        default => 'text-gray-600',
                                                    };
                                                @endphp
                                                <span class="{{ $color }}">{{ substr($estado, 0, 1) }}</span>
                                            </td>
                                        @endforeach
                                        <td class="px-4 py-3 text-center">{{ $participante->totalAsistido ?? 0 }}</td>
                                        <td class="px-4 py-3 text-center">{{ $participante->porcentajeAsistencia ?? 0 }}%</td>
                                        <td class="px-4 py-3 text-center">
                                            @if ($participante->hasAsistenciasGuardadas)
                                                <button type="button" class="text-blue-600 hover:text-blue-800" onclick="editarAsistencia({{ $participante->participante_id }})">
                                                    <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </button>
                                            @else
                                                <button type="button" class="text-blue-600 hover:text-blue-800" onclick="mostrarFormulario({{ $participante->participante_id }})">
                                                    <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                    </svg>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @elseif (isset($programa) && $programa)
                <div class="bg-white shadow-sm rounded-lg p-6 text-sm text-gray-500">No hay participantes inscritos.</div>
            @endif
        </div>
    </div>

    <!-- Formulario oculto para registrar/editar asistencia -->
    <div id="formulario-asistencia" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h3 id="formulario-titulo" class="text-lg font-semibold text-gray-800 mb-4"></h3>
            <form id="form-asistencia" method="POST" action="{{ route('asistencia.store') }}">
                @csrf
                <input type="hidden" name="programa" value="{{ $programa }}">
                <input type="hidden" name="fecha_inicio" value="{{ $fechaInicio }}">
                <input type="hidden" name="lugar_de_encuentro_del_programa" value="{{ $lugar_encuentro }}">
                <input type="hidden" name="grado_p" value="{{ $grado }}">
                <input type="hidden" name="participante_id" id="participante_id">

                <div class="grid grid-cols-2 gap-4 mb-4">
                    @foreach ($diasSemana as $dia => $fecha)
                        <div>
                            <label class="block text-xs font-medium text-gray-700">
                                @php
                                    $abreviaturas = [
                                        'Lunes' => 'Lun',
                                        'Martes' => 'Mar',
                                        'Miércoles' => 'Mié',
                                        'Jueves' => 'Jue',
                                        'Viernes' => 'Vie',
                                        'Sábado' => 'Sáb',
                                        'Domingo' => 'Dom',
                                    ];
                                    $diaAbreviado = $abreviaturas[$dia] ?? mb_substr($dia, 0, 3, 'UTF-8');
                                @endphp
                                {{ $diaAbreviado }} {{ \Carbon\Carbon::parse($fecha)->format('d') }}
                            </label>
                            <select name="asistencias[{{ $dia }}]" class="mt-1 block w-full rounded-md border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="Presente">Presente</option>
                                <option value="Ausente" selected>Ausente</option>
                                <option value="Justificado">Justificado</option>
                            </select>
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300" onclick="cerrarFormulario()">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function mostrarFormulario(participanteId) {
            document.getElementById('formulario-titulo').innerText = 'Registrar Asistencia';
            document.getElementById('participante_id').value = participanteId;
            document.getElementById('formulario-asistencia').classList.remove('hidden');

            // Restablecer los valores del formulario a "Ausente"
            const selects = document.querySelectorAll('#form-asistencia select');
            selects.forEach(select => {
                select.value = 'Ausente';
            });
        }

        function editarAsistencia(participanteId) {
            document.getElementById('formulario-titulo').innerText = 'Editar Asistencia';
            document.getElementById('participante_id').value = participanteId;
            document.getElementById('formulario-asistencia').classList.remove('hidden');

            // Cargar los valores actuales de las asistencias
            @foreach ($participantes as $participante)
                if (participanteId == {{ $participante->participante_id }}) {
                    @foreach ($diasSemana as $dia => $fecha)
                        document.querySelector(`select[name="asistencias[{{ $dia }}]"]`).value = '{{ $asistencias[$participante->participante_id][$dia] }}';
                    @endforeach
                }
            @endforeach
        }

        function cerrarFormulario() {
            document.getElementById('formulario-asistencia').classList.add('hidden');
        }
    </script>
</x-app-layout>