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
           bottom: 100px;
           width: 100%;
           color: white;
           text-align: center;
        }
    </style>
</head>
<body>
    <img src="{{ asset('assets/logo/header_lazdai.png') }}" width="100%">
    <div class="container">
        <p>
            <h2 style="text-align: center; font-size: 20px; line-height: 50%"><u><b>SURAT TUGAS</b></u></h2>
            <p style="text-align: center;">No. {{ $data->no_surat }}/S.TUGAS-CZ/LAZDAICenter/I/2021</p>
        </p>
        <br><br>
        <p>
            Saya yang bertanda tangan dibawah ini :<br>
            <table>
                <tbody>
                    <tr>
                        <td width="17%" style="text-align: left">Nama</td>
                        <td>: <b>Nur Handoyo</b></td>
                    </tr>
                    <tr>
                        <td width="17%" style="text-align: left">Jabatan</td>
                        <td>: <b>Direktur</b></td>
                    </tr>
                    <tr>
                        <td width="17%" style="text-align: left">Lembaga</td>
                        <td>: <b>Lembaga Amil Zakat Dompet Amal Insani (LAZDAI) Lampung</b></td>
                    </tr>
                </tbody>
            </table>
        </p>
        <br><br>
        <br><br>
        <p>
            Memberikan tugas dan wewenang sepenuhnya kepada :<br>
            <table>
                <tbody>
                    <tr>
                        <td width="17%" style="text-align: left">Nama</td>
                        <td>: <b>{{ $data->nama }}</b></td>
                    </tr>
                    <tr>
                        <td width="17%" style="text-align: left">Jabatan</td>
                        <td>: <b>RELAWAN RAMADHAN 1442 H</b></td>
                    </tr>
                    <tr>
                        <td width="17%" style="text-align: left">Kode Relawan</td>
                        <td>: <b>{{ $data->no_punggung }}</b></td>
                    </tr>
                </tbody>
            </table>
        </p>
        <br><br>
        <br><br>
        <p>
            Dengan tugas:
            <ol>
                <li>Melakukan kegiatan <b>Edukasi</b> (Sosialisasi Pentingnya dan Manfaat Zakat, Infak, dan Sedekah).</li>
                <li>Membantu Layanan Pembayaran dan Penyaluran Zakat, Infak, dan Sedekah kepada para mustahik/penerima manfaat.</li>
            </ol>
            Demikian surat tugas ini dibuat untuk dipergunakan sebagaimana mestinya.
        </p>
        <br>
        <table>
            <tbody>
                <tr>
                    <td width="60%"></td>
                    <td>
                        <p>
                            Bandar Lampung, 1 April 2021<br>
                            LAZDAI Lampung<br>
                            <img src="{{ asset('assets/logo/ttd_lazdai.png') }}" width="100px" style="margin-left:-50px;margin-bottom:-20px;"/><br>
                            <b><u>Nur Handoyo</u></b><br>
                            Direktur
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="footer">
        <img src="{{ asset('assets/logo/footer_lazdai.png') }}" width="100%">
    </div>
</body>
</html>