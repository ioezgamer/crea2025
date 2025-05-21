{{-- 
    Notes on this updated navigation:
    1. Theme: Light theme applied, removing dark mode classes.
    2. Styling: Uses Tailwind CSS to match the "Sistema CREA" aesthetic (gradients, rounded elements, modern feel).
    3. Logo: The "SistemaCREA" text is styled with a gradient. You can replace the text with an SVG logo if preferred.
    4. Nav Links & Dropdown: Styled for the light theme with hover and active states.
    5. Mobile Menu: Also styled for the light theme.
    6. Fixed Position: The navbar remains fixed at the top.
    7. Alpine.js: The `x-data`, `x-show`, `@click` attributes for Alpine.js functionality are preserved.
    8. Blade Components: The structure using `x-nav-link`, `x-dropdown`, `x-responsive-nav-link` is maintained.
       You'll need to ensure these components correctly render the passed classes or have their internal styles
       updated if they were previously hardcoded for a dark theme. The classes provided here are intended to be
       applied to the final HTML output of those components.
--}}

<nav x-data="{ open: false }" 
     class="bg-white/80 backdrop-blur-xl text-slate-700 fixed top-0 left-2 right-0 z-50 shadow-sm transition-all duration-300 ease-in-out"
     aria-label="Navegación Principal">
    <div class="container mx-auto px-4 sm:px-4 lg:px-4">
        <div class="flex items-center justify-between h-24 sm:h-20"> {{-- Increased height slightly for better spacing --}}
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center group">
                    {{-- Optional: SVG Logo Icon --}}
                    <div class="mr-2 h-8 w-8 sm:h-10 sm:w-10 flex items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 p-0.5 shadow-md group-hover:shadow-lg transition-shadow">
                        <div class="flex h-full w-full items-center justify-center rounded-full bg-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        </div>
                    </div>
                    <span class="font-bold text-xl text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 group-hover:opacity-80 transition-opacity">
                        SistemaCREA
                    </span>
                </a>

                {{-- Desktop Navigation Links --}}
                <div class="hidden md:ml-6 lg:ml-10 md:flex md:items-center md:space-x-2 lg:space-x-4">
                    {{-- It's assumed your x-nav-link component will handle applying appropriate classes for active/inactive states.
                         Base classes for styling:
                         Inactive: "text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 px-3 py-2 rounded-md text-sm font-medium transition-colors duration-150"
                         Active: "text-indigo-700 bg-indigo-100 px-3 py-2 rounded-md text-sm font-medium"
                         The `count` prop styling would need to be handled within the x-nav-link component itself.
                    --}}
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        <div class="flex items-center space-x-1.5">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                            <span class="text-sm font-medium">Panel</span>
                        </div>
                    </x-nav-link>

                    <x-nav-link :href="route('programas')" :active="request()->routeIs('programas')" :count="$activeProgramsCount ?? 0">
                        <div class="flex items-center space-x-1.5">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            <span class="text-sm font-medium">Programas</span>
                        </div>
                    </x-nav-link>
                    
                    <x-nav-link :href="route('participante.index')" :active="request()->routeIs('participante.index')" :count="$totalParticipants ?? 0">
                        <div class="flex items-center space-x-1.5">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            <span class="text-sm font-medium">Participantes</span>
                        </div>
                    </x-nav-link>

                    <x-nav-link :href="route('asistencia.create')" :active="request()->routeIs('asistencia.create')">
                        <div class="flex items-center space-x-1.5">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01m-.01 4h.01"></path></svg>
                            <span class="text-sm font-medium">Asistencia</span>
                        </div>
                    </x-nav-link>
                    
                    <x-nav-link :href="route('asistencia.reporte')" :active="request()->routeIs('asistencia.reporte')">
                        <div class="flex items-center space-x-1.5">
                           <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <span class="text-sm font-medium">Reportes</span>
                        </div>
                    </x-nav-link>

                    @can('manage-roles')
                    <x-nav-link :href="route('tutores')" :active="request()->routeIs('tutores')" :count="$tutorsCount ?? 0">
                        <div class="flex items-center space-x-1.5">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            <span class="text-sm font-medium">Tutores</span>
                        </div>
                    </x-nav-link>
                    @endcan
                    
                    {{-- Consider if 'tutores_participantes' is distinct enough or can be merged --}}
                    <x-nav-link :href="route('tutores_participantes')" :active="request()->routeIs('tutores_participantes')">
                        <div class="flex items-center space-x-1.5">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            <span class="text-sm font-medium">Detalles tutores</span>
                        </div>
                    </x-nav-link> 

                    @can('manage-roles')
                        <x-nav-link :href="route('roles.index')" :active="request()->routeIs('roles.index')" :count="$rolesCount ?? 0">
                            <div class="flex items-center space-x-1.5">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0-1.104-.896-2-2-2s-2 .896-2 2c0 .738.402 1.376 1 1.723V15a1 1 0 001 1h2a1 1 0 001-1v-2.277c.598-.347 1-.985 1-1.723zm8 0c0-1.104-.896-2-2-2s-2 .896-2 2c0 .738.402 1.376 1 1.723V15a1 1 0 001 1h2a1 1 0 001-1v-2.277c.598-.347 1-.985 1-1.723zM6 3a3 3 0 00-3 3v12a3 3 0 003 3h12a3 3 0 003-3V6a3 3 0 00-3-3H6z"></path></svg>
                                <span class="text-sm font-medium">Roles</span>
                            </div>
                        </x-nav-link>
                    @endcan
                </div>
            </div>

            {{-- Right side: User Dropdown & Mobile Menu Button --}}
            <div class="flex items-center">
                {{-- User Dropdown (Desktop) --}}
                <div class="hidden md:ml-4 md:relative md:flex md:items-center">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center space-x-2 text-sm font-medium rounded-full 
                                           {{ request()->routeIs('profile.edit') 
                                                ? 'text-indigo-700 bg-indigo-100 hover:bg-indigo-200' 
                                                : 'text-slate-600 hover:text-indigo-600 hover:bg-indigo-50' }} 
                                           px-3 py-2 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 
                                           transition-colors duration-150 ease-in-out">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                <span class="text-xs hidden sm:inline">{{ Auth::user()->name }}</span>
                                <svg class="h-4 w-4 opacity-75" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            {{-- Dropdown content classes: mt-2 w-48 bg-white rounded-md shadow-xl ring-1 ring-black ring-opacity-5 py-1 --}}
                            <x-dropdown-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">
                                {{-- Link classes: block px-4 py-2 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 --}}
                                <svg class="h-4 w-4 mr-2 inline-block opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066 2.573c-.94-1.543.826-3.31 2.37-2.37a1.724 1.724 0 002.572-1.065zM12 14a2 2 0 100-4 2 2 0 000 4z"></path></svg>
                                {{ __('Perfil') }}
                            </x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                    <svg class="h-4 w-4 mr-2 inline-block opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    {{ __('Cerrar Sesión') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>

                {{-- Mobile Menu Button --}}
                <div class="md:hidden flex items-center ml-2">
                    <button @click="open = !open" 
                            class="inline-flex items-center justify-center p-2 rounded-full text-slate-500 hover:text-indigo-600 hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 transition-colors duration-150"
                            aria-controls="mobile-menu" 
                            :aria-expanded="open.toString()"
                            aria-label="Alternar Menú Móvil">
                        <span class="sr-only">Abrir menú principal</span>
                        <svg x-show="!open" class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                        <svg x-show="open" class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
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
         class="md:hidden fixed inset-x-0 top-16 sm:top-20 z-40 bg-white/95 backdrop-blur-lg shadow-xl rounded-b-2xl overflow-hidden" 
         id="mobile-menu"
         @click.away="open = false">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            {{-- 
                Responsive Nav Link Styling:
                Base: "block px-3 py-2 rounded-md text-base font-medium text-slate-700 hover:text-indigo-700 hover:bg-indigo-100"
                Active: "block px-3 py-2 rounded-md text-base font-medium text-indigo-700 bg-indigo-100"
            --}}
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                <div class="flex items-center space-x-2">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span>Panel</span>
                </div>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('programas')" :active="request()->routeIs('programas')">
                <div class="flex items-center space-x-2">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    <span>Programas</span>
                </div>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('participante.index')" :active="request()->routeIs('participante.index')">
                <div class="flex items-center space-x-2">
                     <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span>Participantes</span>
                </div>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('asistencia.create')" :active="request()->routeIs('asistencia.create')">
                <div class="flex items-center space-x-2">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01m-.01 4h.01"></path></svg>
                    <span>Asistencia</span>
                </div>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('asistencia.reporte')" :active="request()->routeIs('asistencia.reporte')">
                <div class="flex items-center space-x-2">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Reportes</span>
                </div>
            </x-responsive-nav-link>
            @can('manage-roles')
            <x-responsive-nav-link :href="route('tutores')" :active="request()->routeIs('tutores')">
                <div class="flex items-center space-x-2">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <span>Tutores</span>
                </div>
            </x-responsive-nav-link>
            @endcan
            {{-- Responsive link for 'Detalles tutores' --}}
            <x-responsive-nav-link :href="route('tutores_participantes')" :active="request()->routeIs('tutores_participantes')">
                <div class="flex items-center space-x-2">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span>Detalles tutores</span>
                </div>
            </x-responsive-nav-link>
            @can('manage-roles')
                <x-responsive-nav-link :href="route('roles.index')" :active="request()->routeIs('roles.index')">
                    <div class="flex items-center space-x-2">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0-1.104-.896-2-2-2s-2 .896-2 2c0 .738.402 1.376 1 1.723V15a1 1 0 001 1h2a1 1 0 001-1v-2.277c.598-.347 1-.985 1-1.723zm8 0c0-1.104-.896-2-2-2s-2 .896-2 2c0 .738.402 1.376 1 1.723V15a1 1 0 001 1h2a1 1 0 001-1v-2.277c.598-.347 1-.985 1-1.723zM6 3a3 3 0 00-3 3v12a3 3 0 003 3h12a3 3 0 003-3V6a3 3 0 00-3-3H6z"></path></svg>
                        <span>Roles</span>
                    </div>
                </x-responsive-nav-link>
            @endcan
        </div>
        {{-- Mobile Menu User Info & Actions --}}
        <div class="pt-4 pb-3 border-t border-slate-200">
            <div class="flex items-center px-5">
                <div class="flex-shrink-0">
                    <svg class="h-10 w-10 rounded-full text-slate-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <div class="text-base font-medium leading-none text-slate-800">{{ Auth::user()->name }}</div>
                    @if(Auth::user()->email)
                    <div class="text-sm font-medium leading-none text-slate-500 mt-1">{{ Auth::user()->email }}</div>
                    @endif
                </div>
                {{-- Future: Notification button could go here --}}
            </div>
            <div class="mt-3 px-2 space-y-1">
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
<div class="h-16 sm:h-20"></div>
