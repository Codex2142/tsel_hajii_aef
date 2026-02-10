<x-Supvis.SupvisLayouts>
    <div class="container my-4">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <h3 class="fw-bold mb-0">Kelola Bertugas - Role Sales</h3>

            <div class="d-flex gap-2">
                <a href="{{ route('add_sales') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah
                </a>
                <button form="massUpdateForm" class="btn btn-success">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </div>

        <!-- Filter -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Status Bertugas</label>
                        <select id="filter-bertugas" class="form-select">
                            <option value="">Semua</option>
                            <option value="1">Ya</option>
                            <option value="0">Tidak</option>
                        </select>
                    </div>

                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Tempat Tugas</label>
                        <input type="text" id="filter-tempat" class="form-control"
                            placeholder="Cari berdasarkan tempat tugas...">
                    </div>

                    <div class="col-md-4 text-end">
                        <button type="button" class="btn btn-outline-secondary w-100" onclick="resetFilter()">
                            <i class="fas fa-sync"></i> Reset Filter
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('role-users.mass-update') }}" method="POST" id="massUpdateForm">
            @csrf

            <div class="card shadow-sm border-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="userTable">
                        <thead class="table-light">
                            <tr class="text-center">
                                <th style="width:50px">
                                    <input type="checkbox" id="select-all" class="form-check-input">
                                </th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th style="width:110px">Bertugas</th>
                                <th>Tempat Tugas</th>
                                <th style="width:80px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="userTableBody">
                            @foreach ($users as $user)
                                <tr data-user-id="{{ $user->id }}">
                                    <td class="text-center">
                                        <input type="checkbox" class="form-check-input row-check">
                                        <input type="hidden" name="users[{{ $user->id }}][id]"
                                            value="{{ $user->id }}">
                                    </td>

                                    <td>
                                        <input type="text" name="users[{{ $user->id }}][name]"
                                            value="{{ $user->name }}" class="form-control form-control-sm">
                                    </td>

                                    <td>
                                        <input type="email" name="users[{{ $user->id }}][email]"
                                            value="{{ $user->email }}" class="form-control form-control-sm">
                                    </td>

                                    <td>
                                        <input type="text" name="users[{{ $user->id }}][phone]"
                                            value="{{ $user->phone }}" class="form-control form-control-sm">
                                    </td>

                                    <td>
                                        <select name="users[{{ $user->id }}][bertugas]"
                                            class="form-select form-select-sm">
                                            <option value="1" {{ $user->bertugas ? 'selected' : '' }}>Ya</option>
                                            <option value="0" {{ !$user->bertugas ? 'selected' : '' }}>Tidak
                                            </option>
                                        </select>
                                    </td>

                                    <td>
                                        <input type="text" name="users[{{ $user->id }}][tempat_tugas]"
                                            value="{{ $user->tempat_tugas }}" class="form-control form-control-sm">
                                    </td>

                                    <td class="text-center">
                                        <button type="button" class="btn btn-outline-danger btn-sm delete-row">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <input type="hidden" name="deleted_ids[]" value="{{ $user->id }}"
                                            disabled>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </form>

    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // SELECT ALL
            const selectAll = document.getElementById('select-all');

            selectAll.addEventListener('change', function() {
                document.querySelectorAll('.row-check').forEach(cb => {
                    cb.checked = this.checked;
                });
            });



            // FILTER
            document.getElementById('filter-bertugas').addEventListener('change', filterTable);
            document.getElementById('filter-tempat').addEventListener('input', filterTable);

            function filterTable() {
                const bertugasFilter = document.getElementById('filter-bertugas').value;
                const tempatFilter = document.getElementById('filter-tempat').value.toLowerCase();

                document.querySelectorAll('#userTableBody tr').forEach(row => {

                    const bertugas = row.querySelector('select').value;
                    const tempat = row.children[5].querySelector('input').value.toLowerCase();

                    let show = true;

                    if (bertugasFilter !== '' && bertugas !== bertugasFilter) {
                        show = false;
                    }

                    if (tempatFilter && !tempat.includes(tempatFilter)) {
                        show = false;
                    }

                    row.style.display = show ? '' : 'none';
                });
            }

            // SORTING TEMPAT TUGAS
            sortTable();

            function sortTable() {
                const tbody = document.getElementById("userTableBody");
                const rows = Array.from(tbody.querySelectorAll("tr"));

                rows.sort((a, b) => {
                    const at = a.children[5].querySelector('input').value.toLowerCase();
                    const bt = b.children[5].querySelector('input').value.toLowerCase();
                    return at.localeCompare(bt);
                });

                rows.forEach(row => tbody.appendChild(row));
            }

            // DELETE ROW
            document.querySelectorAll('.delete-row').forEach(btn => {
                btn.addEventListener('click', function() {
                    const row = this.closest('tr');
                    const hidden = row.querySelector('input[name="deleted_ids[]"]');

                    hidden.disabled = false;
                    row.style.display = 'none';
                });
            });

        });
    </script>

</x-Supvis.SupvisLayouts>
