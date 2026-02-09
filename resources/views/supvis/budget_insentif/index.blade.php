<x-Supvis.SupvisLayouts>
    <div class="container mt-4">
        <h2 class="text-center mb-4">Budget Insentif</h2>
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        {{-- <div class="card p-4 shadow-sm mb-4">
            <div class="text-start">
                <h5 style="font-size: 1.20rem;">Total Budget:</h5>
                <h6 class="text-primary" style="font-size: 1.0rem;">{{ number_format($totalBudget, 2) }}</h6>
                <hr>
                <h5 style="font-size: 1.20rem;">Total Insentif:</h5>
                <h6 class="text-danger" style="font-size: 1.0rem;">{{ number_format($totalInsentif, 2) }}</h6>
                <hr>
                <h5 style="font-size: 1.20rem;">Sisa Budget:</h5>
                <h6 class="text-success" style="font-size: 1.0rem;">{{ number_format($sisaBudget, 2) }}</h6>
            </div>
        </div> --}}

        <div class="row g-3 mb-3">

            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100">
                    <img src="https://placehold.co/100x70/2563eb/ffffff?text=Total+Budget" class="card-img-top" alt="">
                    <div class="card-body text-center">
                        <h2 class="text-primary mb-0">
                            Rp. {{ number_format($totalBudget, 2) }}
                        </h2>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100">
                    <img src="https://placehold.co/100x70/DA3D20/ffffff?text=Total+Insentif" class="card-img-top" alt="">
                    <div class="card-body text-center">
                        <h2 class="text-danger mb-0">
                            Rp. {{ number_format($totalInsentif, 2) }}
                        </h2>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100">
                    <img src="https://placehold.co/100x70/6CA651/ffffff?text=Sisa+Budget" class="card-img-top" alt="">
                    <div class="card-body text-center">
                        <h2 class="text-success mb-0">
                            Rp. {{ number_format($sisaBudget, 2) }}
                        </h2>
                    </div>
                </div>
            </div>

        </div>


        <div class="card p-4 shadow-sm">
            <h3 class="mb-4">Update Budget Insentif</h3>
            <form action="{{ route('supvis.budget_insentif.update') }}" method="POST">
                @csrf

                <div class="mb-3 radio-btn">
                    <label class="form-label d-block">Aksi:</label>

                    <input type="radio" name="action" value="tambah" id="tambah" checked>
                    <label for="tambah">Tambah</label>

                    <input type="radio" name="action" value="ganti" id="ganti">
                    <label for="ganti" class="ms-2">Ganti</label>
                </div>

                <div class="mb-3">
                    <label for="total_insentif" class="form-label">Jumlah Budget:</label>
                    <input type="number" class="form-control" name="total_insentif" id="total_insentif"
                        value="{{ old('total_insentif') }}" required>
                </div>

                <button type="submit" class="btn btn-custom btn-lg">Simpan</button>
            </form>
        </div>
    </div>

    <style>
        .btn-custom {
            background: linear-gradient(135deg, rgb(33, 226, 62), #2575FC);
            color: white;
            border: none;
        }

        .btn-custom:hover {
            opacity: 0.9;
        }

        .radio-btn input[type="radio"] {
            display: none;
        }

        .radio-btn label {
            padding: 8px 18px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.2s;
            font-weight: 500;
            color: #495057;
        }

        /* TAMBAH - HIJAU */
        .radio-btn input#tambah:checked + label {
            background-color: #198754;
            border-color: #198754;
            color: #fff;
            box-shadow: 0 2px 6px rgba(25,135,84,.35);
        }

        /* GANTI - KUNING */
        .radio-btn input#ganti:checked + label {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #212529;
            box-shadow: 0 2px 6px rgba(255,193,7,.35);
        }

    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const radios = document.querySelectorAll('input[name="action"]');
            const input = document.getElementById('total_insentif');
            const label = document.querySelector('label[for="total_insentif"]');

            function updateUI() {
                const selected = document.querySelector('input[name="action"]:checked').value;

                if (selected === 'tambah') {
                    label.textContent = 'Jumlah Budget (Tambah):';
                    input.placeholder = 'Masukkan jumlah yang akan ditambahkan';
                    input.classList.remove('border-danger');
                    input.classList.add('border-primary');
                } else {
                    label.textContent = 'Jumlah Budget (Ganti):';
                    input.placeholder = 'Masukkan jumlah budget baru';
                    input.classList.remove('border-primary');
                    input.classList.add('border-danger');
                }
            }

            radios.forEach(radio => {
                radio.addEventListener('change', updateUI);
            });

            updateUI(); // init saat page load
        });
    </script>

</x-Supvis.SupvisLayouts>
