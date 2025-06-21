<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Cache;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/attendance/record', [AttendanceController::class, 'recordAttendance']);
Route::post('/attendance', [AttendanceController::class, 'store']);
Route::post('/rfid-scan', [AdminController::class, 'storeRFID']);

Route::post('/push-rfid', function (Request $request) {
    $request->validate([
        'rfid_number' => 'required|string',
    ]);
    Cache::put('recent_rfid', $request->rfid_number, 5);
    return response()->json(['success' => true]);
});

Route::get('/push-rfid', function () {
    $rfid = Cache::get('recent_rfid');
    if (!$rfid) {
        return response()->json(['message' => 'Tidak ada RFID terbaru'], 404);
    }
    return response()->json(['rfid_number' => $rfid]);
});
