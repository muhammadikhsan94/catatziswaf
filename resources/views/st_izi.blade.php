<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SURAT TUGAS LAZDAI</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" href="{{ URL::asset('lte/bower_components/bootstrap/dist/css/bootstrap.min.css') }}" type="text/css" />
    <style>
        body{
            font-family:'calibri', sans-serif;
            color:#333;
            font-size:12px;
            height: 100%;
            width: 100%;
            line-height: 150%;
        }
        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }
        .container{
            height:auto;
            background-color:#fff;
            margin: 20px;
        }
        table{
            width: 100%;
            float: left;
            margin:0 auto;
        }
        h4, p{
            margin:0px;
            text-align: justify;
        }
        .tab {
            display: inline-block;
            margin-left: 50px;
        }
        pre{
            font-family:'calibri', sans-serif;
            color:#333;
            font-size:12px;
            line-height: 150%;
            margin:0;
        }
        .footer {
           position: fixed;
           left: 0;
           bottom: 80px;
           width: 100%;
           color: white;
           text-align: center;
        }
    </style>
</head>
<body>
    <p style="text-align: right"><img src="{{ asset('assets/logo/header_izi.png') }}" width="150px"></p>
    <div class="container">
        <p>
            <h2 style="text-align: center; font-size: 20px; line-height: 50%"><u><b>SURAT TUGAS</b></u></h2>
            <p style="text-align: center;">No. IZI-LMP/{{ $data->no_surat }}.EKZ/ST-CZ/2021</p>
        </p>
        <br><br>
        <p>
            Lembaga Amil Zakat Nasional Inisiatif Zakat Indonesia (IZI) Perwakilan Lampung dengan ini memberikan tugas kepada :<br>
            <table>
                <tbody>
                    <tr>
                        <td width="17%" style="text-align: left">Nama</td>
                        <td>: <b>{{ $data->nama }}</b></td>
                    </tr>
                    <tr>
                        <td width="17%" style="text-align: left">Nomor HP</td>
                        <td>: <b>{{ ($data->no_hp != NULL) ? $data->no_hp : '-' }}</b></td>
                    </tr>
                    <tr>
                        <td width="17%" style="text-align: left">Alamat</td>
                        <td>: <b>{{ ($data->alamat != NULL) ? $data->alamat : '-' }}</b></td>
                    </tr>
                    <tr>
                        <td width="17%" style="text-align: left">Nomor Amil</td>
                        <td>: <b>{{ $data->no_punggung }}</b></td>
                    </tr>
                </tbody>
            </table>
        </p>
        <br><br>
        <p>
            Sebagai <b>Petugas Penghimpunan Zakat, Infaq, Shodaqoh dan Wakaf</b> di wilayah Provinsi Lampung. Surat tugas ini berlaku dari tanggal 1 April 2021 sampai 17 Mei 2021.
        </p>
        <br>
        <p>
            Demikian surat tugas ini dibuat agar dapat dipergunakan sebagaimana mestinya dan kepada yang bersangkutan agar menjalankan tugasnya dengan amanah dan profesional.
        </p>
        <br>
        <table>
            <tbody>
                <tr>
                    <td width="40%">
                        <p>
                            Bandar Lampung, 1 April 2021<br>
                            <img src="{{ asset('assets/logo/ttd_izi.png') }}" width="200px" /><br>
                            <b><u>Agus Rin Wirawan</u></b><br>
                            Kepala Perwakilan
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="footer">
        <img src="{{ asset('assets/logo/footer_izi.png') }}" width="100%">
    </div>
</body>
</html>