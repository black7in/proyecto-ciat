<?php

namespace App\Livewire\Clientes;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Cliente;

class Index extends Component
{
    use WithPagination;

    public string $q = '';
    public bool $open = false;
    public ?string $editId = null;

    public array $form = [
        'nombre' => '',
        'apellido' => '',
        'direccion' => '',
        'cel' => '',
        'email' => '',
    ];

    protected function rules(): array
    {
        return [
            'form.nombre' => 'required|string|max:120',
            'form.apellido' => 'nullable|string|max:120',
            'form.direccion' => 'nullable|string|max:255',
            'form.cel' => 'nullable|string|max:30',
            'form.email' => 'nullable|email|max:120',
        ];
    }
    public function updatingQ() { $this->resetPage(); }
    public function updatedQ() { $this->resetPage(); }
    public function create()
    {
        $this->reset(['form','editId']);
        $this->open = true;
    }

    public function edit(int $id)
    {
        $c = Cliente::findOrFail($id);
        $this->form = $c->only(array_keys($this->form));
        $this->editId = $c->id;
        $this->open = true;
    }

    public function save()
    {
        $data = $this->validate()['form'];

        if ($this->editId) {
            Cliente::findOrFail($this->editId)->update($data);
            $msg = 'Cliente actualizado';
        } else {
            Cliente::create($data);
            $msg = 'Cliente creado';
        }

        $this->open = false;
        $this->dispatch('notify', message: $msg); // opcional (toast)
    }

    public function delete(int $id)
    {
        Cliente::findOrFail($id)->delete();
        $this->dispatch('notify', message: 'Cliente eliminado');
        $this->resetPage();
    }

    public function render()
    {
        $term = trim($this->q);
    
        $items = Cliente::query()
            ->when($term !== '', function ($query) use ($term) {
                $like = '%'.$term.'%';
                $query->where(function ($q) use ($like) {
                    $q->where('nombre', 'like', $like)
                      ->orWhere('apellido', 'like', $like)
                      ->orWhere('email', 'like', $like)
                      ->orWhere('cel', 'like', $like)
                      ->orWhere('direccion', 'like', $like);
                });
            })
            ->orderByDesc('created_at')
            ->paginate(10);
    
        return view('livewire.clientes.index', compact('items'));
    }
}
