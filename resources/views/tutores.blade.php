<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tutores') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Program Filter -->
            <div class="mb-6">
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <p class="text-gray-900 text-sm mb-4 sm:mb-0">
                            {{ $selectedProgram ? "Métricas para tutores del programa: $selectedProgram" : 'Resumen de métricas de tutores' }}
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
                <!-- Total Tutores -->
                <div class="bg-white shadow-sm rounded-lg p-6 flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-10 w-10 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-600">Total Tutores Únicos</h3>
                        <p class="text-2xl font-semibold text-gray-900">{{ $totalTutors ?? 0 }}</p>
                    </div>
                </div>

                <!-- Tutores por Programa -->
                <div class="bg-white shadow-sm rounded-lg p-6 flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-10 w-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-600">Programas con Tutores</h3>
                        <p class="text-2xl font-semibold text-gray-900">{{ count($tutorsByProgram ?? []) }}</p>
                    </div>
                </div>

                <!-- Promedio de Participantes por Tutor -->
                <div class="bg-white shadow-sm rounded-lg p-6 flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-10 w-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-600">Promedio de Participantes por Tutor</h3>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($averageParticipantsPerTutor ?? 0, 1) }}</p>
                    </div>
                </div>
            </div>

            <!-- Detailed Breakdown -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Tutores por Programa -->
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-600 mb-4">Tutores por Programa</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-100">
                                <tr class="text-xs font-medium text-gray-600 uppercase">
                                    <th class="px-4 py-3 text-left">Programa</th>
                                    <th class="px-4 py-3 text-right">Tutores Únicos</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($tutorsByProgram ?? [] as $program => $count)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-gray-900">{{ $program }}</td>
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

                <!-- Tutores por Sector Económico -->
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-600 mb-4">Tutores por Sector Económico</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-100">
                                <tr class="text-xs font-medium text-gray-600 uppercase">
                                    <th class="px-4 py-3 text-left">Sector Económico</th>
                                    <th class="px-4 py-3 text-right">Tutores</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($tutorsBySector ?? [] as $sector => $count)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-gray-900">{{ $sector ?: 'No especificado' }}</td>
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

                <!-- Tutores por Nivel de Educación -->
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-600 mb-4">Tutores por Nivel de Educación</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-100">
                                <tr class="text-xs font-medium text-gray-600 uppercase">
                                    <th class="px-4 py-3 text-left">Nivel de Educación</th>
                                    <th class="px-4 py-3 text-right">Tutores</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($tutorsByEducationLevel ?? [] as $level => $count)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-gray-900">{{ $level ?: 'No especificado' }}</td>
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

                <!-- Tutores por Comunidad -->
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-600 mb-4">Tutores por Comunidad</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-100">
                                <tr class="text-xs font-medium text-gray-600 uppercase">
                                    <th class="px-4 py-3 text-left">Comunidad</th>
                                    <th class="px-4 py-3 text-right">Tutores</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($tutorsByCommunity ?? [] as $community => $count)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-gray-900">{{ $community ?: 'No especificado' }}</td>
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