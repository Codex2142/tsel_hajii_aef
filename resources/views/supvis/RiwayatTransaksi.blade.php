@php
    $layout = Auth::user()->hasRole('kasir') ? 'Kasir.KasirLayouts' : 'Supvis.SupvisLayouts';
@endphp
<x-dynamic-component :component="$layout">

    <link href="//cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #43e97b, #2575FC);
            color: #333;
            margin: 0;
            padding: 0;
            height: 100vh;
        }

        h1 {
            color: rgb(0, 0, 0);
            font-size: 2.5rem;
            margin: 40px 0 20px;
            text-align: center;
        }

        .dashboard {
            padding: 20px;
            font-family: Arial, sans-serif;
        }

        .search-container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 9px;
            background-color: #f8f9fa;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 50%;
            margin: 9px auto;
        }

        .filter-box {
            flex: 1;
            margin-right: 5px;
        }

        .filter-box select {
            width: 100%;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 12px;
            background-color: #fff;
            cursor: pointer;
        }

        .search-box {
            display: flex;
            align-items: center;
            position: relative;
            flex: 2;
        }

        .search-box input {
            width: 100%;
            padding: 5px 30px 5px 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 12px;
        }

        .search-box i {
            position: absolute;
            right: 8px;
            color: #888;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        .table-responsive-scroll {
            overflow-x: auto;
            width: 100%;
        }

        th,
        td {
            padding: 9px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .insentif th {
            padding: 9px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .penjualan {
            background: #23a0b0;
            color: white;
            font-weight: bold;
        }

        thead tr {
            background: #23a0b0;
            color: white;
            font-weight: bold;
        }

        th {
            color: white;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #1c828f;
        }

        @media screen and (max-width: 600px) {
            table {
                border: 0;
                width: 100%;
            }

            thead {
                display: none;
            }

            tr {
                display: block;
                margin-bottom: 25px;
                background-color: #fff;
                border-radius: 8px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            }

            td {
                display: flex;
                justify-content: space-between;
                padding: 12px 15px;
                border-bottom: 1px solid #ddd;
            }

            td::before {
                content: attr(data-label);
                font-weight: bold;
                background: linear-gradient(135deg, #2575FC, #43e97b);
                -webkit-background-clip: text;
                color: transparent;
                text-align: left;
                padding: 5px;
            }

        }

        @media (max-width: 768px) {
            .search-container {
                width: 90%;
                margin: 20px auto;
            }

            .filter-box,
            .search-box {
                flex: 1;
            }

            .search-box input {
                font-size: 14px;
                padding: 5px 20px 5px 8px;
            }

            .search-box i {
                font-size: 16px;
            }
        }

        .filter-input {
            border-radius: 12px;
            padding: 10px 14px;
            border: 1px solid #e5e7eb;
            background-color: #fff;
            transition: all .2s ease;
            font-size: 14px;
        }

        .filter-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .15);
            outline: none;
        }

        .filter-box select,
        .filter-box input {
            min-height: 42px;
        }
    </style>

    <body>
        <h1><b>Riwayat Transaksi</b></h1>

        @if (session('success'))
            <p style="color: green;">{{ session('success') }}</p>
        @elseif(session('error'))
            <p style="color: red;">{{ session('error') }}</p>
        @endif
        <form method="GET" class="search-container">
            <div class="filter-box">
                <input type="date" name="tanggal_transaksi" value="{{ request('tanggal_transaksi') }}"
                    class="form-control filter-input">
            </div>

            @php
                $user = auth()->user();
                $isKasir = $user->hasRole('kasir');
            @endphp

            <div class="filter-box">
                <select name="id_supervisor" {{ $isKasir ? 'disabled' : '' }} class="form-select filter-input">
                    <option value="">Semua Kasir</option>
                    @foreach ($transaksi->pluck('supervisor')->filter()->unique('id')->sortBy('name') as $supervisor)
                        <option value="{{ $supervisor->id }}"
                            {{ (request('id_supervisor') ?? ($isKasir ? $user->id : null)) == $supervisor->id ? 'selected' : '' }}>
                            {{ $supervisor->name }}
                        </option>
                    @endforeach
                </select>

                @if ($isKasir)
                    <input type="hidden" name="id_supervisor" value="{{ $user->id }}">
                @endif
            </div>

            <div class="filter-box">
                <select name="metode_pembayaran" class="form-select filter-input">
                    <option value="">Semua Metode</option>
                    @foreach ($transaksi->pluck('metode_pembayaran')->unique() as $metode)
                        <option value="{{ $metode }}"
                            {{ request('metode_pembayaran') == $metode ? 'selected' : '' }}>
                            {{ $metode }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-box">
                <button type="submit" class="btn btn-primary px-4 rounded-pill shadow-sm">
                    <i class="fa-solid fa-filter"></i>
                </button>
            </div>
        </form>


        <div class="container d-flex justify-content-center align-items-center mt-3">
            <div class="row w-100">

                <div class="col-md-6">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <h3 class="card-title text-success">Total Penjualan</h3>
                            <p class="card-text fw-bold">Rp {{ number_format($totalPenjualan, 0, ',', '.') }},-</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <h3 class="card-title text-primary">Mandiri</h3>
                            <p class="card-text fw-bold">Rp
                                {{ number_format($paymentSums['Mandiri'] ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container d-flex justify-content-center align-items-center mt-3">
            <div class="row w-100">

                <div class="col-md-6">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <h3 class="card-title text-success">BNI</h3>
                            <p class="card-text fw-bold">Rp {{ number_format($paymentSums['BNI'] ?? 0, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <h3 class="card-title text-primary">Tunai</h3>
                            <p class="card-text fw-bold">Rp
                                {{ number_format($paymentSums['Tunai'] ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container d-flex justify-content-center align-items-center mt-3">
            <div class="row w-100">

                <div class="col-md-6">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <h3 class="card-title text-success">BCA</h3>
                            <p class="card-text fw-bold">Rp {{ number_format($paymentSums['BCA'] ?? 0, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <h3 class="card-title text-primary">Others</h3>
                            <p class="card-text fw-bold">Rp
                                {{ number_format($paymentSums['Others'] ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container mt-5">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="fw-semibold text-center mb-3">
                        Metode Pembayaran
                    </h5>

                    <div class="d-flex justify-content-center">
                        <div style="max-width:380px;">
                            <canvas id="paymentPie"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="container mx-auto mt-6 px-2">
            <div class="bg-white rounded-xl shadow-sm border overflow-hidden">

                <!-- Header -->
                <div class="px-4 py-3 border-b flex items-center justify-between">
                    <h3 class="font-semibold text-gray-700">Data Transaksi</h3>
                    <span class="text-sm text-gray-400">
                        Total: {{ $transaksi->count() }} transaksi
                    </span>
                </div>

                <!-- Table Wrapper -->
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-gray-700">
                        <thead class="bg-gray-50 sticky top-0 z-10">
                            <tr class="text-left text-xs uppercase tracking-wider text-gray-500">
                                <th class="px-4 py-3">ID</th>
                                <th class="px-4 py-3">Kasir</th>
                                <th class="px-4 py-3">Tanggal</th>
                                <th class="px-4 py-3">Sales</th>
                                <th class="px-4 py-3">Telp Sales</th>
                                <th class="px-4 py-3">Tugas</th>
                                <th class="px-4 py-3">Aktif</th>
                                <th class="px-4 py-3">Pelanggan</th>
                                <th class="px-4 py-3">Telp</th>
                                <th class="px-4 py-3">Injeksi</th>
                                <th class="px-4 py-3 text-center">Addon</th>
                                <th class="px-4 py-3">Aktivasi</th>
                                <th class="px-4 py-3">Produk</th>
                                <th class="px-4 py-3">Merch</th>
                                <th class="px-4 py-3">Metode</th>
                                <th class="px-4 py-3 text-right">Harga</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @foreach ($transaksi as $t)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 py-3 font-medium text-gray-900">
                                        {{ $t->id_transaksi }}
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ $t->supervisor?->name ?? '-' }}
                                    </td>

                                    <td class="px-4 py-3 whitespace-nowrap">
                                        {{ $t->tanggal_transaksi }}
                                    </td>

                                    <td class="px-4 py-3 font-medium">
                                        {{ $t->nama_sales }}
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ $t->nomor_telepon }}
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ optional($t->sales)->tempat_tugas ?? '-' }}
                                    </td>

                                    <td class="px-4 py-3">
                                        @if (optional($t->sales)->bertugas)
                                            <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700">
                                                Aktif
                                            </span>
                                        @else
                                            <span class="px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-500">
                                                Tidak
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ $t->nama_pelanggan }}
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ $t->telepon_pelanggan }}
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ $t->nomor_injeksi }}
                                    </td>

                                    <td class="px-4 py-3 text-center">
                                        @if ($t->addon_perdana)
                                            <span class="text-green-600 font-bold">✓</span>
                                        @else
                                            <span class="text-red-500 font-bold">✗</span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ $t->aktivasi_tanggal }}
                                    </td>

                                    <td class="px-4 py-3 font-medium">
                                        {{ optional($t->produk)->produk_nama ?? '-' }}
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ $t->merchandise }}
                                    </td>

                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 rounded-md bg-blue-100 text-blue-700 text-xs">
                                            {{ $t->metode_pembayaran }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-3 text-right font-semibold text-gray-900 whitespace-nowrap">
                                        Rp
                                        {{ number_format(optional($t->produk)->produk_harga_akhir ?? 0, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        @php
            $queryParams = request()->query();

            // Force id_supervisor into export URL if role is kasir
            if ($isKasir) {
                $queryParams['id_supervisor'] = auth()->user()->id;
            }
        @endphp

        <div class="container text-center mt-3 mb-4">
            <a href="{{ route('export.excel', $queryParams) }}" class="btn btn-success">Export ke Excel</a>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="//cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
        <script>
            const paymentData = @json($paymentSums);

            const labels = Object.keys(paymentData);
            const values = Object.values(paymentData);

            const total = values.reduce((a, b) => a + b, 0);

            new Chart(document.getElementById('paymentPie'), {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: values,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    const value = ctx.raw || 0;
                                    const percent = total ? ((value / total) * 100).toFixed(1) : 0;

                                    return `${ctx.label}: Rp ${value.toLocaleString('id-ID')} (${percent}%)`;
                                }
                            }
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
            $(document).ready(function() {
                $('#transactionTable').DataTable({
                    paging: true,
                    searching: true,
                    ordering: true,
                    order: [
                        [0, "asc"]
                    ],
                    language: {
                        lengthMenu: "Tampilkan _MENU_ data per halaman",
                        zeroRecords: "Tidak ada data ditemukan",
                        info: "Menampilkan _PAGE_ dari _PAGES_ halaman",
                        infoEmpty: "Tidak ada data tersedia",
                        infoFiltered: "(difilter dari total _MAX_ data)"
                    }
                });
            });

            function filterByDateRange(days) {
                const table = document.getElementById('transactionTable');
                const rows = table.getElementsByTagName('tr');
                const now = new Date();

                for (let i = 1; i < rows.length; i++) {
                    const cell = rows[i].getElementsByTagName('td')[1]; // index 1 = tanggal transaksi
                    if (!cell) continue;

                    const rowDate = new Date(cell.textContent);
                    const timeDiff = Math.floor((now - rowDate) / (1000 * 60 * 60 * 24));

                    if (days === 'all' || timeDiff <= days) {
                        rows[i].style.display = '';
                    } else {
                        rows[i].style.display = 'none';
                    }
                }
            }
        </script>
    </body>
</x-dynamic-component>
