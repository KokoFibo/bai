<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Timeoff;
use Livewire\Component;
use App\Models\Timeofffile;
use Livewire\WithPagination;
use App\Models\Timeoffrequester;
use Google\Service\CloudSearch\History;

class Timeoffapprovewr extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $karyawan_id, $department_id, $request_type, $start_date, $end_date;
    public $description, $status, $tanggal,  $approve1, $approve1_date, $approve2, $approve2_date, $done_by, $done_date;
    public $id, $is_show, $is_checked, $is_approve1, $is_approve2, $is_done;
    public $is_hrd, $show_attachment, $hasFile;
    public $filenames = [];


    public function refresh_file($id)
    {
        $file_data = Timeofffile::where('timeoff_id', $id)->first();
        $this->hasFile = $file_data;

        $file_data = Timeofffile::where('timeoff_id', $id)->get();
        $this->filenames = $file_data;
    }

    public function show_toggle($id)
    {
        $this->refresh_file($id);
        $this->show_attachment = !$this->show_attachment;
    }

    public function undone($id)
    {
        $data = Timeoff::find($id);
        $data->status = 'Confirmed';
        $data->done_by = null;
        $data->done_date = null;
        $data->save();
        $this->dispatch(
            'message',
            type: 'success',
            title: "It's Undone",
        );
        $this->is_show = false;
        $this->is_checked = false;
        $this->redirect(Timeoffapprovewr::class);
    }

    public function done($id)
    {
        $data = Timeoff::find($id);
        $data->status = 'Done';
        $data->done_by = auth()->user()->username;
        $data->done_date = Carbon::now()->toDateString();
        $data->save();
        $this->dispatch(
            'message',
            type: 'success',
            title: "It's Done",
        );
        $this->is_show = false;
        $this->is_checked = false;
        $this->redirect(Timeoffapprovewr::class);
    }

    public function disapprove()
    {
        if ($this->is_checked == 0) {
            $this->dispatch(
                'message',
                type: 'error',
                title: 'Please Check to confirm',
            );
        } else {
            $data = Timeoff::find($this->id);
            if ($this->is_approve1) {

                $data->approve1 = 1;
                $data->approve1_date = null;
                $data->status = 'Tidak Disetujui';

                $data->save();
                $this->dispatch(
                    'message',
                    type: 'success',
                    title: 'Request Disapproved Confirmed',
                );
                $this->is_checked = false;
                $this->is_show = false;
            }
            if ($this->is_approve2) {

                $data->approve2 = 1;
                $data->approve2_date = null;
                $data->status = 'Tidak Disetujui';
                $data->save();
                $this->dispatch(
                    'message',
                    type: 'success',
                    title: 'Request Disapproved Confirmed',
                );
                $this->is_checked = false;
                $this->is_show = false;
            }
        }
        $this->redirect(Timeoffapprovewr::class);
    }


    public function approve()
    {
        if ($this->is_checked == 0) {
            $this->dispatch(
                'message',
                type: 'error',
                title: 'Please Check to confirm',
            );
        } else {
            $data = Timeoff::find($this->id);
            if ($this->is_approve1) {
                if ($data->approve1 == 1) $kode = 'da';
                else $kode = 'a';
                if ($data->approve1 == '' || $data->approve1 == 1) {
                    $data->approve1 = auth()->user()->username;
                    $data->approve1_date =  Carbon::now()->toDateString();
                    $data->save();
                    $this->dispatch(
                        'message',
                        type: 'success',
                        title: 'Request Confirmed',
                    );
                    $this->is_show = false;
                    $this->is_checked = false;


                    // if ($data->approve1 != '' && $data->approve2 != '') {
                    //     $data->status = 'Confirmed';
                    //     $data->save();
                    // }
                } else {
                    $this->dispatch(
                        'message',
                        type: 'success',
                        title: 'Request Already Confirmed',
                    );
                    $this->is_show = false;
                    $this->is_checked = false;
                }
            }
            if ($this->is_approve2) {
                if ($data->approve2 == 1) $kode = 'da';
                else $kode = 'a';

                if ($data->approve2 == '' || $data->approve2 == 1) {
                    $data->approve2 = auth()->user()->username;
                    $data->approve2_date =  Carbon::now()->toDateString();
                    $data->save();
                    $this->dispatch(
                        'message',
                        type: 'success',
                        title: 'Request Confirmed',
                    );

                    // } elseif ($kode == 'da') {
                    //     $data->status = 'Menunggu Approval';
                    //     $data->save();
                    // }
                    $this->is_show = false;
                    $this->is_checked = false;
                } else {
                    $this->dispatch(
                        'message',
                        type: 'success',
                        title: 'Request Already Confirmed',
                    );
                    $this->is_show = false;
                    $this->is_checked = false;
                }
            }
            if ($data->approve1 != '' && $data->approve2 != '' && $data->approve1 != 1 && $data->approve2 != 1) {
                $data->status = 'Confirmed';
                $data->save();
            } elseif ($kode == 'a' || $data->approve1 != 1 || $data->approve2 != 1) {
                // } else {
                $data->status = 'Menunggu Approval';
                $data->save();
            }
            if ($data->approve1 == 1 || $data->approve2 == 1) {
                $data->status = 'Tidak Disetujui';
                $data->save();
            }
        }
        $this->redirect(Timeoffapprovewr::class);
    }

    public function close()
    {

        $this->is_show = false;
        $this->is_checked = false;
    }

    public function mount()
    {
        // id_karyawan untuk HRD
        $this->show_attachment = false;
        $this->is_hrd = false;
        if (auth()->user()->username == 58 || auth()->user()->username == 1146) $this->is_hrd = true;
        $this->is_approve1 = false;
        $this->is_approve2 = false;
        $this->is_done = false;
        $this->is_show = false;
        $this->is_checked = false;
        $department = getDepartment(auth()->user()->username);
        $data_user = Timeoffrequester::where('department_id', $department)->get();
        // dd($data_user->all());
        // apa ya
        foreach ($data_user as $d) {
            if ($d->approve_by_1 == auth()->user()->username) $this->is_approve1 = true;
            if ($d->approve_by_2 == auth()->user()->username) $this->is_approve2 = true;
        }
    }

    public function show($id)
    {
        $this->id = $id;
        $data = Timeoff::find($id);
        // $this->karyawan_id = $data->karyawan_id;
        $this->karyawan_id = $data->karyawan->nama;
        $this->department_id = $data->department_id;
        $this->request_type = $data->request_type;
        $this->start_date = $data->start_date;
        $this->end_date = $data->end_date;
        $this->description = $data->description;
        $this->status = $data->status;
        $this->tanggal = $data->tanggal;
        $this->approve1 = $data->approve1;
        $this->approve1_date = $data->approve1_date;
        $this->approve2 = $data->approve2;
        $this->approve2_date = $data->approve2_date;
        $this->done_by = $data->done_by;
        $this->done_date = $data->done_date;
        $this->is_show = true;
        $this->refresh_file($id);
    }
    public function render()
    {
        if (auth()->user()->username == 58 || auth()->user()->username == 1146) {
            $data = Timeoff::whereIn('status', ['Confirmed', 'Done'])->orderBy('id', 'desc')->paginate(5);
        } else {

            $data = Timeoff::where('department_id', getDepartment(auth()->user()->username))->orderBy('id', 'desc')->paginate(5);
            // dibawah ini utk test buat yg data 80000
            if (auth()->user()->username >= 60000) $data = Timeoff::orderBy('id', 'desc')->paginate(5);
        }
        return view('livewire.timeoffapprovewr', [
            'data' => $data
        ]);
    }
}
