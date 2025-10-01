{{-- resources/views/livewire/muestras/index.blade.php --}}
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __('Muestras') }}
    </h2>
</x-slot>
<div class="space-y-4">

    {{-- Filtros --}}
    <div class="flex items-center gap-3">
        <input wire:model.live.debounce.300ms="q" placeholder="Buscar por código, cliente…"
            class="w-full max-w-md px-3 py-2 rounded-lg bg-gray-100 dark:bg-gray-700" />
        <select wire:model.live="f_tipo" class="rounded-lg">
            <option value="">Todos los tipos</option>
            <option value="suelo">Suelo</option>
            <option value="semilla">Semilla</option>
            <option value="vegetal">Vegetal</option>
            <option value="insecto">Insecto</option>
            <option value="acaro">Ácaro</option>
        </select>

        <select wire:model.live="f_estado" class="rounded-lg">
            <option value="">Todos los estados</option>
            <option value="recibida">Recibida</option>
            <option value="en_proceso">En proceso</option>
            <option value="finalizada">Finalizada</option>
            <option value="reportada">Reportada</option>
            <option value="rechazada">Rechazada</option>
          </select>
        <x-button class="ml-auto bg-green-600 hover:bg-green-700" wire:click="create">Nueva muestra</x-button>
    </div>
    

    {{-- Tabla --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-200">
                <tr>
                    <th class="px-4 py-2 text-left">Código</th>
                    <th class="px-4 py-2 text-left">Cliente</th>
                    <th class="px-4 py-2 text-left">Tipo</th>
                    <th class="px-4 py-2 text-left">Fecha recepción</th>
                    <th class="px-4 py-2 text-left">Detalle</th>
                    <th class="px-4 py-2 text-left">Estado</th>
                    <th class="px-4 py-2 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse ($items as $m)
                    <tr>
                        <td class="px-4 py-2 font-medium">{{ $m->codigo }}</td>
                        <td class="px-4 py-2">{{ $m->cliente?->nombre }} {{ $m->cliente?->apellido }}</td>
                        <td class="px-4 py-2 capitalize">{{ $m->tipo }}</td>
                        <td class="px-4 py-2">
                            {{ \Illuminate\Support\Carbon::parse($m->fecha_recepcion)->format('d/m/Y') }}</td>
                        <td class="px-4 py-2">
                            @switch($m->tipo)
                                @case('semilla')
                                    {{ $m->semilla?->nombre_comun }} ({{ $m->tamano_semilla }})
                                @break

                                @case('vegetal')
                                    {{ $m->vegetal?->nombre_comun }} ({{ $m->parte_vegetal }})
                                @break

                                @case('insecto')
                                    {{ $m->insecto?->nombre }}
                                @break

                                @case('acaro')
                                    {{ $m->acaro?->nombre }}
                                @break

                                @default
                                    —
                            @endswitch
                        </td>
                        <td class="px-4 py-2">
                            @php
                              $badge = match($m->estado) {
                                'recibida'   => 'bg-gray-100 text-gray-800',
                                'en_proceso' => 'bg-blue-100 text-blue-800',
                                'finalizada' => 'bg-green-100 text-green-800',
                                'reportada'  => 'bg-emerald-100 text-emerald-800',
                                'rechazada'  => 'bg-red-100 text-red-800',
                                default => 'bg-gray-100 text-gray-800',
                              };
                            @endphp
                            <span class="px-2 py-1 text-xs font-medium rounded {{ $badge }}">
                              {{ Str::headline($m->estado) }}
                            </span>
                        </td>
                        <td class="px-4 py-2 text-right">
                            {{-- menú kebab reutilizado --}}
                            <div x-data="{ open: false }" class="relative inline-block text-left">
                                <button @click="open=!open"
                                    class="w-9 h-9 rounded hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 12h.01M12 12h.01M19 12h.01" />
                                    </svg>
                                </button>
                                <div x-show="open" x-transition @click.outside="open=false"
                                    class="absolute right-0 mt-2 w-36 rounded-md bg-white dark:bg-gray-800 shadow ring-1 ring-black/5 z-20">
                                    <button
                                        class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700"
                                        wire:click="edit({{ $m->id }})" @click="open=false">Editar</button>
                                    <button
                                        class="w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20"
                                        x-on:click.prevent="if(confirm('¿Eliminar?')) { $wire.delete({{ $m->id }}); open=false }">Eliminar
                                    </button>
                                
                                    <div class="py-1">
                                        <div class="px-3 py-1 text-xs text-gray-400">Estado</div>
                                        <button class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700"
                                                wire:click="setEstado({{ $m->id }}, 'en_proceso')" @click="open=false">
                                          Marcar como “En proceso”
                                        </button>
                                        <button class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700"
                                                wire:click="setEstado({{ $m->id }}, 'finalizada')" @click="open=false">
                                          Marcar como “Finalizada”
                                        </button>
                                        <button class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700"
                                                wire:click="setEstado({{ $m->id }}, 'reportada')" @click="open=false">
                                          Marcar como “Reportada”
                                        </button>
                                        <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>
                                        <button class="w-full text-left px-3 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20"
                                                wire:click="setEstado({{ $m->id }}, 'rechazada')" @click="open=false">
                                          Marcar como “Rechazada”
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
        <x-dialog-modal wire:model="open" maxWidth="2xl">
            <x-slot name="title">{{ $editId ? 'Editar muestra' : 'Nueva muestra' }}</x-slot>

            <x-slot name="content">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    {{-- Cliente --}}
                    <div class="md:col-span-2">
                        <x-label value="Cliente" />
                        <div class="flex gap-2">
                            <select wire:model="form.cliente_id" class="w-full rounded-lg">
                                <option value="">Seleccione…</option>
                                @foreach ($clientes as $c)
                                    <option value="{{ $c->id }}">{{ $c->nombre }} {{ $c->apellido }}</option>
                                @endforeach
                            </select>

                            <x-button class="bg-green-600 hover:bg-green-700 whitespace-nowrap" wire:click="createClient">
                                Nuevo
                            </x-button>
                        </div>
                        <x-input-error for="form.cliente_id" class="mt-1" />
                    </div>

                    {{-- Tipo --}}
                    <div>
                        <x-label value="Tipo de muestra" />
                        <select wire:model.live="form.tipo" class="w-full rounded-lg">
                            <option value="">Seleccione…</option>
                            <option value="suelo">Suelo</option>
                            <option value="semilla">Semilla</option>
                            <option value="vegetal">Vegetal</option>
                            <option value="insecto">Insecto</option>
                            <option value="acaro">Ácaro</option>
                        </select>
                        <x-input-error for="form.tipo" class="mt-1" />
                    </div>

                    {{-- Fechas --}}
                    <div>
                        <x-label value="Fecha muestreo" />
                        <x-input type="date" class="w-full" wire:model="form.fecha_muestreo" />
                        <x-input-error for="form.fecha_muestreo" class="mt-1" />
                    </div>
                    <div>
                        <x-label value="Fecha recepción *" />
                        <x-input type="date" class="w-full" wire:model="form.fecha_recepcion" />
                        <x-input-error for="form.fecha_recepcion" class="mt-1" />
                    </div>

                    {{-- Cantidad / Unidad --}}
                    <div>
                        <x-label value="Cantidad" />
                        <x-input class="w-full" wire:model="form.cantidad" />
                        <x-input-error for="form.cantidad" class="mt-1" />
                    </div>
                    <div>
                        <x-label value="Unidad" />
                        <select wire:model="form.unidad_id" class="w-full rounded-lg">
                            <option value="">Seleccione…</option>
                            @foreach ($unidads as $u)
                                <option value="{{ $u->id }}">{{ $u->nombre }} ({{ $u->codigo }})</option>
                            @endforeach
                        </select>
                        <x-input-error for="form.unidad_id" class="mt-1" />
                    </div>

                    <div>
                        <x-label value="Estado" />
                        <select wire:model="form.estado" class="w-full rounded-lg">
                            @foreach (\App\Enums\EstadoMuestra::cases() as $estado)
                                <option value="{{ $estado->value }}">{{ $estado->label() }}</option>
                            @endforeach
                        </select>
                        <x-input-error for="form.estado" class="mt-1" />
                    </div>

                    {{-- Subtipo: SEMILLA --}}
                    @if ($form['tipo'] === 'semilla')
                        <div>
                            <x-label value="Tamaño de semilla" />
                            <select wire:model="form.tamano_semilla" class="w-full rounded-lg">
                                <option value="">Seleccione…</option>
                                <option value="Grande">Grande</option>
                                <option value="Peque">Peque</option>
                            </select>
                            <x-input-error for="form.tamano_semilla" class="mt-1" />
                        </div>
                        <div class="md:col-span-2">
                            <x-label value="Semilla" />
                            <select wire:model="form.semilla_id" class="w-full rounded-lg">
                                <option value="">Seleccione…</option>
                                @foreach ($semillas as $s)
                                    <option value="{{ $s->id }}">{{ $s->nombre_comun }} —
                                        <i>{{ $s->nombre_cientifico }}</i>
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error for="form.semilla_id" class="mt-1" />
                        </div>
                    @endif

                    {{-- Subtipo: VEGETAL --}}
                    @if ($form['tipo'] === 'vegetal')
                        <div>
                            <x-label value="Parte vegetal" />
                            <select wire:model="form.parte_vegetal" class="w-full rounded-lg">
                                <option value="">Seleccione…</option>
                                <option value="tallo">Tallo</option>
                                <option value="raiz">Raíz</option>
                                <option value="hoja">Hoja</option>
                                <option value="fruto">Fruto</option>
                            </select>
                            <x-input-error for="form.parte_vegetal" class="mt-1" />
                        </div>
                        <div class="md:col-span-2">
                            <x-label value="Vegetal" />
                            <select wire:model="form.vegetal_id" class="w-full rounded-lg">
                                <option value="">Seleccione…</option>
                                @foreach ($vegetales as $v)
                                    <option value="{{ $v->id }}">{{ $v->nombre_comun }} —
                                        <i>{{ $v->nombre_cientifico }}</i>
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error for="form.vegetal_id" class="mt-1" />
                        </div>
                    @endif

                    {{-- Subtipo: INSECTO --}}
                    @if ($form['tipo'] === 'insecto')
                        <div class="md:col-span-3">
                            <x-label value="Insecto" />
                            <select wire:model="form.insecto_id" class="w-full rounded-lg">
                                <option value="">Seleccione…</option>
                                @foreach ($insectos as $i)
                                    <option value="{{ $i->id }}">{{ $i->nombre }} — {{ $i->especie }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error for="form.insecto_id" class="mt-1" />
                        </div>
                    @endif

                    {{-- Subtipo: ACARO --}}
                    @if ($form['tipo'] === 'acaro')
                        <div class="md:col-span-3">
                            <x-label value="Ácaro" />
                            <select wire:model="form.acaro_id" class="w-full rounded-lg">
                                <option value="">Seleccione…</option>
                                @foreach ($acaros as $a)
                                    <option value="{{ $a->id }}">{{ $a->nombre }} — {{ $a->especie }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error for="form.acaro_id" class="mt-1" />
                        </div>
                    @endif

                    {{-- Observaciones --}}
                    <div class="md:col-span-3">
                        <x-label value="Observaciones" />
                        <textarea wire:model="form.observaciones" rows="3" class="w-full rounded-lg border-gray-300 dark:bg-gray-700"></textarea>
                        <x-input-error for="form.observaciones" class="mt-1" />
                    </div>
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$set('open', false)">Cancelar</x-secondary-button>
                <x-button class="ml-2 bg-green-600 hover:bg-green-700" wire:click="save">Guardar</x-button>
            </x-slot>
        </x-dialog-modal>

        {{-- Sub-modal: Crear Cliente rápido --}}
        <x-dialog-modal wire:model="openClient" maxWidth="md">
            <x-slot name="title">Nuevo cliente</x-slot>

            <x-slot name="content">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-label value="Nombre *" />
                        <x-input class="w-full" wire:model.defer="clientForm.nombre" />
                        <x-input-error for="clientForm.nombre" class="mt-1" />
                    </div>
                    <div>
                        <x-label value="Apellido" />
                        <x-input class="w-full" wire:model.defer="clientForm.apellido" />
                        <x-input-error for="clientForm.apellido" class="mt-1" />
                    </div>
                    <div class="md:col-span-2">
                        <x-label value="Dirección" />
                        <x-input class="w-full" wire:model.defer="clientForm.direccion" />
                        <x-input-error for="clientForm.direccion" class="mt-1" />
                    </div>
                    <div>
                        <x-label value="Cel" />
                        <x-input class="w-full" wire:model.defer="clientForm.cel" />
                        <x-input-error for="clientForm.cel" class="mt-1" />
                    </div>
                    <div>
                        <x-label value="Email" />
                        <x-input class="w-full" wire:model.defer="clientForm.email" />
                        <x-input-error for="clientForm.email" class="mt-1" />
                    </div>
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$set('openClient', false)">Cancelar</x-secondary-button>
                <x-button class="ml-2 bg-green-600 hover:bg-green-700" wire:click="saveClient">Guardar</x-button>
            </x-slot>
        </x-dialog-modal>

    </div>
