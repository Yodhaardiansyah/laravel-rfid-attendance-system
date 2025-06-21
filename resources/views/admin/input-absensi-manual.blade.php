@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Tombol Kembali ke Home -->
<a href="{{ url('/') }}" class="btn btn-secondary mb-3">Kembali ke Home</a>

    <h2 class="mt-4">Input Absensi Manual</h2>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.attendance.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="santri_id" class="form-label">Pilih Santri</label>
            <select name="santri_id" id="santri_id" class="form-control" required>
                <option value="" selected disabled>-- Pilih Santri --</option>
                @foreach($santriList as $santri)
                    <option value="{{ $santri->id }}">{{ $santri->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="prayer_name" class="form-label">Sholat</label>
            <select name="prayer_name" id="prayer_name" class="form-control" required>
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
            <select name="status" id="status" class="form-control" required>
                <option value="Tepat Waktu">Tepat Waktu</option>
                <option value="Terlambat">Terlambat</option>
                <option value="Belum Waktunya">Belum Waktunya</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="time" class="form-label">Waktu (Opsional)</label>
            <input type="datetime-local" name="time" id="time" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Simpan Absensi</button>
    </form>
</div>
@endsection
