<div class="p-4">
    <div class="bg-white rounded-2xl shadow p-4">

        <h2 class="text-lg font-semibold mb-4">Data Karyawan</h2>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left border border-gray-200 rounded-xl overflow-hidden">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-4 py-2 border">No</th>
                        <th class="px-4 py-2 border">Nama</th>
                        <th class="px-4 py-2 border">Status</th>
                        <th class="px-4 py-2 border">Gaji Pokok</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $index => $item)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-2 border">
                                {{ $data->firstItem() + $index }}
                            </td>
                            <td class="px-4 py-2 border">
                                {{ $item->nama ?? '-' }}
                            </td>
                            <td class="px-4 py-2 border">
                                {{ $item->status_karyawan }}
                            </td>
                            <td class="px-4 py-2 border">
                                Rp {{ number_format($item->gaji_pokok, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-gray-500">
                                Data tidak ditemukan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $data->links() }}
        </div>

    </div>
</div>
