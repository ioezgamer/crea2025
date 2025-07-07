@props([
    'comunidades',
    'name',
    'id',
    'value' => '',
    'required' => false
])

@php
    // Lógica para determinar el estado inicial en el servidor
    $oldValue = old($name, $value);
    $showNuevaComunidad = old('nueva_'.$name) || (!in_array($oldValue, $comunidades->all()) && !empty($oldValue));
    $selectedComunidad = $showNuevaComunidad ? '_OTRA_' : $oldValue;
@endphp

<div x-data="{
    open: false,
    selected: '{{ $selectedComunidad }}',
    esOtraComunidad: {{ $showNuevaComunidad ? 'true' : 'false' }},
    get displayText() {
        if (this.selected === '_OTRA_') {
            return 'Otra... (especificar)';
        }
        return this.selected || 'Seleccione...';
    }
}">
    {{-- Botón visible del selector personalizado --}}
    <button @click="open = !open" type="button"
            {{ $attributes->merge(['class' => 'relative w-full px-3 py-2 text-left bg-white border border-gray-300 rounded-3xl shadow-sm dark:border-slate-600 dark:bg-slate-900/50 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:text-sm']) }}>
        <span class="block truncate" x-text="displayText"></span>
        <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
            <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a.75.75 0 01.53.22l3.5 3.5a.75.75 0 01-1.06 1.06L10 4.81 6.53 8.28a.75.75 0 01-1.06-1.06l3.5-3.5A.75.75 0 0110 3zm-3.72 9.53a.75.75 0 011.06 0L10 15.19l3.47-3.47a.75.75 0 111.06 1.06l-4 4a.75.75 0 01-1.06 0l-4-4a.75.75 0 010-1.06z" clip-rule="evenodd" /></svg>
        </span>
    </button>

    {{-- Panel desplegable con las opciones --}}
    <div x-show="open" @click.away="open = false" x-transition
         class="absolute z-50 mt-1 overflow-auto text-base bg-white rounded-md shadow-lg w-80 dark:bg-slate-800 max-h-60 ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm" style="display: none;">
        @foreach ($comunidades as $comunidad)
            <div @click="selected = '{{ $comunidad }}'; esOtraComunidad = false; open = false"
                 :class="{'bg-indigo-600 text-white': selected === '{{ $comunidad }}'}"
                 class="relative py-2 pl-3 text-gray-900 cursor-default select-none pr-9 hover:bg-indigo-600 hover:text-white dark:text-white">
                <span class="block truncate" :class="{'font-semibold': selected === '{{ $comunidad }}'}">{{ $comunidad }}</span>
                 <span x-show="selected === '{{ $comunidad }}'" class="absolute inset-y-0 right-0 flex items-center pr-4 text-white"><svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.052-.143z" clip-rule="evenodd" /></svg></span>
            </div>
        @endforeach
        <div @click="selected = '_OTRA_'; esOtraComunidad = true; open = false"
             :class="{'bg-indigo-600 text-white': selected === '_OTRA_'}"
             class="relative py-2 pl-3 text-gray-900 cursor-default select-none pr-9 hover:bg-indigo-600 hover:text-white dark:text-white">
            <span class="block truncate" :class="{'font-semibold': selected === '_OTRA_'}">Otra... (especificar)</span>
        </div>
    </div>

    {{-- Select oculto que mantiene el valor para el formulario --}}
    <select name="{{ $name }}" id="{{ $id }}" x-model="selected" class="hidden" {{ $required ? 'required' : '' }}>
        <option value="" disabled>Seleccione...</option>
        @foreach ($comunidades as $comunidad)
            <option value="{{ $comunidad }}">{{ $comunidad }}</option>
        @endforeach
        <option value="_OTRA_">Otra... (especificar)</option>
    </select>
    <x-input-error :messages="$errors->get($name)" class="mt-2" />

    {{-- Campo de texto para "Otra" --}}
    <div x-show="esOtraComunidad" x-transition class="mt-2">
        <label for="nueva_{{ $id }}" class="block mb-1 text-xs font-medium text-gray-700 dark:text-slate-400">
            Nombre de la nueva comunidad <span class="text-red-500">*</span>
        </label>
        <x-text-input
            id="nueva_{{ $id }}"
            type="text"
            name="nueva_{{ $name }}"
            :value="old('nueva_'.$name, $showNuevaComunidad ? $value : '')"
            class="block w-full mt-1"
            placeholder="Escriba el nombre aquí..."
            x-bind:required="esOtraComunidad"
        />
        <x-input-error :messages="$errors->get('nueva_'.$name)" class="mt-2" />
    </div>
</div>
