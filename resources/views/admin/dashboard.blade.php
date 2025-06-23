@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="glass-card mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="text-primary"><i class="fas fa-chart-line me-2"></i> Dashboard Admin</h3>
            <a href="{{ url('/') }}" class="btn btn-outline-light">
                <i class="fas fa-home me-1"></i> Kembali ke Home
            </a>
        </div>

        <!-- Statistik -->
        <div class="row g-4 mb-4 text-center">
            <div class="col-md-4">
                <div class="glass-card">
                    <h6><i class="fas fa-users me-1"></i> Total Santri</h6>
                    <h2 class="text-primary">{{ $totalSantri ?? '--' }}</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="glass-card">
                    <h6><i class="fas fa-clipboard-check me-1"></i> Absensi Hari Ini</h6>
                    <h2 class="text-success">{{ $totalHadir ?? '--' }}</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="glass-card">
                    <h6><i class="fas fa-mosque me-1"></i> Sholat Terakhir</h6>
                    <h2 class="text-warning">{{ $latestPrayer ?? '--' }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Waktu Sholat -->
    <div class="glass-card mb-4">
        <h5 class="text-info mb-3"><i class="fas fa-clock me-2"></i> Waktu Sholat</h5>
        <div class="table-responsive">
            <table class="table table-transparent table-borderless text-center align-middle" style="background-color: transparent; color: var(--text-color);">
                <thead>
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
                            <a href="{{ route('admin.prayer.edit') }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-pen"></i> Edit
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Daftar Santri -->
    <div class="glass-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="text-info"><i class="fas fa-users me-2"></i> Daftar Santri</h5>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.export.excel') }}" class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Excel
                </a>
                <a href="{{ route('admin.export.csv') }}" class="btn btn-primary">
                    <i class="fas fa-file-csv"></i> CSV
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-transparent table-borderless align-middle" style="background-color: transparent; color: var(--text-color);">
                <thead>
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
                            <button class="btn btn-warning btn-sm" onclick="openEditModal({{ $santri }})">Edit</button>
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
    </div>
</div>

<!-- Modal Edit Santri -->
<div class="modal fade" id="editSantriModal" tabindex="-1" aria-labelledby="editSantriModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="background: var(--card-bg); color: var(--text-color);">
            <div class="modal-header">
                <h5 class="modal-title" id="editSantriModalLabel">Edit Data Santri</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
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
                        <small class="text-muted">Klik "Scan RFID" lalu tempelkan kartu ke scanner.</small>
                    </div>

                    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>

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
