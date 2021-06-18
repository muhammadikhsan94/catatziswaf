<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Selamat !!!</title>
    <style>
        body {
            background-color:#bdc3c7;
            margin:0;
        }
        .card {
            background-color:#fff;
            padding:20px;
            margin:20%;
            text-align:center;
            margin:0px auto;
            width: 580px; 
            max-width: 580px;
            margin-top:10%;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
        }

        .garis {
            width: 75%;
        }
        
    </style>
</head>
<body>
    <div class="card">
        <p>Selamat bergabung, <b>{{ $user->nama }}</b>.</p>
        <p>Email anda sudah terdaftar di Aplikasi Pencatatan Ziswaf (<b>www.catatziswaf.id</b>).</p>
        <p>Silahkan login dengan username menggunakan email anda (<b>{{ $user->email }}</b>) atau dapat juga menggunakan kode punggung anda yaitu <b>{{ $user->no_punggung }}</b>.</p>
        <p>Jika ada pertanyaan silahkan balas email ini.</p>
        <h4>Terima kasih</h4>
    </div>
</body>
</html>