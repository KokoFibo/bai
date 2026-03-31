<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Placement;
use Livewire\WithPagination;


class AddPlacement extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $placement_name, $id, $is_update = false;

    public function cancel()
    {
        $this->is_update = true;
        $this->reset();
    }
    public function add()
    {
        $this->validate([
            'placement_name' => 'required'
        ]);
        $data = new Placement;
        $data->placement_name = $this->placement_name;
        $data->save();
        $this->reset();
        // $this->dispatch('success', message: 'Placement added');
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Placement added',
        );
    }

    public function edit($id)
    {
        $this->is_update = true;
        $placement = Placement::find($id);
        $this->id = $placement->id;
        $this->placement_name = $placement->placement_name;
    }

    public function update()
    {
        $data = Placement::find($this->id);
        $data->placement_name = $this->placement_name;
        $data->save();
        $this->is_update = false;
        $this->reset();
        // $this->dispatch('success', message: 'Placement updated');
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Placement updated',
        );
    }

    public function delete($id)
    {
        $data = Placement::find($id);
        $data->delete();
        // $this->dispatch('success', message: 'Placement deleted');
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Placement deleted',
        );
    }

    public function render()
    {
        $datas = Placement::paginate(10);
        return view('livewire.add-placement', [
            'datas' => $datas
        ]);
    }
}
