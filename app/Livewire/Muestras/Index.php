<?php

namespace App\Livewire\Muestras;

use App\Models\Muestra;
use App\Models\Cliente;
use App\Models\Unidad;
use App\Models\Semilla;
use App\Models\Vegetal;
use App\Models\Insecto;
use App\Models\Acaro;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

class Index extends Component
{
    use WithPagination;

    // Filtros / búsqueda
    public string $q = '';
    public string $f_tipo = '';

    public string $f_estado = '';

    // Modal
    public bool $open = false;
    public ?int $editId = null;

    // Form
    public array $form = [
        'cliente_id' => null,
        'fecha_muestreo' => null,
        'fecha_recepcion' => null,
        'tipo' => '',
        'estado' => 'recibida',
        'cantidad' => '',
        'unidad_id' => null,
        'observaciones' => '',

        // Semilla
        'tamano_semilla' => null,
        'semilla_id' => null,

        // Vegetal
        'parte_vegetal' => null,
        'vegetal_id' => null,

        // Insecto
        'insecto_id' => null,

        // Acaro
        'acaro_id' => null,
    ];


    // props para el sub-modal
    public bool $openClient = false;

    public array $clientForm = [
        'nombre' => '',
        'apellido' => '',
        'direccion' => '',
        'cel' => '',
        'email' => '',
    ];

    public function updatedQ()
    {
        $this->resetPage();
    }
    public function updatedFTipo()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->reset(['form', 'editId']);
        $this->form['tipo'] = ''; // obliga a elegir
        $this->open = true;
    }

    public function edit(int $id)
    {
        $m = Muestra::findOrFail($id);
        $this->form = array_merge($this->form, $m->only(array_keys($this->form)));
        $this->editId = $m->id;
        $this->open = true;
    }

    public function save()
    {
        $data = $this->validate($this->rules())['form'];

        // Limpieza según tipo (evita basura de otros subtipos)
        $tipo = $data['tipo'];
        if ($tipo !== 'semilla') {
            $data['tamano_semilla'] = $data['semilla_id'] = null;
        }
        if ($tipo !== 'vegetal') {
            $data['parte_vegetal'] = $data['vegetal_id'] = null;
        }
        if ($tipo !== 'insecto') {
            $data['insecto_id'] = null;
        }
        if ($tipo !== 'acaro') {
            $data['acaro_id']   = null;
        }

        if ($this->editId) {
            Muestra::findOrFail($this->editId)->update($data);
            $msg = 'Muestra actualizada';
        } else {
            Muestra::create($data);
            $msg = 'Muestra creada';
        }

        $this->open = false;
        $this->dispatch('notify', message: $msg);
    }

    public function delete(int $id)
    {
        Muestra::findOrFail($id)->delete();
        $this->dispatch('notify', message: 'Muestra eliminada');
        $this->resetPage();
    }

    protected function rules(): array
    {
        $tipos = ['suelo', 'semilla', 'vegetal', 'insecto', 'acaro'];
        $estados = ['recibida', 'en_proceso', 'finalizada', 'reportada', 'rechazada'];
        $tamSem = ['Grande', 'Peque'];
        $partes = ['tallo', 'raiz', 'hoja', 'fruto'];

        return [
            'form.cliente_id'      => ['required', 'exists:clientes,id'],
            'form.fecha_muestreo'  => ['nullable', 'date'],
            'form.fecha_recepcion' => ['required', 'date'],
            'form.tipo'            => ['required', Rule::in($tipos)],
            'form.estado'          => ['required', Rule::in($estados)],
            'form.cantidad'        => ['nullable', 'string', 'max:50'],
            'form.unidad_id'       => ['nullable', 'exists:unidads,id'],
            'form.observaciones'   => ['nullable', 'string'],

            // Condicionales por subtipo
            'form.tamano_semilla'  => [Rule::requiredIf($this->form['tipo'] === 'semilla'), 'nullable', Rule::in($tamSem)],
            'form.semilla_id'      => [Rule::requiredIf($this->form['tipo'] === 'semilla'), 'nullable', 'exists:semillas,id'],

            'form.parte_vegetal'   => [Rule::requiredIf($this->form['tipo'] === 'vegetal'), 'nullable', Rule::in($partes)],
            'form.vegetal_id'      => [Rule::requiredIf($this->form['tipo'] === 'vegetal'), 'nullable', 'exists:vegetales,id'],

            'form.insecto_id'      => [Rule::requiredIf($this->form['tipo'] === 'insecto'), 'nullable', 'exists:insectos,id'],
            'form.acaro_id'        => [Rule::requiredIf($this->form['tipo'] === 'acaro'),   'nullable', 'exists:acaros,id'],
        ];
    }

    // cambio rápido desde el menú de acciones
    public function setEstado(int $id, string $estado)
    {
        $validos = ['recibida', 'en_proceso', 'finalizada', 'reportada', 'rechazada'];
        abort_unless(in_array($estado, $validos, true), 400, 'Estado inválido');

        $m = Muestra::findOrFail($id);
        $m->update(['estado' => $estado]);

        $this->dispatch('notify', message: 'Estado actualizado');
    }

    public function render()
    {
        $term = trim($this->q);

        $items = Muestra::query()
            ->with(['cliente', 'unidad', 'semilla', 'vegetal', 'insecto', 'acaro'])
            ->when($this->f_tipo !== '', fn($q) => $q->where('tipo', $this->f_tipo))
            ->when($this->f_estado !== '', fn($q) => $q->where('estado',$this->f_estado))
            ->when($term !== '', function ($q) use ($term) {
                $like = '%' . $term . '%';
                $q->where(function ($sub) use ($like) {
                    $sub->where('codigo', 'like', $like)
                        ->orWhere('cantidad', 'like', $like)
                        ->orWhere('observaciones', 'like', $like);
                })->orWhereHas('cliente', fn($c) => $c->where('nombre', 'like', $like)->orWhere('apellido', 'like', $like));
            })
            ->latest()->paginate(10);

        return view('livewire.muestras.index', [
            'items'     => $items,
            'clientes'  => Cliente::orderBy('nombre')->get(),
            'unidads'  => Unidad::orderBy('nombre')->get(),
            'semillas'  => Semilla::orderBy('nombre_comun')->get(),
            'vegetales' => Vegetal::orderBy('nombre_comun')->get(),
            'insectos'  => Insecto::orderBy('nombre')->get(),
            'acaros'    => Acaro::orderBy('nombre')->get(),
        ]);
    }

    public function createClient()
    {
        $this->reset('clientForm');
        $this->openClient = true;
    }

    protected function clientRules(): array
    {
        return [
            'clientForm.nombre'   => 'required|string|max:120',
            'clientForm.apellido' => 'nullable|string|max:120',
            'clientForm.direccion' => 'nullable|string|max:255',
            'clientForm.cel'      => 'nullable|string|max:30',
            'clientForm.email'    => 'nullable|email|max:120',
        ];
    }

    public function saveClient()
    {
        $data = $this->validate($this->clientRules())['clientForm'];

        $cliente = Cliente::create($data);

        // seleccionar automáticamente el nuevo cliente en la muestra
        $this->form['cliente_id'] = $cliente->id;

        // cerrar sub-modal y notificar
        $this->openClient = false;
        $this->dispatch('notify', message: 'Cliente creado');

        // opcional: refrescar el combo de clientes sin esperar al próximo render
        // (si usas $clientes del render, no hace falta)
    }
}
