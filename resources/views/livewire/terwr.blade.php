<div>

    <div class="col-8 mx-auto mt-5">
        <h1 class='text-center '>Table TER PPH21</h1>
        <div class="d-flex justify-content-between align-items-center">
            <div>
                @if (auth()->user()->role > 4)
                    <a href="getexcel"><button class="btn btn-primary my-2">Upload</button></a>
                @endif
                <button wire:click="filter('A')" class='btn btn-success mx-2 {{ $ter == 'A' ? 'disabled' : '' }}'>TER
                    A</button>
                <button wire:click="filter('B')" class='btn btn-success mx-2 {{ $ter == 'B' ? 'disabled' : '' }}'>TER
                    B</button>
                <button wire:click="filter('C')" class='btn btn-success mx-2 {{ $ter == 'C' ? 'disabled' : '' }}'>TER
                    C</button>
            </div>
            <div>
                <a href="/payroll"><button class='btn btn-dark mx-2'>Back to Payroll</button></a>
            </div>
        </div>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th style="text-align: center">TER</th>
                    <th style="text-align: right">Dari</th>
                    <th style="text-align: right">Sampai</th>
                    <th style="text-align: right">Rate</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $d)
                    <tr>
                        <td style="text-align: center">{{ $d->ter }}</td>
                        <td style="text-align: right">{{ number_format($d->from) }}</td>
                        <td style="text-align: right">{{ number_format($d->to) }}</td>
                        <td style="text-align: right">{{ number_format($d->rate, 2) }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
