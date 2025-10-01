<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laboratorio - CIAT') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
</head>

<body class="font-sans antialiased">
    <x-banner />
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <div x-data="{ open: false }" class="min-h-screen bg-gray-100 dark:bg-gray-900">

            {{-- TOPBAR FIJO, alineado al sidebar en desktop --}}
            <header
                class="fixed top-0 right-0 left-0 lg:left-72 z-40
               bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                <div class="h-14 px-4 sm:px-6 lg:px-8 flex items-center justify-between">
                    {{-- Botón hamburguesa SOLO móvil (abre el sidebar) --}}
                    <button @click="open = true" class="lg:hidden p-2 rounded-md text-gray-700 dark:text-gray-200"
                        aria-label="Abrir menú">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    {{-- Buscador (solo desktop) --}}
                    <div class="hidden lg:block w-full max-w-xl">
                        <form action="#" method="GET" class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-gray-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z" />
                            </svg>
                            <input name="q"
                                class="w-full pl-10 pr-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-sm
                      text-gray-800 dark:text-gray-100 placeholder-gray-400
                      focus:outline-none focus:ring-2 focus:ring-green-600"
                                placeholder="Buscar muestras, solicitantes, informes…">
                        </form>
                    </div>

                    {{-- Acciones derecha: notificaciones + perfil --}}
                    <div class="flex items-center gap-2">
                        <button
                            class="p-2 rounded-full text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                            aria-label="Notificaciones">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1" />
                            </svg>
                        </button>

                        {{-- Menú de Perfil (usa componentes de Jetstream) --}}
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                    <button
                                        class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                        <img class="size-8 rounded-full object-cover"
                                            src="{{ Auth::user()->profile_photo_url }}"
                                            alt="{{ Auth::user()->name }}" />
                                    </button>
                                @else
                                    <span class="inline-flex rounded-md">
                                        <button type="button"
                                            class="inline-flex items-center px-3 py-2 text-sm rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                            {{ Auth::user()->name }}
                                            <svg class="ms-2 -me-0.5 size-4" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                            </svg>
                                        </button>
                                    </span>
                                @endif
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link
                                    href="{{ route('profile.show') }}">{{ __('Profile') }}</x-dropdown-link>
                                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                    <x-dropdown-link
                                        href="{{ route('api-tokens.index') }}">{{ __('API Tokens') }}</x-dropdown-link>
                                @endif
                                <div class="border-t border-gray-200 dark:border-gray-600"></div>
                                <form method="POST" action="{{ route('logout') }}" x-data>
                                    @csrf
                                    <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>
            </header>

            {{-- CONTENEDOR PRINCIPAL con offset por el topbar fijo --}}
            <div class="pt-14 flex">

                {{-- SIDEBAR fijo (ya lo tienes) --}}
                <x-sidebar x-bind:class="open ? 'translate-x-0' : '-translate-x-full'"
                    class="fixed z-50 inset-y-0 left-0 w-72
         lg:fixed lg:inset-y-0 lg:left-0 lg:translate-x-0" />

                {{-- Overlay móvil --}}
                <div x-show="open" x-transition.opacity @click="open = false" @keydown.escape.window="open = false"
                    class="fixed inset-0 z-40 bg-black/40 lg:hidden"></div>

                {{-- CONTENIDO (compensado a la derecha en desktop) --}}
                <div class="flex-1 min-h-screen lg:ml-72">
                    @if (isset($header))
                        <header class="bg-white dark:bg-gray-800 shadow">
                            <div class="py-6 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endif

                    <main class="py-6">
                        <div class="px-4 sm:px-6 lg:px-8">
                            {{ $slot }}
                        </div>
                    </main>
                </div>
            </div>
        </div>

    </div>
    @stack('modals')
    @livewireScripts
</body>

</html>
