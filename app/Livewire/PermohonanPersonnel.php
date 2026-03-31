<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\Karyawan;
use App\Models\Requester;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\Personnelrequestform;


class PermohonanPersonnel extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $requester_id, $placement_id;
    public $posisi, $jumlah_dibutuhkan, $level_posisi;
    public $manpower_posisi, $jumlah_manpower_saat_ini, $waktu_masuk_kerja, $job_description, $usia;
    public $pendidikan, $pengalaman_kerja, $kualifikasi_lain, $kisaran_gaji, $gender;
    public $skil_wajib = [], $alasan_permohonan = [], $tgl_request, $status;
    public $delete_id, $update_id;
    public $approve_1, $approve_2;
    public $approve_date_1, $approve_date_2;
    public $signature1 = false;
    public $signature2 = false;
    public $is_approved_1 = false, $is_approved_2 = false, $is_request_approved = false;

    public $is_add, $is_update, $done_id;
    public $user_id, $is_requester, $is_approval_1, $is_approval_2, $is_admin, $requestBy = [];



    public function DoneConfirmation($id)
    {
        $this->done_id = $id;
        $this->dispatch('show-done-confirmation');
    }

    #[On('done-confirmed')]
    public function done()
    {
        $data = Personnelrequestform::find($this->done_id);

        $data->done_by = $this->user_id;
        $data->done_date = Carbon::now()->toDateString();
        $data->status = 'Done';
        $data->save();

        $this->dispatch(
            'message',
            type: 'success',
            title: 'Personnel Request Done',
        );
        // $this->mount();
        $this->redirect(PermohonanPersonnel::class);
    }

    public function exit_approval_by()
    {
        $this->is_add = false;
        $this->is_update = false;
    }

    public function save_approve_1()
    {
        $this->validate([
            'signature1' => 'required|accepted'
        ], [
            'signature1.accepted' => 'Please tick to approve'
        ]);
        $data = Personnelrequestform::find($this->update_id);
        $data->approve_by_1 = $this->user_id;
        $data->approve_date_1 = $this->approve_date_1;
        $data->save();
        if ($data->approve_by_1 != '' && $data->approve_by_2 != '' && $data->approve_date_1 != '' && $data->approve_date_2 != '') {
            $data->status = 'Approved';
            $data->save();
        }
        // $this->mount();
        $this->redirect(PermohonanPersonnel::class);

        $this->dispatch(
            'message',
            type: 'success',
            title: 'Request Approved',
        );
    }
    public function save_approve_2()
    {
        $this->validate([
            'signature2' => 'required|accepted'
        ], [
            'signature2.accepted' => 'Please tick to approve'
        ]);
        $data = Personnelrequestform::find($this->update_id);
        $data->approve_by_2 = $this->user_id;
        $data->approve_date_2 = $this->approve_date_2;
        $data->save();
        if ($data->approve_by_1 != '' && $data->approve_by_2 != '' && $data->approve_date_1 != '' && $data->approve_date_2 != '') {
            $data->status = 'Approved';
            $data->save();
        }
        // $this->mount();
        $this->redirect(PermohonanPersonnel::class);

        $this->dispatch(
            'message',
            type: 'success',
            title: 'Request Approved',
        );
    }

    public function mount()
    {
        $this->is_add = false;
        $this->is_update = false;
        $this->is_requester = false;
        $this->is_approval_1 = false;
        $this->is_approval_2 = false;
        $this->is_admin = false;
        // $this->approve_1 = false;
        // $this->approve_2 = false;
        $this->signature1 = false;
        $this->signature2 = false;
        $this->is_approved_1 = false;
        $this->is_approved_2 = false;
        $this->is_request_approved = false;

        $this->user_id = auth()->user()->username;
        // $this->user_id = 38;














        $check_user = Requester::where('request_id', $this->user_id)
            ->where('request_id', $this->user_id)
            ->orWhere('approve_by_1', $this->user_id)
            ->orWhere('approve_by_2', $this->user_id)->first();
        if ($check_user != null) {
            if ($check_user->request_id == $this->user_id) $this->is_requester = true;
            if ($check_user->approve_by_1 == $this->user_id) $this->is_approval_1 = true;
            if ($check_user->approve_by_2 == $this->user_id) $this->is_approval_2 = true;
        }
        if (auth()->user()->role >= 6) {
            $this->is_admin = true;
        }

        if ($this->is_approval_1) {
            $this->approve_1 = auth()->user()->name;
            $this->approve_date_1 = Carbon::now()->toDateString();
        }

        if ($this->is_approval_2) {
            $this->approve_2 = auth()->user()->name;
            $this->approve_date_2 = Carbon::now()->toDateString();
        }


        $check = Requester::where(function ($query) {
            $query->where('request_id', $this->user_id)
                ->orWhere('approve_by_1', $this->user_id)
                ->orWhere('approve_by_2', $this->user_id);
        })->pluck('request_id');

        if ($check->isNotEmpty()) {
            $this->requestBy = $check->toArray(); // Convert the collection to an array
        }
    }

    public function exit()
    {
        $this->mount();
        $this->is_add = false;
        $this->is_update = false;
    }

    public function edit($id)
    {
        $this->update_id = $id;
        $data = Personnelrequestform::find($id);
        $this->posisi = $data->posisi;
        $this->jumlah_dibutuhkan = $data->jumlah_dibutuhkan;
        $this->level_posisi = $data->level_posisi;
        $this->manpower_posisi = $data->manpower_posisi;
        $this->jumlah_manpower_saat_ini = $data->jumlah_manpower_saat_ini;
        $this->waktu_masuk_kerja = $data->waktu_masuk_kerja;
        $this->job_description = $data->job_description;
        $this->usia = $data->usia;
        $this->pendidikan = $data->pendidikan;
        $this->pengalaman_kerja = $data->pengalaman_kerja;
        $this->kualifikasi_lain = $data->kualifikasi_lain;
        $this->kisaran_gaji = $data->kisaran_gaji;
        $this->gender = $data->gender;
        $this->skil_wajib = explode(',', $data->skil_wajib);
        $this->alasan_permohonan = explode(',', $data->alasan_permohonan);
        $this->requester_id = $data->requester_id;
        $this->tgl_request = $data->tgl_request;
        $this->status = $data->status;
        if ($data->approve_by_1 != '') $this->approve_1 = $data->approve_by_1;
        if ($data->approve_by_2 != '') $this->approve_2 = $data->approve_by_2;
        if ($data->approve_date_1 != '') $this->approve_date_1 = $data->approve_date_1;
        if ($data->approve_date_2 != '') $this->approve_date_2 = $data->approve_date_2;

        $this->signature1 = false;
        $this->is_approved_1 = false;
        $this->signature2 = false;
        $this->is_approved_2 = false;
        $this->is_request_approved = false;

        if ($data->approve_by_1 != '' && $data->approve_date_1 != '') {
            $this->is_approved_1 = true;
            $this->signature1 = true;
        }
        if ($data->approve_by_2 != '' && $data->approve_date_2 != '') {
            $this->signature2 = true;
            $this->is_approved_2 = true;
        }

        if ($data->requester_id != '' && $data->tgl_request != '') {
            $this->is_request_approved = true;
        }

        // $approve_1, $approve_2;

        $this->is_update = true;
    }

    public function update()
    {
        $data = Personnelrequestform::find($this->update_id);
        $data->posisi = $this->posisi;
        $data->jumlah_dibutuhkan = $this->jumlah_dibutuhkan;
        $data->level_posisi = $this->level_posisi;
        $data->manpower_posisi = $this->manpower_posisi;
        $data->jumlah_manpower_saat_ini = $this->jumlah_manpower_saat_ini;
        $data->waktu_masuk_kerja = $this->waktu_masuk_kerja;
        $data->job_description = $this->job_description;
        $data->usia = $this->usia;
        $data->pendidikan = $this->pendidikan;
        $data->pengalaman_kerja = $this->pengalaman_kerja;
        $data->kualifikasi_lain = $this->kualifikasi_lain;
        $data->kisaran_gaji = $this->kisaran_gaji;
        $data->gender = $this->gender;
        $data->skil_wajib = implode(',', $this->skil_wajib);
        $data->alasan_permohonan = implode(',', $this->alasan_permohonan);
        $data->save();
        $this->reset();
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Request Form Updated',
        );
        $this->is_update = false;
        $this->mount();
    }

    public function deleteConfirmation($id)
    {
        $this->delete_id = $id;
        $data = Personnelrequestform::find($id);
        $text = getName($data->requester_id) . ' -> ' . $data->posisi . ' -> ' . $data->jumlah_dibutuhkan;
        $this->dispatch('show-delete-confirmation', text: $text);
    }

    #[On('delete-confirmed')]
    public function delete()
    {
        $data = Personnelrequestform::find($this->delete_id)->delete();
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Form Deleted',
        );
    }

    protected $rules = [
        'posisi' => 'required',
        'jumlah_dibutuhkan' => 'required',
        'level_posisi' => 'required',
        'manpower_posisi' => 'required',
        'jumlah_manpower_saat_ini' => 'required',
        'waktu_masuk_kerja' => 'required',
        'job_description' => 'required',
        'usia' => 'required',
        'pendidikan' => 'required',
        'pengalaman_kerja' => 'required',
        'kualifikasi_lain' => 'required',
        'kisaran_gaji' => 'required',
        'gender' => 'required',
        'skil_wajib' => 'required|array|min:1',
        'alasan_permohonan' => 'required|array|min:1',
        // 'skil_wajib.*' => 'boolean',
        // 'alasan_permohonan.*' => 'boolean'
        // 'tgl_request' => 'required'
    ];

    public function save()
    {
        $this->validate();

        $data = Personnelrequestform::create([
            'posisi' => $this->posisi,
            'jumlah_dibutuhkan' => $this->jumlah_dibutuhkan,
            'level_posisi' => $this->level_posisi,
            'manpower_posisi' => $this->manpower_posisi,
            'jumlah_manpower_saat_ini' => $this->jumlah_manpower_saat_ini,
            'waktu_masuk_kerja' => $this->waktu_masuk_kerja,
            'job_description' => $this->job_description,
            'usia' => $this->usia,
            'pendidikan' => $this->pendidikan,
            'pengalaman_kerja' => $this->pengalaman_kerja,
            'kualifikasi_lain' => $this->kualifikasi_lain,
            'kisaran_gaji' => $this->kisaran_gaji,
            'gender' => $this->gender,
            'skil_wajib' => implode(',', $this->skil_wajib),
            'alasan_permohonan' => implode(',', $this->alasan_permohonan),
            'tgl_request' => Carbon::now()->toDateString(),
            'requester_id' => auth()->user()->username,
            'status' => 'Applying'
        ]);


        $this->reset();
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Request Form Created',
        );
        $this->mount();

        $this->is_add = false;
    }

    public function add()
    {
        $this->is_add = true;
        $this->tgl_request = Carbon::now()->toDateString();
        $this->requester_id = auth()->user()->username;
    }



    public function render()
    {
        // $this->is_add = false;
        // $this->is_update = false;
        // $this->is_requester = false;
        // $this->is_approval_1 = false;
        // $this->is_approval_2 = false;
        // $this->is_admin = false;

        // $this->user_id = auth()->user()->username;

        $check_user = Requester::where('request_id', $this->user_id)
            ->where('request_id', $this->user_id)
            ->orWhere('approve_by_1', $this->user_id)
            ->orWhere('approve_by_2', $this->user_id)->first();

        if ($check_user != null) {
            if ($check_user->request_id == $this->user_id) $this->is_requester = true;
            if ($check_user->approve_by_1 == $this->user_id) $this->is_approval_1 = true;
            if ($check_user->approve_by_2 == $this->user_id) $this->is_approval_2 = true;
        }

        if (auth()->user()->role >= 6) {
            $this->is_admin = true;
            $data = Personnelrequestform::whereIn('status', ['Approved', 'Done'])->orderBy('id', 'desc')->paginate(5);
        } else {

            // $data = Personnelrequestform::where('requester_id', $this->requestBy)
            $data = Personnelrequestform::whereIn('requester_id', $this->requestBy)->orderBy('id', 'desc')
                // dd($data);
                ->paginate(5);
        }

        return view('livewire.permohonan-personnel', [
            'data' => $data
        ]);
    }
}
