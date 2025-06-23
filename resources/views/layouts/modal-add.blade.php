<!-- Modal Tambah Santri -->
<div class="modal fade" id="addSantriModal" tabindex="-1" aria-labelledby="addSantriLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="background: var(--card-bg); color: var(--text-color);">
            <div class="modal-header">
                <h5 class="modal-title" id="addSantriLabel">Tambah Santri</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <form id="addSantriForm" method="POST" action="{{ route('santri.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Santri:</label>
                        <input type="text" class="form-control" id="name" name="name" required placeholder="Masukkan nama santri">
                    </div>

                    <div class="mb-3">
                        <label for="rfid_number" class="form-label">RFID Number:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="rfid_number" name="rfid_number" required readonly placeholder="Klik 'Ambil RFID'">
                            <button type="button" id="fetchRfid" class="btn btn-outline-primary">Ambil RFID</button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="year" class="form-label">Tahun Ajaran:</label>
                        <input type="number" class="form-control" id="year" name="year" required min="2000" max="{{ date('Y') }}" placeholder="Masukkan tahun ajaran">
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">Tambah</button>
                    </div>
                </form>

                <div id="notification" class="alert d-none mt-3"></div>
            </div>
        </div>
    </div>
</div>
    