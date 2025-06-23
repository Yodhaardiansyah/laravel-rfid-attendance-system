@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="glass-card text-center">
                <h2 class="mb-3 text-primary"><i class="fas fa-fingerprint me-2"></i> Absensi Santri Real-Time</h2>
                <p class="text-secondary mb-4">Sistem ini menampilkan data absensi santri secara langsung setiap beberapa detik.</p>

                <!-- Loading indicator -->
                <div id="loading" class="mb-3" style="display:none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

                <!-- Data Absensi -->
                <div id="attendance-box" class="glass-card mt-3">
                    <h4 id="santri-name"><i class="fas fa-user"></i> Menunggu data...</h4>
                    <p id="prayer-name" class="text-muted"><i class="fas fa-mosque"></i> ---</p>
                    <p id="status" class="text-info"><i class="fas fa-clock"></i> ---</p>
                    <p id="time" class="text-danger"><i class="fas fa-calendar-alt"></i> ---</p>
                </div>

                <div class="mt-4">
                    <a href="{{ url('/') }}" class="btn btn-outline-light px-4 py-2 rounded shadow">
                        <i class="fas fa-sign-in-alt me-1"></i> Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let timeout;

    function fetchLatestAttendance() {
        document.getElementById('loading').style.display = 'block';

        fetch('/api/attendance/latest')
            .then(response => response.json())
            .then(data => {
                if (data.santri) {
                    document.getElementById('santri-name').innerHTML = `<i class="fas fa-user"></i> ${data.santri}`;
                    document.getElementById('prayer-name').innerHTML = `<i class="fas fa-mosque"></i> Sholat: ${data.prayer}`;
                    document.getElementById('status').innerHTML = `<i class="fas fa-clock"></i> Status: ${data.status}`;
                    document.getElementById('time').innerHTML = `<i class="fas fa-calendar-alt"></i> Waktu: ${data.time}`;
                    resetTimeout();
                }
            })
            .catch(error => console.error("Gagal mengambil data:", error))
            .finally(() => {
                document.getElementById('loading').style.display = 'none';
            });
    }

    function resetTimeout() {
        clearTimeout(timeout);
        timeout = setTimeout(resetDisplay, 5000);
    }

    function resetDisplay() {
        document.getElementById('santri-name').innerHTML = `<i class="fas fa-user"></i> Menunggu data...`;
        document.getElementById('prayer-name').innerHTML = `<i class="fas fa-mosque"></i> ---`;
        document.getElementById('status').innerHTML = `<i class="fas fa-clock"></i> ---`;
        document.getElementById('time').innerHTML = `<i class="fas fa-calendar-alt"></i> ---`;
    }

    setInterval(fetchLatestAttendance, 3000);
</script>
@endsection
