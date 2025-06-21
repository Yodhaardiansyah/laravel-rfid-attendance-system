@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Edit Waktu Sholat</h1>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary mb-3">
        ← Kembali ke Dashboard
    </a>
    
    <div class="card">
        <div class="card-header">Pengaturan Waktu Sholat</div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('admin.prayer.update') }}" method="POST">
                @csrf
                @method('PUT')
            
                @foreach ($prayerTimes as $prayer)
                    <div class="mb-3">
                        <label class="form-label">{{ ucfirst($prayer->name) }}:</label>
                        <input type="time" class="form-control" name="times[{{ $prayer->name }}]" value="{{ \Carbon\Carbon::parse($prayer->time)->format('H:i') }}" required>
                    </div>
                @endforeach
            
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</div>
@endsection
