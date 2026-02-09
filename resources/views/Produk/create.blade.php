<x-Supvis.SupvisLayouts>
    <div class="container mt-5">
        <h2 class="text-center mb-4"><strong>Tambah Produk</strong></h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <x-form-card title="Tambah Produk">
            <form id="produkForm" action="{{ route('produk.store') }}" method="POST" onsubmit="removeFormatBeforeSubmit()">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <x-form-group
                            label="Nama Produk"
                            name="produk_nama"
                            placeholder="Masukkan nama produk"
                            required="true"
                        />
                    </div>

                    <div class="col-md-6">
                        <x-form-group
                            label="Harga Produk"
                            name="produk_harga"
                            placeholder="Masukkan harga produk"
                            required="true"
                        />
                    </div>

                    <div class="col-md-6">
                        <x-form-group
                            label="Diskon (Rp)"
                            name="produk_diskon"
                            placeholder="Masukkan diskon"
                        />
                    </div>

                    <div class="col-md-6">
                        <x-form-group
                            label="Stok Produk"
                            name="produk_stok"
                            type="number"
                            placeholder="Masukkan stok produk"
                            required="true"
                        />
                    </div>

                    <div class="col-md-12">
                        <x-form-group
                            label="Detail Produk"
                            name="produk_detail"
                            type="textarea"
                            rows="4"
                            placeholder="Masukkan detail produk"
                        />
                    </div>

                    <div class="col-md-6">
                        <x-form-group
                            label="Insentif (Rp)"
                            name="produk_insentif"
                            placeholder="Masukkan insentif"
                        />
                    </div>

                    <div class="col-md-12">
                        <label class="fw-semibold mb-2">Merchandise</label>
                        <div class="row g-2">
                            @foreach ($merchandises as $merchandise)
                                <div class="col-md-4 col-sm-6">
                                    <input
                                        type="checkbox"
                                        class="btn-check"
                                        name="merchandises[]"
                                        id="merchandise_{{ $merchandise->id }}"
                                        value="{{ $merchandise->id }}"
                                        {{ is_array(old('merchandises')) && in_array($merchandise->id, old('merchandises')) ? 'checked' : '' }}
                                    >

                                    <label class="btn btn-outline-primary w-100"
                                        for="merchandise_{{ $merchandise->id }}">
                                        {{ $merchandise->merch_nama }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-center gap-3 mt-4">
                    <x-form-button id="simpanBtn" type="button" variant="primary">Simpan</x-form-button>
                    <x-form-button type="button" variant="danger" id="batalBtn">Batal</x-form-button>
                </div>
            </form>
        </x-form-card>

    </div>

    <script>
        // Fungsi format Rupiah
        const formatRupiah = (value) => {
            const numberString = value.replace(/[^,\d]/g, '').toString();
            const split = numberString.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            const ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                const separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            return split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
        };

        document.getElementById('produk_harga').addEventListener('input', function(e) {
            this.value = formatRupiah(this.value);
        });

        document.getElementById('produk_diskon').addEventListener('input', function(e) {
            this.value = formatRupiah(this.value);
        });

        document.getElementById('produk_insentif').addEventListener('input', function(e) {
            this.value = formatRupiah(this.value);
        });

        function removeFormatBeforeSubmit() {
            const hargaInput = document.getElementById('produk_harga');
            const diskonInput = document.getElementById('produk_diskon');
            const insentifInput = document.getElementById('produk_insentif');

            hargaInput.value = hargaInput.value.replace(/\./g, '').replace(',', '.');
            diskonInput.value = diskonInput.value.replace(/\./g, '').replace(',', '.');
            insentifInput.value = insentifInput.value.replace(/\./g, '').replace(',', '.');

            if (diskonInput.value === '') diskonInput.value = 0;
            if (insentifInput.value === '') insentifInput.value = 0;
        }

        document.addEventListener("DOMContentLoaded", function () {
            document.getElementById("simpanBtn").addEventListener("click", function () {
                let namaProduk = document.getElementById("produk_nama").value.trim();
                let hargaProduk = document.getElementById("produk_harga").value.trim();
                let stokProduk = document.getElementById("produk_stok").value.trim();
                let detailProduk = document.getElementById("produk_detail").value.trim();
                let insentif = document.getElementById("produk_insentif").value.trim();
                let merchandise = document.querySelectorAll("input[name='merchandises[]']:checked");
                let merchandiseChecked = merchandise.length > 0;

                if (
                    namaProduk !== "" &&
                    hargaProduk !== "" &&
                    stokProduk !== "" &&
                    detailProduk !== "" &&
                    insentif !== "" &&
                    merchandiseChecked
                ) {
                    Swal.fire({
                        title: "Konfirmasi Simpan",
                        text: "Apakah Anda yakin ingin menyimpan data ini?",
                        icon: "question",
                        showCancelButton: true,
                        confirmButtonColor: "#28a745",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Ya, Simpan!",
                        cancelButtonText: "Batal"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            removeFormatBeforeSubmit();
                            document.getElementById("produkForm").submit();
                        }
                    });
                } else {
                    Swal.fire({
                        title: "Oops!",
                        text: "Harap isi semua kolom sebelum menyimpan.",
                        icon: "warning",
                        confirmButtonText: "OK"
                    });
                }
            });

            document.getElementById("batalBtn").addEventListener("click", function () {
                Swal.fire({
                    title: "Batalkan Perubahan?",
                    text: "Perubahan yang belum disimpan akan hilang.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Ya, Batalkan!",
                    cancelButtonText: "Kembali"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('produk.index') }}";
                    }
                });
            });
        });
    </script>
</x-Supvis.SupvisLayouts>
