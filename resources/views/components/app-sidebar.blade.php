{{-- resources/views/components/app-sidebar.blade.php --}}
@props(['class' => ''])

@php
    // Define tu menú principal aquí; puedes moverlo a una clase/helper si prefieres.
    $items = [
        ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'M3 12h18M3 6h18M3 18h18', 'can' => null],
    ];

    $isActive = fn($name) => request()->routeIs($name);
@endphp

<aside {{ $attributes->merge(['class' => $class]) }}>
    <div class="h-full flex flex-col">
        {{-- Branding / Logo pequeño en el sidebar --}}
        <div class="h-16 flex items-center px-4 border-b border-gray-200 dark:border-gray-700">
            <span class="text-base font-semibold text-gray-800 dark:text-gray-100">Menú</span>
        </div>

        {{-- Navegación --}}
        <nav class="flex-1 overflow-y-auto p-3 space-y-1">
            @foreach ($items as $item)
                @if (is_null($item['can']) || Gate::check($item['can']))
                    <a href="{{ route($item['route']) }}"
                       class="group flex items-center gap-3 px-3 py-2 rounded-lg
                              {{ $isActive($item['route']) ? 'bg-green-600 text-white' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                        {{-- Icono minimal con path dinámico --}}
                        <svg class="h-5 w-5 {{ $isActive($item['route']) ? 'text-white' : 'text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300' }}"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                        </svg>
                        <span class="text-sm font-medium">{{ $item['label'] }}</span>
                    </a>
                @endif
            @endforeach
        </nav>

        {{-- Footer opcional del sidebar --}}
        <div class="p-3 border-t border-gray-200 dark:border-gray-700 text-xs text-gray-500 dark:text-gray-400">
            © {{ date('Y') }} {{ config('app.name') }}
        </div>
    </div>
</aside>
