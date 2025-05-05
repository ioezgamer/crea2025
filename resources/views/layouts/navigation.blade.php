<nav x-data="{ 
    open: false, 
    sidebarOpen: window.innerWidth >= 640 ? true : false 
}" 
x-init="
    console.log('Sidebar initialized, sidebarOpen:', sidebarOpen);
    window.addEventListener('resize', () => {
        if (window.innerWidth < 640) {
            sidebarOpen = false;
            open = false;
        } else {
            sidebarOpen = true;
        }
    });
" 
x-bind:class="{ 'w-64': sidebarOpen && window.innerWidth >= 640, 'w-16': !sidebarOpen && window.innerWidth >= 640 }" 
class="bg-gradient-to-b from-gray-900 to-gray-800 text-white fixed inset-y-0 left-0 z-50 transform transition-all duration-300 ease-in-out shadow-lg"
x-bind:class="{ 'hidden': !open && window.innerWidth < 640 }"
x-bind:aria-expanded="sidebarOpen"
aria-label="Navegación Principal">
    <div class="h-full flex flex-col">
        <!-- Logo y Botón de Alternancia -->
        <div class="flex items-center justify-between px-4 py-4 border-b border-gray-700">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                <x-application-logo class="h-8 w-auto fill-current text-white transition-transform duration-300 hover:scale-105" />
                <span x-show="sidebarOpen && window.innerWidth >= 640" class="text-xl font-bold transition-opacity duration-300">Tu Proyecto</span>
            </a>
            <button @click="sidebarOpen = !sidebarOpen; console.log('Toggled sidebarOpen to:', !sidebarOpen)" 
                    class="p-2 rounded-full hover:bg-gray-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-white"
                    x-show="window.innerWidth >= 640"
                    aria-label="Alternar Sidebar">
                <svg x-show="!sidebarOpen" class="h-6 w-6 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
                <svg x-show="sidebarOpen" class="h-6 w-6 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Enlaces de Navegación -->
        <div class="flex-1 overflow-y-auto pt-4 px-2 space-y-2">
            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" count="585">
                <div class="flex items-center space-x-3">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span x-show="sidebarOpen && window.innerWidth >= 640">Total Inscritos</span>
                </div>
            </x-nav-link>

            <x-nav-link :href="route('programas')" :active="request()->routeIs('programas')" count="3">
                <div class="flex items-center space-x-3">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <span x-show="sidebarOpen && window.innerWidth >= 640">Programas Activos</span>
                </div>
            </x-nav-link>

            <x-nav-link :href="route('participante.index')" :active="request()->routeIs('participante')" count="7">
                <div class="flex items-center space-x-3">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span x-show="sidebarOpen && window.innerWidth >= 640">Lugares de Encuentro</span>
                </div>
            </x-nav-link>

            <x-nav-link :href="route('asistencia.create')" :active="request()->routeIs('asistencia')" count="0">
                <div class="flex items-center space-x-3">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01m-.01 4h.01"></path>
                    </svg>
                    <span x-show="sidebarOpen && window.innerWidth >= 640">Asistencia</span>
                </div>
            </x-nav-link>

            <x-nav-link :href="route('asistencia.reporte')" :active="request()->routeIs('asistencia')" count="5">
                <div class="flex items-center space-x-3">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span x-show="sidebarOpen && window.innerWidth >= 640">Reportes asistencia</span>
                </div>
            </x-nav-link>

            <x-nav-link :href="route('tutores')" :active="request()->routeIs('tutores')" count="12">
                <div class="flex items-center space-x-3">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span x-show="sidebarOpen && window.innerWidth >= 640">Tutores</span>
                </div>
            </x-nav-link>

            <x-nav-link :href="route('tutores_participantes')" :active="request()->routeIs('tutores_participantes')" count="20">
                <div class="flex items-center space-x-3">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span x-show="sidebarOpen && window.innerWidth >= 640">Tutores y Participantes</span>
                </div>
            </x-nav-link>

            @can('manage-roles')
                <x-nav-link :href="route('roles.index')" :active="request()->routeIs('roles.index')" count="9">
                    <div class="flex items-center space-x-3">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0-1.104-.896-2-2-2s-2 .896-2 2c0 .738.402 1.376 1 1.723V15a1 1 0 001 1h2a1 1 0 001-1v-2.277c.598-.347 1-.985 1-1.723zm8 0c0-1.104-.896-2-2-2s-2 .896-2 2c0 .738.402 1.376 1 1.723V15a1 1 0 001 1h2a1 1 0 001-1v-2.277c.598-.347 1-.985 1-1.723zM6 3a3 3 0 00-3 3v12a3 3 0 003 3h12a3 3 0 003-3V6a3 3 0 00-3-3H6z"></path>
                        </svg>
                        <span x-show="sidebarOpen && window.innerWidth >= 640">Roles</span>
                    </div>
                </x-nav-link>
            @endcan
        </div>

        <!-- Sección de Usuario -->
        <div class="border-t border-gray-700 p-4">
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="flex items-center space-x-3 w-full text-gray-300 hover:bg-gray-700 rounded-lg px-3 py-2 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-white">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&size=32" 
                             class="h-8 w-8 rounded-full" 
                             alt="Avatar de {{ Auth::user()->name }}">
                        <div x-show="sidebarOpen && window.innerWidth >= 640" class="flex-1 flex flex-col text-left">
                            <span class="text-sm font-medium">{{ Auth::user()->name }}</span>
                            <span class="text-xs text-gray-400">{{ Auth::user()->email }}</span>
                        </div>
                        <svg x-show="sidebarOpen && window.innerWidth >= 640" class="h-4 w-4 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link :href="route('profile.edit')">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c-.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37a1.724 1.724 0 002.572-1.065zM12 14a2 2 0 100-4 2 2 0 000 4z"></path>
                        </svg>
                        {{ __('Perfil') }}
                    </x-dropdown-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            {{ __('Cerrar Sesión') }}
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>
    </div>

    <!-- Botón de Hamburguesa para Móvil -->
    <div class="-me-2 flex items-center sm:hidden">
        <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('programas')" :active="request()->routeIs('programas')">
                {{ __('Programas') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('participante.index')" :active="request()->routeIs('participante')">
                {{ __('Participantes') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('asistencia.create')" :active="request()->routeIs('asistencia')">
                {{ __('Asistencia') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('tutores')" :active="request()->routeIs('tutores')">
                {{ __('Tutores') }}
            </x-responsive-nav-link>
            @can('manage-roles')
            <x-responsive-nav-link :href="route('roles.index')" :active="request()->routeIs('roles.index')">
                {{ __('Roles') }}
            </x-responsive-nav-link>
            @endcan
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>