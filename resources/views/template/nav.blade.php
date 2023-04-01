<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="{{ asset('images/profile.png') }}" class="img-circle" alt="User Image">
                </div>

                <div class="pull-left info">
                    <small>{{ strtoupper(Auth::user()->nama) }}</small><br>
                    <small>{{ $data['user']->nama_wilayah }}</small>
                </div>
            </div>

            <!--<div style="position:fixed;right:10px;bottom:10px;">-->
            <!--    <a href="https://api.whatsapp.com/send?phone=+628123456789&text=Halo, saya {{Auth::user()->nama}} dengan Nomor Punggung yaitu {{ Auth::user()->no_punggung }}. Saya ingin bertanya.." target="_blank">-->
            <!--        <img src="{{asset('/images/wa.png')}}" height="50px"></button>-->
            <!--    </a>-->
            <!--</div>-->

            <li class="header"><strong>MENU</strong></li>
            <li><a href="{{route('profil')}}"><i class="fa  fa-users"></i>  <span>Kelola Profil</span></a></li>

            @if (Auth::check() && $data['user_panziswil'] != NULL)
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-files-o"></i>
                    <span>Sebagai Panziswil</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('panziswil.beranda')}}"><i class="fa fa-home"></i>  <span>Beranda</span></a></li>
                    <li><a href="{{route('panziswil.user')}}"><i class="fa fa-users"></i>  <span>Kelola Pengguna</span></a></li>
                    <li><a href="{{route('panziswil.transaksi')}}"><i class="fa  fa-shopping-cart"></i>  <span>Kelola Transaksi</span></a>
                    <li><a href="{{route('panziswil.lembaga')}}"><i class="fa  fa-files-o"></i>  <span>Kelola Lembaga</span></a></li>
                    <li><a href="{{route('panziswil.paket')}}"><i class="fa  fa-files-o"></i>  <span>Kelola Paket Zakat</span></a></li>
                    <li><a href="{{route('panziswil.wilayah')}}"><i class="fa  fa-files-o"></i>  <span>Kelola Wilayah</span></a></li>
                    <li><a href="{{route('panziswil.jenisTransaksi')}}"><i class="fa  fa-files-o"></i>  <span>Kelola Jenis Transaksi</span></a></li>
                    <li><a href="{{route('panziswil.rekeningLembaga')}}"><i class="fa  fa-files-o"></i>  <span>Kelola Rekening Lembaga</span></a></li>
                    <li><a href="{{route('panziswil.distribusi')}}"><i class="fa  fa-files-o"></i>  <span>Kelola Distribusi</span></a></li>
                    <li><a href="{{route('panziswil.group')}}"><i class="fa fa-files-o"></i>  <span>Kelola Group</span></a></li>
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-list"></i>  <span>Kelola Laporan</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{ route('panziswil.laporanDZ') }}"><i class="fa fa-files-o"></i>  <span>Duta Zakat</span></a></li>
                            <li class="treeview">
                                <a href="#">
                                    <i class="fa fa-list"></i>  <span>Monitoring Validasi</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="{{ route('panziswil.laporanValidasiLembaga') }}"><i class="fa fa-files-o"></i>  <span>Lembaga</span></a></li>
                                    <li><a href="{{ route('panziswil.laporanValidasiWilayah') }}"><i class="fa fa-files-o"></i>  <span>Wilayah</span></a></li>
                                </ul>
                            </li>
                            <li><a href="{{ route('panziswil.laporanDonatur') }}"><i class="fa fa-files-o"></i>  <span>Muzakki</span></a></li>
                            <li><a href="{{ route('panziswil.laporanRealisasiDistribusi') }}"><i class="fa fa-files-o"></i>  <span>Realisasi Distribusi</span></a></li>
                            <li><a href="{{ route('panziswil.laporanJenisZiswaf') }}"><i class="fa fa-files-o"></i>  <span>Realisasi Jenis Ziswaf</span></a></li>
                            <li><a href="{{ route('panziswil.laporanRealisasiPaketZiswaf') }}"><i class="fa fa-files-o"></i>  <span>Realisasi Paket Ziswaf</span></a></li>
                            <li><a href="{{ route('panziswil.laporanRealisasiDutaZakat') }}"><i class="fa fa-files-o"></i>  <span>Realisasi Duta Zakat</span></a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            @endif

            @if (Auth::check() && $data['user_panzisda'] != NULL)
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-files-o"></i>
                    <span>Sebagai Panzisda</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('panzisda.beranda')}}"><i class="fa fa-home"></i>  <span>Beranda</span></a></li>
                    <li><a href="{{route('panzisda.transaksi')}}"><i class="fa fa-shopping-cart"></i>  <span>Kelola Transaksi</span></a></li>
                    <li><a href="{{route('panzisda.user')}}"><i class="fa fa-users"></i>  <span>Kelola Pengguna</span></a></li>
                    <li><a href="{{route('panzisda.donatur')}}"><i class="fa fa-users"></i>  <span>Kelola Muzakki</span></a></li>
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-list"></i>  <span>Kelola Laporan</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{ route('panzisda.laporanDZ') }}"><i class="fa fa-files-o"></i>  <span>Duta Zakat</span></a></li>
                            <li><a href="{{ route('panzisda.laporanValidasi') }}"><i class="fa fa-files-o"></i>  <span>Monitoring Validasi</span></a></li>
                            <li><a href="{{ route('panzisda.laporanDonatur') }}"><i class="fa fa-files-o"></i>  <span>Muzakki</span></a></li>
                            <li><a href="{{ route('panzisda.laporanRealisasi') }}"><i class="fa fa-files-o"></i>  <span>Realisasi Duta Zakat</span></a></li>
                            <li><a href="{{ route('panzisda.laporanRealisasiDistribusi') }}"><i class="fa fa-files-o"></i>  <span>Realisasi Distribusi</span></a></li>
                            <li><a href="{{ route('panzisda.laporanRealisasiPaketZiswaf') }}"><i class="fa fa-files-o"></i>  <span>Realisasi Paket Ziswaf</span></a></li>
                            <li><a href="{{ route('panzisda.laporanRekonsiliasi') }}"><i class="fa fa-files-o"></i>  <span>Rekonsiliasi Ziswaf</span></a></li>
                            <li><a href="{{ route('panzisda.laporanRealisasiDutaZakat') }}"><i class="fa fa-files-o"></i>  <span>Realisasi Duta Zakat</span></a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            @endif

            @if (Auth::check() && $data['user_manajerarea'] != NULL)
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-files-o"></i>
                    <span>Sebagai Manajer Area</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('manajerarea.beranda')}}"><i class="fa fa-home"></i>  <span>Beranda</span></a></li>
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-list"></i>  <span>Kelola Laporan</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{ route('manajerarea.laporanRealisasi') }}"><i class="fa fa-files-o"></i>  <span>Realisasi Duta Zakat</span></a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            @endif

            @if (Auth::check() && $data['user_manajer'] != NULL)
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-files-o"></i>
                    <span>Sebagai Manajer Group</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('manajer.beranda')}}"><i class="fa fa-home"></i>  <span>Beranda</span></a></li>
                    <li><a href="{{route('manajer.transaksi')}}"><i class="fa  fa-shopping-cart"></i>  <span>Kelola Transaksi</span></a></li>
                    <li><a href="{{route('manajer.user')}}"><i class="fa  fa-users"></i>  <span>Kelola Duta Zakat</span></a></li>
                    <li><a href="{{route('manajer.donatur')}}"><i class="fa  fa-users"></i>  <span>Kelola Muzakki</span></a></li>
                    <li><a href="{{ route('manajer.laporan') }}"><i class="fa fa-list"></i>  <span>Kelola Laporan</span></a></li>
                </ul>
            </li>
            @endif

            @if (Auth::check() && $data['user_duta'] != NULL)
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-files-o"></i>
                    <span>Sebagai Duta Zakat</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('duta.beranda')}}"><i class="fa fa-home"></i>  <span>Beranda</span></a></li>
                    <li><a href="{{route('duta.transaksi')}}"><i class="fa fa-shopping-cart"></i>  <span>Kelola Transaksi</span></a></li>
                    <li><a href="{{route('duta.donatur')}}"><i class="fa  fa-users"></i>  <span>Kelola Muzakki</span></a></li>
                    <li><a href="{{ route('duta.laporan') }}"><i class="fa fa-list"></i>  <span>Kelola Laporan</span></a></li>
                </ul>
            </li>
            @endif

            @if (Auth::check() && $data['user_lazis'] != NULL)
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-files-o"></i>
                    <span>Sebagai Lazis</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('lazis.beranda')}}"><i class="fa fa-home"></i>  <span>Beranda</span></a></li>
                    <li><a href="{{route('lazis.transaksi')}}"><i class="fa fa-shopping-cart"></i>  <span>Kelola Transaksi</span></a></li>
                    <li><a href="{{route('lazis.donatur')}}"><i class="fa fa-users"></i>  <span>Data Donatur</span></a></li>
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-list"></i>  <span>Kelola Laporan</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{ route('lazis.laporanValidasi') }}"><i class="fa fa-files-o"></i>  <span>Monitoring Validasi</span></a></li>
                            <li><a href="{{ route('lazis.laporanRealisasiPaketZiswaf') }}"><i class="fa fa-files-o"></i>  <span>Realisasi Paket Ziswaf</span></a></li>
                            <li><a href="{{ route('lazis.laporanRealisasiDistribusi') }}"><i class="fa fa-files-o"></i>  <span>Realisasi Distribusi</span></a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            @endif

            <li><a href="{{route('logout')}}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-sign-out"></i> <span>Logout</span></a></li>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>

            <li class="header"><strong>About</strong></li>
            <li><a href="{{ route('faq') }}"><i class="fa fa-question-circle"></i>  <span>FAQ</span></a></li>
            <li><a href="{{route('informasi')}}"><i class="fa fa-info-circle"></i>  <span>Informasi</span></a></li>

        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
@push('scripts')
<script>
    /** add active class and stay opened when selected */
    var url = window.location;

    // for sidebar menu entirely but not cover treeview
    $('ul.sidebar-menu a').filter(function() {
        return this.href == url;
    }).parent().addClass('active');

    // for treeview
    $('ul.treeview-menu a').filter(function() {
        return this.href == url;
    }).parentsUntil(".sidebar-menu > .treeview-menu").addClass('active');
</script>
@endpush