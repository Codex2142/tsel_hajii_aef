<x-Supvis.SupvisLayouts>
    <div class="container mt-5">
        <h2 class="mb-4">Edit Produk</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <x-form-card title="Update Produk">
            <form action="{{ route('produk.update', $produk->id) }}" method="POST" onsubmit="removeFormatBeforeSubmit()">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    <div class="col-md-6">
                        <x-form-group label="Nama Produk" name="produk_nama"
                            value="{{ old('produk_nama', $produk->produk_nama) }}" readonly />
                    </div>

                    <div class="col-md-6">
                        <x-form-group label="Harga Produk" name="produk_harga"
                            value="{{ old('produk_harga', number_format($produk->produk_harga, 0, ',', '.')) }}"
                            readonly onkeyup="formatRupiah(this)" />
                    </div>

                    <div class="col-md-6">
                        <x-form-group label="Diskon (Rp)" name="produk_diskon"
                            value="{{ old('produk_diskon', number_format($produk->produk_diskon, 0, ',', '.')) }}"
                            readonly onkeyup="formatRupiah(this)" />
                    </div>

                    <div class="col-md-6">
                        <x-form-group label="Insentif (Rp)" name="produk_insentif"
                            value="{{ old('produk_insentif', number_format($produk->produk_insentif, 0, ',', '.')) }}"
                            readonly onkeyup="formatRupiah(this)" />
                    </div>

                    <div class="col-md-6">
                        <x-form-group label="Jumlah Stok" name="produk_stok" type="number"
                            placeholder="{{ old('stok_option') == 'tambah' ? 'Masukkan tambahan stok' : '' }}"
                            value="{{ old('stok_option') == 'tambah' ? '' : old('produk_stok', $produk->produk_stok) }}"
                            required />
                        <small class="text-muted">
                            Masukkan jumlah stok yang ingin ditambahkan atau mengganti stok lama.
                        </small>
                    </div>

                    @if ($produk->produk_stok > 0)
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Opsi Stok</label>
                            <select class="form-select" id="stok_option" name="stok_option" required>
                                <option value="ganti" {{ old('stok_option') == 'ganti' ? 'selected' : '' }}>
                                    Ganti Stok Lama
                                </option>
                                <option value="tambah" {{ old('stok_option') == 'tambah' ? 'selected' : '' }}>
                                    Tambah Stok Lama
                                </option>
                            </select>
                        </div>
                    @endif

                    <div class="col-md-12">
                        <x-form-group label="Detail Produk" name="produk_detail" type="textarea" rows="4"
                            value="{{ old('produk_detail', $produk->produk_detail) }}" readonly />
                    </div>

                    <div class="col-md-12">
                        <label class="fw-semibold mb-2">Merchandise</label>

                        <fieldset disabled>
                            <div class="row g-2">
                                @foreach ($merchandises as $merchandise)
                                    <div class="col-md-4 col-sm-6">
                                        <input type="checkbox" class="btn-check"
                                            id="merchandise_{{ $merchandise->id }}"
                                            checked="{{ in_array($merchandise->id, old('merchandises', $produk->merchandises->pluck('id')->toArray())) }}">

                                        <label class="btn btn-outline-secondary w-100"
                                            for="merchandise_{{ $merchandise->id }}">
                                            {{ $merchandise->merch_nama }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </fieldset>
                    </div>

                </div>

                <div class="d-flex justify-content-center gap-3 mt-4">
                    <x-form-button type="submit" variant="primary">
                        Simpan Perubahan
                    </x-form-button>

                    <a href="/programhaji/produk">
                        <x-form-button type="button" variant="danger" id="batalBtn">
                            Batal
                        </x-form-button>
                    </a>
                </div>
            </form>
        </x-form-card>

    </div>
    <script>
        function formatRupiah(angka) {
            let numberString = angka.value.replace(/[^,\d]/g, '').toString();
            let split = numberString.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
            angka.value = rupiah;
        }

        function removeFormatBeforeSubmit() {
            let hargaInput = document.getElementById('produk_harga');
            let diskonInput = document.getElementById('produk_diskon');
            let insentifInput = document.getElementById('produk_insentif');
            let hargaValue = hargaInput.value.replace(/\./g, '').replace(',', '.');
            let diskonValue = diskonInput.value.replace(/\./g, '').replace(',', '.');
            let insentifValue = insentifInput.value.replace(/\./g, '').replace(',', '.');

            hargaInput.value = hargaValue;
            diskonInput.value = diskonValue === '' ? 0 : parseInt(diskonValue);
            insentifInput.value = insentifValue === '' ? 0 : parseInt(insentifValue);
        }
    </script>
</x-Supvis.SupvisLayouts>
