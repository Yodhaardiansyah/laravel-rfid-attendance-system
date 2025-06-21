<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Real-Time</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #007bff, #6610f2);
            color: white;
            font-family: 'Inter', sans-serif;
        }
        .container {
            max-width: 500px;
            margin-top: 50px;
        }
        .brand-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .brand-logo img {
            height: 50px;
        }
        .attendance-box {
            background: white;
            color: #333;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
            transition: 0.3s;
        }
        .attendance-box h2 {
            font-size: 1.8rem;
            font-weight: 600;
        }
        .attendance-box p {
            font-size: 1.1rem;
        }
        .fade-in {
            animation: fadeIn 0.8s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .btn-login {
            background: white;
            color: #007bff;
            font-weight: bold;
            transition: 0.3s;
        }
        .btn-login:hover {
            background: #f8f9fa;
            color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container text-center">
    <div class="brand-logo">
        <img src="{{ asset('logo.png') }}" alt="Logo">
        <h1>{{ config('app.name', 'Laravel') }}</h1>
    </div>

    <!-- Tombol Login -->
    <a href="/" class="btn btn-login mt-3 mb-4 px-4 py-2"><i class="fas fa-sign-in-alt"></i> Login Admin</a>

    <!-- Kotak Absensi -->
    <div id="attendance-box" class="attendance-box fade-in">
        <h2 id="santri-name"><i class="fas fa-user"></i> Menunggu data...</h2>
        <p id="prayer-name" class="text-muted"><i class="fas fa-mosque"></i> ---</p>
        <p id="status" class="text-primary"><i class="fas fa-clock"></i> ---</p>
        <p id="time" class="text-danger"><i class="fas fa-calendar-alt"></i> ---</p>
    </div>
</div>

<script>
    let lastUpdate = Date.now();
    let timeout;

    function fetchLatestAttendance() {
        fetch('/api/attendance/latest')
            .then(response => response.json())
            .then(data => {
                if (data.santri) {
                    document.getElementById('santri-name').innerHTML = `<i class="fas fa-user"></i> ${data.santri}`;
                    document.getElementById('prayer-name').innerHTML = `<i class="fas fa-mosque"></i> Sholat: ${data.prayer}`;
                    document.getElementById('status').innerHTML = `<i class="fas fa-clock"></i> Status: ${data.status}`;
                    document.getElementById('time').innerHTML = `<i class="fas fa-calendar-alt"></i> Waktu: ${data.time}`;

                    document.getElementById('attendance-box').classList.add('fade-in');

                    lastUpdate = Date.now();
                    resetTimeout();
                }
            })
            .catch(error => console.error("Gagal mengambil data:", error));
    }

    function resetTimeout() {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            resetDisplay();
        }, 5000);
    }

    function resetDisplay() {
        document.getElementById('santri-name').innerHTML = `<i class="fas fa-user"></i> Menunggu data...`;
        document.getElementById('prayer-name').innerHTML = `<i class="fas fa-mosque"></i> ---`;
        document.getElementById('status').innerHTML = `<i class="fas fa-clock"></i> ---`;
        document.getElementById('time').innerHTML = `<i class="fas fa-calendar-alt"></i> ---`;
    }

    setInterval(fetchLatestAttendance, 3000);
</script>

</body>
</html>
