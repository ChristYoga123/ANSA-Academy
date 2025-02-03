<!-- resources/views/emails/mentor/next_stage.blade.php -->
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pemberitahuan Lolos Tahap Berikutnya - ANSA Academy</title>
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

        .info-box {
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
        <h1>Selamat! Anda Lolos ke Tahap Berikutnya</h1>
    </div>

    <div class="content">
        <p>Hai {{ $mentor->nama }},</p>

        <p>Selamat! Kami dengan senang hati memberitahukan bahwa Anda telah {{ $status_penerimaan }} dalam
            proses seleksi mentor ANSA Academy untuk bidang {{ $mentor->lokerMentorBidang->nama }}.</p>

        <div class="info-box">
            {!! $catatan !!}
        </div>

        <p>Jika Anda memiliki pertanyaan atau kendala, jangan ragu untuk menghubungi tim kami di nomor WhatsApp yang
            tertera.</p>

        <p>Salam hangat,<br>Tim ANSA Academy</p>
    </div>

    <div class="footer">
        <p>© {{ date('Y') }} ANSA Academy. All rights reserved.</p>
        <p>Jalan Batu Raden gang 6 No. 56 Kecamatan Sumbersari, Jember</p>
    </div>
</body>

</html>
