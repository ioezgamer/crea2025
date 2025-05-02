<nav x-data="{ open: false }" class="bg-white border-r border-gray-100 fixed inset-y-0 left-0 w-64 z-50 transform transition-transform duration-300 ease-in-out sm:translate-x-0" :class="{ '-translate-x-full': !open }">
    <!-- Primary Navigation Menu -->
    <div class="h-full flex flex-col">
        <!-- Logo -->
        <div class="flex items-center justify-center h-16 border-b border-gray-200">
            <a href="{{ route('dashboard') }}">
                <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
            </a>
        </div>

        <!-- Navigation Links -->
        <div class="flex-1 overflow-y-auto pt-4 px-4 space-y-1">
            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="flex items-center justify-between space-x-3 text-gray-700 hover:bg-gray-100 rounded-md px-3 py-2">
                <div class="flex items-center space-x-3">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span>Panel</span>
                </div>
                <span class="text-xs text-gray-500">10</span>
            </x-nav-link>

            <x-nav-link :href="route('programas')" :active="request()->routeIs('programas')" class="flex items-center justify-between space-x-3 text-gray-700 hover:bg-gray-100 rounded-md px-3 py-2">
                <div class="flex items-center space-x-3">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <span>Programas</span>
                </div>
                <span class="text-xs text-gray-500">25</span>
            </x-nav-link>

            <x-nav-link :href="route('participante.index')" :active="request()->routeIs('participante')" class="flex items-center justify-between space-x-3 text-gray-700 hover:bg-gray-100 rounded-md px-3 py-2">
                <div class="flex items-center space-x-3">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span>Participantes</span>
                </div>
                <span class="text-xs text-gray-500">81</span>
            </x-nav-link>

            <x-nav-link :href="route('asistencia.create')" :active="request()->routeIs('asistencia')" class="flex items-center justify-between space-x-3 text-gray-700 hover:bg-gray-100 rounded-md px-3 py-2">
                <div class="flex items-center space-x-3">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01m-.01 4h.01"></path>
                    </svg>
                    <span>Asistencia</span>
                </div>
                <span class="text-xs text-gray-500">0</span>
            </x-nav-link>

            <x-nav-link :href="route('asistencia.reporte')" :active="request()->routeIs('asistencia')" class="flex items-center justify-between space-x-3 text-gray-700 hover:bg-gray-100 rounded-md px-3 py-2">
                <div class="flex items-center space-x-3">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>Reportes asistencia</span>
                </div>
                <span class="text-xs text-gray-500">5</span>
            </x-nav-link>

            <x-nav-link :href="route('tutores')" :active="request()->routeIs('tutores')" class="flex items-center justify-between space-x-3 text-gray-700 hover:bg-gray-100 rounded-md px-3 py-2">
                <div class="flex items-center space-x-3">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span>Tutores</span>
                </div>
                <span class="text-xs text-gray-500">12</span>
            </x-nav-link>

            <x-nav-link :href="route('tutores_participantes')" :active="request()->routeIs('tutores_participantes')" class="flex items-center justify-between space-x-3 text-gray-700 hover:bg-gray-100 rounded-md px-3 py-2">
                <div class="flex items-center space-x-3">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span>Tutores y Participantes</span>
                </div>
                <span class="text-xs text-gray-500">20</span>
            </x-nav-link>

            @can('manage-roles')
                <x-nav-link :href="route('roles.index')" :active="request()->routeIs('roles.index')" class="flex items-center justify-between space-x-3 text-gray-700 hover:bg-gray-100 rounded-md px-3 py-2">
                    <div class="flex items-center space-x-3">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0-1.104-.896-2-2-2s-2 .896-2 2c0 .738.402 1.376 1 1.723V15a1 1 0 001 1h2a1 1 0 001-1v-2.277c.598-.347 1-.985 1-1.723zm8 0c0-1.104-.896-2-2-2s-2 .896-2 2c0 .738.402 1.376 1 1.723V15a1 1 0 001 1h2a1 1 0 001-1v-2.277c.598-.347 1-.985 1-1.723zM6 3a3 3 0 00-3 3v12a3 3 0 003 3h12a3 3 0 003-3V6a3 3 0 00-3-3H6z"></path>
                        </svg>
                        <span>Roles</span>
                    </div>
                    <span class="text-xs text-gray-500">9</span>
                </x-nav-link>
            @endcan
        </div>
    </div>

    <!-- Hamburger Button for Mobile -->
    <div class="sm:hidden fixed top-4 right-4 z-50">
        <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Overlay for Mobile -->
    <div x-show="open" @click="open = false" class="sm:hidden fixed inset-0 bg-black bg-opacity-50 z-40"></div>
</nav>

<!-- Top Bar for User Profile -->
<div class="fixed top-0 right-0 w-full sm:w-auto sm:ml-64 bg-white border-b border-gray-200 p-4 flex justify-end z-40">
    <x-dropdown align="right" width="48">
        <x-slot name="trigger">
            <button class="flex items-center space-x-3 text-gray-700 hover:bg-gray-100 rounded-md px-3 py-2">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&size=32" class="h-8 w-8 rounded-full" alt="{{ Auth::user()->name }}">
                <span>{{ Auth::user()->name }}</span>
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
        </x-slot>

        <x-slot name="content">
            <x-dropdown-link :href="route('profile.edit')">
                {{ __('Profile') }}
            </x-dropdown-link>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-dropdown-link :href="route('logout')"
                        onclick="event.preventDefault();
                                    this.closest('form').submit();">
                    {{ __('Log Out') }}
                </x-dropdown-link>
            </form>
        </x-slot>
    </x-dropdown>
</div>

<!-- Main Content and Footer -->
<div class="sm:ml-64 pt-16 min-h-screen">
    {{ $slot }}

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-auto p-4 text-center text-gray-600 text-sm">
        <p>&copy; {{ date('Y') }} Tu Aplicación. Todos los derechos reservados.</p>
    </footer>
</div>