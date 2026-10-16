<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class DeleteUserBackend extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $deleteAll = false;
    public $sortColumn = 'name';
    public $sortDirection = 'asc';

    private function filteredQuery()
    {
        // Ubah daftar username terproteksi ke format String secara eksplisit
        $protectedUsernames = [
            '10000',
            '20000',
            '30000',
            '40000',
            '50000',
            '60000',
            '70000',
            '80000',
            '100000',
        ];

        return User::query()
            ->where('role', '<', 5)
            ->whereNotIn('username', $protectedUsernames);
    }

    public function sortByColumn($column)
    {
        $allowedColumns = ['id', 'name', 'email', 'username', 'role', 'language', 'device'];

        if (!in_array($column, $allowedColumns, true)) {
            return;
        }

        if ($this->sortColumn === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortColumn = $column;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    public function delete($id)
    {
        // Cari user spesifik berdasarkan ID
        $user = User::findOrFail($id);
        $user->delete();

        $this->resetPage();

        $this->dispatch(
            'message',
            type: 'success',
            title: 'Data Karyawan Berhasil Dihapus'
        );
    }

    public function Deletesemua()
    {
        // Ambil array ID secara eksplisit
        $userIds = $this->filteredQuery()->pluck('id')->toArray();
        // dd($userIds);
        if (empty($userIds)) {
            $this->dispatch(
                'message',
                type: 'warning',
                title: 'Tidak ada data kriteria khusus yang dapat dihapus'
            );
            return;
        }

        // Gunakan Model::destroy() untuk menghapus berdasarkan Primary Key
        $deleted = User::destroy($userIds);

        $this->resetPage();

        $this->dispatch(
            'message',
            type: 'success',
            title: $deleted . ' Data Karyawan Berhasil Dihapus'
        );
    }

    public function render()
    {
        // Menggunakan count() atau exists() pada kueri
        $hasFilteredUsers = $this->filteredQuery()->exists();

        $this->deleteAll = $hasFilteredUsers;

        // Jika data terfilter ada -> pakai filteredQuery(), jika kosong -> tampilkan seluruh data
        $query = $hasFilteredUsers
            ? $this->filteredQuery()
            : User::query();

        $data = $query
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate(10);
        return view('livewire.delete-user-backend', [
            'data' => $data,
            'isFiltered' => $hasFilteredUsers,
        ]);
    }
}
