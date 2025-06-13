{{-- resources/views/asistencia/partials/tabla_asistencia.blade.php --}}
@if ($participantes->isNotEmpty())
    <div class="mt-6 overflow-x-auto shadow-lg rounded-2xl border-x-2 border-y-2">
        <table class="min-w-full text-sm divide-y divide-gray-200">
            <thead class="bg-gray-200">
                <tr class="text-xs font-medium text-gray-600">
                    <th class="sticky left-0 z-10 px-4 py-3 text-left bg-gray-200">Nombres y apellidos</th>
                    <th class="px-3 py-3 text-left">Género</th>
                    <th class="px-3 py-3 text-left">Grado</th>
                    {{-- <th class="px-3 py-3 text-left">Programa(s)</th> --}}
                    <th class="px-3 py-3 text-left">Días Esperados</th>
                    @foreach ($diasSemana as $diaNombre => $fechaDia)
                        <th class="px-2 py-3 text-center whitespace-nowrap">
                            @php
                                $abreviaturas = ['Lunes'=>'Lun','Martes'=>'Mar','Miércoles'=>'Mié','Jueves'=>'Jue','Viernes'=>'Vie'];
                            @endphp
                            {{ $abreviaturas[$diaNombre] ?? mb_substr($diaNombre, 0, 3) }}
                            <span class="block text-xxs">{{ \Carbon\Carbon::parse($fechaDia)->format('d') }}</span>
                        </th>
                    @endforeach
                    <th class="px-3 py-3 text-center">Total P.</th>
                    <th class="px-3 py-3 text-center">% Asist.</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($participantes as $participante)
                    <tr class="hover:bg-sky-100" id="fila-participante-{{ $participante->participante_id }}">
                        <td class="sticky left-0 z-10 px-4 py-3 text-gray-900 bg-white hover:bg-sky-100 whitespace-nowrap">
                            {{ $participante->primer_nombre_p }} {{ $participante->segundo_nombre_p ?? '' }} {{ $participante->primer_apellido_p }} {{ $participante->segundo_apellido_p ?? '' }}
                            <span class="ml-2 text-xs save-feedback"></span>
                        </td>
                        <td class="px-3 py-3 text-gray-600">{{ $participante->genero }}</td>
                        <td class="px-3 py-3 text-gray-600">{{ $participante->grado_p ?? 'N/A' }}</td>
                        {{-- <td class="px-3 py-3 text-gray-600">{{ $participante->programa ?? 'N/A' }}</td> --}}
                        <td class="px-3 py-3 text-xs text-gray-600">
                            @if($participante->dias_de_asistencia_al_programa)
                                @php $diasEsperados = explode(',', $participante->dias_de_asistencia_al_programa); @endphp
                                @foreach($diasEsperados as $de)
                                    <span class="inline-block px-1 py-0.5 bg-gray-100 rounded text-xxs mr-1">{{ mb_substr(trim($de), 0, 3) }}</span>
                                @endforeach
                            @else
                                N/A
                            @endif
                        </td>

                        @foreach ($diasSemana as $diaNombre => $fechaDia)
                            <td class="px-2 py-3 text-center">
                                <select name="asistencia_individual"
                                        class="w-10 p-1 text-xs border-gray-300 rounded-md focus:border-blue-500 focus:ring-indigo-500 asistencia-select"
                                        data-participante-id="{{ $participante->participante_id }}"
                                        data-fecha-asistencia="{{ $fechaDia }}"
                                        data-dia-nombre="{{ $diaNombre }}">
                                    <option value="Presente" {{ ($asistencias[$participante->participante_id][$diaNombre] ?? 'Ausente') == 'Presente' ? 'selected' : '' }}>P</option>
                                    <option value="Ausente" {{ ($asistencias[$participante->participante_id][$diaNombre] ?? 'Ausente') == 'Ausente' ? 'selected' : '' }}>A</option>
                                    <option value="Justificado" {{ ($asistencias[$participante->participante_id][$diaNombre] ?? 'Ausente') == 'Justificado' ? 'selected' : '' }}>J</option>
                                </select>
                            </td>
                        @endforeach
                        <td class="px-3 py-3 text-center total-asistido" data-participante-id="{{ $participante->participante_id }}">{{ $participante->totalAsistido ?? 0 }}</td>
                        <td class="px-3 py-3 text-center porcentaje-asistencia" data-participante-id="{{ $participante->participante_id }}">{{ $participante->porcentajeAsistencia ?? 0 }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="p-6 mt-6 text-sm text-gray-500 bg-white rounded-lg shadow-sm">
        Seleccione todos los filtros (Programa, Lugar, Grado y Semana) para cargar la lista de participantes.
        @if(isset($selectedPrograma) && $selectedPrograma && isset($selectedLugar) && $selectedLugar && isset($selectedGrado) && $selectedGrado)
            <br>No se encontraron participantes para los filtros seleccionados.
        @endif
    </div>
@endif
