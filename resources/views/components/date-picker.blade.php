@props([
    'disabled' => false,
    'value' => '',
    // 'options' => '{}' // Descomenta si quieres pasar opciones avanzadas como JSON
])

<div
    wire:ignore {{-- Si usas Livewire, esto evita que se pierda el estado del datepicker --}}
    x-data="{
        value: @js($value),
        instance: null,
        init() {
            this.instance = flatpickr(this.$refs.input, {
                // Configuración de flatpickr
                dateFormat: 'Y-m-d', // Formato de fecha que entiende la base de datos
                altInput: true,      // Muestra un formato legible para el usuario
                altFormat: 'd F, Y', // Formato legible (ej: 09 Junio, 2025)
                locale: 'es',        // Usar el idioma español
                defaultDate: this.value,
                monthSelectorType: 'dropdown', // Selector de mes como desplegable

                onChange: (selectedDates, dateStr, instance) => {
                    this.value = dateStr;
                },

                //maxDate: 'today', // No permitir fechas futuras
                // ... puedes añadir más opciones aquí ...
            });
        }
    }"
>
    <input
        x-ref="input"
        type="text"
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->merge(['class' => 'block w-full text-sm text-left border-gray-300 rounded-3xl shadow-sm dark:border-slate-600 dark:bg-slate-900/50 dark:text-slate-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-600']) }}
        :value="value"
    >
</div>
