@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="glass-card">
        <a href="{{ url('/') }}" class="btn btn-outline-light mb-3">← Kembali ke Home</a>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 style="color: var(--text-color);">Daftar Santri</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSantriModal">+ Tambah Santri</button>
        </div>

        <div class="table-responsive">
            <table class="table table-transparent table-hover table-borderless align-middle">
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
                            <button class="btn btn-warning btn-sm" onclick="editSantri({{ $santri }})" data-bs-toggle="modal" data-bs-target="#editSantriModal">Edit</button>
                            <button class="btn btn-danger btn-sm" onclick="deleteSantri({{ $santri->id }})">Hapus</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
@include('layouts.modal-add')

<!-- Modal Edit -->
@include('layouts.modal-edit')

<script src="{{ asset('assets/js/santri.js') }}"></script>
@endsection
