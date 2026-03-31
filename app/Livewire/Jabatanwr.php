<?php

namespace App\Livewire;

use App\Models\Jabatan;
use Livewire\Component;
use Livewire\WithPagination;

class Jabatanwr extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $nama_jabatan, $id, $is_update = false;

    public function cancel()
    {
        $this->is_update = true;
        $this->reset();
    }
    public function add()
    {
        $this->validate([
            'nama_jabatan' => 'required'
        ]);
        $data = new Jabatan;
        $data->nama_jabatan = $this->nama_jabatan;
        $data->save();
        $this->reset();
        // $this->dispatch('success', message: 'Jabatan added');
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Jabatan added',
        );
    }

    public function edit($id)
    {
        $this->is_update = true;
        $jabatan = Jabatan::find($id);
        $this->id = $jabatan->id;
        $this->nama_jabatan = $jabatan->nama_jabatan;
    }

    public function update()
    {
        $data = Jabatan::find($this->id);
        $data->nama_jabatan = $this->nama_jabatan;
        $data->save();
        $this->is_update = false;
        $this->reset();
        // $this->dispatch('success', message: 'Jabatan updated');
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Jabatan updated',
        );
    }

    public function delete($id)
    {
        $data = Jabatan::find($id);
        $data->delete();
        // $this->dispatch('success', message: 'Jabatan deleted');
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Jabatan deleted',
        );
    }

    public function render()
    {
        $datas = Jabatan::paginate(10);
        return view('livewire.jabatanwr', [
            'datas' => $datas
        ]);
    }
}
