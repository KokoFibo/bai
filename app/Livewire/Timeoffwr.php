<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Timeoff;
use Livewire\Component;
use App\Models\Karyawan;
use App\Models\Timeofffile;
use Livewire\Attributes\On;
use App\Rules\FileSizeLimit;
use Livewire\WithFileUploads;
use App\Rules\AllowedFileExtension;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class Timeoffwr extends Component
{
    use WithFileUploads;
    public $karyawan_id, $department_id, $name, $request_type, $description, $start_date, $end_date, $folder_name;
    public $is_add, $is_update, $edit_id, $show_image, $delete_id, $selected_id;

    public $files = [];
    public $filenames = [];

    public function exit()
    {
        $this->is_add = false;
        $this->is_update = false;
        $this->clear_fields();
    }

    public function confirm_delete($id)
    {
        $this->delete_id = $id;
        $title = 'Yakin hapus Request ini?';
        $this->dispatch(
            'delete_confirmation',
            type: 'success',
            title: $title
        );
    }

    #[On('delete-confirmed')]
    public function delete()
    {
        $data = Timeoff::find($this->delete_id);
        $data->delete();
        $timeoff_id = $this->delete_id;
        $datas = Timeofffile::where('timeoff_id', $timeoff_id)->get();
        // dd($datas);
        if ($datas) {
            foreach ($datas as $data) {
                // $data = Timeofffile::find($id);
                if ($data != null) {
                    try {
                        $result = Storage::disk('public')->delete($data->filename);
                        if ($result) {
                            // File was deleted successfully
                            $data->delete();
                            // $this->dispatch('success', message: 'File telah di delete');

                        } else {
                            // File could not be deleted
                            // return 'Failed to delete file.';


                            // $this->dispatch('error', message: 'File GAGAL di delete');
                            $this->dispatch(
                                'message',
                                type: 'error',
                                title: 'File GAGAL di delete',
                            );
                            return;
                        }
                    } catch (\Exception $e) {
                        // An error occurred while deleting the file
                        return 'An error occurred: ' . $e->getMessage();
                    }
                }
            }
        }


        $this->dispatch(
            'message',
            type: 'success',
            title: 'File telah di delete',
        );
    }

    public function deleteFile($id)
    {
        // $data = Applicantfile::where('filename', $filename)->first();
        $data = Timeofffile::find($id);

        if ($data != null) {


            try {
                // $result = Storage::disk('google')->delete($data->filename);
                $result = Storage::disk('public')->delete($data->filename);
                if ($result) {
                    // File was deleted successfully
                    $timeoff_id = $data->timeoff_id;
                    $data->delete();

                    $this->refresh_file($timeoff_id);
                    // $this->dispatch('success', message: 'File telah di delete');
                    $this->dispatch(
                        'message',
                        type: 'success',
                        title: 'File telah di delete',
                    );

                    return 'File deleted successfully.';
                } else {
                    // File could not be deleted
                    // return 'Failed to delete file.';


                    // $this->dispatch('error', message: 'File GAGAL di delete');
                    $this->dispatch(
                        'message',
                        type: 'error',
                        title: 'File GAGAL di delete',
                    );
                }
            } catch (\Exception $e) {
                // An error occurred while deleting the file
                return 'An error occurred: ' . $e->getMessage();
            }
        } else {
            // $this->dispatch('error', message: 'File tidak ketemu');
            $this->dispatch(
                'message',
                type: 'error',
                title: 'File tidak ketemu',
            );
        }
    }


    public function rules()
    {
        return [
            'request_type' => 'required',
            'start_date' => 'date|required',
            'end_date' => 'date|nullable',
            'description' => 'required',
            'files.*' =>  ['nullable',  new AllowedFileExtension, new FileSizeLimit(1024)]
        ];
    }
    public function messages()
    {
        return [
            'request_type.required' => 'Jenis Request wajib diisi.',
            'start_date.required' => 'Tanggal dari wajib diisi.',
            'start_date.date' => 'Harus berformat tanggal dd/mm/yyyy',
            'end_date.required' => 'Tanggal sampai wajib diisi.',
            'end_date.date' => 'Harus berformat tanggal dd/mm/yyyy',
            'description.required' => 'Deskripsi wajib diisi',
            'files.*.mimes' => 'Hanya menerima file png, jpg dan jpeg',
            'files.*.max' => 'Max file size 1Mb',
        ];
    }

    public function clear_fields()
    {

        $this->request_type = '';
        $this->start_date = '';
        $this->end_date = '';
        $this->description = '';
    }
    public function updatedFiles()
    {
        $this->validate([
            'files.*' => ['nullable', 'mimes:png,jpg,jpeg', new FileSizeLimit(1024)],
        ]);
    }

    public function mount()
    {
        $data = Karyawan::where('id_karyawan', auth()->user()->username)->first();
        // $data = Karyawan::where('id_karyawan', 1070)->first();
        $this->karyawan_id = $data->id;
        $this->department_id = $data->department_id;
        $this->name = $data->nama;
        $this->folder_name = get_first_name($this->name) . '_' . $data->id_karyawan;
        $this->is_add = false;
        $this->is_update = false;
        $this->show_image = false;
    }




    public function show_image_toggle($id)
    {
        $this->refresh_file($id);
        $this->selected_id = $id;
        $this->show_image = !$this->show_image;
    }
    public function add()
    {
        $this->is_add = true;
    }

    public function edit($id)
    {
        $this->edit_id = $id;
        $this->is_update = true;

        $data = Timeoff::find($id);
        $this->request_type = $data->request_type;
        $this->start_date = $data->start_date;
        $this->end_date = $data->end_date;
        $this->description = $data->description;
    }



    public function update()
    {
        $this->validate();
        if ($this->files) {
            // dd($this->files);

            foreach ($this->files as $file) {
                $folder = 'Timeoff/' . $this->folder_name;
                $fileExension = $file->getClientOriginalExtension();

                if ($fileExension != 'pdf') {
                    $folder = 'Timeoff/' . $this->folder_name . '/' . random_int(100000, 900000) . '.' . $fileExension;
                    $manager = ImageManager::gd();

                    // resize gif image
                    $image = $manager
                        ->read($file)
                        ->scale(width: 800);

                    // $imagedata = (string) $image->toJpeg();
                    $imagedata = (string) $image->toWebp(60);

                    // Storage::disk('google')->put($folder, $imagedata);
                    Storage::disk('public')->put($folder, $imagedata);
                    $this->path = $folder;
                } else {
                    // $this->path = Storage::disk('google')->put($folder, $file);
                    $this->path = Storage::disk('public')->put($folder, $file);
                }

                $this->originalFilename = $file->getClientOriginalName();
                $timeoff_id = $this->edit_id;
                Timeofffile::create([
                    'timeoff_id' => $timeoff_id,
                    // 'originalName' => $this->originalFilename,
                    'originalName' => clear_dot($this->originalFilename, $fileExension),
                    'filename' => $this->path,
                ]);
            }
            $this->files = '';
        }
        $data = Timeoff::find($this->edit_id);

        $data->request_type = $this->request_type;
        $data->start_date = $this->start_date;
        $data->end_date = $this->end_date;
        $data->description = $this->description;
        $data->save();
        $this->refresh_file($data->id);
        $this->is_update = false;
        $this->clear_fields();


        // $this->dispatch('success', 'Request Anda sudah berhasil di submit');
        $this->dispatch(
            'message',
            type: 'success',
            title: 'Request Anda sudah berhasil di update',
        );
    }

    public function save()
    {
        $this->validate();
        $time_off = Timeoff::create([
            'karyawan_id' => $this->karyawan_id,
            'department_id' => $this->department_id,
            'request_type' => $this->request_type,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'description' => $this->description,
            'tanggal' => Carbon::now()->toDateString(),
            'status' => 'Menunggu Approval',
        ]);
        if ($this->files) {
            // dd($this->files);

            foreach ($this->files as $file) {
                $folder = 'Timeoff/' . $this->folder_name;
                $fileExension = $file->getClientOriginalExtension();

                if ($fileExension != 'pdf') {
                    $folder = 'Timeoff/' . $this->folder_name . '/' . random_int(100000, 900000) . '.' . $fileExension;
                    $manager = ImageManager::gd();

                    // resize gif image
                    $image = $manager
                        ->read($file)
                        ->scale(width: 800);

                    // $imagedata = (string) $image->toJpeg();
                    $imagedata = (string) $image->toWebp(60);

                    // Storage::disk('google')->put($folder, $imagedata);
                    Storage::disk('public')->put($folder, $imagedata);
                    $this->path = $folder;
                } else {
                    // $this->path = Storage::disk('google')->put($folder, $file);
                    $this->path = Storage::disk('public')->put($folder, $file);
                }

                $this->originalFilename = $file->getClientOriginalName();
                Timeofffile::create([
                    'timeoff_id' => $time_off->id,
                    // 'originalName' => $this->originalFilename,
                    'originalName' => clear_dot($this->originalFilename, $fileExension),
                    'filename' => $this->path,
                ]);
            }
            $this->files = '';
            // return response()->json(['success' => true]);
        }

        $this->is_add = false;
        $this->clear_fields();
        // $this->dispatch('success', 'Request Anda sudah berhasil di submit');


        $this->dispatch(
            'message',
            type: 'success',
            title: 'Request Anda sudah berhasil di submit',
        );
    }

    public function refresh_file($id)
    {
        $file_data = Timeofffile::where('timeoff_id', $id)->get();
        $this->filenames = $file_data;
    }

    public function render()
    {
        $data = Timeoff::where('karyawan_id', $this->karyawan_id)->orderBy('id', 'desc')->get();
        return view('livewire.timeoffwr', [
            'data' => $data,

        ])->layout('layouts.polos');
    }
}
