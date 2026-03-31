@extends('layouts.app2')
@section('content')
    @if (auth()->user()->role > 4)
        <div class="pt-5 text-center mx-auto">
            <h1>Upload File Excel TER</h1>
            <form action="upload/ter" method="POST" enctype="multipart/form-data" class="col-4 mx-auto mt-5 ">
                @csrf
                <div class="mb-3">
                    <label for="formFile" class="form-label">Masukan file excel yg berisi data TER pph 21</label>
                    <input class="form-control" type="file" id="formFile" name='file'>
                    @error('file')
                        <div class="text-danger">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <button class="btn btn-primary" type="submit">Upload</button>
            </form>
        </div>
    @endif
@endsection
