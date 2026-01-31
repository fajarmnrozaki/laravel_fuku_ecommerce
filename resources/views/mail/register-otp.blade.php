<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Email Verification</title>
</head>
<body style="font-family: Arial, sans-serif;">

    <h2>Selamat Bapak/Ibu {{ $name }}</h2>

    <p>
        Email Anda berhasil terdaftar.<br>
        Silakan gunakan OTP code di bawah ini.<br>
        OTP berlaku <b>5 menit</b> dari sekarang.
    </p>

    <div style="
        background-color: yellow;
        text-align: center;
        padding: 15px;
        margin-top: 20px;
        font-size: 24px;
        font-weight: bold;
    ">
        {{ $otp }}
    </div>

</body>
</html>
