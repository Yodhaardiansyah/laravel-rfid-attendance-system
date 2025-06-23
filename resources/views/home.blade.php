@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="glass-card">
                <h4 class="mb-4 text-primary">
                    <i class="fas fa-tachometer-alt me-2"></i> Home Admin
                </h4>

                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show rounded" role="alert">
                        <i class="fas fa-check-circle me-1"></i> {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <p class="text-center text-secondary">
                    <i class="fas fa-user-shield me-2"></i> Anda telah berhasil masuk sebagai Admin.
                </p>

                <!-- Menu Cards -->
                <div class="row g-4 mt-4">
                    <div class="col-md-4">
                        <a href="{{ url('/admin/dashboard') }}" class="btn btn-outline-light w-100 py-3 shadow rounded glass-card text-decoration-none">
                            <i class="fas fa-cogs fa-lg mb-2"></i><br> Kelola Absensi
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ url('/admin/santri') }}" class="btn btn-outline-light w-100 py-3 shadow rounded glass-card text-decoration-none">
                            <i class="fas fa-users fa-lg mb-2"></i><br> Daftar Santri
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ url('/admin/attendance/manual') }}" class="btn btn-outline-light w-100 py-3 shadow rounded glass-card text-decoration-none">
                            <i class="fas fa-edit fa-lg mb-2"></i><br> Input Manual
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
