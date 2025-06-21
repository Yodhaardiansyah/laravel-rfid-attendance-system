@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg border-0 rounded">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0"><i class="fas fa-tachometer-alt"></i> Dashboard Admin</h4>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <p class="text-center text-muted"><i class="fas fa-user-shield"></i> Anda telah berhasil masuk sebagai Admin.</p>

                    <!-- Pilihan Menu dalam Kotak Besar -->
                    <div class="row g-4">
                        <div class="col-md-4">
                            <a href="{{ url('/admin/dashboard') }}" class="text-decoration-none">
                                <div class="card text-center shadow-sm border-0 h-100">
                                    <div class="card-body">
                                        <i class="fas fa-chart-line fa-3x text-primary mb-3"></i>
                                        <h5 class="card-title">Dashboard Admin</h5>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-4">
                            <a href="{{ url('/admin/santri') }}" class="text-decoration-none">
                                <div class="card text-center shadow-sm border-0 h-100">
                                    <div class="card-body">
                                        <i class="fas fa-users fa-3x text-success mb-3"></i>
                                        <h5 class="card-title">Daftar Santri</h5>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-4">
                            <a href="{{ url('/admin/attendance/manual') }}" class="text-decoration-none">
                                <div class="card text-center shadow-sm border-0 h-100">
                                    <div class="card-body">
                                        <i class="fas fa-clipboard-check fa-3x text-warning mb-3"></i>
                                        <h5 class="card-title">Absensi Manual</h5>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Tombol Logout -->
                    <div class="mt-4 text-center">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>  
            </div>
        </div>
    </div>
</div>
@endsection
