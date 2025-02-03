<!-- resources/views/emails/mentor/rejected.blade.php -->
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Informasi Pendaftaran Mentor ANSA Academy</title>
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

        .message {
            margin: 20px 0;
            padding: 15px;
            background: #fff;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Informasi Pendaftaran Mentor</h1>
    </div>

    <div class="content">
        <p>Hai {{ $mentor->nama }},</p>

        <p>Terima kasih telah mendaftar sebagai Mentor di ANSA Academy. Kami telah meninjau aplikasi Anda dengan
            seksama.</p>

        <div class="message">
            <p>Dengan berat hati kami informasikan bahwa kami belum dapat menerima Anda sebagai Mentor di ANSA Academy
                untuk saat ini dengan alasan:
                <br>
                <strong>{{ $mentor->alasan_ditolak }}</strong>
            </p>
        </div>

        <p>Keputusan ini diambil setelah pertimbangan menyeluruh terhadap semua aplikasi yang kami terima. Meskipun Anda
            memiliki kualifikasi yang baik, kami harus membuat keputusan yang sulit berdasarkan kebutuhan spesifik kami
            saat ini.</p>

        <p>Beberapa hal yang dapat Anda lakukan untuk meningkatkan peluang di kesempatan berikutnya:</p>
        <ul>
            <li>Tingkatkan pengalaman dalam kompetisi dan riset</li>
            <li>Kembangkan portfolio prestasi akademik</li>
            <li>Aktif dalam kegiatan mentoring atau pengajaran</li>
        </ul>

        <p>Kami mendorong Anda untuk mencoba kembali di periode rekrutmen berikutnya setelah mengembangkan pengalaman
            dan kualifikasi Anda.</p>

        <p>Salam hangat,<br>Tim ANSA Academy</p>
    </div>

    <div class="footer">
        <p>© {{ date('Y') }} ANSA Academy. All rights reserved.</p>
        <p>Jalan Batu Raden gang 6 No. 56 Kecamatan Sumbersari, Jember</p>
    </div>
</body>

</html>
