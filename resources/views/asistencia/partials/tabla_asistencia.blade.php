{{-- resources/views/asistencia/partials/tabla_asistencia.blade.php --}}
@if ($participantes->isNotEmpty())
    <div class="mt-6 overflow-x-auto overflow-scroll max-h-[70vh] shadow-sm rounded-2xl border-x-2 border-y-2 border-gray-200 bg-white ">

        <table class="min-w-full text-xs divide-y divide-gray-600">
            <thead class="bg-gray-200">
                <tr class="text-xs  text-gray-600 ">
                    <th class="sticky top-0 left-0 z-20 px-2 py-2 text-left bg-gray-200  ">Nombres y apellidos</th>
                    <th class="sticky top-0 z-10 px-2 py-2 text-left bg-gray-200  ">Género</th>
                    <th class="sticky top-0 z-10 px-2 py-2 text-left bg-gray-200  ">Grado</th>
                    <th class="sticky top-0 z-10 px-2 py-2 text-left bg-gray-200  ">Días esperados</th>
                        {{-- <th class="sticky top-0 z-10 px-3 py-3 text-left bg-gray-200">Programa</th> --}}
                    @foreach ($diasSemana as $diaNombre => $fechaDia)
                        <th class="sticky top-0 left-0 z-20 px-1.5 py-1.5 text-center bg-gray-200  whitespace-nowrap uppercase">
                            @php
                                $abreviaturas = ['Lunes'=>'Lun','Martes'=>'Mar','Miércoles'=>'Mié','Jueves'=>'Jue','Viernes'=>'Vie'];
                            @endphp
                            {{ $abreviaturas[$diaNombre] ?? mb_substr($diaNombre, 0, 3) }}
                            <span class="block text-xs">{{ \Carbon\Carbon::parse($fechaDia)->format('d') }}</span>
                        </th>
                    @endforeach
                    <th class="sticky top-0 z-10 px-2 py-2 text-center bg-gray-200 ">Total P.</th>
                    <th class="sticky top-0 z-10 px-2 py-2 text-center bg-gray-200 ">% Asist.</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 ">
                @foreach ($participantes as $participante)
                    <tr class="group" id="fila-participante-{{ $participante->participante_id }}">
                        <td class="sticky left-0 z-10 px-2 py-2 text-gray-900 bg-white whitespace-nowrap group-hover:bg-sky-100 text-sm">
                            {{ $participante->primer_nombre_p }} {{ $participante->segundo_nombre_p ?? '' }} {{ $participante->primer_apellido_p }} {{ $participante->segundo_apellido_p ?? '' }}
                            <span class="ml-2 text-xs save-feedback"></span>
                        </td>
                        <td class="px-2 py-2 text-gray-600 group-hover:bg-sky-100">{{ $participante->genero }}</td>
                        <td class="px-2 py-2 text-gray-600 group-hover:bg-sky-100">{{ $participante->grado_p ?? 'N/A' }}</td>
                        {{-- <td class="px-3 py-3 text-gray-600">{{ $participante->programa ?? 'N/A' }}</td> --}}
                        <td class="px-2 py-2 text-[10px] text-gray-600 group-hover:bg-sky-100">
                            @if($participante->dias_de_asistencia_al_programa)
                                @php
                                    $diasEsperados = explode(',', $participante->dias_de_asistencia_al_programa);
                                @endphp
                                @foreach($diasEsperados as $de)
                                    @php
                                        $dia = strtolower(trim($de));
                                        $color = match($dia) {
                                            'lunes' => 'bg-red-200',
                                            'martes' => 'bg-yellow-200',
                                            'miércoles', 'miercoles' => 'bg-green-200',
                                            'jueves' => 'bg-blue-200',
                                            'viernes' => 'bg-purple-200',
                                            default => 'bg-gray-200',
                                        };
                                    @endphp
                                    <span class="inline-flex px-1 py-1 {{ $color }} rounded-full text-xs mr-1 text-center w-8 h-8 items-center justify-center">
                                        {{ mb_substr(ucfirst($dia), 0, 2) }}
                                    </span>
                                @endforeach
                            @else
                                N/A
                            @endif
                        </td>


                        {{-- Celdas para cada día de la semana --}}
                            @foreach ($diasSemana as $diaNombre => $fechaDia)
                            <td class="px-1 py-2 text-center group-hover:bg-sky-100/50 dark:group-hover:bg-sky-800/20">
                                @php
                                    // Determina el estado inicial de la asistencia para este participante y día.
                                    // Si no hay un registro, se asume 'Ausente'.
                                    $estadoInicial = $asistencias[$participante->participante_id][$diaNombre] ?? 'Ausente';
                                @endphp

                                {{-- Componente de Alpine.js para el selector de asistencia --}}
                                <div x-data="{
                                        open: false,
                                        selected: '{{ $estadoInicial }}',
                                        get colorClass() {
                                            switch(this.selected) {
                                                case 'Presente': return 'bg-green-200 dark:bg-green-700/50 text-green-800 dark:text-green-200 border-green-400 dark:border-green-600';
                                                case 'Justificado': return 'bg-yellow-200 dark:bg-yellow-600/50 text-yellow-800 dark:text-yellow-200 border-yellow-400 dark:border-yellow-500';
                                                default: return 'bg-red-200 dark:bg-red-700/50 text-red-800 dark:text-red-200 border-red-400 dark:border-red-600';
                                            }
                                        }
                                    }"
                                    class="relative flex items-center justify-center w-full">

                                    {{-- Botón visible del selector --}}
                                    {{-- Muestra la inicial del estado (P, A, J) y cambia de color según el estado. --}}
                                    <button @click="open = !open"
                                            :class="colorClass"
                                            class="flex items-center justify-center w-8 h-8 text-xs font-bold border rounded-full shadow-sm cursor-pointer focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-indigo-500 dark:focus:ring-offset-slate-800 transition-all duration-150">
                                        <span x-text="selected.charAt(0)"></span>
                                    </button>

                                    {{-- Panel desplegable con las opciones --}}
                                    <div x-show="open"
                                        @click.away="open = false"
                                        x-transition
                                        class="absolute z-30 w-32 mt-2 bg-white rounded-md shadow-lg dark:bg-slate-800 ring-1 ring-black ring-opacity-5"
                                        style="display: none; top: 100%;">
                                        <div class="py-1">
                                            {{--
                                            Al hacer clic en una opción:
                                            1. Se actualiza la variable 'selected' de Alpine.
                                            2. Se cierra el panel (open = false).
                                            3. Se espera al siguiente ciclo de renderizado ($nextTick) para asegurar que el <select> oculto se actualice.
                                            4. Se dispara manualmente un evento 'change' en el <select> oculto para que cualquier script de JS que escuche cambios reaccione.
                                            --}}
                                            <a @click.prevent="selected = 'Presente'; open = false; $nextTick(() => $refs.select.dispatchEvent(new Event('change')))" href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-slate-200 hover:bg-green-100 dark:hover:bg-green-700/50">
                                                <span class="w-3 h-3 mr-3 bg-green-400 rounded-full"></span> Presente
                                            </a>
                                            <a @click.prevent="selected = 'Ausente'; open = false; $nextTick(() => $refs.select.dispatchEvent(new Event('change')))" href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-slate-200 hover:bg-red-100 dark:hover:bg-red-700/50">
                                                <span class="w-3 h-3 mr-3 bg-red-400 rounded-full"></span> Ausente
                                            </a>
                                            <a @click.prevent="selected = 'Justificado'; open = false; $nextTick(() => $refs.select.dispatchEvent(new Event('change')))" href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-slate-200 hover:bg-yellow-100 dark:hover:bg-yellow-600/50">
                                                <span class="w-3 h-3 mr-3 bg-yellow-400 rounded-full"></span> Justificado
                                            </a>
                                        </div>
                                    </div>

                                    {{-- Select original oculto para mantener la funcionalidad del backend y JS --}}
                                    {{-- x-model="selected" vincula el valor de este select con la variable 'selected' de Alpine. --}}
                                    <select x-ref="select"
                                            name="asistencia_individual"
                                            x-model="selected"
                                            class="hidden asistencia-select"
                                            data-participante-id="{{ $participante->participante_id }}"
                                            data-fecha-asistencia="{{ $fechaDia }}"
                                            data-dia-nombre="{{ $diaNombre }}">
                                        <option value="Presente" {{ $estadoInicial == 'Presente' ? 'selected' : '' }}>P</option>
                                        <option value="Ausente" {{ $estadoInicial == 'Ausente' ? 'selected' : '' }}>A</option>
                                        <option value="Justificado" {{ $estadoInicial == 'Justificado' ? 'selected' : '' }}>J</option>
                                    </select>
                                </div>
                            </td>
                        @endforeach

                        {{-- Celdas para totales y porcentajes --}}
                        <td class="px-3 py-3 text-center total-asistido group-hover:bg-sky-100/50 dark:group-hover:bg-sky-800/20" data-participante-id="{{ $participante->participante_id }}">{{ $participante->totalAsistido ?? 0 }}</td>
                        <td class="px-3 py-3 text-center porcentaje-asistencia group-hover:bg-sky-100/50 dark:group-hover:bg-sky-800/20" data-participante-id="{{ $participante->participante_id }}">{{ $participante->porcentajeAsistencia ?? 0 }}%</td>
                    </tr>
                    @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="p-6 mt-6 text-sm text-gray-500 bg-white shadow-sm rounded-3xl">
        Seleccione todos los filtros (Programa, Lugar, Grado y Semana) para cargar la lista de participantes.
        @if(isset($selectedPrograma) && $selectedPrograma && isset($selectedLugar) && $selectedLugar && isset($selectedGrado) && $selectedGrado)
            <br>No se encontraron participantes para los filtros seleccionados.
        @endif
    </div>
@endif
