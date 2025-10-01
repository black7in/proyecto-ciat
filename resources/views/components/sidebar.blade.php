@php $isActive = fn($name) => request()->routeIs($name); @endphp

<aside
    {{ $attributes->merge([
        'class' => 'h-screen w-72 overflow-y-auto
                  border-r border-gray-200 dark:border-gray-700
                  bg-white dark:bg-gray-800
                  transform transition-transform duration-200 ease-out',
    ]) }}>
    <div class="h-14 flex items-center justify-center border-b border-gray-200 dark:border-gray-700">
        <a href="{{ route('dashboard') }}">
            <x-application-mark class="block h-14 w-auto" />
        </a>
    </div>

    <nav class="p-4 space-y-1">
        <a href="{{ route('dashboard') }}"
            class="group flex items-center gap-3 px-3 py-2 rounded-lg
              {{ $isActive('dashboard')
                  ? 'bg-green-600 text-white'
                  : 'text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
            <svg class="h-5 w-5 {{ $isActive('dashboard') ? 'text-white' : 'text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300' }}"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6h18M3 12h18M3 18h18" />
            </svg>
            <span class="text-sm font-medium">Dashboard</span>
        </a>

        <a href="{{ route('muestras.index') }}"
            class="group flex items-center gap-3 px-3 py-2 rounded-lg
              {{ $isActive('muestras.*')
                  ? 'bg-green-600 text-white'
                  : 'text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
            <svg class="h-5 w-5 {{ $isActive('muestras.*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300' }}"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 7l9-4 9 4-9 4-9-4zM3 17l9 4 9-4M3 12l9 4 9-4" />
            </svg>
            <span class="text-sm font-medium">Muestras</span>
        </a>

        <a href="{{ route('clientes.index') }}"
            class="group flex items-center gap-3 px-3 py-2 rounded-lg
           {{ request()->routeIs('clientes.*') ? 'bg-green-600 text-white' : 'text-gray-700 hover:bg-gray-50' }}">
            <svg class="h-5 w-5 {{ request()->routeIs('clientes.*') ? 'text-white' : 'text-gray-400 group-hover:text-gray-600' }}"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span class="text-sm font-medium">Clientes</span>
        </a>
    </nav>
</aside>
