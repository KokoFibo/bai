<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\Karyawan;
use Livewire\WithPagination;
use App\Models\Applicantdata;

class ApplicantDiterima extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public function delete($id)
    {
        $data = Applicantdata::find($id);
        $data->delete();
    }

    public function clean()
    {

        // bersih dari Karyawan

        $diterima_karyawan = [];
        $diterima_user = [];
        $data = Applicantdata::all();

        foreach ($data as $d) {
            $karyawan = Karyawan::where('email', $d->email)->first();
            $user = User::where('email', $d->email)->first();

            if ($user != null) {
                $diterima_user[] = $d->id;  // Append the applicant ID to the array
            }

            if ($karyawan != null) {
                $diterima_karyawan[] = $d->id;  // Append the applicant ID to the array
            }
        }

        // Delete the applicant data whose IDs are in the diterima array
        foreach ($diterima_karyawan as $id) {
            $data = Applicantdata::find($id);
            if ($data) {  // Check if data exists before attempting to delete
                $data->delete();
            }
        }
        foreach ($diterima_karyawan as $id) {
            $data = Applicantdata::find($id);
            if ($data) {  // Check if data exists before attempting to delete
                $data->delete();
            }
        }
        // bersih dari user



        $this->dispatch(
            'message',
            type: 'success',
            title: 'Data Applicant yang sudah diterima telah di delete',
            position: 'center'
        );
    }
    public function render()
    {
        $data = Applicantdata::orderBy('applicant_id', 'asc')->paginate(10);
        return view('livewire.applicant-diterima', [
            'data' => $data
        ]);
    }
}
