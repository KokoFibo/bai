<div>
    @section('title', 'BPJS/PTKP')

    <style>
        .bpjs-page {
            padding: 1.5rem;
        }

        /* Header */
        .bpjs-header {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid #e9ecef;
            border-radius: 16px;
            padding: 1.5rem 1.75rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
        }

        .bpjs-header h1 {
            font-size: 1.6rem;
            font-weight: 700;
            color: #212529;
            margin: 0;
        }

        .bpjs-header .subtitle {
            color: #6c757d;
            font-size: 0.9rem;
            margin-top: 5px;
        }

        .bpjs-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eaf4ff;
            color: #0d6efd;
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        /* Table Card */
        .bpjs-card {
            background: #ffffff;
            border: 1px solid #e9ecef;
            border-radius: 16px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .bpjs-table-wrapper {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .bpjs-table {
            margin-bottom: 0;
            min-width: 1050px;
            vertical-align: middle;
        }

        .bpjs-table thead th {
            background: #f8f9fa;
            color: #495057;
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            padding: 14px 16px;
            border-bottom: 2px solid #e9ecef;
            white-space: nowrap;
        }

        .bpjs-table tbody td {
            padding: 14px 16px;
            font-size: 0.9rem;
            color: #343a40;
            border-bottom: 1px solid #f0f1f3;
            white-space: nowrap;
        }

        .bpjs-table tbody tr {
            transition: all 0.2s ease;
        }

        .bpjs-table tbody tr:hover {
            background-color: #f8fbff;
        }

        .bpjs-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* ID */
        .employee-id {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 5px 9px;
            border-radius: 7px;
            background: #f1f3f5;
            color: #495057;
            font-size: 0.78rem;
            font-weight: 700;
        }

        /* Employee Name */
        .employee-name {
            font-weight: 600;
            color: #212529;
        }

        /* Salary */
        .bpjs-salary {
            font-weight: 700;
            color: #198754;
        }

        .bpjs-salary::before {
            content: "Rp ";
            font-size: 0.78rem;
            font-weight: 500;
            color: #6c757d;
        }

        /* PTKP Badge */
        .ptkp-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            background: #fff4e5;
            color: #b76e00;
            font-size: 0.75rem;
            font-weight: 700;
        }

        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 5px 11px;
            border-radius: 20px;
            background: #e8f5e9;
            color: #2e7d32;
            font-size: 0.75rem;
            font-weight: 700;
        }

        /* Company / Placement / Department */
        .company-text {
            font-weight: 600;
            color: #343a40;
        }

        .secondary-text {
            color: #6c757d;
        }

        /* Card Footer */
        .bpjs-footer {
            padding: 14px 18px;
            background: #fafbfc;
            border-top: 1px solid #e9ecef;
        }

        /* Empty State */
        .empty-state {
            padding: 50px 20px !important;
            text-align: center;
            color: #6c757d;
        }

        .empty-state-icon {
            width: 60px;
            height: 60px;
            margin: 0 auto 15px;
            border-radius: 50%;
            background: #f1f3f5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: #adb5bd;
        }

        /* Pagination */
        .bpjs-footer .pagination {
            margin-bottom: 0;
        }

        .bpjs-footer .page-link {
            border-radius: 8px;
            margin: 0 2px;
            border: 1px solid #dee2e6;
            color: #495057;
        }

        .bpjs-footer .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: white;
        }

        /* Mobile */
        @media (max-width: 768px) {
            .bpjs-page {
                padding: 0.75rem;
            }

            .bpjs-header {
                padding: 1rem;
                border-radius: 12px;
            }

            .bpjs-header h1 {
                font-size: 1.15rem;
            }

            .bpjs-header .subtitle {
                font-size: 0.8rem;
            }

            .bpjs-icon {
                width: 40px;
                height: 40px;
                font-size: 1.1rem;
            }

            .bpjs-card {
                border-radius: 12px;
            }

            .bpjs-table {
                min-width: 1000px;
            }

            .bpjs-table thead th {
                padding: 12px;
                font-size: 0.72rem;
            }

            .bpjs-table tbody td {
                padding: 12px;
                font-size: 0.82rem;
            }

            .bpjs-footer {
                padding: 12px;
                overflow-x: auto;
            }
        }
    </style>

    <div class="bpjs-page">

        {{-- PAGE HEADER --}}
        <div class="bpjs-header">
            <div class="d-flex align-items-center gap-3">

                <div class="bpjs-icon">
                    <i class="fas fa-id-card"></i>
                </div>

                <div>
                    <h1>Daftar Karyawan BPJS</h1>
                    <div class="subtitle">
                        Data karyawan dengan informasi Gaji BPJS dan PTKP
                    </div>
                </div>

            </div>
        </div>


        {{-- TABLE CARD --}}
        <div class="bpjs-card">

            <div class="bpjs-table-wrapper">

                <table class="table bpjs-table">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>HP</th>
                            <th>Company</th>
                            <th>Placement</th>
                            <th>Department</th>
                            <th class="text-end">Gaji BPJS</th>
                            <th>PTKP</th>
                            <th>Status Karyawan</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($data as $key => $d)
                            <tr>

                                {{-- ID --}}
                                <td>
                                    <span class="employee-id">
                                        {{ $d->id_karyawan }}
                                    </span>
                                </td>

                                {{-- NAMA --}}
                                <td>
                                    <span class="employee-name">
                                        {{ $d->nama }}
                                    </span>
                                </td>

                                {{-- HP --}}
                                <td>
                                    <span class="secondary-text">
                                        {{ $d->hp }}
                                    </span>
                                </td>

                                {{-- COMPANY --}}
                                <td>
                                    <span class="company-text">
                                        {{ $d->company->company_name }}
                                    </span>
                                </td>

                                {{-- PLACEMENT --}}
                                <td>
                                    <span class="secondary-text">
                                        {{ $d->placement->placement_name }}
                                    </span>
                                </td>

                                {{-- DEPARTMENT --}}
                                <td>
                                    <span class="secondary-text">
                                        {{ $d->department->nama_department }}
                                    </span>
                                </td>

                                {{-- GAJI BPJS --}}
                                <td class="text-end">
                                    <span class="bpjs-salary">
                                        {{ number_format($d->gaji_bpjs, 0, ',', '.') }}
                                    </span>
                                </td>

                                {{-- PTKP --}}
                                <td>
                                    <span class="ptkp-badge">
                                        {{ $d->ptkp }}
                                    </span>
                                </td>

                                {{-- STATUS --}}
                                <td>
                                    <span class="status-badge">
                                        {{ $d->status_karyawan }}
                                    </span>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="9" class="empty-state">

                                    <div class="empty-state-icon">
                                        <i class="fas fa-users-slash"></i>
                                    </div>

                                    <div class="fw-semibold">
                                        Tidak ada data karyawan
                                    </div>

                                    <small>
                                        Belum terdapat karyawan dengan data BPJS.
                                    </small>

                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- PAGINATION --}}
            @if ($data->hasPages())
                <div class="bpjs-footer">
                    {{ $data->links() }}
                </div>
            @endif

        </div>

    </div>

</div>
