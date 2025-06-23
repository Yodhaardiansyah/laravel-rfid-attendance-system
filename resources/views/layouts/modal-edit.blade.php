<!-- Modal Edit Santri -->
<div class="modal fade" id="editSantriModal" tabindex="-1" aria-labelledby="editSantriLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="background: var(--card-bg); color: var(--text-color);">
            <div class="modal-header">
                <h5 class="modal-title" id="editSantriLabel">Edit Santri</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <form id="editSantriForm" method="POST">
                    @csrf
                    @method('PUT')

                    <input type="hidden" id="edit_id" name="id">

                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Nama Santri:</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit_rfid_number" class="form-label">RFID Number:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="edit_rfid_number" name="rfid_number" required>
                            <button type="button" class="btn btn-outline-primary" id="fetchRfidEdit">Ambil RFID</button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_year" class="form-label">Tahun Ajaran:</label>
                        <input type="number" class="form-control" id="edit_year" name="year" required min="2000" max="{{ date('Y') }}">
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-warning">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
