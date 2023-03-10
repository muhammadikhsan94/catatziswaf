<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SURAT TUGAS LAZDAI</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body{
            font-family:'verdana', sans-serif;
            color:#333;
            font-size:10px;
            height: 100%;
            width: 100%;
            line-height: 100%;
        }
        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }
        .container{
            height:auto;
            background-color:#fff;
        }
        table{
            border: none !important;
            width: 100%;
            float: left;
            margin: 0;
            border-collapse: collapse;
        }
        thead, tbody{
            border: none !important;
            width: 100%;
            float: left;
            margin: 0;
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
            line-height: 100%;
            margin:0;
        }
        img{
            filter: gray;
            -webkit-filter: grayscale(1);
            filter: grayscale(1);
        }
        tr.border_bottom td {
            border-top: 1px solid black;
        }
    </style>
</head>
<body>
    <img src="{{ ($data->header != null) ? asset($data->header) : '#' }}" height="50px">
    <div class="container">
        <p>
            <h2 style="text-align: center; font-size: 20px; line-height: 0"><u><b>KUITANSI</b></u></h2>
        </p>
        <br><br>
        <p>
            Kepada Bapak/Ibu {{ $data->donatur->nama }}<br><br>
            Kuitansi ini adalah bukti pembayaran zakat, infaq, dan shodaqoh Anda yang tercatat di Aplikasi Pencatatan Ziswaf. Berikut kami sertakan detail pembayaran Anda:<br><br>
            <table style="background-color: #eee;padding:5px;">
                <tbody>
                    <tr>
                        <td width="15%" style="text-align: left"><b>Nama Donatur</b></td>
                        <td width="35%">: {{ $data->donatur->nama }}</td>
                        <td width="15%" style="text-align: left"><b>Nomor Kuitansi</b></td>
                        <td width="35%">: {{ $data->no_kuitansi }}</td>
                    </tr>
                    <tr>
                        <td width="15%" style="text-align: left"><b>Lembaga</b></td>
                        <td width="35%">: {{ $data->lembaga->nama_lembaga }}</td>
                        <td width="15%" style="text-align: left"><b>Tanggal Transaksi</b></td>
                        <td width="35%">: {{ date('d-m-Y', strtotime($data->tanggal_transfer)) }}</td>
                    </tr>
                </tbody>
            </table>
        </p>
        <br><br><br><br>
        <table>
            <thead>
                <tr class="noBorder" style="background-color: green; color: white">
                    <th colspan="4" style="padding: 5px;"><b>Detail Transaksi</b></th>
                </tr>
                <tr class="noBorder" style="background-color: #eee">
                    <th colspan="2" style="text-align: left;padding: 5px;">
                        <b>Jenis Transaksi</b>
                    </th>
                    <th colspan="1" style="text-align: left;padding: 5px;">
                        <b>Barang</b>
                    </th>
                    <th colspan="1" style="text-align: right;padding: 5px;">
                        <b>Sub Total</b>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data->detailtransaksi as $item)
                <tr>
                    <td colspan="2" style="text-align: left;padding: 5px;">{{ $item->paketzakat->nama_paket_zakat }}</td>
                    <td colspan="1" style="text-align: left;padding: 5px;">{{ ($data->barang != null) ? $data->barang->nama_barang : '-' }}</td>
                    <td colspan="1" style="text-align: right;padding: 5px;">{{ format_uang($item->jumlah) }}</td>
                </tr>
                @endforeach
                <tr class="border_bottom">
                    <td colspan="3" style="text-align: right;padding: 5px;"><b>TOTAL TRANSAKSI</b></td>
                    <td colspan="1" style="text-align: right;padding: 5px;"><b>{{ format_uang($data->total) }}</b></td>
                </tr>
            </tbody>
        </table>
        <br><br>
        <p>
            Semoga Allah memberikan pahala atas apa yang telah Bapak/Ibu {{ $data->donatur->nama }} tunaikan, semoga Allah memberikan keberkahan atas harta yang masih tertinggal dan semoga <b>zakat, infaq, dan shodaqoh</b> ini menjadi pembersih bagi jiwa dan harta Bapak/Ibu {{ $data->donatur->nama }} beserta keluarga.
        </p>
        <br>
        <table>
            <tbody>
                <tr>
                    <td>
                        {{ $data->user->wilayah->nama_wilayah }},  {{ date('d-m-Y', strtotime($data->tanggal_transfer)) }}<br>
                        Diterima Oleh<br>
                        <img src="{{ ($data->ttd != null) ? asset($data->ttd) : '#' }}" height="50px" style="margin: 5px 0;"><br>
                        <u>{{ $data->user->nama }}</u><br>
                        Petugas Amil Zakat
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>