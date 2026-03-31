@extends('layouts.app2')

@section('title', 'Karyawan')

@section('content')

    <div class="p-4">
        <div class="row ">
            <div class="col-4 mb-3">

                <form action="/cari" method="post">
                    @csrf
                    <div class="input-group">

                        <input type="text" class="form-control" placeholder="Cari ..." aria-label="Recipient's username"
                            aria-describedby="button-addon2" name="cari">
                        <button class="btn btn-primary" type="submit" id="button-addon2">Cari</button>

                    </div>
                </form>
            </div>

            <div class="col-2 input-group mb-3">
                <a href="/resettable"><button class="btn btn-primary" type="button" id="button-addon2">Reset</button></a>
            </div>

        </div>

        <div class="card p-2">
            <div class="card-header bg-secondary text-light">
                <h3>Data Karyawan
                    <a href="/karyawancreate" class="float-end"><button class="btn btn-primary">Create New</button></a>
                </h3>

            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>ID Karyawan</th>
                            <th>Branch</th>
                            <th>Department</th>
                            <th>Jabatan</th>
                            <th>Level Jabatan</th>
                            <th></th>

                        </tr>
                    </thead>
                    <tbody x-data="{}">
                        @foreach ($datas as $data)
                            <tr>
                                <input type="hidden" class="delete_id" value="{{ $data->id }}">

                                <td @dblclick="window.location.href = '/karyawanupdate/'+{{ $data->id }}">
                                    {{ $data->nama }}</td>
                                <td @dblclick="window.location.href = '/karyawanupdate/'+{{ $data->id }}">
                                    {{ $data->id_karyawan }}</td>
                                <td @dblclick="window.location.href = '/karyawanupdate/'+{{ $data->id }}">
                                    {{ $data->branch }}</td>
                                <td @dblclick="window.location.href = '/karyawanupdate/'+{{ $data->id }}">
                                    {{ $data->departemen }}</td>
                                <td @dblclick="window.location.href = '/karyawanupdate/'+{{ $data->id }}">
                                    {{ $data->jabatan }}</td>
                                <td @dblclick="window.location.href = '/karyawanupdate/'+{{ $data->id }}">
                                    {{ $data->level_jabatan }}</td>
                                <td class="btn-group gap-2"
                                    @dblclick="window.location.href = '/karyawanupdate/'+{{ $data->id }}">
                                    <a href="/karyawanupdate/{{ $data->id }}"><button class="btn btn-warning btn-sm"><i
                                                class="fa-regular fa-pen-to-square"></i></button></a>
                                    {{-- <a href="/karyawandelete/{{ $data->id }}" onclick="confirmation(event)"
                                        class="btn btn-danger btn-sm"><i class="fa-solid fa-trash-can"></i></a> --}}

                                    <form method="post" action="{{ route('karyawan.destroy', ['id' => $data->id]) }}">
                                        @csrf
                                        @method('delete')



                                        <a href="{{ route('karyawan.destroy', ['id' => $data->id]) }}"><button
                                                type="submit" class="btn btn-danger btn-sm confirm-delete"><i
                                                    class="fa-solid fa-trash-can"></i></button></a>
                                    </form>


                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <span>{{ $datas->links() }}</span>
            </div>
        </div>
    </div>
    <script>
        @if (Session::has('message'))

            toastr.options = {

                "progressBar": true,
                "timeOut": "1500",
                "progressBar": true,
                "positionClass": "toast-top-right",
                "closeButton": true,
            }
            toastr.success("{{ session('message') }}");
        @endif
    </script>

@endsection
