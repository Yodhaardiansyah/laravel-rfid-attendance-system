@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Status Absensi Santri</h1>

    <div class="row">
        <div class="col-md-12">
            <h2 class="mb-4">Daftar Santri</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nama Santri</th>
                        <th scope="col">Status Absen</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($santris as $santri)
                    <tr>
                        <th scope="row">{{ $santri->id }}</th>
                        <td>{{ $santri->name }}</td>
                        <td>
                            @if ($santri->latestAttendance)
                                {{ $santri->latestAttendance->status }}
                            @else
                                Belum Absen
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
