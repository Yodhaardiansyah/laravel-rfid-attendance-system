@extends('layouts.app')

@section('content')

<div class="container mt-4">
    <!-- Tombol Kembali ke Home -->
<a href="{{ url('/') }}" class="btn btn-secondary mb-3">Kembali ke Home</a>

    <h2>Daftar Santri</h2>
    
    <!-- Tombol Tambah -->
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addSantriModal">Tambah Santri</button>

    <!-- Tabel Santri -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>RFID</th>
                <th>Tahun Ajaran</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="santriTable">
            @foreach($santris as $index => $santri)
            <tr id="row-{{ $santri->id }}">
                <td>{{ $index + 1 }}</td>
                <td>{{ $santri->name }}</td>
                <td>{{ $santri->rfid_number }}</td>
                <td>{{ $santri->year }}</td>
                <td>
                    <!-- Tombol Edit -->
                    <button class="btn btn-warning btn-sm" onclick="editSantri({{ $santri }})" data-bs-toggle="modal" data-bs-target="#editSantriModal">Edit</button>

                    <!-- Tombol Hapus -->
                    <button class="btn btn-danger btn-sm" onclick="deleteSantri({{ $santri->id }})">Hapus</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Tambah Santri -->
<div class="modal fade" id="addSantriModal" tabindex="-1" aria-labelledby="addSantriLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSantriLabel">Tambah Santri</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addSantriForm" method="POST" action="{{ route('santri.store') }}">
                    @csrf
            
                    <!-- Nama Santri -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Santri:</label>
                        <input type="text" class="form-control" id="name" name="name" required placeholder="Masukkan nama santri">
                    </div>
            
                    <!-- RFID Number -->
                    <div class="mb-3">
                        <label for="rfid_number" class="form-label">RFID Number:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="rfid_number" name="rfid_number" required readonly placeholder="Klik 'Ambil RFID'">
                            <button type="button" id="fetchRfid" class="btn btn-primary">Ambil RFID</button>
                        </div>
                    </div>
            
                    <!-- Tahun Ajaran -->
                    <div class="mb-3">
                        <label for="year" class="form-label">Tahun Ajaran:</label>
                        <input type="number" class="form-control" id="year" name="year" required min="2000" max="{{ date('Y') }}" placeholder="Masukkan tahun ajaran">
                    </div>
            
                    <!-- Tombol Submit -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success">Tambah Santri</button>
                    </div>
                </form>
            
                <!-- Notifikasi -->
                <div id="notification" class="alert d-none mt-3"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Santri -->
<div class="modal fade" id="editSantriModal" tabindex="-1" aria-labelledby="editSantriLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSantriLabel">Edit Santri</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editSantriForm" method="POST">
                    @csrf
                    @method('PUT') <!-- 🔥 Wajib untuk Laravel -->
                    
                    <input type="hidden" id="edit_id" name="id">
                
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Nama Santri:</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                
                    <div class="mb-3">
                        <label for="edit_rfid_number" class="form-label">RFID Number:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="edit_rfid_number" name="rfid_number" required>
                            <button type="button" class="btn btn-primary" id="fetchRfidEdit">Ambil RFID</button>
                        </div>
                    </div>
                            
                    
                    <div class="mb-3">
                        <label for="edit_year" class="form-label">Tahun Ajaran:</label>
                        <input type="number" class="form-control" id="edit_year" name="year" required min="2000" max="{{ date('Y') }}">
                    </div>
                
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/santri.js') }}"></script>
@endsection
