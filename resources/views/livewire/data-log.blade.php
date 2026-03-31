<div>
    @section('title', 'History Gaji')

    <h2 class='my-3 text-center'>Log Perubahan Gaji karyawan</h2>
    <div>
        <div class="d-flex gap-3  p-3">
            <select class="form-select" aria-label="Default select example" wire:model='admin'>
                <option selected>Pilih Admin</option>
                @foreach ($admins as $key => $a)
                    <option value="{{ $a }}">{{ getCauserName($a) }}</option>
                @endforeach
                {{-- <option value="2">Two</option>
            <option value="3">Three</option> --}}
            </select>
            <select class="form-select" aria-label="Default select example" wire:model='month'>
                {{-- <option selected>Bulan</option> --}}
                @foreach ($months as $key => $m)
                    <option value="{{ $m }}">{{ nama_bulan($m) }}</option>
                @endforeach
                {{-- <option value="2">Two</option>
            <option value="3">Three</option> --}}
            </select>
            <select class="form-select" aria-label="Default select example" wire:model='year'>
                {{-- <option selected value="{{ $thisYear }}">{{ $thisYear }}</option> --}}
                @foreach ($years as $key => $y)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endforeach
                {{-- <option value="2">Two</option>
            <option value="3">Three</option> --}}
            </select>
            <button class="btn btn-primary" wire:click='proses'>Proses</button>
            <button class="btn btn-success" wire:click='excel'>Excel</button>
        </div>
        <table class='table'>
            <thead>
                <tr>
                    <th>#</th>
                    <th>description</th>
                    <th>Karyawan</th>
                    <th>Admin</th>

                    <th>Keterangan Perubahan</th>
                    <th>Tanggal Update</th>
                </tr>
            </thead>
            @foreach ($activity as $key => $a)
                @if ($a->event == 'deleted')
                    <tr class='table-warning'>
                    @else
                    <tr>
                @endif
                <td>{{ $key + 1 }}</td>
                <td>{{ $a->event }}</td>
                <td>{{ getSubjectName($a->subject_id) }}</td>
                <td>{{ getCauserName($a->causer_id) }}</td>
                @if ($a->event == 'deleted')
                    <td></td>
                @else
                    <td>

                        @php
                            $data = json_decode($a->properties, true);
                        @endphp
                        <ul>
                            @foreach ($data as $key => $value)
                                @if (is_array($value))
                                    <li>
                                        {{-- <strong>{{ ucfirst($key) }}:</strong> --}}
                                        @if (ucfirst($key) == 'Attributes')
                                            <strong>Sesudah:</strong>
                                        @else
                                            <strong>Sebelum:</strong>
                                        @endif
                                        <ul>
                                            @foreach ($value as $subKey => $subValue)
                                                @if (is_array($subValue))
                                                    <li>
                                                        <strong>{{ ucfirst($subKey) }}:</strong>
                                                        <ul>
                                                            @foreach ($subValue as $subSubKey => $subSubValue)
                                                                <li>{{ ucfirst($subSubKey) }}: {{ $subSubValue }}
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </li>
                                                @else
                                                    {{-- <li>{{ ucfirst($subKey) }}: {{ $subValue }}</li> --}}

                                                    @if (ucfirst($subKey) == 'Gaji_pokok')
                                                        <li>Gaji Pokok: {{ number_format($subValue) }}</li>
                                                    @else
                                                        <li>Gaji Overtime: {{ number_format($subValue) }}</li>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </ul>
                                    </li>
                                @else
                                    <li><strong>{{ ucfirst($key) }}:</strong> {{ $value }}</li>
                                @endif
                            @endforeach
                        </ul>

                    </td>
                    {{-- <td>{{ $attribute }}</td> --}}
                    {{-- <td>{{ $a->properties }}</td> --}}
                @endif
                <td>{{ $a->updated_at }}</td>
                </tr>
                </td>
            @endforeach
        </table>
        {{ $activity->links() }}
    </div>
</div>
