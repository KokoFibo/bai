<div class="container py-4">
    hello
    @if ($metode_penggajian->count() > 0)
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <h5 class="card-title text-primary">Karyawan Tanpa Metode Penggajian</h5>
                <ul class="list-group list-group-flush">
                    @foreach ($metode_penggajian as $mp)
                        <li class="list-group-item">{{ $mp->id_karyawan }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif


    @if ($placement->count() > 0)
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <h5 class="card-title text-warning">Karyawan Tanpa Placement</h5>
                <ul class="list-group list-group-flush">
                    @foreach ($placement as $p)
                        <li class="list-group-item">{{ $p->id_karyawan }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @if ($company->count() > 0)
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <h5 class="card-title text-info">Karyawan Tanpa Company</h5>
                <ul class="list-group list-group-flush">
                    @foreach ($company as $c)
                        <li class="list-group-item">{{ $c->id_karyawan }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @if ($jabatan->count() > 0)
        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <h5 class="card-title text-danger">Karyawan Tanpa Jabatan</h5>
                <ul class="list-group list-group-flush">
                    @foreach ($jabatan as $j)
                        <li class="list-group-item">{{ $j->id_karyawan }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
</div>
