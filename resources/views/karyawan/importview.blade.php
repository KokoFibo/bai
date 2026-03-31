@extends('layouts.app')

@section('title', 'Import - Presensi')

@section('content')
    <div class="col-3 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-light">
                <h4>Upload File Excel Data Karyawan</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('karyawan.import') }}" method="POST" enctype="multipart/form-data">
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
                        <label for="formFile" class="form-label mt-2 mb-3">Upload File Excel Karyawan</label>
                        <input wire:model="fileExcel" class="form-control" type="file" name="file" id="formFile"
                            accept="xlsx">
                        @error('photos.*')
                            <span class="error">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-outline-primary">Upload</button>
                    <a href="/erasedatakarayawan"><button class="btn btn-warning " type="button">Erase</button></a>
                    <a href="/dashboard"><button class="btn btn-primary float-end" type="button">Exit</button></a>

                </form>

            </div>
        </div>

    </div>
@endsection
