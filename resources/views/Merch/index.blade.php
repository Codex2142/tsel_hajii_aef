<x-Supvis.SupvisLayouts>
    <!-- SweetAlert2 -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

    <h1>button 'detail' masih tidak bisa dipakai</h1>
    <div class="container mt-5">
        <h2 class="mb-4 text-center"><strong>Daftar Merchandise</strong></h2>

        <a href="{{ route('merch.create') }}" class="btn btn-success mb-3">
            Tambah Merchandise
        </a>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        @if ($merchandises->whereNull('deleted_at')->isEmpty())
            <div class="alert alert-warning text-center">
                Belum ada merchandise yang tersedia.
            </div>
            <br><br><br>
        @else
            <div class="row">
                @foreach ($merchandises->whereNull('deleted_at') as $merchandise)
                    <div class="col-12 col-sm-6 col-lg-4 mb-4">
                        <div class="card h-100 border-0 shadow-sm product-card">

                            <div class="card-body d-flex flex-column">

                                <!-- Header -->
                                <div class="mb-3">
                                    <h5 class="fw-semibold text-dark mb-1">
                                        {{ $merchandise->merch_nama }}
                                    </h5>

                                    <span class="badge bg-primary bg-opacity-10 text-primary">
                                        Terambil {{ $merchandise->merch_terambil }}
                                    </span>
                                </div>

                                <!-- Content -->
                                <div class="small text-muted mb-3">

                                    <div class="mb-2">
                                        <span class="d-block text-secondary">Deskripsi</span>
                                        <span class="text-dark">
                                            {{ Str::limit($merchandise->merch_detail, 80) }}
                                        </span>
                                    </div>

                                    <hr class="my-2">

                                    <div class="d-flex justify-content-between">
                                        <span>Stok</span>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                            {{ $merchandise->merch_stok }}
                                        </span>
                                    </div>

                                </div>

                                <!-- Action -->
                                <div class="mt-auto d-flex gap-2">

                                    <button type="button" class="btn btn-outline-primary btn-sm flex-fill btn-detail"
                                        data-id="{{ $merchandise->id }}">
                                        Detail
                                    </button>

                                    <a href="{{ route('merch.edit', $merchandise->id) }}"
                                        class="btn btn-outline-warning btn-sm flex-fill">
                                        Edit Stok
                                    </a>

                                    <form action="{{ route('merch.destroy', $merchandise->id) }}" method="POST"
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


        {{-- Display Deleted Merchs --}}

        @if ($merchandises->whereNotNull('deleted_at')->isNotEmpty())
            <h2 class="mb-4 text-center">
                <strong>Merchandise Terhapus</strong>
            </h2>

            <div class="row">
                @foreach ($merchandises->whereNotNull('deleted_at') as $merch)
                    <div class="col-12 col-sm-6 col-lg-4 mb-4">
                        <div class="card h-100 border-0 shadow-sm product-card">

                            <div class="card-body d-flex flex-column">

                                <!-- Header -->
                                <div class="mb-3">
                                    <h5 class="fw-semibold text-dark mb-1">
                                        {{ $merch->merch_nama }}
                                    </h5>

                                    <div class="d-flex gap-2 flex-wrap">
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                            Terambil {{ $merch->merch_terambil }}
                                        </span>

                                        <span class="badge bg-danger bg-opacity-10 text-danger">
                                            Terhapus {{ $merch->deleted_at->format('d M Y H:i') }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="small text-muted mb-3">
                                    <div class="mb-2">
                                        <span class="d-block text-secondary">Deskripsi</span>
                                        <span class="text-dark">
                                            {{ Str::limit($merch->merch_detail, 90) }}
                                        </span>
                                    </div>

                                    <hr class="my-2">

                                    <div class="d-flex justify-content-between">
                                        <span>Stok</span>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                            {{ $merch->merch_stok }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Action -->
                                <div class="mt-auto">
                                    <form action="{{ route('merch.restore', $merch->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-success btn-sm w-100">
                                            Restore Merchandise
                                        </button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="d-none">
            </div>
        @endif

        @php
            $allHistory = collect();
            foreach ($merchandises as $merchandise) {
                $history = json_decode($merchandise->merch_terambil_history, true);
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

        <!-- Filter Total Pengambilan -->
        <h3 class="mt-5 text-center"><strong>Riwayat Pengambilan Merchandise</strong></h3>

        <div class="mb-3">
            <label for="filterTanggal" class="form-label"><strong>Filter Berdasarkan Tanggal:</strong></label>
            <select id="filterTanggal" class="form-select">
                <option value="all">Semua Tanggal</option>
                @foreach ($uniqueDates as $date)
                    <option value="{{ $date }}">{{ $date }}</option>
                @endforeach
            </select>
        </div>

        <!-- Tabel Total Pengambilan Per Tanggal -->
        <div class="table-responsive">
            <table class="table table-striped table-hover mt-3">
                <thead class="table-dark">
                    <tr class="text-center">
                        <th style="width: 50%;">Tanggal</th>
                        <th style="width: 50%;">Total Merchandise Terambil</th>
                    </tr>
                </thead>
                <tbody id="totalPengambilanBody">
                    @foreach ($groupedHistory as $entry)
                        <tr class="text-center" data-tanggal="{{ $entry['tanggal'] }}">
                            <td><strong>{{ Carbon\Carbon::parse($entry['tanggal'])->format('Y-m-d') }}</strong></td>
                            <td><span class="badge bg-success fs-6">{{ $entry['total_jumlah'] }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Tabel Detail Riwayat -->
        <h4 class="mt-5 text-center"><strong>Detail Riwayat </strong></h4>
        <div class="table-responsive">
            <table id="detilHistoryTable" class="table table-bordered table-striped mt-3">
                <thead class="table-dark">
                    <tr class="text-center">
                        <th>Tanggal & Waktu</th>
                        <th>Jumlah</th>
                        <th>Merchandise</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($allHistory as $entry)
                        <tr class="text-center">
                            <td>{{ $entry['tanggal'] }}</td>
                            <td><span class="badge bg-primary">{{ $entry['jumlah'] ?? '-' }}</span></td>
                            <td>{{ $entry['merch_nama'] ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.getElementById('filterTanggal').addEventListener('change', function() {
            let selectedDate = this.value;

            // Filter "Total Merchandise Terambil" table
            let totalRows = document.querySelectorAll('#totalPengambilanBody tr');
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
    </script>
</x-Supvis.SupvisLayouts>
