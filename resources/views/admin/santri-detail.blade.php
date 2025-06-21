@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Detail Santri</h2>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th>Nama</th>
                    <td>{{ $santri->name }}</td>
                </tr>
                <tr>
                    <th>Tahun Ajaran</th>
                    <td>{{ $santri->year }}</td>
                </tr>
            </table>

            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                </a>

                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addAttendanceModal">
                    <i class="fas fa-plus"></i> Tambah Absensi Manual
                </button>

                <a href="{{ route('admin.santri.edit', $santri->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit Santri
                </a>
            </div>
        </div>
    </div>

    <!-- Riwayat Absensi -->
    <h3 class="mb-3">Riwayat Absensi</h3>

    <div class="card shadow-sm mb-3">
        <div class="card-body d-flex gap-4">
            <div>
                <h5 class="mb-1">Total Terlambat</h5>
                <p class="text-danger fs-4 fw-bold mb-0">{{ $total_terlambat }}</p>
            </div>
            <div>
                <h5 class="mb-1">Total Tepat Waktu</h5>
                <p class="text-success fs-4 fw-bold mb-0">{{ $total_tepat_waktu }}</p>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <label for="filterType" class="form-label">Filter Tipe:</label>
            <select id="filterType" class="form-select" onchange="updateFilterInput()">
                <option value="bulan" {{ request('filter_type') == 'bulan' ? 'selected' : '' }}>Bulan</option>
                <option value="minggu" {{ request('filter_type') == 'minggu' ? 'selected' : '' }}>Minggu</option>
                <option value="hari" {{ request('filter_type') == 'hari' ? 'selected' : '' }}>Hari</option>
            </select>

            <div id="filterInputContainer" class="mt-3">
                <!-- Filter input will be inserted here by JS -->
            </div>

            <button class="btn btn-primary mt-3" onclick="filterAttendance()">
                <i class="fas fa-filter"></i> Filter
            </button>
        </div>
    </div>

    <!-- Tombol Download Riwayat Absensi -->
    <div class="d-flex flex-wrap gap-2 mb-3">
        <a href="{{ route('admin.santri.attendance.export', ['santri_id' => $santri->id, 'format' => 'csv']) }}" class="btn btn-success">
            <i class="fas fa-file-csv"></i> Download CSV
        </a>
        <a href="{{ route('admin.santri.attendance.export', ['santri_id' => $santri->id, 'format' => 'xlsx']) }}" class="btn btn-primary">
            <i class="fas fa-file-excel"></i> Download Excel
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Waktu</th>
                        <th>Nama Sholat</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($attendances as $attendance)
                        <tr>
                            <td>{{ $attendance->time }}</td>
                            <td>{{ $attendance->prayer_name }}</td>
                            <td>{{ $attendance->status }}</td>
                            <td class="text-center">
                                <button class="btn btn-danger btn-sm" onclick="confirmDelete({{ $attendance->id }})">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                                <form id="delete-form-{{ $attendance->id }}" 
                                    action="{{ route('admin.attendance.destroy', $attendance->id) }}" 
                                    method="POST" 
                                    style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- Pagination -->
        <div class="d-flex justify-content-center">
            <nav aria-label="Page navigation">
                <ul class="pagination pagination-sm my-2">
                    {!! $attendances->appends(request()->only(['filter_type', 'filter_value']))->links('pagination::bootstrap-4') !!}

                </ul>
            </nav>
        </div>

    </div>
</div>

<!-- Modal Tambah Absensi Manual -->
<div class="modal fade" id="addAttendanceModal" tabindex="-1" aria-labelledby="addAttendanceLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAttendanceLabel">Tambah Absensi Manual</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addAttendanceForm" action="{{ route('admin.attendance.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="santri_id" value="{{ $santri->id }}">

                    <div class="mb-3">
                        <label for="time" class="form-label">Waktu:</label>
                        <input type="datetime-local" class="form-control" id="time" name="time" required>
                    </div>

                    <div class="mb-3">
                        <label for="prayer_name" class="form-label">Nama Sholat:</label>
                        <select class="form-control" id="prayer_name" name="prayer_name" required>
                            <option value="subuh">Subuh</option>
                            <option value="dzuhur">Dzuhur</option>
                            <option value="ashar">Ashar</option>
                            <option value="maghrib">Maghrib</option>
                            <option value="isya">Isya</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Status:</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="Tepat Waktu">Tepat Waktu</option>
                            <option value="Terlambat">Terlambat</option>
                            <option value="Belum Waktunya">Belum Waktunya</option>
                        </select>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-plus"></i> Tambah Absensi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(attendanceId) {
        if (confirm("Apakah Anda yakin ingin menghapus absensi ini?")) {
            document.getElementById(`delete-form-${attendanceId}`).submit();
        }
    }

    function updateFilterInput() {
        const filterType = document.getElementById('filterType').value;
        const container = document.getElementById('filterInputContainer');
        container.innerHTML = '';

        if (filterType === 'hari') {
            container.innerHTML = `
                <label for="filterDay" class="form-label">Pilih Hari:</label>
                <input type="date" id="filterDay" class="form-control" value="{{ request('filter_value') ?? '' }}">
            `;
        } else if (filterType === 'minggu') {
            container.innerHTML = `
                <label for="filterWeek" class="form-label">Pilih Minggu (Tahun-Minggu):</label>
                <input type="week" id="filterWeek" class="form-control" value="{{ request('filter_value') ?? '' }}">
            `;
        } else { // bulan
            let monthOptions = '<option value="">Semua Bulan</option>';
            for (let i = 1; i <= 12; i++) {
                const selected = "{{ request('filter_value') }}" == i ? 'selected' : '';
                const monthName = new Date(0, i - 1).toLocaleString('id-ID', { month: 'long' });
                monthOptions += `<option value="${i}" ${selected}>${monthName}</option>`;
            }
            container.innerHTML = `
                <label for="filterMonth" class="form-label">Pilih Bulan:</label>
                <select id="filterMonth" class="form-select">${monthOptions}</select>
            `;
        }
    }

    function filterAttendance() {
        const filterType = document.getElementById('filterType').value;
        let filterValue = '';
        if (filterType === 'hari') {
            filterValue = document.getElementById('filterDay').value;
        } else if (filterType === 'minggu') {
            filterValue = document.getElementById('filterWeek').value;
        } else {
            filterValue = document.getElementById('filterMonth').value;
        }

        const url = new URL(window.location.href);
        url.searchParams.set('filter_type', filterType);
        url.searchParams.set('filter_value', filterValue);
        url.searchParams.delete('page'); // Reset pagination on filter change
        window.location.href = url.toString();
    }

    document.addEventListener('DOMContentLoaded', function () {
        updateFilterInput();
    });
</script>

@endsection
