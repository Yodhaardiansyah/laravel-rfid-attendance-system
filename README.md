
# Laravel RFID Attendance System

![License](https://img.shields.io/github/license/Yodhaardiansyah/laravel-rfid-attendance-system)
![Laravel](https://img.shields.io/badge/Laravel-10.x-red)
![RFID](https://img.shields.io/badge/RFID-Enabled-green)

A modern **RFID-based attendance system** built with **Laravel**. This project is designed for use in schools, boarding schools (pondok pesantren), companies, or any institutions that require automated presence logging with RFID cards.

## 🔧 Key Features

- Attendance using **RFID Tags**
- Display of user name and photo upon scan
- Admin dashboard for attendance management
- Customizable check-in and check-out times
- Attendance logs and daily recaps
- Role-based access (Admin and General Users)
- Hardware integration with ESP8266 (WiFi)

## 📸 Interface Preview

> ![WhatsApp Image 2025-06-21 at 23 13 05_6ba0381a](https://github.com/user-attachments/assets/aea99f18-16e1-48b8-9b5b-9cdf855240fc)
> ![WhatsApp Image 2025-06-21 at 23 14 08_939614e0](https://github.com/user-attachments/assets/1f091d28-b3e4-437b-bd97-aed9197ceceb)



## 🚀 Installation Guide

### 1. Clone the Repository

```bash
git clone https://github.com/Yodhaardiansyah/laravel-rfid-attendance-system.git
cd laravel-rfid-attendance-system
````

### 2. Install Dependencies

```bash
composer install
npm install && npm run dev
```

### 3. Configure Environment

Copy the `.env.example` file to `.env` and configure your database and app settings:

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Set Up the Database

```bash
php artisan migrate --seed
```

### 5. Run the Laravel Development Server

```bash
php artisan serve
```

---

## 🧠 System Workflow

1. RFID Reader (ESP8266) reads a card.
2. ESP sends the UID to Laravel backend via HTTP POST.
3. Server checks UID in the database.
4. If found, it logs attendance and returns user details (name, photo).
5. ESP displays the result on LCD or screen.

---

## 📁 Project Structure

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
├── images/ (user photos)
.env
```

---

## 🔐 User Roles

* **Admin**: Manages users, attendance records, and system settings.
* **General User**: Can view personal attendance info (optional login feature).

---

## 📡 API Endpoints

| Method | Endpoint          | Description                        |
| ------ | ----------------- | ---------------------------------- |
| POST   | `/api/absen`      | Receive UID data from RFID scanner |
| GET    | `/api/user/{uid}` | Get user info based on RFID UID    |

---

## 🛠️ Tech Stack

* Laravel 10.x
* MySQL / MariaDB
* Blade Templates
* (Optional) Tailwind CSS
* ESP8266 (NodeMCU)
* RC522 RFID Reader

---

## 📄 License

This project is open-source under the MIT License. Feel free to use and modify it as needed.

---

## 🤝 Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you’d like to change.

---

## 👨‍💻 Author

**Yodha Ardiansyah**
[GitHub Profile](https://github.com/Yodhaardiansyah)


