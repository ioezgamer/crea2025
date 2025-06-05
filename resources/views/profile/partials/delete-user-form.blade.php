<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    {{-- Se asume que x-danger-button maneja sus propios estilos dark: --}}
    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Delete Account') }}</x-danger-button>

    {{-- Se asume que x-modal maneja sus propios estilos dark: para el fondo del modal, etc. --}}
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-6">
                {{-- Se asume que x-input-label es compatible con modo oscuro o se le añaden clases dark: en su definición --}}
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                {{-- Se asume que x-text-input es compatible con modo oscuro o se le añaden clases dark: en su definición:
                     ej. dark:bg-slate-700 dark:border-slate-600 dark:text-slate-300 dark:focus:border-indigo-600 dark:focus:ring-indigo-600
                --}}
                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="block w-3/4 mt-1"
                    placeholder="{{ __('Password') }}"
                />

                {{-- Se asume que x-input-error maneja el color de texto en modo oscuro --}}
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="flex justify-end mt-6">
                {{-- Se asume que x-secondary-button maneja sus propios estilos dark: --}}
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                {{-- Se asume que x-danger-button maneja sus propios estilos dark: --}}
                <x-danger-button class="ms-3">
                    {{ __('Delete Account') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
