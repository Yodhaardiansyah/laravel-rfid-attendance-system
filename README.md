
# Laravel RFID Attendance System

![License](https://img.shields.io/github/license/Yodhaardiansyah/laravel-rfid-attendance-system)
![Laravel](https://img.shields.io/badge/Laravel-10.x-red)
![RFID](https://img.shields.io/badge/RFID-Enabled-green)

Sistem absensi digital berbasis **RFID** menggunakan **Laravel** sebagai backend dan web interface. Proyek ini dirancang untuk digunakan di pondok pesantren, sekolah, atau institusi lain yang membutuhkan sistem presensi otomatis berbasis kartu RFID.

## 🔧 Fitur Utama

- Absensi menggunakan **RFID Tag**
- Tampilan nama dan foto pengguna saat tap
- Dashboard admin untuk memantau dan mengelola absensi
- Pengaturan jam masuk dan pulang
- Riwayat absensi dan rekap harian
- Role-based access (Admin dan Umum)
- Integrasi ke hardware berbasis ESP8266 (WiFi)

## 📸 Tampilan Antarmuka

> ![WhatsApp Image 2025-06-21 at 23 13 05_6ba0381a](https://github.com/user-attachments/assets/31e22028-40dc-49e1-9a0c-8f3492827d91)
> ![WhatsApp Image 2025-06-21 at 23 14 08_939614e0](https://github.com/user-attachments/assets/6519bd61-d555-4615-8656-e7181f52fae4)



## 🚀 Instalasi

### 1. Clone Repo

```bash
git clone https://github.com/Yodhaardiansyah/laravel-rfid-attendance-system.git
cd laravel-rfid-attendance-system
````

### 2. Install Dependensi

```bash
composer install
npm install && npm run dev
```

### 3. Konfigurasi Environment

Copy file `.env.example` menjadi `.env`, lalu sesuaikan konfigurasi database dan lainnya:

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Setup Database

```bash
php artisan migrate --seed
```

### 5. Jalankan Server

```bash
php artisan serve
```

---

## 🧠 Alur Sistem

1. RFID Reader (ESP8266) membaca kartu.
2. ESP mengirim UID kartu ke server Laravel (API endpoint).
3. Server mencocokkan UID dengan database.
4. Jika cocok, server mencatat absensi, dan merespons nama + foto.
5. ESP menampilkan info di LCD atau layar.

---

## 🖥️ Struktur Proyek

```bash
app/
├── Http/
│   ├── Controllers/
│   ├── Middleware/
│   └── Requests/
resources/
├── views/
│   ├── auth/
│   ├── dashboard/
│   └── attendance/
routes/
├── web.php
├── api.php
database/
├── migrations/
├── seeders/
public/
├── images/ (foto pengguna)
.env
```

---

## 🔐 Role Akses

* **Admin**: Mengelola data pengguna, absensi, dan pengaturan sistem.
* **Umum**: Melihat data absensinya sendiri (jika fitur login user diaktifkan).

---

## 📡 API Endpoint

| Method | Endpoint          | Deskripsi                               |
| ------ | ----------------- | --------------------------------------- |
| POST   | `/api/absen`      | Menerima data dari RFID (UID)           |
| GET    | `/api/user/{uid}` | Mengambil data pengguna berdasarkan UID |

---

## 🛠️ Teknologi

* Laravel 10.x
* MySQL / MariaDB
* Blade Template
* Tailwind CSS (opsional)
* ESP8266 (NodeMCU)
* RFID RC522 Reader

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah MIT License. Silakan gunakan dan modifikasi sesuai kebutuhan.

---

## 🤝 Kontribusi

Pull request sangat dipersilakan! Untuk perubahan besar, buka dulu issue untuk didiskusikan.

---

## 🧑‍💻 Author

[Yodha Ardiansyah](https://github.com/Yodhaardiansyah)

```

---

Jika kamu punya:
- Gambar atau screenshot, tinggal tambahkan pada bagian 📸 *Tampilan Antarmuka*.
- Endpoint API lebih lengkap, bisa ditambahkan ke bagian 📡 *API Endpoint*.
- Link demo atau domain, bisa ditaruh di bagian atas juga.

Ingin aku bantu juga buat dokumentasi multi-page (misalnya `docs/installation.md`, `docs/api.md`, dsb)?
```
