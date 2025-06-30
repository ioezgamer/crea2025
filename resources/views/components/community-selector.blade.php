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
    $showNuevaComunidad = old($name) === '_OTRA_' || (!in_array($oldValue, $comunidades->all()) && !empty($oldValue));
    $selectedComunidad = $showNuevaComunidad ? '_OTRA_' : $oldValue;
@endphp

<div x-data="{
    esOtraComunidad: {{ $showNuevaComunidad ? 'true' : 'false' }}
}">
    {{-- Menú desplegable con las comunidades existentes y la opción "Otra" --}}
    <select
        name="{{ $name }}"
        id="{{ $id }}"
        @change="esOtraComunidad = ($event.target.value === '_OTRA_')"
        {{ $attributes->merge(['class' => 'block w-full text-sm border-gray-300 rounded-3xl shadow-sm dark:border-slate-600 dark:bg-slate-900/50 dark:text-slate-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-600']) }}
        {{ $required ? 'required' : '' }}
    >
        <option value="" disabled {{ $selectedComunidad == '' ? 'selected' : '' }}>Seleccione...</option>
        @foreach ($comunidades as $comunidad)
            <option value="{{ $comunidad }}" {{ $selectedComunidad == $comunidad ? 'selected' : '' }}>
                {{ $comunidad }}
            </option>
        @endforeach
        <option value="_OTRA_" {{ $selectedComunidad == '_OTRA_' ? 'selected' : '' }}>
            Otra... (especificar)
        </option>
    </select>
    <x-input-error :messages="$errors->get($name)" class="mt-2" />

    {{-- Campo de texto que aparece si se selecciona "Otra" o si el valor existente es personalizado --}}
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
