@extends('layouts.app2')
@section('title', 'Report')
@section('content')

    <div class="pt-5 col-12 col-xl-6 mx-auto">
        <div class="card">
            <div class="card-header bg-success">
                <h3>Export Excel Presensi Summary</h3>
            </div>
            <div class="card-body">
                <form action="/createexcelpresensisummary" method="POST">
                    @csrf
                    <div class="d-lg-flex flex-row ">
                        <div class="col-12 col-lg-6">
                            <select class="form-select form-select-lg mb-3" aria-label="Large select example" name="year">
                                <option value="2023">2023</option>

                            </select>
                        </div>
                        <div class="col-12 col-lg-6">
                            <select class="form-select form-select-lg mb-3" aria-label="Large select example"
                                name="month">
                                <option value="11">November</option>
                                <option value="12">Desember</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <select class="form-select form-select-lg mb-3" aria-label="Large select example"
                            name="selectedCompany">
                            <option value="0">Semua Company</option>
                            <option value="1">Pabrik 1</option>
                            <option value="2">Pabrik 2</option>
                            <option value="3">Kantor</option>
                            <option value="4">ASB</option>
                            <option value="5">DPA</option>
                            <option value="6">YCME</option>
                            <option value="7">YEV</option>
                            <option value="8">YIG</option>
                            <option value="9">YSM</option>

                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">Excel</button>
                </form>

            </div>
        </div>
    </div>
@endsection
