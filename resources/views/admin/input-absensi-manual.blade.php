@extends('layouts.app')

@section('content')
<div class="container">
    <div class="glass-card">
        <a href="{{ url('/') }}" class="btn btn-outline-light mb-3">← Kembali ke Home</a>

        <h2 class="mb-4" style="color: var(--text-color)">Input Absensi Manual</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('admin.attendance.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="santri_id" class="form-label">Pilih Santri</label>
                <select name="santri_id" id="santri_id" class="form-select" required>
                    <option value="" selected disabled>-- Pilih Santri --</option>
                    @foreach($santriList as $santri)
                        <option value="{{ $santri->id }}">{{ $santri->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="prayer_name" class="form-label">Sholat</label>
                <select name="prayer_name" id="prayer_name" class="form-select" required>
                    <option value="" selected disabled>-- Pilih Sholat --</option>
                    <option value="subuh">Subuh</option>
                    <option value="dzuhur">Dzuhur</option>
                    <option value="ashar">Ashar</option>
                    <option value="maghrib">Maghrib</option>
                    <option value="isya">Isya</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select" required>
                    <option value="Tepat Waktu">Tepat Waktu</option>
                    <option value="Terlambat">Terlambat</option>
                    <option value="Belum Waktunya">Belum Waktunya</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="time" class="form-label">Waktu (Opsional)</label>
                <input type="datetime-local" name="time" id="time" class="form-control">
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Simpan Absensi</button>
            </div>
        </form>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const timeInput = document.getElementById("time");
        if (timeInput && !timeInput.value) {
            const now = new Date();
            const localTime = now.toISOString().slice(0, 16);
            timeInput.value = localTime;
        }
    });
</script>


@endsection
