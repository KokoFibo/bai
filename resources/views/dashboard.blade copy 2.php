@extends('layouts.app4')

@section('title', 'Dashboard')

{{-- Charts --}}
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.0.0-rc.1/chartjs-plugin-datalabels.min.js"
        integrity="sha512-+UYTD5L/bU1sgAfWA0ELK5RlQ811q8wZIocqI7+K0Lhh8yVdIoAMEs96wJAIbgFvzynPm36ZCXtkydxu1cs27w=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>



    {{-- Company --}}
    <script>
        var companyArr = <?php echo json_encode($companyArr); ?>;
        var companyLabelArr = <?php echo json_encode($companyLabelArr); ?>;

        const ctx1 = document.getElementById('chart_company');

        new Chart(ctx1, {
            type: 'pie',
            data: {
                {{-- labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'], --}}
                labels: companyLabelArr,
                datasets: [{
                    label: 'Jumlah Karyawan ',
                    data: companyArr,

                    datalabels: {

                        anchor: 'center',
                        display: true,
                        align: 'center',

                    },

                }]
            },
            plugins: [ChartDataLabels],
            options: {
                layout: {
                    padding: 20
                },
                plugins: {
                    legend: {
                        display: true
                    },
                    datalabels: {
                        color: 'white',

                        formatter: function(value, context) {
                            {{-- return context.chart.data.labels[context.dataIndex] + ' : ' + context.chart.data
                            .datasets[0].data[context.dataIndex] --}}
                            return context.chart.data
                                .datasets[0].data[context.dataIndex]
                        }
                    },

                },

            },



        });
    </script>

    {{-- Jumlah karyawan pria wanita --}}
    <script>
        var jumlah_karyawanArr = <?php echo json_encode($jumlah_karyawanArr); ?>;
        var jumlah_karyawan_labelArr = <?php echo json_encode($jumlah_karyawan_labelArr); ?>;

        const ctx3 = document.getElementById('jumlah_karyawan');

        new Chart(ctx3, {
            type: 'pie',
            data: {
                {{-- labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'], --}}
                labels: jumlah_karyawan_labelArr,
                datasets: [{
                    label: 'Jumlah Karyawan ',
                    data: jumlah_karyawanArr,
                    borderWidth: 1,

                    {{-- datalabels: {
                    color: 'white',
                },
                formatter: function(value, ctx3) {
                    return context.chart.data.label[ctx.dataIndex];
                } --}}
                }]
            },
            plugins: [ChartDataLabels],
            options: {
                layout: {
                    padding: 20
                },

                plugins: {
                    legend: {
                        display: true
                    },
                    datalabels: {
                        color: 'white',

                        formatter: function(value, context) {
                            {{-- return context.chart.data.labels[context.dataIndex] + ' : ' + context.chart.data
                            .datasets[0].data[context.dataIndex] --}}
                            return context.chart.data
                                .datasets[0].data[context.dataIndex]
                        }
                    },
                },
            },
        });
    </script>

    {{-- Kehadiran --}}
    <script>
        var dataCountLatestHadir = <?php echo json_encode($dataCountLatestHadir); ?>;
        // var jumlah_karyawan_labelArr = <?php echo json_encode($jumlah_karyawan_labelArr); ?>;

        const ctx4 = document.getElementById('latestKehadiran');

        new Chart(ctx4, {
            type: 'pie',
            data: {
                {{-- labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'], --}}
                labels: ['Hadir 出勤', 'Absen 缺勤'],
                datasets: [{
                    label: 'Jumlah Karyawan ',
                    data: dataCountLatestHadir,
                    // data: ['1000', '2000'],
                    borderWidth: 1,


                }]
            },
            plugins: [ChartDataLabels],
            options: {
                layout: {
                    padding: 20
                },

                plugins: {
                    legend: {
                        display: true
                    },
                    datalabels: {
                        color: 'white',

                        formatter: function(value, context) {
                            {{-- return context.chart.data.labels[context.dataIndex] + ' : ' + context.chart.data
                            .datasets[0].data[context.dataIndex] --}}
                            return context.chart.data
                                .datasets[0].data[context.dataIndex]
                        }
                    },
                },
            },
        });
    </script>

    {{-- shiftPagiMalam --}}
    <script>
        var shiftPagiMalam = <?php echo json_encode($shiftPagiMalam); ?>;
        // var jumlah_karyawan_labelArr = <?php echo json_encode($jumlah_karyawan_labelArr); ?>;

        const ctx13 = document.getElementById('shiftPagiMalam');

        new Chart(ctx13, {
            type: 'pie',
            data: {
                {{-- labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'], --}}
                labels: ['Shift Pagi 早班', 'Shift Malam 夜班'],
                datasets: [{
                    label: 'Jumlah Karyawan ',
                    data: shiftPagiMalam,
                    // data: ['700', '300'],
                    borderWidth: 1,


                }]
            },
            plugins: [ChartDataLabels],
            options: {
                layout: {
                    padding: 20
                },

                plugins: {
                    legend: {
                        display: true
                    },
                    datalabels: {
                        color: 'white',

                        formatter: function(value, context) {
                            {{-- return context.chart.data.labels[context.dataIndex] + ' : ' + context.chart.data
                            .datasets[0].data[context.dataIndex] --}}
                            return context.chart.data
                                .datasets[0].data[context.dataIndex]
                        }
                    },
                },
            },
        });
    </script>

    {{-- Rata-rata 7 hari --}}
    <script>
        var average7Hari = <?php echo json_encode($average7Hari); ?>;
        // var jumlah_karyawan_labelArr = <?php echo json_encode($jumlah_karyawan_labelArr); ?>;

        const ctx5 = document.getElementById('rataRata7Hari');

        new Chart(ctx5, {
            type: 'pie',
            data: {
                {{-- labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'], --}}
                labels: ['Hadir 出勤', 'Absen 缺勤'],
                datasets: [{
                    label: 'Rata-rata 7 hari ',
                    data: average7Hari,
                    // data: ['1000', '2000'],
                    borderWidth: 1,


                }]
            },
            plugins: [ChartDataLabels],
            options: {
                layout: {
                    padding: 20
                },

                plugins: {
                    legend: {
                        display: true
                    },
                    datalabels: {
                        color: 'white',

                        formatter: function(value, context) {
                            {{-- return context.chart.data.labels[context.dataIndex] + ' : ' + context.chart.data
                            .datasets[0].data[context.dataIndex] --}}
                            return context.chart.data
                                .datasets[0].data[context.dataIndex]
                        }
                    },
                },
            },
        });
    </script>

    {{-- Rata-rata 30 hari --}}
    <script>
        var average30Hari = <?php echo json_encode($average30Hari); ?>;
        // var jumlah_karyawan_labelArr = <?php echo json_encode($jumlah_karyawan_labelArr); ?>;

        const ctx6 = document.getElementById('rataRata30Hari');

        new Chart(ctx6, {
            type: 'pie',
            data: {
                {{-- labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'], --}}
                labels: ['Hadir 出勤', 'Absen 缺勤'],
                datasets: [{
                    label: 'Rata-rata 30 hari ',
                    data: average30Hari,
                    // data: ['1000', '2000'],
                    borderWidth: 1,


                }]
            },
            plugins: [ChartDataLabels],
            options: {
                layout: {
                    padding: 20
                },

                plugins: {
                    legend: {
                        display: true
                    },
                    datalabels: {
                        color: 'white',

                        formatter: function(value, context) {
                            {{-- return context.chart.data.labels[context.dataIndex] + ' : ' + context.chart.data
                            .datasets[0].data[context.dataIndex] --}}
                            return context.chart.data
                                .datasets[0].data[context.dataIndex]
                        }
                    },
                },
            },
        });
    </script>
    {{-- Presensi by Departement --}}
    <script>
        var presensi_by_departement_Arr = <?php echo json_encode($presensi_by_departement_Arr); ?>;
        var presensi_by_departement_LabelArr = <?php echo json_encode($presensi_by_departement_LabelArr); ?>;

        const ctxPbD = document.getElementById('presensiByDepartment');

        new Chart(ctxPbD, {
            type: 'pie',
            data: {
                {{-- labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'], --}}
                labels: presensi_by_departement_LabelArr,
                datasets: [{
                    label: 'Total Hadir',
                    data: presensi_by_departement_Arr,
                    // data: ['1000', '2000'],
                    borderWidth: 1,


                }]
            },
            plugins: [ChartDataLabels],
            options: {
                layout: {
                    padding: 20
                },

                plugins: {
                    legend: {
                        display: true
                    },
                    datalabels: {
                        color: 'white',

                        formatter: function(value, context) {
                            {{-- return context.chart.data.labels[context.dataIndex] + ' : ' + context.chart.data
                            .datasets[0].data[context.dataIndex] --}}
                            return context.chart.data
                                .datasets[0].data[context.dataIndex]
                        }
                    },
                },
            },
        });
    </script>

    {{-- Barchart Payroll All --}}
    <script>
        var dataAll = <?php echo json_encode($dataAll); ?>;
        var dataTgl = <?php echo json_encode($dataTgl); ?>;

        const ctx7 = document.getElementById('payrollAll');

        new Chart(ctx7, {
            type: 'bar',
            data: {
                {{-- labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'], --}}
                labels: dataTgl,
                datasets: [{
                    label: 'Payroll All ',
                    data: dataAll,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 205, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(201, 203, 207, 0.2)'
                    ],
                    borderColor: [
                        'rgb(255, 99, 132)',
                        'rgb(255, 159, 64)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(54, 162, 235)',
                        'rgb(153, 102, 255)',
                        'rgb(201, 203, 207)'
                    ],
                    borderWidth: 1,
                }]
            },

            borderWidth: 1
        });
    </script>

    {{-- Barchart Payroll payrollASB --}}
    <script>
        var dataASB = <?php echo json_encode($dataASB); ?>;
        var dataTgl = <?php echo json_encode($dataTgl); ?>;

        const ctx8 = document.getElementById('payrollASB');

        new Chart(ctx8, {
            type: 'bar',
            data: {
                {{-- labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'], --}}
                labels: dataTgl,
                datasets: [{
                    label: 'Payroll ASB ',
                    data: dataASB,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 205, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(201, 203, 207, 0.2)'
                    ],
                    borderColor: [
                        'rgb(255, 99, 132)',
                        'rgb(255, 159, 64)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(54, 162, 235)',
                        'rgb(153, 102, 255)',
                        'rgb(201, 203, 207)'
                    ],
                    borderWidth: 1,
                }]
            },

            borderWidth: 1
        });
    </script>

    {{-- Barchart Payroll payrollDPA --}}
    <script>
        var dataDPA = <?php echo json_encode($dataDPA); ?>;
        var dataTgl = <?php echo json_encode($dataTgl); ?>;

        const ctx9 = document.getElementById('payrollDPA');

        new Chart(ctx9, {
            type: 'bar',
            data: {
                {{-- labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'], --}}
                labels: dataTgl,
                datasets: [{
                    label: 'Payroll DPA ',
                    data: dataDPA,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 205, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(201, 203, 207, 0.2)'
                    ],
                    borderColor: [
                        'rgb(255, 99, 132)',
                        'rgb(255, 159, 64)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(54, 162, 235)',
                        'rgb(153, 102, 255)',
                        'rgb(201, 203, 207)'
                    ],
                    borderWidth: 1,
                }]
            },

            borderWidth: 1
        });
    </script>

    {{-- Barchart Payroll payrollYCME --}}
    <script>
        var dataYCME = <?php echo json_encode($dataYCME); ?>;
        var dataTgl = <?php echo json_encode($dataTgl); ?>;

        const ctx10 = document.getElementById('payrollYCME');

        new Chart(ctx10, {
            type: 'bar',
            data: {
                {{-- labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'], --}}
                labels: dataTgl,
                datasets: [{
                    label: 'Payroll YCME ',
                    data: dataYCME,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 205, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(201, 203, 207, 0.2)'
                    ],
                    borderColor: [
                        'rgb(255, 99, 132)',
                        'rgb(255, 159, 64)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(54, 162, 235)',
                        'rgb(153, 102, 255)',
                        'rgb(201, 203, 207)'
                    ],
                    borderWidth: 1,
                }]
            },

            borderWidth: 1
        });
    </script>

    {{-- Barchart Payroll payrollYIG --}}
    <script>
        var dataYIG = <?php echo json_encode($dataYIG); ?>;
        var dataTgl = <?php echo json_encode($dataTgl); ?>;

        const ctx11 = document.getElementById('payrollYIG');

        new Chart(ctx11, {
            type: 'bar',
            data: {
                {{-- labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'], --}}
                labels: dataTgl,
                datasets: [{
                    label: 'Payroll YIG ',
                    data: dataYIG,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 205, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(201, 203, 207, 0.2)'
                    ],
                    borderColor: [
                        'rgb(255, 99, 132)',
                        'rgb(255, 159, 64)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(54, 162, 235)',
                        'rgb(153, 102, 255)',
                        'rgb(201, 203, 207)'
                    ],
                    borderWidth: 1,
                }]
            },

            borderWidth: 1
        });
    </script>
    {{-- Barchart Payroll payrollYSM --}}
    <script>
        var dataYSM = <?php echo json_encode($dataYSM); ?>;
        var dataTgl = <?php echo json_encode($dataTgl); ?>;

        const ctx12 = document.getElementById('payrollYSM');

        new Chart(ctx12, {
            type: 'bar',
            data: {
                {{-- labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'], --}}
                labels: dataTgl,
                datasets: [{
                    label: 'Payroll YSM ',
                    data: dataYSM,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 205, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(201, 203, 207, 0.2)'
                    ],
                    borderColor: [
                        'rgb(255, 99, 132)',
                        'rgb(255, 159, 64)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(54, 162, 235)',
                        'rgb(153, 102, 255)',
                        'rgb(201, 203, 207)'
                    ],
                    borderWidth: 1,
                }]
            },

            borderWidth: 1
        });
    </script>
    {{-- Barchart Payroll payrolYEV --}}
    <script>
        var dataYEV = <?php echo json_encode($dataYEV); ?>;
        var dataTgl = <?php echo json_encode($dataTgl); ?>;

        const ctx50 = document.getElementById('payrollYEV');

        new Chart(ctx50, {
            type: 'bar',
            data: {
                {{-- labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'], --}}
                labels: dataTgl,
                datasets: [{
                    label: 'Payroll YEV ',
                    data: dataYEV,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 205, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(201, 203, 207, 0.2)'
                    ],
                    borderColor: [
                        'rgb(255, 99, 132)',
                        'rgb(255, 159, 64)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(54, 162, 235)',
                        'rgb(153, 102, 255)',
                        'rgb(201, 203, 207)'
                    ],
                    borderWidth: 1,
                }]
            },

            borderWidth: 1
        });
    </script>

    {{-- Barchart Payroll payrolYAM --}}
    <script>
        var dataYAM = <?php echo json_encode($dataYAM); ?>;
        var dataTgl = <?php echo json_encode($dataTgl); ?>;

        const ctx51 = document.getElementById('payrollYAM');

        new Chart(ctx51, {
            type: 'bar',
            data: {
                {{-- labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'], --}}
                labels: dataTgl,
                datasets: [{
                    label: 'Payroll YAM ',
                    data: dataYAM,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 205, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(201, 203, 207, 0.2)'
                    ],
                    borderColor: [
                        'rgb(255, 99, 132)',
                        'rgb(255, 159, 64)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(54, 162, 235)',
                        'rgb(153, 102, 255)',
                        'rgb(201, 203, 207)'
                    ],
                    borderWidth: 1,
                }]
            },

            borderWidth: 1
        });
    </script>
    {{-- Barchart Payroll payrolGAMA --}}
    <script>
        var dataGAMA = <?php echo json_encode($dataGAMA); ?>;
        var dataTgl = <?php echo json_encode($dataTgl); ?>;

        const ctx52 = document.getElementById('payrollGAMA');

        new Chart(ctx52, {
            type: 'bar',
            data: {
                {{-- labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'], --}}
                labels: dataTgl,
                datasets: [{
                    label: 'Payroll GAMA ',
                    data: dataGAMA,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 205, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(201, 203, 207, 0.2)'
                    ],
                    borderColor: [
                        'rgb(255, 99, 132)',
                        'rgb(255, 159, 64)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(54, 162, 235)',
                        'rgb(153, 102, 255)',
                        'rgb(201, 203, 207)'
                    ],
                    borderWidth: 1,
                }]
            },

            borderWidth: 1
        });
    </script>
    {{-- Barchart Payroll payrolWAS --}}
    <script>
        var dataWAS = <?php echo json_encode($dataWAS); ?>;
        var dataTgl = <?php echo json_encode($dataTgl); ?>;

        const ctx53 = document.getElementById('payrollWAS');

        new Chart(ctx53, {
            type: 'bar',
            data: {
                {{-- labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'], --}}
                labels: dataTgl,
                datasets: [{
                    label: 'Payroll WAS ',
                    data: dataWAS,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 205, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(201, 203, 207, 0.2)'
                    ],
                    borderColor: [
                        'rgb(255, 99, 132)',
                        'rgb(255, 159, 64)',
                        'rgb(255, 205, 86)',
                        'rgb(75, 192, 192)',
                        'rgb(54, 162, 235)',
                        'rgb(153, 102, 255)',
                        'rgb(201, 203, 207)'
                    ],
                    borderWidth: 1,
                }]
            },

            borderWidth: 1
        });
    </script>


@endsection

@section('content')
    <h1 class="pt-3  text-xl lg:text-4xl text-center font-semibold">{{ __('Dashboard') }}</h1>
    <div>
    </div>



    {{-- Dashboard device = {{ isDesktop() }} --}}
    <div id="root">
        @if (auth()->user()->role == 5)
            <div class="container">
                <button class="bg-blue-500 text-white px-3 py-2 rounded-md shadow-sm">Tanpa Etnis :
                    {{ $belum_isi_etnis }}</button>
                <button class="bg-violet-500 text-white px-3 py-2 rounded-md shadow-sm">Tanpa Kontak Darurat :
                    {{ $belum_isi_kontak_darurat }}</button>
            </div>
        @endif
        <div class="container pt-5">
            <div class="row align-items-stretch">
                <div class="c-dashboardInfo col-lg-3 col-md-6">
                    <div class="wrap">
                        <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title">
                            {{ __('Karyawan Baru MTD') }}
                        </h4><span class="hind-font caption-12 c-dashboardInfo__count">{{ $karyawan_baru_mtd }}</span>

                    </div>
                </div>
                <div class="c-dashboardInfo col-lg-3 col-md-6">
                    <div class="wrap">
                        <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title">
                            {{ __('Karyawan Resigned MTD') }}</h4>
                        <span class="hind-font caption-12 c-dashboardInfo__count">{{ $karyawan_resigned_mtd }}</span>
                        {{-- <span class="hind-font caption-12 c-dashboardInfo__subInfo">Last month: €30</span> --}}
                    </div>
                </div>
                <div class="c-dashboardInfo col-lg-3 col-md-6">
                    <div class="wrap">
                        <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title text-center">
                            {{ __('Karyawan Blacklist MTD') }}</h4><span
                            class="hind-font caption-12 c-dashboardInfo__count">{{ $karyawan_blacklist_mtd }}</span>
                    </div>
                </div>
                <div class="c-dashboardInfo col-lg-3 col-md-6">
                    <div class="wrap">
                        <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title">
                            {{ __('Karyawan Aktif MTD') }}
                        </h4><span
                            class="hind-font caption-12 c-dashboardInfo__count">{{ number_format($karyawan_aktif_mtd) }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="container pt-5">
            <div class="row align-items-stretch">
                <div class="c-dashboardInfo col-lg-4 col-md-6">
                    <div class="wrap">
                        <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title">
                            {{ __('Karyawan Baru Hari Ini') }}
                        </h4><span
                            class="hind-font caption-12 c-dashboardInfo__count">{{ $jumlah_karyawan_baru_hari_ini }}</span>
                    </div>
                </div>
                <div class="c-dashboardInfo col-lg-4 col-md-6">
                    <div class="wrap">
                        <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title">
                            {{ __('Karyawan Resigned Hari Ini') }}</h4>
                        <span
                            class="hind-font caption-12 c-dashboardInfo__count">{{ $jumlah_karyawan_Resigned_hari_ini }}</span>
                        {{-- <span class="hind-font caption-12 c-dashboardInfo__subInfo">Last month: €30</span> --}}
                    </div>
                </div>
                <div class="c-dashboardInfo col-lg-4 col-md-6">
                    <div class="wrap">
                        <h4 class="heading heading5 hind-font medium-font-weight c-dashboardInfo__title text-center">
                            {{ __('Karyawan Blacklist Hari Ini') }}</h4><span
                            class="hind-font caption-12 c-dashboardInfo__count">{{ $jumlah_karyawan_blacklist_hari_ini }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 lg:gap-3 px-2 flex-column flex-xl-row justify-evenly mt-3 lg:mb-5">
        <div>
            <div class="h-3 rounded-t-lg w-full lg:w-96 bg-violet-500">
            </div>
            <div class="bg-violet-100 w-full lg:w-96  rounded-b-lg shadow p-3  ">
                <p class="text-center text-lg text-gray-700 mt-2">{{ __('Presensi') }} {{ format_tgl($latestDate->date) }}
                </p>
                <div style="width:350px;">
                    <canvas id="latestKehadiran"></canvas>
                    </canvas>
                </div>
            </div>
        </div>
        <div>
            <div class="h-3 rounded-t-lg w-full lg:w-96 bg-green-500">
            </div>
            <div class="bg-green-100 w-full lg:w-96  rounded-b-lg shadow p-3  ">
                <p class="text-center text-lg text-gray-700 mt-2">{{ __('Rata Rata 7 Hari') }}</p>
                <div style="width:350px;">
                    <canvas id="rataRata7Hari"></canvas>
                    </canvas>
                </div>
            </div>
        </div>
        <div>
            <div class="h-3 rounded-t-lg w-full lg:w-96 bg-blue-500">
            </div>
            <div class="bg-blue-100 w-full lg:w-96  rounded-b-lg shadow p-3  ">
                <p class="text-center text-lg text-gray-700 mt-2">{{ __('Rata Rata 30 Hari') }}</p>
                <div style="width:350px;">
                    <canvas id="rataRata30Hari"></canvas>
                    </canvas>
                </div>
            </div>
        </div>

    </div>
    {{-- Presensi by Department --}}
    <div
        class="flex px-2 mt-2 flex-col flex-xl-row lg:items-center justify-evenly  bg-purple-100  col-xl-10 mx-auto rounded-xl shadow  ">
        <div class="text-lg">
            <div class="rounded-t-lg bg-purple-500 w-full lg:w-96 ">
            </div>
            <div class="bg-purple-200  rounded-b-lg w-full lg:w-96 shadow-md p-3">

                <p class="text-center  mb-3">{{ __('Presensi by Department') }}
                    <br class="text-center  mb-3">{{ format_tgl($latestDate->date) }}
                </p>

                <div class="flex  justify-evenly">
                    <div class="flex flex-column ">
                        <h2 class="text-center   text-gray-600 text-base">{{ __('BD') }}</h2>
                        <h2 class="text-center   text-gray-600 text-base">{{ __('Engineering') }}</h2>
                        <h2 class="text-center   text-gray-600 text-base">{{ __('EXIM') }}</h2>
                        <h2 class="text-center   text-gray-600 text-base">{{ __('Finance Accounting') }}</h2>
                        <h2 class="text-center   text-gray-600 text-base">{{ __('GA') }}</h2>
                        <h2 class="text-center   text-gray-600 text-base">{{ __('Gudang') }}</h2>
                        <h2 class="text-center   text-gray-600 text-base">{{ __('HR') }}</h2>
                        <h2 class="text-center   text-gray-600 text-base">{{ __('Legal') }}</h2>
                        <h2 class="text-center   text-gray-600 text-base">{{ __('Procurement') }}</h2>
                        <h2 class="text-center   text-gray-600 text-base">{{ __('Produksi') }}</h2>
                        <h2 class="text-center   text-gray-600 text-base">{{ __('Quality Control') }}</h2>
                        <h2 class="text-center font-semibold  text-gray-600 text-lg ">{{ __('Total') }}</h2>
                    </div>

                    <div class="flex flex-column">
                        <h2 class="text-right  font-semibold text-gray-600 text-base">{{ number_format($bd) }}</h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-base">{{ number_format($engineering) }}
                        </h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-base">{{ number_format($exim) }}
                        </h2>

                        <h2 class="text-right  font-semibold text-gray-600 text-base">
                            {{ number_format($finance_accounting) }}
                        </h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-base">{{ number_format($ga) }}
                        </h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-base">{{ number_format($gudang) }}
                        </h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-base">{{ number_format($hr) }}
                        </h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-base">{{ number_format($legal) }}
                        </h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-base">{{ number_format($procurement) }}
                        </h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-base ">{{ number_format($produksi) }}
                        </h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-base ">
                            {{ number_format($quality_control) }}
                        </h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-lg">
                            {{ number_format($total_presensi_by_departemen) }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts --}}


        <div class="w-full xl:w-1/3  mt-2">

            <canvas id="presensiByDepartment"></canvas>
        </div>

    </div>

    {{-- Jumlah karyawan Pria wanita --}}
    <div class="d-flex gap-2 lg:gap-3 px-2 flex-column flex-xl-row justify-evenly mt-3 lg:mb-5">
        <div>
            <div class="h-3 rounded-t-lg w-full lg:w-96 bg-teal-500">
            </div>
            <div class="bg-teal-100 w-full lg:w-96  rounded-b-lg shadow p-3  ">
                <p class="text-center text-lg mb-3 ">{{ __('Jumlah Karyawan') }}</p>
                <h1 class="text-center font-semibold text-xl">{{ number_format($jumlah_total_karyawan) }}</h1>
                <div style="width:350px;">
                    <canvas id="jumlah_karyawan">
                    </canvas>
                </div>
            </div>
        </div>
        <div>
            <div class="h-3 rounded-t-lg w-full lg:w-96 bg-pink-500">
            </div>
            <div class="bg-pink-100 w-full lg:w-96  rounded-b-lg shadow p-3  ">
                <p class="text-center text-lg mb-3 ">{{ __('Shift Pagi & Shift Malam') }}</p>
                <h1 class="text-center font-semibold text-xl">{{ __('Month to Date') }}</h1>
                <div style="width:350px;">
                    <canvas id="shiftPagiMalam">
                    </canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex gap-2 lg:gap-3 px-2 flex-column flex-xl-row justify-evenly mt-3 lg:mb-5">

        {{-- Departement --}}
        <div>
            <div class="h-3 rounded-t-lg  w-full lg:w-96 bg-green-500">
            </div>
            <div class="bg-green-200 w-full lg:w-96  rounded-b-lg shadow p-3  ">
                <p class="text-center text-lg mb-3 ">{{ __('Department') }}</p>
                <div class="flex gap-3 justify-evenly">
                    <div class="flex flex-column ">
                        <h2 class="text-center   text-gray-600 text-base">{{ __('BD') }}</h2>
                        <h2 class="text-center   text-gray-600 text-base">{{ __('Engineering') }}</h2>
                        <h2 class="text-center   text-gray-600 text-base">{{ __('EXIM') }}</h2>
                        <h2 class="text-center   text-gray-600 text-base">{{ __('Finance Accounting') }}</h2>
                        <h2 class="text-center   text-gray-600 text-base">{{ __('GA') }}</h2>
                        <h2 class="text-center   text-gray-600 text-base">{{ __('Gudang') }}</h2>
                        <h2 class="text-center   text-gray-600 text-base">{{ __('HR') }}</h2>
                        <h2 class="text-center   text-gray-600 text-base">{{ __('Legal') }}</h2>
                        <h2 class="text-center   text-gray-600 text-base">{{ __('Procurement') }}</h2>
                        <h2 class="text-center   text-gray-600 text-base">{{ __('Produksi') }}</h2>
                        <h2 class="text-center   text-gray-600 text-base">{{ __('Quality Control') }}</h2>
                        <h2 class="text-center   text-gray-600 text-base">{{ __('Board of Director') }}</h2>


                    </div>
                    <div class="flex flex-column">
                        <h2 class="text-right  font-semibold text-gray-600 text-base">{{ number_format($department_BD) }}
                        </h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-base">
                            {{ number_format($department_Engineering) }}
                        </h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-base">
                            {{ number_format($department_EXIM) }}</h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-base">
                            {{ number_format($department_Finance_Accounting) }}</h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-base">{{ number_format($department_GA) }}
                        </h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-base">
                            {{ number_format($department_Gudang) }}
                        </h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-base">{{ number_format($department_HR) }}
                        </h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-base">
                            {{ number_format($department_Legal) }}
                        </h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-base">
                            {{ number_format($department_Procurement) }}
                        </h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-base">
                            {{ number_format($department_Produksi) }}
                        </h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-base">
                            {{ number_format($department_Quality_Control) }}</h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-base">
                            {{ number_format($department_Board_of_Director) }}</h2>
                    </div>
                </div>
            </div>
        </div>
        {{-- Jabatan --}}
        <div>
            <div class="h-3 rounded-t-lg w-full lg:w-96 bg-red-500">
            </div>

            <div class="bg-red-200 w-full lg:w-96 h-96 shadow p-3 rounded-b-lg overflow-y-auto ">
                <p class="text-center text-lg mb-3">{{ __('Jabatan') }}</p>
                <div class="flex gap-3 justify-evenly">
                    <div class="flex flex-column">
                        <h2 class="text-center   text-gray-600 text-base">{{ __('Admin') }}</h2>
                        <h2 class="text-center   text-gray-600 text-base">{{ __('Asisten Direktur') }}</h2>
                        <h2 class="text-center   text-gray-600 text-base">{{ __('Asisten Kepala') }}</h2>
                        <h2 class="text-center   text-gray-600 text-base">{{ __('Asisten Manager') }}</h2>
                        <h2 class="text-center   text-gray-600 text-base">{{ __('Asisten Pengawas') }}</h2>
                        <h2 class="text-center   text-gray-600 text-base">{{ __('Asisten Wakil_Presiden') }}</h2>
                        <h2 class="text-center   text-gray-600 text-base">{{ __('Design grafis') }}</h2>
                        <h2 class="text-center   text-gray-600 text-base">{{ __('Director') }}</h2>
                        <h2 class="text-center   text-gray-600 text-base">{{ __('Kepala') }}</h2>
                        <h2 class="text-center   text-gray-600 text-base">{{ __('Manager') }}</h2>
                        <h2 class="text-center   text-gray-600 text-base">{{ __('Pengawas') }}</h2>
                        <h2 class="text-center   text-gray-600 text-base">{{ __('President') }}</h2>
                        <h2 class="text-center   text-gray-600 text-base">{{ __('Senior staff') }}</h2>
                        <h2 class="text-center   text-gray-600 text-base">{{ __('Staff') }}</h2>
                        <h2 class="text-center   text-gray-600 text-base">{{ __('Supervisor') }}</h2>
                        <h2 class="text-center   text-gray-600 text-base">{{ __('Vice President') }}</h2>
                        <h2 class="text-center   text-gray-600 text-base">{{ __('Satpam') }}</h2>
                        <h2 class="text-center   text-gray-600 text-base">{{ __('Koki') }}</h2>
                        <h2 class="text-center   text-gray-600 text-base">{{ __('Dapur Kantor') }}</h2>
                        <h2 class="text-center   text-gray-600 text-base">{{ __('Dapur Pabrik') }}</h2>
                        <h2 class="text-center   text-gray-600 text-base">{{ __('QC Aging') }}</h2>
                        <h2 class="text-center   text-gray-600 text-base">{{ __('Driver') }}</h2>

                    </div>
                    <div class="flex flex-column">
                        <h2 class="text-right  font-semibold text-gray-600 text-base">{{ number_format($jabatan_Admin) }}
                        </h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-base">
                            {{ number_format($jabatan_Asisten_Direktur) }}
                        </h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-base">
                            {{ number_format($jabatan_Asisten_Kepala) }}
                        </h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-base">
                            {{ number_format($jabatan_Asisten_Manager) }}
                        </h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-base">
                            {{ number_format($jabatan_Asisten_Pengawas) }}
                        </h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-base">
                            {{ number_format($jabatan_Asisten_Wakil_Presiden) }}
                        </h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-base">
                            {{ number_format($jabatan_Design_grafis) }}
                        </h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-base">
                            {{ number_format($jabatan_Director) }}</h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-base">
                            {{ number_format($jabatan_Kepala) }}</h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-base">
                            {{ number_format($jabatan_Manager) }}</h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-base">
                            {{ number_format($jabatan_Pengawas) }}</h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-base">
                            {{ number_format($jabatan_President) }}</h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-base">
                            {{ number_format($jabatan_Senior_staff) }}
                        </h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-base">{{ number_format($jabatan_Staff) }}
                        </h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-base">
                            {{ number_format($jabatan_Supervisor) }}
                        </h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-base">
                            {{ number_format($jabatan_Vice_President) }}
                        </h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-base">
                            {{ number_format($jabatan_Satpam) }}</h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-base">{{ number_format($jabatan_Koki) }}
                        </h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-base">
                            {{ number_format($jabatan_Dapur_Kantor) }}
                        </h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-base">
                            {{ number_format($jabatan_Dapur_Pabrik) }}
                        </h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-base">
                            {{ number_format($jabatan_QC_Aging) }}</h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-base">
                            {{ number_format($jabatan_Driver) }}</h2>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div
        class="flex px-2 mt-2 flex-col flex-xl-row lg:items-center justify-evenly  bg-blue-100  col-xl-10 mx-auto rounded-xl shadow  ">
        <div>
            <div class="h-3 rounded-t-lg bg-blue-500 w-full lg:w-96">
            </div>
            <div class="bg-blue-200  rounded-b-lg w-full lg:w-96 shadow-md p-3">
                <p class="text-center text-lg mb-3">{{ __('Jumlah Karyawan') }}</p>
                <div class="flex gap-3 justify-evenly">
                    <div class="flex flex-column">

                        <h2 class="text-center   text-gray-600 text-base">ASB</h2>
                        <h2 class="text-center   text-gray-600 text-base">DPA</h2>
                        <h2 class="text-center   text-gray-600 text-base">YCME</h2>
                        <h2 class="text-center   text-gray-600 text-base">YEV</h2>
                        <h2 class="text-center   text-gray-600 text-base">YIG</h2>
                        <h2 class="text-center   text-gray-600 text-base">YSM</h2>
                        <h2 class="text-center   text-gray-600 text-base">YAM</h2>
                        <h2 class="text-center   text-gray-600 text-base">GAMA</h2>
                        <h2 class="text-center   text-gray-600 text-base">WAS</h2>
                        <h2 class="text-center font-semibold  text-gray-600 text-lg">{{ __('Total') }}</h2>
                    </div>
                    <div class="flex flex-column">

                        <h2 class="text-right  font-semibold text-gray-600 text-base">{{ number_format($jumlah_ASB) }}
                        </h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-base">{{ number_format($jumlah_DPA) }}
                        </h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-base">{{ number_format($jumlah_YCME) }}
                        </h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-base">{{ number_format($jumlah_YEV) }}
                        </h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-base">{{ number_format($jumlah_YIG) }}
                        </h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-base">{{ number_format($jumlah_YSM) }}
                        </h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-base">{{ number_format($jumlah_YAM) }}
                        </h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-base">{{ number_format($jumlah_GAMA) }}
                        </h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-base">{{ number_format($jumlah_WAS) }}
                        </h2>
                        <h2 class="text-right  font-semibold text-gray-600 text-lg">{{ number_format($jumlah_company) }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts --}}



        <div class="w-full xl:w-1/3  mt-2">

            <canvas id="chart_company"></canvas>
        </div>

    </div>




    @if (auth()->user()->role >= 4 || auth()->user()->role == 0)
        <div class="w-full px-2 lg:w-5/6 mx-auto">
            <div class="relative overflow-x-auto pb-2 mt-3">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-red-100 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-center"></th>
                            <th scope="col" class="px-6 py-3 text-center">All</th>
                            <th scope="col" class="px-6 py-3 text-center">ASB</th>
                            <th scope="col" class="px-6 py-3 text-center">DPA</th>
                            <th scope="col" class="px-6 py-3 text-center">YCME</th>
                            <th scope="col" class="px-6 py-3 text-center">YEV</th>
                            <th scope="col" class="px-6 py-3 text-center">YIG</th>
                            <th scope="col" class="px-6 py-3 text-center">YSM</th>
                            <th scope="col" class="px-6 py-3 text-center">YAM</th>
                            <th scope="col" class="px-6 py-3 text-center">GAMA</th>
                            <th scope="col" class="px-6 py-3 text-center">WAS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dataPayroll as $dp)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap ">
                                    {{ $dp['tgl'] }}</th>
                                <td class="px-6 py-4 text-right">{{ number_format($dp['All']) }}</td>
                                <td class="px-6 py-4 text-right">{{ number_format($dp['ASB']) }}</td>
                                <td class="px-6 py-4 text-right">{{ number_format($dp['DPA']) }}</td>
                                <td class="px-6 py-4 text-right">{{ number_format($dp['YCME']) }}</td>
                                <td class="px-6 py-4 text-right">{{ number_format($dp['YEV']) }}</td>
                                <td class="px-6 py-4 text-right">{{ number_format($dp['YIG']) }}</td>
                                <td class="px-6 py-4 text-right">{{ number_format($dp['YSM']) }}</td>
                                <td class="px-6 py-4 text-right">{{ number_format($dp['YAM']) }}</td>
                                <td class="px-6 py-4 text-right">{{ number_format($dp['GAMA']) }}</td>
                                <td class="px-6 py-4 text-right">{{ number_format($dp['WAS']) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row justify-evenly px-2">
            <div class="bg-white p-2 rounded shadow w-full lg:w-1/4  mt-2">
                <canvas id="payrollAll"></canvas>
            </div>
            <div class="bg-white p-2 rounded shadow w-full lg:w-1/4  mt-2">
                <canvas id="payrollASB"></canvas>
            </div>
            <div class="bg-white p-2 rounded shadow w-full lg:w-1/4  mt-2">
                <canvas id="payrollDPA"></canvas>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row justify-evenly lg:mt-3 px-2">
            <div class="bg-white p-2 rounded shadow w-full xl:w-1/4  mt-2">
                <canvas id="payrollYCME"></canvas>
            </div>
            <div class="bg-white p-2 rounded shadow w-full xl:w-1/4  mt-2">
                <canvas id="payrollYEV"></canvas>
            </div>
            <div class="bg-white p-2 rounded shadow w-full xl:w-1/4  mt-2">
                <canvas id="payrollYIG"></canvas>
            </div>

        </div>
        <div class="flex flex-col lg:flex-row justify-evenly lg:mt-3 px-2 pb-5">
            <div class="bg-white p-2 rounded shadow w-full xl:w-1/4  mt-2">
                <canvas id="payrollYSM"></canvas>
            </div>
            <div class="bg-white p-2 rounded shadow w-full xl:w-1/4  mt-2">
                <canvas id="payrollYAM"></canvas>
            </div>
            <div class="bg-white p-2 rounded shadow w-full xl:w-1/4  mt-2">
                <canvas id="payrollGAMA"></canvas>
            </div>
        </div>
        <div class="flex flex-col lg:flex-row justify-evenly lg:mt-3 px-2 pb-5">
            <div class="bg-white p-2 rounded shadow w-full xl:w-1/4  mt-2">
                <canvas id="payrollWAS"></canvas>
            </div>

        </div>

    @endif

    {{-- <div style="display: none">
        <div class="mt-5 w-1/5 h-40 bg-teal-500 rounded-xl shadow-xl">
            <h2></h2>
            1000
        </div>
    </div> --}}
    <style>
        .c-dashboardInfo {
            margin-bottom: 15px;
        }

        .c-dashboardInfo .wrap {
            background: #ffffff;
            box-shadow: 2px 10px 20px rgba(0, 0, 0, 0.1);
            border-radius: 7px;
            text-align: center;
            position: relative;
            overflow: hidden;
            padding: 40px 25px 20px;
            height: 100%;
        }

        .c-dashboardInfo__title,
        .c-dashboardInfo__subInfo {
            color: #6c6c6c;
            font-size: 1.18em;
        }

        .c-dashboardInfo span {
            display: block;
        }

        .c-dashboardInfo__count {
            font-weight: 600;
            font-size: 2.5em;
            line-height: 64px;
            color: #323c43;
        }

        .c-dashboardInfo .wrap:after {
            display: block;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 10px;
            content: "";
        }

        .c-dashboardInfo:nth-child(1) .wrap:after {
            background: linear-gradient(82.59deg, #00c48c 0%, #00a173 100%);
        }

        .c-dashboardInfo:nth-child(2) .wrap:after {
            background: linear-gradient(81.67deg, #0084f4 0%, #1a4da2 100%);
        }

        .c-dashboardInfo:nth-child(3) .wrap:after {
            background: linear-gradient(69.83deg, #0084f4 0%, #00c48c 100%);
        }

        .c-dashboardInfo:nth-child(4) .wrap:after {
            background: linear-gradient(81.67deg, #ff647c 0%, #1f5dc5 100%);
        }

        .c-dashboardInfo__title svg {
            color: #d7d7d7;
            margin-left: 5px;
        }

        .MuiSvgIcon-root-19 {
            fill: currentColor;
            width: 1em;
            height: 1em;
            display: inline-block;
            font-size: 24px;
            transition: fill 200ms cubic-bezier(0.4, 0, 0.2, 1) 0ms;
            user-select: none;
            flex-shrink: 0;
        }
    </style>
@endsection
