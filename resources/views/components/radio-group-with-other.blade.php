@props([
    'options',
    'name',
    'value' => '',
    'required' => false
])

@php
    $oldValue = old($name, $value);
    $isOtherSelected = old($name) === '_OTRA_' || (!in_array($oldValue, $options->all()) && !empty($oldValue));
    $selectedValue = $isOtherSelected ? '_OTRA_' : $oldValue;
@endphp

<div x-data="{ selected: '{{ $selectedValue }}' }">
    <div class="grid grid-cols-2 gap-x-6 gap-y-3 sm:grid-cols-3 md:grid-cols-4">
        @foreach ($options as $option)
            <label class="flex items-center text-sm cursor-pointer dark:text-slate-200">
                <input type="radio" name="{{ $name }}" value="{{ $option }}" x-model="selected"
                       class="w-4 h-4 mr-2 text-indigo-600 border-gray-300 dark:border-slate-500 focus:ring-indigo-500 dark:focus:ring-offset-slate-800">
                <span class="truncate">{{ $option }}</span>
            </label>
        @endforeach
        {{-- Radio Button para "Otra" --}}
        <label class="flex items-center text-sm cursor-pointer dark:text-slate-200">
            <input type="radio" name="{{ $name }}" value="_OTRA_" x-model="selected"
                   class="w-4 h-4 mr-2 text-indigo-600 border-gray-300 dark:border-slate-500 focus:ring-indigo-500 dark:focus:ring-offset-slate-800">
            <span>Otra...</span>
        </label>
    </div>
    <x-input-error :messages="$errors->get($name)" class="mt-2" />

    {{-- Campo de texto que aparece si se selecciona "Otra" --}}
    <div x-show="selected === '_OTRA_'" x-transition class="mt-3">
        <x-text-input
            type="text"
            name="nueva_{{ $name }}"
            :value="old('nueva_'.$name, $isOtherSelected ? $value : '')"
            class="block w-full sm:w-1/2"
            placeholder="Especifique el nuevo lugar..."
            x-bind:required="selected === '_OTRA_'"
        />
        <x-input-error :messages="$errors->get('nueva_'.$name)" class="mt-2" />
    </div>
</div>
