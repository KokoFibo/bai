<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\Karyawan;
use App\Models\Placement;
use App\Models\Department;
use Livewire\Attributes\On;
use App\Models\Timeoffrequester;


class Timeoutrequsterwr extends Component
{
    public $department_id, $approveBy1, $approveBy2;
    public $namaDepartment, $namaApproveBy1, $namaApproveBy2;
    public $is_update = false;
    public $is_update_id, $delete_id;
    public $old_department_id, $old_Approve1, $old_Approve2;

    public function exit()
    {
        $this->redirect(PermohonanPersonnel::class);
    }

    public function edit($id)
    {
        $data = Timeoffrequester::find($id);
        $this->old_department_id = $data->department_id;
        $this->old_Approve1 = $data->approve_by_1;
        $this->old_Approve2 = $data->approve_by_2;
        $this->department_id = $data->department_id;
        $this->approveBy1 = $data->approve_by_1;
        $this->approveBy2 = $data->approve_by_2;
        $this->is_update_id = $data->id;
        $this->namaDepartment = nama_department($this->department_id);
        $this->namaApproveBy1 = getName($this->approveBy1);
        $this->namaApproveBy2 = getName($this->approveBy2);
        $this->is_update = true;
    }

    public function update()
    {
        $this->validate([
            'department_id' => 'required|numeric',
            'approveBy1' => 'required|numeric',
            'approveBy2' => 'required|numeric'
        ]);

        if ($this->namaDepartment != '' && $this->namaApproveBy1 != '' && $this->namaApproveBy2 != '') {
            if (!isResigned($this->approveBy1) && !isResigned($this->approveBy2)) {
                $data = Timeoffrequester::find($this->is_update_id);
                $data->department_id = $this->department_id;
                $data->approve_by_1 = $this->approveBy1;
                $data->approve_by_2 = $this->approveBy2;
                $data->save();

                if ($this->department_id != $this->old_department_id) changeToAdmin($this->old_department_id);
                if ($this->approveBy1 != $this->old_Approve1) changeToAdmin($this->old_Approve1);
                if ($this->approveBy2 != $this->old_Approve2) changeToAdmin($this->old_Approve2);
                changeToRequest($this->department_id);
                changeToRequest($this->approveBy1);
                changeToRequest($this->approveBy2);

                $this->dispatch(
                    'message',
                    type: 'success',
                    title: 'Requester Updated',
                );
                $this->is_update = false;
                $this->reset();
            } else {
                $this->dispatch(
                    'message',
                    type: 'error',
                    title: '1st approve or 2nd approve is resigned or blacklisted',
                );
            }
        } else {
            $this->dispatch(
                'message',
                type: 'error',
                title: 'Fail to update, user ID unavailable',
            );
        }
    }

    public function deleteConfirmation($id)
    {

        $data = Timeoffrequester::find($id);
        $this->old_department_id = $data->department_id;
        $this->old_Approve1 = $data->approve_by_1;
        $this->old_Approve2 = $data->approve_by_2;


        $this->delete_id = $id;
        $data = Timeoffrequester::find($id);
        $text = nama_department($data->department_id) . '(' . $data->department_id . ') -> ' . getName($data->approve_by_1) . '(' . $data->approve_by_1 . ') -> ' . getName($data->approve_by_2) . '(' . $data->approve_by_2 . ')';
        $this->dispatch('show-delete-confirmation', text: $text);
    }

    #[On('delete-confirmed')]
    public function delete()
    {
        // kembalikan role semua yg akan delete menjadi 1 
        $data = Timeoffrequester::find($this->delete_id);
        $data->delete();
        changeToAdmin($this->old_Approve1);
        changeToAdmin($this->old_Approve2);

        $this->dispatch(
            'message',
            type: 'success',
            title: 'Requester Deleted',
        );
    }

    public function updatedPlacementId()
    {
        // $data = Karyawan::where('id_karyawan', $this->requestId)->first();
        // if ($data != null) {
        //     $this->namaRequestId = $data->nama;
        // } else {
        //     $this->namaRequestId = '';
        // }

        $this->namaDepartment = nama_department($this->department_id);
    }

    public function updatedApproveBy1()
    {
        $data = Karyawan::where('id_karyawan', $this->approveBy1)->first();
        if ($data != null) {
            $this->namaApproveBy1 = $data->nama;
        } else {
            $this->namaApproveBy1 = '';
        }
    }
    public function updatedApproveBy2()
    {
        $data = Karyawan::where('id_karyawan', $this->approveBy2)->first();
        if ($data != null) {
            $this->namaApproveBy2 = $data->nama;
        } else {
            $this->namaApproveBy2 = '';
        }
    }

    public function save()
    {
        $this->validate([
            'department_id' => 'required|numeric',
            'approveBy1' => 'required|numeric',
            'approveBy2' => 'required|numeric'
        ]);

        if ($this->department_id != '' && $this->namaApproveBy1 != '' && $this->namaApproveBy2 != '') {
            if (!isResigned($this->approveBy1) && !isResigned($this->approveBy2)) {


                Timeoffrequester::create([
                    'department_id' => $this->department_id,
                    'approve_by_1' => $this->approveBy1,
                    'approve_by_2' => $this->approveBy2
                ]);

                $data2 = User::where('username', $this->approveBy1)->first();
                $data3 = User::where('username', $this->approveBy2)->first();
                $data2->role = 2;
                $data3->role = 2;
                $data2->save();
                $data3->save();

                $this->dispatch(
                    'message',
                    type: 'success',
                    title: 'Requester Created',
                );
                $this->is_update = false;
                $this->reset();
            } else {
                $this->dispatch(
                    'message',
                    type: 'error',
                    title: '1st approve or 2nd approve is resigned or blacklisted',
                );
            }
        } else {
            $this->dispatch(
                'message',
                type: 'error',
                title: 'Fail to create, user ID unavailable',
            );
        }
    }

    public function render()
    {
        $data_timeoff_requester = Timeoffrequester::all();
        $data_department = Department::orderBy('nama_department', 'asc')->get();
        return view('livewire.timeoutrequsterwr', [
            'data_timeoff_requester' => $data_timeoff_requester,
            'data_department' => $data_department
        ]);
    }
}
