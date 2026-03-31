<?php

namespace App\Livewire;

use App\Models\Jobgrade;
use Livewire\Component;
use Livewire\WithPagination;

class Jobgradewr extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $grade, $grade_name, $id;
    public $is_update = false;

    public function cancel()
    {
        $this->is_update = false;
        $this->reset(['grade', 'grade_name', 'id']);
    }

    public function add()
    {
        $this->validate([
            'grade' => 'required',
            'grade_name' => 'required',
        ]);

        Jobgrade::create([
            'grade' => $this->grade,
            'grade_name' => $this->grade_name,
        ]);

        $this->reset(['grade', 'grade_name']);
        $this->dispatch('message', type: 'success', title: 'Grade added');
    }

    public function edit($id)
    {
        $this->is_update = true;

        $jobgrade = Jobgrade::findOrFail($id);

        $this->id = $jobgrade->id;
        $this->grade = $jobgrade->grade;
        $this->grade_name = $jobgrade->grade_name;
    }

    public function update()
    {
        $this->validate([
            'grade' => 'required',
            'grade_name' => 'required',
        ]);

        $jobgrade = Jobgrade::findOrFail($this->id);

        $jobgrade->update([
            'grade' => $this->grade,
            'grade_name' => $this->grade_name,
        ]);

        $this->is_update = false;
        $this->reset(['grade', 'grade_name', 'id']);

        $this->dispatch('message', type: 'success', title: 'Jobgrade updated');
    }

    public function delete($id)
    {
        Jobgrade::findOrFail($id)->delete();

        $this->dispatch('message', type: 'success', title: 'Jobgrade deleted');
    }

    public function render()
    {
        return view('livewire.jobgradewr', [
            'datas' => Jobgrade::orderBy('grade')->paginate(10),
        ]);
    }
}
