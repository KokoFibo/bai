<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Company;
use Livewire\WithPagination;


class AddCompany extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $company_name, $id, $is_update = false;

    public function cancel()
    {
        $this->is_update = true;
        $this->reset();
    }
    public function add()
    {
        $this->validate([
            'company_name' => 'required'
        ]);
        $data = new Company();
        $data->company_name = $this->company_name;
        $data->save();
        $this->reset();
        // $this->dispatch('success', message: 'Company added');
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Company added',
        );
    }

    public function edit($id)
    {
        $this->is_update = true;
        $company = Company::find($id);
        $this->id = $company->id;
        $this->company_name = $company->company_name;
    }

    public function update()
    {
        $data = Company::find($this->id);
        $data->company_name = $this->company_name;
        $data->save();
        $this->is_update = false;
        $this->reset();
        // $this->dispatch('success', message: 'Company updated');
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Company updated',
        );
    }

    public function delete($id)
    {
        $data = Company::find($id);
        $data->delete();
        // $this->dispatch('success', message: 'Company deleted');
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Company deleted',
        );
    }

    public function render()
    {
        $datas = Company::paginate(10);
        return view('livewire.add-company', [
            'datas' => $datas
        ]);
    }
}
