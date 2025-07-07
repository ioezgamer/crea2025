{{-- resources/views/layouts/navigation.blade.php --}}
<nav x-data="{ open: false }"
     class="fixed top-0 left-0 right-0 z-50 h-auto backdrop-blur-[10px] bg-white/50 dark:bg-slate-900/30 border-white/70 dark:border-slate-200/10 text-slate-800 dark:text-slate-100 "
     aria-label="Navegación Principal">
    <div class="max-w-full px-4 mx-auto sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 sm:h-18"> {{-- Increased height slightly for better spacing --}}
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex items-center flex-shrink-0 group">
                    <x-application-logo class="w-8 h-8 mr-2 text-indigo-600 dark:text-indigo-400" />
                    {{-- Logo Text with gradient and hover effect --}}
                    <span class="text-sm font-bold text-transparent transition-opacity bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 group-hover:opacity-80">
                        SistemaCREA
                    </span>
                </a>

                <div class="hidden md:ml-6 lg:ml-10 md:flex md:items-center md:space-x-2 lg:space-x-4">
                    @can('manage-roles')
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        <div class="flex items-center space-x-1.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            <span class="text-xs font-medium">Panel</span>
                        </div>
                    </x-nav-link>
                    @endcan
                    <x-nav-link :href="route('programas')" :active="request()->routeIs('programas')" :count="$activeProgramsCount ?? 0">
                        <div class="flex items-center space-x-1.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            <span class="text-xs font-medium">Programas</span>
                        </div>
                    </x-nav-link>
                    <x-nav-link :href="route('participante.index')" :active="request()->routeIs('participante.index')" :count="$totalParticipants ?? 0">
                        <div class="flex items-center space-x-1.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            <span class="text-xs font-medium">Participantes</span>
                        </div>
                    </x-nav-link>
                    <x-nav-link :href="route('asistencia.create')" :active="request()->routeIs('asistencia.create')">
                        <div class="flex items-center space-x-1.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01m-.01 4h.01"></path></svg>
                            <span class="text-xs font-medium">Asistencia</span>
                        </div>
                    </x-nav-link>
                    <x-nav-link :href="route('asistencia.reporte')" :active="request()->routeIs('asistencia.reporte')">
                        <div class="flex items-center space-x-1.5">
                           <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <span class="text-xs font-medium">Reportes</span>
                        </div>
                    </x-nav-link>
                    @can('manage-roles')
                    <x-nav-link :href="route('tutores')" :active="request()->routeIs('tutores')" :count="$tutorsCount ?? 0">
                        <div class="flex items-center space-x-1.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            <span class="text-xs font-medium">Tutores</span>
                        </div>
                    </x-nav-link>
                    @endcan
                    <x-nav-link :href="route('tutores_participantes')" :active="request()->routeIs('tutores_participantes')">
                        <div class="flex items-center space-x-1.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            <span class="text-xs font-medium">Detalles tutores</span>
                        </div>
                    </x-nav-link>
                    @can('manage-roles')
                        <x-nav-link :href="route('roles.index')" :active="request()->routeIs('roles.index')" :count="$managedUsersCount ?? 0">
                            <div class="flex items-center space-x-1.5">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0-1.104-.896-2-2-2s-2 .896-2 2c0 .738.402 1.376 1 1.723V15a1 1 0 001 1h2a1 1 0 001-1v-2.277c.598-.347 1-.985 1-1.723zm8 0c0-1.104-.896-2-2-2s-2 .896-2 2c0 .738.402 1.376 1 1.723V15a1 1 0 001 1h2a1 1 0 001-1v-2.277c.598-.347 1-.985 1-1.723zM6 3a3 3 0 00-3 3v12a3 3 0 003 3h12a3 3 0 003-3V6a3 3 0 00-3-3H6z"></path></svg>
                                <span class="text-xs font-medium">Roles</span>
                            </div>
                        </x-nav-link>
                    @endcan
                </div>
            </div>

            {{-- Right side: User Dropdown & Mobile Menu Button --}}
            <div class="flex items-center">
                {{-- Dark Mode Toggle Button (Desktop) --}}
                <div class="hidden md:ml-4 md:flex md:items-center">
                    <x-dark-mode-toggle />
                </div>

                {{-- User Dropdown (Desktop) --}}
                <div class="hidden md:ml-3 md:relative md:flex md:items-center">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center space-x-2 text-sm font-medium rounded-full
                                           {{ request()->routeIs('profile.edit')
                                                ? 'text-indigo-700 bg-indigo-100 dark:text-indigo-300 dark:bg-indigo-700/50 hover:bg-indigo-200 dark:hover:bg-indigo-700'
                                                : 'text-slate-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-300 hover:bg-indigo-50 dark:hover:bg-slate-700' }}
                                           px-3 py-2 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-slate-800
                                           transition-colors duration-150 ease-in-out">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                <span class="hidden text-xs sm:inline">{{ collect(explode(' ', Auth::user()->name))->map(fn($p) => strtoupper(substr($p, 0, 1)))->implode('') }}</span>
                                <svg class="w-4 h-4 opacity-75" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            {{-- Dropdown content classes: mt-2 w-48 bg-white dark:bg-slate-700 rounded-md shadow-xl ring-1 ring-black dark:ring-slate-600 ring-opacity-5 py-1 --}}
                            <x-dropdown-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">
                                {{-- Link classes: block px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-indigo-50 dark:hover:bg-slate-600 hover:text-indigo-600 dark:hover:text-indigo-300 --}}
                                <svg class="inline-block w-4 h-4 mr-2 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066 2.573c-.94-1.543.826-3.31 2.37-2.37a1.724 1.724 0 002.572-1.065zM12 14a2 2 0 100-4 2 2 0 000 4z"></path></svg>
                                {{ __('Perfil') }}
                            </x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                    <svg class="inline-block w-4 h-4 mr-2 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    {{ __('Cerrar Sesión') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>

                {{-- Mobile Menu Button --}}
                <div class="flex items-center ml-2 md:hidden">
                    <button @click="open = !open"
                            class="inline-flex items-center justify-center p-2 transition-colors duration-150 rounded-full text-slate-500 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-300 hover:bg-indigo-100 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 dark:focus:ring-indigo-400"
                            aria-controls="mobile-menu"
                            :aria-expanded="open.toString()"
                            aria-label="Alternar Menú Móvil">
                        <span class="sr-only">Abrir menú principal</span>
                        <svg x-show="!open" class="block w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                        <svg x-show="open" class="block w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Mobile Menu Panel --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-x-0 z-40 overflow-hidden shadow-xl md:hidden top-16 sm:top-18 bg-white/95 dark:bg-slate-800/95 backdrop-blur-lg rounded-b-2xl"
         id="mobile-menu"
         @click.away="open = false"
         style="display: none;" {{-- Alpine handles visibility --}}
         >
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            {{-- Se asume que x-responsive-nav-link maneja sus propios estilos dark:
                 Base: "block px-3 py-2 rounded-md text-base font-medium text-slate-700 dark:text-slate-200 hover:text-indigo-700 dark:hover:text-indigo-300 hover:bg-indigo-100 dark:hover:bg-slate-700"
                 Active: "block px-3 py-2 rounded-md text-base font-medium text-indigo-700 dark:text-indigo-300 bg-indigo-100 dark:bg-indigo-700/50"
            --}}
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span>Panel</span>
                </div>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('programas')" :active="request()->routeIs('programas')">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    <span>Programas</span>
                </div>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('participante.index')" :active="request()->routeIs('participante.index')">
                <div class="flex items-center space-x-2">
                     <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span>Participantes</span>
                </div>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('asistencia.create')" :active="request()->routeIs('asistencia.create')">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01m-.01 4h.01"></path></svg>
                    <span>Asistencia</span>
                </div>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('asistencia.reporte')" :active="request()->routeIs('asistencia.reporte')">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Reportes</span>
                </div>
            </x-responsive-nav-link>
            @can('manage-roles')
            <x-responsive-nav-link :href="route('tutores')" :active="request()->routeIs('tutores')">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <span>Tutores</span>
                </div>
            </x-responsive-nav-link>
            @endcan
            <x-responsive-nav-link :href="route('tutores_participantes')" :active="request()->routeIs('tutores_participantes')">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span>Detalles tutores</span>
                </div>
            </x-responsive-nav-link>
            @can('manage-roles')
                <x-responsive-nav-link :href="route('roles.index')" :active="request()->routeIs('roles.index')">
                    <div class="flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0-1.104-.896-2-2-2s-2 .896-2 2c0 .738.402 1.376 1 1.723V15a1 1 0 001 1h2a1 1 0 001-1v-2.277c.598-.347 1-.985 1-1.723zm8 0c0-1.104-.896-2-2-2s-2 .896-2 2c0 .738.402 1.376 1 1.723V15a1 1 0 001 1h2a1 1 0 001-1v-2.277c.598-.347 1-.985 1-1.723zM6 3a3 3 0 00-3 3v12a3 3 0 003 3h12a3 3 0 003-3V6a3 3 0 00-3-3H6z"></path></svg>
                        <span>Roles</span>
                    </div>
                </x-responsive-nav-link>
            @endcan
        </div>
        {{-- Mobile Menu User Info & Actions --}}
        <div class="pt-4 pb-3 border-t border-slate-200 dark:border-slate-700">
            <div class="flex items-center px-5">
                <div class="flex-shrink-0">
                    <svg class="w-10 h-10 rounded-full text-slate-400 dark:text-slate-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <div class="text-base font-medium leading-none text-slate-800 dark:text-slate-200">{{ Auth::user()->name }}</div>
                    @if(Auth::user()->email)
                    <div class="mt-1 text-sm font-medium leading-none text-slate-500 dark:text-slate-400">{{ Auth::user()->email }}</div>
                    @endif
                </div>
            </div>
            <div class="px-2 mt-3 space-y-1">
                {{-- Dark mode toggle for mobile, styled like a responsive nav link --}}
                <div class="block px-3 py-2 text-base font-medium rounded-md text-slate-700 dark:text-slate-200 hover:text-indigo-700 dark:hover:text-indigo-300 hover:bg-indigo-100 dark:hover:bg-slate-700">
                    <div class="flex items-center justify-between">
                        <span>Modo Oscuro</span>
                        <x-dark-mode-toggle /> {{-- El componente dropdown se incluye aquí --}}
                    </div>
                </div>
                <x-responsive-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">
                    {{ __('Perfil') }}
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Cerrar Sesión') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

{{-- Spacer div to prevent content from being hidden behind the fixed navbar --}}
<div class="h-16 sm:h-18"></div>
