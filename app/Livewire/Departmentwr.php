<?php

namespace App\Livewire;

use App\Models\Department;
use Livewire\Component;
use Livewire\WithPagination;

class Departmentwr extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $nama_department, $id, $is_update = false;

    public function cancel()
    {
        $this->is_update = true;
        $this->reset();
    }
    public function add()
    {
        $this->validate([
            'nama_department' => 'required'
        ]);
        $data = new Department;
        $data->nama_department = $this->nama_department;
        $data->save();
        $this->reset();
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Department added',
        );
    }

    public function edit($id)
    {
        $this->is_update = true;
        $department = Department::find($id);
        $this->id = $department->id;
        $this->nama_department = $department->nama_department;
    }

    public function update()
    {
        $data = Department::find($this->id);
        $data->nama_department = $this->nama_department;
        $data->save();
        $this->is_update = false;
        $this->reset();
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Department updated',
        );
    }

    public function delete($id)
    {
        $data = Department::find($id);
        $data->delete();
        // $this->dispatch('success', message: 'department deleted');
        $this->dispatch(
            'message',
            type: 'success',
            title: 'department deleted',
        );
    }
    public function render()
    {
        $datas = department::paginate(10);
        return view('livewire.departmentwr', [
            'datas' => $datas
        ]);
    }
}
