{{-- resources/views/livewire/clientes/index.blade.php --}}
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Clientes') }}
    </h2>
</x-slot>
<div class="space-y-4">
    {{-- Encabezado --}}
    <div class="flex items-center justify-between">
        <input wire:model.live.debounce.300ms="q" type="text" placeholder="Buscar por nombre, apellido, email o cel..."
            class="w-full max-w-md px-3 py-2 rounded-lg bg-gray-100 dark:bg-gray-700" />
        <x-button class="ml-3" wire:click="create">Nuevo</x-button>
        
    </div>

    {{-- Tabla --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-200">
                <tr>
                    <th class="px-4 py-2 text-left">Nombre</th>
                    <th class="px-4 py-2 text-left">Apellido</th>
                    <th class="px-4 py-2 text-left">Dirección</th>
                    <th class="px-4 py-2 text-left">Cel</th>
                    <th class="px-4 py-2 text-left">Email</th>
                    <th class="px-4 py-2">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse ($items as $c)
                    <tr>
                        <td class="px-4 py-2">{{ $c->nombre }}</td>
                        <td class="px-4 py-2">{{ $c->apellido }}</td>
                        <td class="px-4 py-2">{{ $c->direccion }}</td>
                        <td class="px-4 py-2">{{ $c->cel }}</td>
                        <td class="px-4 py-2">{{ $c->email }}</td>
                        <td class="px-4 py-2 text-right">
                            <div x-data="{ open: false }" class="relative inline-block text-left">
                                <!-- Botón menú -->
                                <button @click="open = !open" @keydown.escape.window="open = false"
                                    class="inline-flex items-center justify-center w-9 h-9 rounded-md
                                             hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 12h.01M12 12h.01M19 12h.01" />
                                    </svg>
                                </button>

                                <!-- Dropdown -->
                                <div x-show="open" x-transition @click.outside="open = false"
                                    class="absolute right-0 mt-2 w-36 rounded-md bg-white dark:bg-gray-800
                                          shadow ring-1 ring-black/5 z-20">
                                    <div class="py-1" role="menu" aria-orientation="vertical" tabindex="-1">
                                        <button wire:click="edit({{ $c->id }})" @click="open=false"
                                            class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700"
                                            role="menuitem">
                                            Editar
                                        </button>
                                        <button
                                            x-on:click.prevent="if(confirm('¿Eliminar este cliente?')) { $wire.delete({{ $c->id }}); open=false }"
                                            class="w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50
                                                 dark:hover:bg-red-900/20"
                                            role="menuitem">
                                            Eliminar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">Sin resultados</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-3">{{ $items->links() }}</div>
    </div>

    {{-- Modal crear/editar --}}
    <x-dialog-modal wire:model="open">
        <x-slot name="title">{{ $editId ? 'Editar cliente' : 'Nuevo cliente' }}</x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-label value="Nombre *" />
                    <x-input class="w-full" wire:model.defer="form.nombre" />
                    <x-input-error for="form.nombre" class="mt-1" />
                </div>
                <div>
                    <x-label value="Apellido" />
                    <x-input class="w-full" wire:model.defer="form.apellido" />
                    <x-input-error for="form.apellido" class="mt-1" />
                </div>
                <div>
                    <x-label value="Dirección" />
                    <x-input class="w-full" wire:model.defer="form.direccion" />
                    <x-input-error for="form.direccion" class="mt-1" />
                </div>
                <div>
                    <x-label value="Cel" />
                    <x-input class="w-full" wire:model.defer="form.cel" />
                    <x-input-error for="form.cel" class="mt-1" />
                </div>
                <div class="md:col-span-2">
                    <x-label value="Email" />
                    <x-input class="w-full" wire:model.defer="form.email" />
                    <x-input-error for="form.email" class="mt-1" />
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('open', false)">Cancelar</x-secondary-button>
            <x-button class="ml-2" wire:click="save">Guardar</x-button>
        </x-slot>
    </x-dialog-modal>
</div>
