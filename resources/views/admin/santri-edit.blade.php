@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Data Santri</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.santri.update', $santri->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Nama Santri</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $santri->name) }}" required>
        </div>

        <div class="mb-3">
            <label for="year" class="form-label">Tahun Ajaran</label>
            <input type="number" class="form-control" id="year" name="year" value="{{ old('year', $santri->year) }}" required>
        </div>

        <div class="mb-3">
            <label for="rfid" class="form-label">RFID Santri</label>
            <div class="input-group">
                <input type="text" class="form-control" id="rfid" name="rfid_number" value="{{ old('rfid_number', $santri->rfid_number) }}" required readonly autocomplete="off">
                <button type="button" class="btn btn-primary" id="scanRFID">
                    <span id="scanText">Scan RFID</span>
                    <span id="loading" class="spinner-border spinner-border-sm d-none"></span>
                </button>
            </div>
            <small class="text-muted">Klik "Scan RFID" lalu tempelkan kartu RFID ke scanner.</small>
        </div>

        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
        <a href="{{ route('admin.santri.detail', $santri->id) }}" class="btn btn-secondary">Batal</a>
    </form>
</div>

<script>
document.getElementById('scanRFID').addEventListener('click', function() {
    let scanButton = document.getElementById('scanRFID');
    let scanText = document.getElementById('scanText');
    let loading = document.getElementById('loading');
    let rfidInput = document.getElementById('rfid');

    // Tampilkan loading
    scanText.classList.add('d-none');
    loading.classList.remove('d-none');

    fetch("{{ route('admin.scan.rfid') }}")
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                rfidInput.value = data.rfid_number;
                showAlert("RFID berhasil dipindai: " + data.rfid_number, "success");
            } else {
                showAlert("Gagal mendapatkan data RFID.", "danger");
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert("Terjadi kesalahan, coba lagi.", "danger");
        })
        .finally(() => {
            // Sembunyikan loading
            scanText.classList.remove('d-none');
            loading.classList.add('d-none');
        });
});

function showAlert(message, type) {
    let alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.textContent = message;
    document.querySelector('.container').prepend(alertDiv);

    setTimeout(() => {
        alertDiv.remove();
    }, 3000);
}
</script>

@endsection
