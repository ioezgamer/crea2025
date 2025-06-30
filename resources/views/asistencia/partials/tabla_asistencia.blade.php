{{-- resources/views/asistencia/partials/tabla_asistencia.blade.php --}}
@if ($participantes->isNotEmpty())
    <div class="mt-6 overflow-x-auto overflow-y-auto max-h-[70vh] shadow-lg rounded-3xl border border-gray-200 bg-white">
        <table class="min-w-full text-sm divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr class="text-xs font-semibold text-gray-500 uppercase">
                    <th class="sticky top-0 left-0 z-20 px-4 py-3 text-left bg-gray-100 border-r border-gray-200">Nombres y apellidos</th>
                    <th class="sticky top-0 z-10 px-3 py-3 text-left bg-gray-100 border-r border-gray-200">Género</th>
                    <th class="sticky top-0 z-10 px-3 py-3 text-left bg-gray-100 border-r border-gray-200">Grado</th>
                    <th class="sticky top-0 z-10 px-3 py-3 text-left bg-gray-100 border-r border-gray-200">Días Esperados</th>
                    @foreach ($diasSemana as $diaNombre => $fechaDia)
                        <th class="sticky top-0 px-2 py-3 text-center bg-gray-100 border-r border-gray-200 whitespace-nowrap">
                            @php
                                $abreviaturas = ['Lunes'=>'Lun','Martes'=>'Mar','Miércoles'=>'Mié','Jueves'=>'Jue','Viernes'=>'Vie'];
                            @endphp
                            {{ $abreviaturas[$diaNombre] ?? mb_substr($diaNombre, 0, 3) }}
                            <span class="block font-normal text-xxs">{{ \Carbon\Carbon::parse($fechaDia)->format('d') }}</span>
                        </th>
                    @endforeach
                    <th class="sticky top-0 z-10 px-3 py-3 text-center bg-gray-100 border-r border-gray-200">Total P.</th>
                    <th class="sticky top-0 z-10 px-3 py-3 text-center bg-gray-100">Asist. %</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($participantes as $participante)
                    <tr class="transition-colors duration-200 group" id="fila-participante-{{ $participante->participante_id }}">
                        <td class="sticky left-0 z-10 px-4 py-2 font-medium text-gray-800 bg-white whitespace-nowrap group-hover:bg-sky-50/70">
                            {{ $participante->primer_nombre_p }} {{ $participante->segundo_nombre_p ?? '' }} {{ $participante->primer_apellido_p }} {{ $participante->segundo_apellido_p ?? '' }}
                            {{-- El span para feedback ahora empieza oculto --}}
                            <span class="ml-2 text-xs font-normal opacity-0 save-feedback"></span>
                        </td>
                        <td class="px-3 py-2 text-gray-600 group-hover:bg-sky-50/70">{{ $participante->genero }}</td>
                        <td class="px-3 py-2 text-gray-600 group-hover:bg-sky-50/70">{{ $participante->grado_p ?? 'N/A' }}</td>
                        <td class="px-3 py-3 text-xs text-gray-600 group-hover:bg-sky-50/70">
                            @if($participante->dias_de_asistencia_al_programa)
                                @php
                                    $diasEsperados = explode(',', $participante->dias_de_asistencia_al_programa);
                                @endphp
                                @foreach($diasEsperados as $de)
                                    @php
                                        $dia = strtolower(trim($de));
                                        $color = match($dia) {
                                            'lunes' => 'bg-red-100 text-red-800',
                                            'martes' => 'bg-yellow-100 text-yellow-800',
                                            'miércoles', 'miercoles' => 'bg-green-100 text-green-800',
                                            'jueves' => 'bg-blue-100 text-blue-800',
                                            'viernes' => 'bg-purple-100 text-purple-800',
                                            default => 'bg-gray-100 text-gray-800',
                                        };
                                    @endphp
                                    <span class="inline-block px-1 py-0.5 {{ $color }} rounded font-medium text-xs mr-1">
                                        {{ mb_substr(ucfirst($dia), 0, 3) }}
                                    </span>
                                @endforeach
                            @else
                                <span class="text-gray-400">N/A</span>
                            @endif
                        </td>

                        @foreach ($diasSemana as $diaNombre => $fechaDia)
                            <td class="px-2 py-3 text-center group-hover:bg-sky-50/70">
                                @php
                                    $estado = $asistencias[$participante->participante_id][$diaNombre] ?? 'Ausente';
                                @endphp
                                {{-- El JS ahora controla todos los estilos dinámicos del select --}}
                                <select name="asistencia_individual"
                                    class="items-center justify-between w-12 px-1.5 text-xs font-bold border appearance-none rounded-3xl focus:outline-none focus:ring-2 focus:ring-blue-400 asistencia-select"
                                    data-participante-id="{{ $participante->participante_id }}"
                                    data-fecha-asistencia="{{ $fechaDia }}">
                                    <option value="Presente" @selected($estado == 'Presente')>P</option>
                                    <option value="Ausente" @selected($estado == 'Ausente')>A</option>
                                    <option value="Justificado" @selected($estado == 'Justificado')>J</option>
                                </select>
                            </td>
                        @endforeach
                        <td class="px-3 py-2 font-bold text-center text-gray-700 total-asistido group-hover:bg-sky-50/70" data-participante-id="{{ $participante->participante_id }}">0</td>
                        <td class="px-3 py-2 font-semibold text-center text-gray-700 porcentaje-asistencia group-hover:bg-sky-50/70" data-participante-id="{{ $participante->participante_id }}">0%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="p-8 mt-6 text-sm text-center text-gray-500 shadow-sm bg-white/70 backdrop-blur-lg rounded-3xl">
        <svg class="w-12 h-12 mx-auto mb-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
        </svg>
        @if(request()->has('programa'))
            No se encontraron participantes para los filtros seleccionados.
        @else
            Seleccione todos los filtros (Programa, Lugar, Grado y Semana) para cargar la lista de participantes.
        @endif
    </div>
@endif
