<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body{
            font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            color:#333;
            text-align:left;
            font-size:12px;
            margin:0;
            max-width: 750px;
        }
        .container{
            margin:0 auto;
            margin-top:25px;
            height:auto;
            background-color:#fff;
        }
        caption{
            font-size:28px;
            margin-bottom:15px;
        }
        sub-caption{
            margin:0px;
        }
        table{
            max-width: 740px;
            border:1px solid #333;
            border-collapse:collapse;
            margin:0 auto;
        }
        td, tr, th{
            padding:12px;
            border:1px solid #333;
            max-width:25%;
        }
        th{
            background-color: #eee;
        }
        h4, p{
            margin:0px;
        }
    </style>
</head>
<body>
    <div class="container">
        <table>
            <caption>
                APLIKASI PENCATATAN ZISWAF
            </caption>
            <thead>
                <tr>
                    <th colspan="3" style="text-align:left">Kuitansi <strong>#{{ $data->no_kuitansi }}</strong></th>
                    <th>{{ date('D, d-m-Y', strtotime($data->tanggal_transfer)) }}</th>
                </tr>
                <tr>
                    <td colspan="4">
                        <h4>Sudah diterima dari Bapak/Ibu/Sdr:</h4>
                        <p><pre>Nama        : {{ $data->donatur->nama }}<br>Alamat      : {{ $data->donatur->alamat }}<br>Nomor HP    : {{ $data->donatur->no_hp }}<br>Email       : {{ $data->donatur->email }}
                        </pre></p>
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th colspan="4" style="text-align:center"><strong>JENIS PEMBAYARAN ZAKAT</strong></th>
                </tr>
                <tr>
                    <th style="text-align:left">Paket Zakat</th>
                    <th style="text-align:left">Barang</th>
                    <th style="text-align:left">Jumlah/Harga</th>
                    <th style="text-align:left">Subtotal</th>
                </tr>
                @foreach ($data->detailtransaksi as $row)
                <tr>
                    <td>{{ $row->paketzakat->nama_paket_zakat }}</td>
                    <td>{{ $data->barang }}</td>
                    <td>Rp {{ format_uang($row->jumlah) }}</td>
                    <td>Rp {{ format_uang($row->jumlah) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" style="text-align:left">Total</th>
                    <td>Rp {{ format_uang($data->total) }}</td>
                </tr>
                <tr>
                    <th colspan="3" style="text-align:center;font-size: 90%">
                        <i>"Aajraka fiima a'thayta wa baarakaAllahu fiima abqayta wa ja'alahu laka thahuran<br>wa tijaratan lan tabuuran"<br><br>
                        Semoga Allah memberikan pahala atas apa yang engkau berikan, dan semoga Allah<br>memberikan berkah atas harta yang kau simpan dan membersihkannya serta menjadikannya ini<br>sebagai perniagaan yang tidak merugikan. Aamiin.</i>
                    </th>
                    <td style="text-align:center">
                        {{ $data->user->wilayah->nama_wilayah }}, {{ date('d/m/Y', strtotime($data->tanggal_transfer)) }}
                        <br><br><br><br>
                        <u>{{ $data->user->nama }}</u><br>
                        Petugas Amil Zakat
                    </td>
                </tr>
            </tfoot>
        </table>
        <p style="padding:5px;text-align:center;">
            <b>~Terima kasih anda telah mempercayakan zakat anda kepada kami~</b>
        </p>
    </div>
</body>
</html>