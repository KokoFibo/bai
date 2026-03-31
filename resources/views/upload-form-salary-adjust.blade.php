@extends('layouts.app2')

@section('title', 'Dashboard')

@section('content')


    <div class="col-xl-3 col-12 mx-auto pt-4">
        <div class="card">
            <div class="card-header bg-primary text-light">
                <h5>Bulk Update Salary Adjust Form</h5>
            </div>
            <div class="card-body">

                @if ($is_uploadable == false)
                    <p>Harap kembali sebentar lagi...</p>
                @else
                    <form action="/bulk-upload" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if (session('info'))
                                <div class="alert alert-info">
                                    {{ session('info') }}
                                </div>
                            @endif



                            <label for="formFile" class="form-label mt-2 mb-3">Upload data</label>
                            <input class="form-control @error('file') is-invalid @enderror" type="file" name="file"
                                id="formFile" accept="xlsx" required>
                            @error('file')
                                <div class="invalid-feedback">
                                    Hanya menerima file Excel XLSX
                                </div>
                            @enderror

                        </div>
                        <div class="d-flex flex-row gap-2" x-data="{ open: false }">
                            <button @click="open=true" x-show="!open" type="submit" class="btn btn-outline-primary"
                                id="import-btn">Update</button>
                            <div x-show="open" class="bg-warning text-light p-2">Mohon di tunggu sampai proses upload
                                selesai
                            </div>
                            <a href="/karyawanindex" wire:navigate><button x-show="!open" type="button" id="exit-btn"
                                    class="btn btn-primary">Exit</button></a>

                            <div x-show="open" class="spinner-grow text-success" role="status" id="loading">
                            </div>
                            <div class="visually-hidden">Loading...</div>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>



@endsection
