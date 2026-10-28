<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">

        {{-- Header --}}
        <div class="d-flex align-items-start justify-content-between mb-4 gap-3">
            <div>
                <h5 class="fw-semibold mb-1">Statistik Pendidikan Karyawan</h5>
                <p class="text-muted small mb-0">Karyawan aktif (PKWT & PKWTT)</p>
            </div>
            <button wire:click="loadData" wire:loading.attr="disabled"
                class="btn btn-light btn-sm rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                style="width: 36px; height: 36px;" title="Refresh data">
                <svg wire:loading.class="spin" wire:target="loadData" xmlns="http://www.w3.org/2000/svg" width="18"
                    height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            </button>
        </div>

        {{-- Summary Cards --}}
        <div class="row g-3 mb-4">
            <div class="col-6">
                <div class="p-3 rounded-3" style="background-color: #eaf2ff;">
                    <p class="text-uppercase text-primary small fw-medium mb-1"
                        style="font-size: .7rem; letter-spacing: .04em;">
                        Total Karyawan Aktif
                    </p>
                    <p class="fs-4 fw-bold text-primary mb-0">
                        {{ number_format($totalKaryawanAktif) }}
                    </p>
                </div>
            </div>
            <div class="col-6">
                <div class="p-3 rounded-3" style="background-color: #e8f9ee;">
                    <p class="text-uppercase text-success small fw-medium mb-1"
                        style="font-size: .7rem; letter-spacing: .04em;">
                        Sudah Isi Pendidikan
                    </p>
                    <p class="fs-4 fw-bold text-success mb-0">
                        {{ number_format($totalTerisi) }}
                        <span class="fs-6 fw-normal">
                            ({{ $totalKaryawanAktif > 0 ? round(($totalTerisi / $totalKaryawanAktif) * 100, 1) : 0 }}%)
                        </span>
                    </p>
                </div>
            </div>
        </div>

        @if ($totalTerisi === 0)
            {{-- Empty state --}}
            <div class="d-flex flex-column align-items-center justify-content-center text-center py-5">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40"
                    class="text-secondary opacity-50 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-muted small mb-0">
                    Belum ada data pendidikan karyawan yang terisi.
                </p>
            </div>
        @else
            {{-- Chart + Detail List --}}
            <div class="row g-4 align-items-center">

                {{-- Chart --}}
                <div class="col-12 col-lg-4 d-flex justify-content-center">
                    <div class="position-relative" style="width: 220px; height: 220px;" wire:ignore
                        x-data="pendidikanChart({{ json_encode($labels) }}, {{ json_encode($totals) }}, {{ json_encode($percentages) }})" x-init="renderChart()"
                        @refresh-chart.window="updateChart({{ json_encode($labels) }}, {{ json_encode($totals) }}, {{ json_encode($percentages) }})">
                        <canvas x-ref="canvas" width="220" height="220"></canvas>

                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column align-items-center justify-content-center"
                            style="pointer-events: none;">
                            <span class="fs-4 fw-bold">{{ number_format($totalTerisi) }}</span>
                            <span class="text-muted small">Terisi</span>
                        </div>
                    </div>
                </div>

                {{-- Detail List --}}
                <div class="col-12 col-lg-8">
                    <ul class="list-group list-group-flush">
                        @foreach ($labels as $i => $label)
                            <li class="list-group-item d-flex align-items-center justify-content-between px-0 py-2">
                                <div class="d-flex align-items-center gap-2 text-truncate">
                                    <span class="rounded-circle flex-shrink-0"
                                        style="width: 10px; height: 10px; background-color: {{ $this->getColor($i) }};"></span>
                                    <span class="small text-body text-truncate">{{ $label }}</span>
                                </div>

                                <div class="d-flex align-items-center gap-3 flex-shrink-0">
                                    <div class="d-none d-sm-block rounded-pill bg-light overflow-hidden"
                                        style="width: 80px; height: 6px;">
                                        <div class="h-100 rounded-pill"
                                            style="width: {{ $percentages[$i] }}%; background-color: {{ $this->getColor($i) }};">
                                        </div>
                                    </div>
                                    <span class="small text-muted text-end" style="width: 64px;">
                                        {{ $totals[$i] }} org
                                    </span>
                                    <span class="small fw-semibold text-end" style="width: 48px;">
                                        {{ $percentages[$i] }}%
                                    </span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    </div>
    <style>
        .spin {
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }
    </style>
</div>

@script
    <script>
        Alpine.data('pendidikanChart', (labels, totals, percentages) => ({
            chart: null,

            colors: [
                '#94a3b8', // Tidak Bersekolah - slate
                '#f97316', // SD - orange
                '#f59e0b', // SMP - amber
                '#eab308', // SMA/SMK - yellow
                '#84cc16', // D1 - lime
                '#22c55e', // D2 - green
                '#10b981', // D3 - emerald
                '#14b8a6', // D4 - teal
                '#06b6d4', // S1 - cyan
                '#3b82f6', // S2 - blue
                '#8b5cf6', // S3 - violet
            ],

            renderChart() {
                const ctx = this.$refs.canvas.getContext('2d');

                this.chart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: totals,
                            backgroundColor: this.colors,
                            borderWidth: 3,
                            borderColor: '#ffffff',
                            hoverOffset: 6,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '70%',
                        plugins: {
                            legend: {
                                display: false
                            }, // diwakili oleh list detail
                            tooltip: {
                                callbacks: {
                                    label: (context) => {
                                        const i = context.dataIndex;
                                        return `${labels[i]}: ${totals[i]} orang (${percentages[i]}%)`;
                                    }
                                }
                            }
                        },
                        animation: {
                            animateScale: true,
                            animateRotate: true,
                        }
                    }
                });
            },

            updateChart(newLabels, newTotals, newPercentages) {
                if (!this.chart) return;
                this.chart.data.labels = newLabels;
                this.chart.data.datasets[0].data = newTotals;
                this.chart.update();
            }
        }));
    </script>
@endscript
