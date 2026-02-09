<x-Supvis.SupvisLayouts>
    <!-- SweetAlert2 -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <style>
        .dataTables_wrapper .dataTables_paginate .page-item .page-link {
            background-color: #23a0b0 !important;
            color: white !important;
            border: none !important;
            border-radius: 5px !important;
            padding: 5px 10px !important;
            margin: 2px !important;
        }

        .dataTables_wrapper .dataTables_paginate .page-item .page-link:hover {
            background-color: #1b8190 !important;
            color: white !important;
        }

        .dataTables_wrapper .dataTables_paginate .page-item.active .page-link {
            background-color: #23a0b0 !important;
            color: white !important;
            font-weight: bold !important;
            box-shadow: none !important;
        }

        .dataTables_wrapper .dataTables_paginate .page-item.disabled .page-link {
            background-color: #b0b0b0 !important;
            color: #ffffff !important;
            opacity: 0.6;
            cursor: not-allowed;
        }
    </style>

    <div class="container mt-5">
        <h2 class="mb-4 text-center"><strong>Daftar Produk</strong></h2>
        <a href="{{ route('produk.create') }}" class="btn btn-success mb-3">Tambah
            Produk</a>

        @if ($produks->whereNull('deleted_at')->isEmpty())
            <div class="alert alert-warning text-center">Belum ada produk yang tersedia.</div>
            <br><br><br>
        @else
            <div class="row">
                @foreach ($produks->whereNull('deleted_at') as $produk)
                    <div class="col-12 col-sm-6 col-lg-4 mb-4">
                        <div class="card h-100 border-0 shadow-sm product-card">

                            <div class="card-body d-flex flex-column">

                                <!-- Header -->
                                <div class="mb-3">
                                    <h5 class="fw-semibold text-dark mb-1">
                                        {{ $produk->produk_nama }}
                                    </h5>
                                    <span class="badge bg-primary bg-opacity-10 text-primary">
                                        Terjual {{ $produk->produk_terjual }}
                                    </span>
                                </div>

                                <!-- Content -->
                                <div class="small text-muted mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span>Harga</span>
                                        <span class="fw-medium text-dark">
                                            Rp {{ number_format($produk->produk_harga, 0, ',', '.') }}
                                        </span>
                                    </div>

                                    <div class="d-flex justify-content-between">
                                        <span>Diskon</span>
                                        <span class="text-danger">
                                            Rp {{ number_format($produk->produk_diskon ?? 0, 0, ',', '.') }}
                                        </span>
                                    </div>

                                    <div class="d-flex justify-content-between">
                                        <span>Insentif</span>
                                        <span class="text-success">
                                            Rp {{ number_format($produk->produk_insentif, 0, ',', '.') }}
                                        </span>
                                    </div>

                                    <hr class="my-2">

                                    <div class="d-flex justify-content-between fw-semibold text-dark">
                                        <span>Harga Final</span>
                                        <span>
                                            Rp {{ number_format($produk->produk_harga_akhir, 0, ',', '.') }}
                                        </span>
                                    </div>

                                    <div class="d-flex justify-content-between mt-1">
                                        <span>Stok</span>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                            {{ $produk->produk_stok }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Action -->
                                <div class="mt-auto d-flex gap-2">
                                    <button class="btn btn-outline-primary btn-sm flex-fill btn-detail"
                                        data-id="{{ $produk->id }}">
                                        Detail
                                    </button>

                                    <a href="{{ route('produk.edit', $produk->id) }}"
                                        class="btn btn-outline-warning btn-sm flex-fill">
                                        Edit Stok
                                    </a>

                                    <form action="{{ route('produk.destroy', $produk->id) }}" method="POST"
                                        class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            Hapus
                                        </button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        @endif


        {{-- Display Deleted Products --}}
        @if (Auth::user() && Auth::user()->is_superuser)
            @if ($produks->whereNotNull('deleted_at')->isEmpty())
                <div class="d-none"></div>
            @else
                <h2 class="mb-4 text-center"><strong>Produk Dihapus</strong></h2>
                <div class="row">
                    @foreach ($produks->whereNotNull('deleted_at') as $produk)
                        <div class="col-12 col-sm-6 col-lg-4 mb-4">
                            <div class="card h-100 border-0 shadow-sm product-card">

                                <div class="card-body d-flex flex-column">

                                    <!-- Header -->
                                    <div class="mb-3">
                                        <h5 class="fw-semibold text-dark mb-1">
                                            {{ $produk->produk_nama }}
                                        </h5>

                                        <div class="d-flex gap-2 flex-wrap">
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                Terjual {{ $produk->produk_terjual }}
                                            </span>

                                            <span class="badge bg-danger bg-opacity-10 text-danger">
                                                Terhapus {{ $produk->deleted_at->format('d M Y H:i') }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Content -->
                                    <div class="small text-muted mb-3">
                                        <div class="d-flex justify-content-between">
                                            <span>Harga</span>
                                            <span class="fw-medium text-dark">
                                                Rp {{ number_format($produk->produk_harga, 0, ',', '.') }}
                                            </span>
                                        </div>

                                        <div class="d-flex justify-content-between">
                                            <span>Diskon</span>
                                            <span class="text-danger">
                                                Rp {{ number_format($produk->produk_diskon ?? 0, 0, ',', '.') }}
                                            </span>
                                        </div>

                                        <div class="d-flex justify-content-between">
                                            <span>Insentif</span>
                                            <span class="text-success">
                                                Rp {{ number_format($produk->produk_insentif, 0, ',', '.') }}
                                            </span>
                                        </div>

                                        <hr class="my-2">

                                        <div class="d-flex justify-content-between fw-semibold text-dark">
                                            <span>Harga Final</span>
                                            <span>
                                                Rp {{ number_format($produk->produk_harga_akhir, 0, ',', '.') }}
                                            </span>
                                        </div>

                                        <div class="d-flex justify-content-between mt-1">
                                            <span>Stok</span>
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                {{ $produk->produk_stok }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Action -->
                                    <div class="mt-auto">
                                        <form action="{{ route('produk.restore', $produk->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success btn-sm w-100">
                                                Restore Produk
                                            </button>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            @endif
        @endif

    </div>

    @php
        $allHistory = collect();
        foreach ($produks as $produk) {
            $history = json_decode($produk->produk_terjual_history, true);
            if (is_array($history)) {
                $allHistory = $allHistory->merge($history);
            }
        }

        $groupedHistory = $allHistory
            ->groupBy(function ($item) {
                return \Carbon\Carbon::parse($item['tanggal'])->format('Y-m-d');
            })
            ->map(function ($items) {
                return [
                    'tanggal' => $items->first()['tanggal'] ?? '-',
                    'total_jumlah' => $items->sum('jumlah'),
                ];
            });

        $uniqueDates = $groupedHistory->keys();
    @endphp

    <!-- Tabel Riwayat Penjualan -->
    {{-- <div class="container mt-4">
        <h2 class="mt-5 text-center"><strong>📊 Riwayat Penjualan</strong></h2>
    </div>

    <!-- Filter Total Penjualan -->
    <div class ="container mt-4">
        <div class="mb-3">
            <label for="filterTanggal" class="form-label"><strong>📅 Filter Berdasarkan Tanggal:</strong></label>
            <select id="filterTanggal" class="form-select">
                <option value="all">Semua Tanggal</option>
                @foreach ($uniqueDates as $date)
                    <option value="{{ $date }}">{{ $date }}</option>
                @endforeach
            </select>
        </div>
    </div> --}}
    <div class="container mt-4">
        <div class="max-w-7xl mx-auto px-4 mt-10">
            <h2 class="text-2xl font-semibold text-center text-gray-800">
                Riwayat Penjualan
            </h2>

            <div class="mt-1 max-w-md mx-auto">
                <label for="filterTanggal" class="form-label"><strong>Filter Berdasarkan Tanggal:</strong></label>

                <select id="filterTanggal" class="form-select">
                    <option value="all">Semua Tanggal</option>
                    @foreach ($uniqueDates as $date)
                        <option value="{{ $date }}">{{ $date }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <!-- Tabel Total Penjualan Per Tanggal -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead style="background-color: #23a0b0; color: white;">
                    <tr class="text-center">
                        <th style="width: 50%;">Tanggal</th>
                        <th style="width: 50%;">Total Jumlah Terjual</th>
                    </tr>
                </thead>
                <tbody id="totalPenjualanBody">
                    @foreach ($groupedHistory as $entry)
                        <tr class="text-center" data-tanggal="{{ $entry['tanggal'] }}">
                            <td><strong>{{ \Carbon\Carbon::parse($entry['tanggal'])->format('d M Y') }}</strong></td>
                            <td><span class="badge bg-success fs-6">{{ $entry['total_jumlah'] }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Tabel Detail Riwayat  -->
        <div class="container mt-4">
            <h2 class="mt-5 text-center">Detail Riwayat</h2>
            <div class="table-responsive">
                <table id="detilHistoryTable" class="table table-striped table-hover">
                    <thead style="background-color: #23a0b0; color: white;">
                        <tr class="text-center">
                            <th>Tanggal & Waktu</th>
                            <th>Jumlah</th>
                            <th>Produk</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($allHistory as $entry)
                            <tr class="text-center">
                                <td>{{ $entry['tanggal'] }}</td>
                                <td><span class="badge bg-primary">{{ $entry['jumlah'] ?? '-' }}</span></td>
                                <td>{{ $entry['produk_nama'] ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    </div>
    <script>
        document.getElementById('filterTanggal').addEventListener('change', function() {
            let selectedDate = this.value;
            let totalRows = document.querySelectorAll('#totalPenjualanBody tr');
            totalRows.forEach(row => {
                let rowDate = row.getAttribute('data-tanggal');
                row.style.display = (selectedDate === "all" || rowDate.startsWith(selectedDate)) ? "" :
                    "none";
            });

            // Filter "Detail Riwayat Pengambilan" table
            let detailRows = document.querySelectorAll('#detilHistoryTable tbody tr');
            detailRows.forEach(row => {
                let rowDate = row.cells[0].textContent.trim();
                row.style.display = (selectedDate === "all" || rowDate.startsWith(selectedDate)) ? "" :
                    "none";
            });

        });
        $(document).ready(function() {
            $(document).off('click', '.btn-detail').on('click', '.btn-detail', function() {
                let produkId = $(this).data('id');

                $.ajax({
                    url: "/programhaji/produk/" + produkId,
                    type: "GET",
                    success: function(response) {
                        Swal.fire({
                            title: response.produk_nama,
                            html: `
                        <div style="text-align:left;">
                            <p><strong>Harga:</strong> Rp ${new Intl.NumberFormat().format(response.produk_harga)}</p>
                            <p><strong>Diskon:</strong> ${response.produk_diskon}</p>
                            <p><strong>Stok:</strong> ${response.produk_stok}</p>
                            <p><strong>Detail:</strong> ${response.produk_detail ?? 'Tidak ada detail'}</p>
                            <p><strong>Insentif:</strong> Rp ${new Intl.NumberFormat().format(response.produk_insentif)}</p>
                            <p><strong>Merchandise:</strong> ${response.merchandises.length > 0 ? response.merchandises.join(', ') : 'Tidak ada merchandise terkait'}</p>
                        </div>
                    `,
                            icon: 'info',
                            confirmButtonText: 'Tutup',
                            confirmButtonColor: '#23a0b0'
                        });
                    }
                });
            });
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            $('#totalPenjualanTable, #detailPenjualanTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "lengthMenu": [5, 10, 25, 50, 100],
                "autoWidth": false,
                "language": {
                    "search": '<label class="custom-search" style="margin-left: 10px; margin-bottom: 10px; display: flex; align-items: center; gap: 5px;">' +
                        '<i class="fas fa-search"></i> Cari:',
                    "lengthMenu": '<div class="d-flex align-items-center gap-1 mb-3">' +
                        '<span class="me-1">Tampilkan</span>' +
                        '<select class="form-select form-select-sm w-auto" style="min-width: 60px;">' +
                        '<option value="5">5</option>' +
                        '<option value="10">10</option>' +
                        '<option value="25">25</option>' +
                        '<option value="50">50</option>' +
                        '<option value="100">100</option>' +
                        '</select>' +
                        '<span>data per halaman</span>' +
                        '</div>',
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty": "Tidak ada data tersedia",
                    "zeroRecords": "Tidak ditemukan data yang sesuai",
                    "paginate": {
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                }
            });
        });
    </script>
</x-Supvis.SupvisLayouts>
