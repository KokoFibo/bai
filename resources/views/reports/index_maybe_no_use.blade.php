@extends('layouts.app2')
@section('title', 'Report')
@section('content')

    <div class="pt-5 col-12 col-xl-6 mx-auto">
        <div class="card">
            <div class="card-header bg-success">
                <h3>Laporan Gaji untuk Bank</h3>
            </div>
            <div class="card-body">
                <form action="/createexcel" method="POST">
                    @csrf
                    <div class="d-lg-flex flex-row ">
                        <div class="col-12 col-lg-6">
                            <select class="form-select form-select-lg mb-3" aria-label="Large select example" name="year">
                                {{-- <option value="2024">2024</option>
                                <option value="2023">2023</option> --}}
                                @foreach ($select_year as $d)
                                    <option value="{{ $d }}">{{ $d }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-lg-6">
                            <select class="form-select form-select-lg mb-3" aria-label="Large select example"
                                name="month">
                                {{-- <option value="1">Januari</option>
                                <option value="11">November</option>
                                <option value="12">Desember</option> --}}
                                @foreach ($select_month as $d)
                                    <option value="{{ $d }}">{{ monthName($d) }}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>

                    <div>
                        <select class="form-select form-select-lg mb-3" aria-label="Large select example"
                            name="selectedCompany">
                            <option value="1">Semua Company</option>
                            <option value="2">Pabrik 1</option>
                            <option value="3">Pabrik 2</option>
                            <option value="4">Kantor</option>
                            <option value="5">ASB</option>
                            <option value="6">DPA</option>
                            <option value="7">YCME</option>
                            <option value="8">YEV</option>
                            <option value="9">YIG</option>
                            <option value="10">YSM</option>
                            <option value="11">YAM</option>

                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">Excel</button>
                </form>

            </div>
        </div>
    </div>
@endsection
