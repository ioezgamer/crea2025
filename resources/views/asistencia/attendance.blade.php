<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-800">Registro de Asistencia</h2>
            <x-boton-regresar onclick="window.location.href='{{ route('participante.index') }}'" />
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

            <!-- Botón para ver el reporte -->
            @if (isset($programa) && $programa)
                <div class="mb-6">
                    <a href="{{ route('asistencia.reporte', [
                        'programa' => $programa,
                        'fecha_inicio' => $fechaInicio,
                        'lugar_de_encuentro_del_programa' => $lugar_encuentro,
                        'grado_p' => $grado
                    ]) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Ver Reporte de Asistencias
                    </a>
                </div>
            @endif

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

            <!-- Tabla -->
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
                                        <!-- Formulario por participante -->
                                        <form method="POST" action="{{ route('asistencia.store') }}">
                                            @csrf
                                            <input type="hidden" name="programa" value="{{ $programa }}">
                                            <input type="hidden" name="fecha_inicio" value="{{ $fechaInicio }}">
                                            <input type="hidden" name="lugar_de_encuentro_del_programa" value="{{ $lugar_encuentro }}">
                                            <input type="hidden" name="grado_p" value="{{ $grado }}">
                                            <input type="hidden" name="participante_id" value="{{ $participante->participante_id }}">

                                            <td class="px-4 py-3 text-gray-900">
                                                {{ $participante->primer_nombre_p }} {{ $participante->segundo_nombre_p ?? '' }} {{ $participante->primer_apellido_p }} {{ $participante->segundo_apellido_p ?? '' }}
                                                <span class="save-message text-green-600 text-xs hidden">¡Asistencia guardada!</span>
                                            </td>
                                            <td class="px-4 py-3 text-gray-600">{{ $participante->genero }}</td>
                                            <td class="px-4 py-3 text-gray-600">{{ $participante->grado_p ?? 'N/A' }}</td>
                                            <td class="px-4 py-3 text-gray-600">{{ $participante->programa ?? 'N/A' }}</td>
                                            @foreach ($diasSemana as $dia => $fecha)
                                                <td class="px-4 py-3 text-center">
                                                    <select name="asistencias[{{ $participante->participante_id }}][{{ $dia }}]" class="w-10 p-1 rounded-md border-gray-300 text-xs focus:border-blue-500 focus:ring-indigo-500 asistencia-select" data-participante-id="{{ $participante->participante_id }}">
                                                        <option value="Presente" {{ $asistencias[$participante->participante_id][$dia] == 'Presente' ? 'selected' : '' }}>P</option>
                                                        <option value="Ausente" {{ $asistencias[$participante->participante_id][$dia] == 'Ausente' ? 'selected' : '' }}>A</option>
                                                        <option value="Justificado" {{ $asistencias[$participante->participante_id][$dia] == 'Justificado' ? 'selected' : '' }}>J</option>
                                                    </select>
                                                </td>
                                            @endforeach
                                            <td class="px-4 py-3 text-center total-asistido" data-participante-id="{{ $participante->participante_id }}">{{ $participante->totalAsistido ?? 0 }}</td>
                                            <td class="px-4 py-3 text-center porcentaje-asistencia" data-participante-id="{{ $participante->participante_id }}">{{ $participante->porcentajeAsistencia ?? 0 }}%</td>
                                            <td class="px-4 py-3 text-center">
                                                @if ($participante->hasAsistenciasGuardadas)
                                                    <span class="text-green-600 mr-2" title="Asistencia registrada">&#10003;</span>
                                                    <x-boton-guardar type="submit" class="bg-gray-400 hover:bg-gray-500">Editar</x-boton-guardar>
                                                @else
                                                    <x-boton-guardar type="submit">Guardar</x-boton-guardar>
                                                @endif
                                            </td>
                                        </form>
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

    <script>
        const updateAttendance = (participanteId) => {
            const selects = document.querySelectorAll(`select[name^="asistencias[${participanteId}]"]`);
            let totalAsistido = 0;
            selects.forEach(sel => { if (sel.value === 'Presente') totalAsistido++; });
            const porcentaje = selects.length > 0 ? ((totalAsistido / selects.length) * 100).toFixed(0) : 0;
            document.querySelector(`.total-asistido[data-participante-id="${participanteId}"]`).textContent = totalAsistido;
            document.querySelector(`.porcentaje-asistencia[data-participante-id="${participanteId}"]`).textContent = `${porcentaje}%`;
        };

        document.querySelectorAll('.asistencia-select').forEach(select => {
            select.addEventListener('change', () => updateAttendance(select.dataset.participanteId));
        });

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.asistencia-select').forEach(select => updateAttendance(select.dataset.participanteId));
        });

        // Validación y mejoras visuales al enviar el formulario
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', (e) => {
                const selects = form.querySelectorAll('.asistencia-select');
                for (const select of selects) {
                    if (!['Presente', 'Ausente', 'Justificado'].includes(select.value)) {
                        e.preventDefault();
                        alert('Error: Estado de asistencia inválido.');
                        return;
                    }
                }
                // Indicador visual: cambio de color y mensaje
                if (!e.defaultPrevented) {
                    const row = form.closest('tr');
                    const saveMessage = row.querySelector('.save-message');
                    row.classList.add('bg-green-100');
                    saveMessage.classList.remove('hidden');
                    setTimeout(() => {
                        row.classList.remove('bg-green-100');
                        saveMessage.classList.add('hidden');
                    }, 2000);
                }
            });
        });
    </script>
</x-app-layout>