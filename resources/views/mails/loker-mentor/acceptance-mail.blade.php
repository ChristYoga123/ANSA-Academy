<!-- resources/views/emails/mentor/accepted.blade.php -->
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Penerimaan Mentor ANSA Academy</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header img {
            max-width: 200px;
            height: auto;
        }

        .content {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 0.9em;
            color: #666;
        }

        .credentials {
            background: #fff;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Selamat! Anda Diterima</h1>
    </div>

    <div class="content">
        <p>Hai {{ $mentor->name }},</p>

        <p>Selamat! Anda telah diterima sebagai Mentor di ANSA Academy di bidang
            {{ $mentor->custom_fields['bidang_mentor'] }}. Kami sangat senang dapat berkolaborasi dengan
            Anda dalam misi kami untuk mendampingi siswa/mahasiswa dalam perjalanan lomba dan riset mereka.</p>

        <div class="credentials">
            <h3>Informasi Akun:</h3>
            <p>Email: {{ $mentor->email }}</p>
            <p>Password: {{ $password }}</p>
            <p><strong>Catatan:</strong> Mohon segera ubah password Anda setelah login pertama kali.</p>
        </div>

        <p>Langkah selanjutnya:</p>
        <ol>
            <li>Login ke <a href="{{ route('filament.mentor.auth.login') }}">dashboard mentor</a> menggunakan kredensial
                di atas</li>
            <li>Ubah password default Anda pada bagian topbar di sisi kanan > Edit Profile</li>
            <li>Mulai menerima mentee</li>
        </ol>

        <center>
            <a href="{{ route('filament.mentor.auth.login') }}" class="button">Login ke Dashboard</a>
        </center>

        <p>Jika Anda memiliki pertanyaan, jangan ragu untuk menghubungi tim kami.</p>

        <p>Salam hangat,<br>Tim ANSA Academy</p>
    </div>

    <div class="footer">
        <p>© {{ date('Y') }} ANSA Academy. All rights reserved.</p>
        <p>Jalan Batu Raden gang 6 No. 56 Kecamatan Sumbersari, Jember</p>
    </div>
</body>

</html>
