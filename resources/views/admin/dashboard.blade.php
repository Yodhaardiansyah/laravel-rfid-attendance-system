@extends('layouts.app')

@section('content')

<div class="container">
    <!-- Tombol Kembali ke Home -->
    <a href="{{ url('/') }}" class="btn btn-secondary mb-3">Kembali ke Home</a>

    <h1 class="my-4">Dashboard Admin</h1>


    <!-- Tabel Waktu Sholat -->
    <div class="card mb-4">
        <div class="card-header">Waktu Sholat</div>
        <div class="card-body">
            <table class="table table-bordered text-center">
                <thead class="table-dark">
                    <tr>
                        @foreach ($prayerTimes as $prayer)
                            <th>{{ ucfirst($prayer->name) }}</th>
                        @endforeach
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @foreach ($prayerTimes as $prayer)
                            <td>{{ $prayer->time }}</td>
                        @endforeach
                        <td>
                            <a href="{{ route('admin.prayer.edit') }}" class="btn btn-primary btn-sm">
                                Edit Waktu Sholat
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Daftar Santri -->
    <h2 class="mb-4">Daftar Santri</h2>
    <!-- Tombol Download -->
    <div class="d-flex flex-wrap gap-2 mb-3">
        <a href="{{ route('admin.export.excel') }}" class="btn btn-success">
            <i class="fas fa-file-excel"></i> Download Excel
        </a>
        <a href="{{ route('admin.export.csv') }}" class="btn btn-primary"> 
            <i class="fas fa-file-csv"></i> Download CSV
        </a>
    </div>
    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Nama Santri</th>
                <th>Tahun Ajaran</th>
                <th>Status Absen Terakhir</th>
                <th>Sholat Terakhir</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($santris as $santri)
            <tr>
                <th>{{ $loop->iteration }}</th>
                <td>{{ $santri->name }}</td>
                <td>{{ $santri->year }}</td>
                <td>
                    @php
                        $latestAttendance = $santri->attendances->sortByDesc('time')->first();
                    @endphp
                    @if ($latestAttendance)
                        @php
                            $attendanceTime = \Carbon\Carbon::parse($latestAttendance->time);
                            $statusClass = match ($latestAttendance->status) {
                                'Tepat Waktu' => 'bg-success',
                                'Telat' => 'bg-warning',
                                default => 'bg-danger'
                            };
                        @endphp
                        <span class="badge {{ $statusClass }}">
                            {{ $latestAttendance->status }} ({{ $attendanceTime->format('d M Y H:i') }})
                        </span>
                    @else
                        <span class="badge bg-danger">Belum Absen</span>
                    @endif
                </td>
                <td>
                    @if ($latestAttendance)
                        {{ ucfirst($latestAttendance->prayer_name) }} ({{ $attendanceTime->format('H:i:s') }})
                    @else
                        Tidak ditemukan
                    @endif
                </td>
                <td>
                    <a href="{{ url('/admin/santri/' . $santri->id) }}" class="btn btn-info btn-sm">Lihat</a>
                    <button class="btn btn-warning btn-sm" onclick="openEditModal({{ $santri }})">
                        Edit
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center text-muted">Belum ada data santri.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal Edit Santri -->
<div class="modal fade" id="editSantriModal" tabindex="-1" aria-labelledby="editSantriModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSantriModalLabel">Edit Data Santri</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editSantriForm">
                    @csrf
                    @method('PUT')

                    <input type="hidden" id="editSantriId">

                    <div class="mb-3">
                        <label for="editName" class="form-label">Nama Santri</label>
                        <input type="text" class="form-control" id="editName" required>
                    </div>

                    <div class="mb-3">
                        <label for="editYear" class="form-label">Tahun Ajaran</label>
                        <input type="number" class="form-control" id="editYear" required>
                    </div>

                    <div class="mb-3">
                        <label for="editRFID" class="form-label">RFID Santri</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="editRFID" required readonly>
                            <button type="button" class="btn btn-primary" id="scanRFID">
                                <span id="scanText">Scan RFID</span>
                                <span id="loading" class="spinner-border spinner-border-sm d-none"></span>
                            </button>
                        </div>
                        <small class="text-muted">Klik "Scan RFID" lalu tempelkan kartu RFID ke scanner.</small>
                    </div>

                    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap & Custom JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function openEditModal(santri) {
    document.getElementById('editSantriId').value = santri.id;
    document.getElementById('editName').value = santri.name;
    document.getElementById('editYear').value = santri.year;
    document.getElementById('editRFID').value = santri.rfid_number;

    let modal = new bootstrap.Modal(document.getElementById('editSantriModal'));
    modal.show();
}
</script>

@endsection
