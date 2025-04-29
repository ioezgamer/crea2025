<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Programas') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Program Filter -->
            <div class="mb-6">
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <p class="text-gray-900 text-sm mb-4 sm:mb-0">
                            {{ $selectedProgram ? "Métricas para el programa: $selectedProgram" : 'Resumen de métricas de los programas activos' }}
                        </p>
                        <div class="flex items-center">
                            <label for="programa" class="mr-2 text-sm text-gray-600">Filtrar por programa:</label>
                            <select id="programa" name="programa" onchange="window.location.href='?programa='+encodeURIComponent(this.value)" class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Todos los programas</option>
                                @foreach ($programs ?? [] as $program)
                                    <option value="{{ $program }}" {{ $selectedProgram === $program ? 'selected' : '' }}>{{ $program }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Total Programs -->
                <div class="bg-white shadow-sm rounded-lg p-6 flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-10 w-10 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-600">Total Programas</h3>
                        <p class="text-2xl font-semibold text-gray-900">{{ $selectedProgram ? 1 : count($participantsByProgram ?? []) }}</p>
                    </div>
                </div>

                <!-- Total Participants -->
                <div class="bg-white shadow-sm rounded-lg p-6 flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-10 w-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-600">Total Inscritos</h3>
                        <p class="text-2xl font-semibold text-gray-900">{{ $totalParticipants ?? 0 }}</p>
                    </div>
                </div>

                <!-- Average Age -->
                <div class="bg-white shadow-sm rounded-lg p-6 flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-10 w-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-600">Edad Promedio</h3>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($averageAge ?? 0, 1) }} años</p>
                    </div>
                </div>
            </div>

            <!-- Detailed Breakdown -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Participants by Grade -->
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-600 mb-4">Participantes por Grado</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-100">
                                <tr class="text-xs font-medium text-gray-600 uppercase">
                                    <th class="px-4 py-3 text-left">Grado</th>
                                    <th class="px-4 py-3 text-right">Inscritos</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($participantsByGrade ?? [] as $grade => $count)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-gray-900">{{ $grade }}</td>
                                        <td class="px-4 py-3 text-right text-gray-600">{{ $count }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-4 py-3 text-gray-500 text-center">No hay datos disponibles</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Participants by Gender -->
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-600 mb-4">Participantes por Género</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-100">
                                <tr class="text-xs font-medium text-gray-600 uppercase">
                                    <th class="px-4 py-3 text-left">Género</th>
                                    <th class="px-4 py-3 text-right">Inscritos</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($participantsByGender ?? [] as $gender => $count)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-gray-900">{{ $gender }}</td>
                                        <td class="px-4 py-3 text-right text-gray-600">{{ $count }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-4 py-3 text-gray-500 text-center">No hay datos disponibles</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Participants by Age Group -->
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-600 mb-4">Participantes por Grupo de Edad</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-100">
                                <tr class="text-xs font-medium text-gray-600 uppercase">
                                    <th class="px-4 py-3 text-left">Rango de Edad</th>
                                    <th class="px-4 py-3 text-right">Inscritos</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($participantsByAgeGroup ?? [] as $ageGroup => $count)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-gray-900">{{ $ageGroup }}</td>
                                        <td class="px-4 py-3 text-right text-gray-600">{{ $count }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-4 py-3 text-gray-500 text-center">No hay datos disponibles</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>