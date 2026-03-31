<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\Karyawan;
use App\Models\Requester;
use Livewire\Attributes\On;


class Requesterwr extends Component
{
    public $requestId, $approveBy1, $approveBy2;
    public $namaRequestId, $namaApproveBy1, $namaApproveBy2;
    public $is_update = false;
    public $is_update_id, $delete_id;
    public $old_requester, $old_Approve1, $old_Approve2;

    public function exit()
    {
        $this->redirect(PermohonanPersonnel::class);
    }

    public function edit($id)
    {
        $data = Requester::find($id);
        $this->old_requester = $data->request_id;
        $this->old_Approve1 = $data->approve_by_1;
        $this->old_Approve2 = $data->approve_by_2;
        $this->requestId = $data->request_id;
        $this->approveBy1 = $data->approve_by_1;
        $this->approveBy2 = $data->approve_by_2;
        $this->is_update_id = $data->id;
        $this->namaRequestId = getName($this->requestId);
        $this->namaApproveBy1 = getName($this->approveBy1);
        $this->namaApproveBy2 = getName($this->approveBy2);
        $this->is_update = true;
    }

    public function update()
    {
        $this->validate([
            'requestId' => 'required|numeric',
            'approveBy1' => 'required|numeric',
            'approveBy2' => 'required|numeric'
        ]);

        if ($this->namaRequestId != '' && $this->namaApproveBy1 != '' && $this->namaApproveBy2 != '') {
            if (!isResigned($this->requestId) && !isResigned($this->approveBy1) && !isResigned($this->approveBy2)) {
                $data = Requester::find($this->is_update_id);
                $data->request_id = $this->requestId;
                $data->approve_by_1 = $this->approveBy1;
                $data->approve_by_2 = $this->approveBy2;
                $data->save();

                if ($this->requestId != $this->old_requester) changeToAdmin($this->old_requester);
                if ($this->approveBy1 != $this->old_Approve1) changeToAdmin($this->old_Approve1);
                if ($this->approveBy2 != $this->old_Approve2) changeToAdmin($this->old_Approve2);
                changeToRequest($this->requestId);
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

        $data = Requester::find($id);
        $this->old_requester = $data->request_id;
        $this->old_Approve1 = $data->approve_by_1;
        $this->old_Approve2 = $data->approve_by_2;


        $this->delete_id = $id;
        $data = Requester::find($id);
        $text = getName($data->request_id) . '(' . $data->request_id . ') -> ' . getName($data->approve_by_1) . '(' . $data->approve_by_1 . ') -> ' . getName($data->approve_by_2) . '(' . $data->approve_by_2 . ')';
        $this->dispatch('show-delete-confirmation', text: $text);
    }

    #[On('delete-confirmed')]
    public function delete()
    {
        // kembalikan role semua yg akan delete menjadi 1 
        $data = Requester::find($this->delete_id);
        $data->delete();
        changeToAdmin($this->old_requester);
        changeToAdmin($this->old_Approve1);
        changeToAdmin($this->old_Approve2);

        $this->dispatch(
            'message',
            type: 'success',
            title: 'Requester Deleted',
        );
    }

    public function updatedRequestId()
    {
        $data = Karyawan::where('id_karyawan', $this->requestId)->first();
        if ($data != null) {
            $this->namaRequestId = $data->nama;
        } else {
            $this->namaRequestId = '';
        }
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
            'requestId' => 'required|numeric',
            'approveBy1' => 'required|numeric',
            'approveBy2' => 'required|numeric'
        ]);

        if ($this->namaRequestId != '' && $this->namaApproveBy1 != '' && $this->namaApproveBy2 != '') {
            if (!isResigned($this->requestId) && !isResigned($this->approveBy1) && !isResigned($this->approveBy2)) {
                Requester::create([
                    'request_id' => $this->requestId,
                    'approve_by_1' => $this->approveBy1,
                    'approve_by_2' => $this->approveBy2
                ]);

                $data1 = User::where('username', $this->requestId)->first();
                $data2 = User::where('username', $this->approveBy1)->first();
                $data3 = User::where('username', $this->approveBy2)->first();
                $data1->role = 2;
                $data2->role = 2;
                $data3->role = 2;
                $data1->save();
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
        $data_requester = Requester::all();
        return view('livewire.requesterwr', [
            'data_requester' => $data_requester
        ]);
    }
}
