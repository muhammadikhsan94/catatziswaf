<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="{{ asset('images/profile.png') }}" class="img-circle" alt="User Image">
                </div>

                <div class="pull-left info">
                    <p><small>{{ strtoupper(Auth::user()->nama) }}</small></p>
                    <!-- Status -->
                    @if($data['user']->id_jabatan == 1)
                    <small>Panziswil</small>
                    @elseif($data['user']->id_jabatan == 2)
                    <small>Panzisda</small>
                    @elseif($data['user']->id_jabatan == 3)
                    <small>Manajer Area</small>
                    @elseif($data['user']->id_jabatan == 4)
                    <small>Manajer Group</small>
                    @elseif($data['user']->id_jabatan == 5)
                    <small>Duta Zakat</small>
                    @elseif($data['user']->id_jabatan == 99)
                    <small>Lazis</small>
                    @else
                    @endif
                </div>
            </div>

            <div style="position:fixed;right:10px;bottom:10px;">
                <a href="https://api.whatsapp.com/send?phone=+628123456789&text=Halo, saya {{Auth::user()->nama}}. Saya ingin bertanya..">
                    <img src="{{asset('/images/wa.png')}}" height="50px"></button>
                </a>
            </div>

            <li class="header"><strong>MENU</strong></li>

            @if (Auth::check() && $data['user']->id_jabatan == 1)
            <li><a href="{{route('panziswil.beranda')}}"><i class="fa fa-home"></i>  <span>Beranda</span></a></li>
            <li><a href="{{route('panziswil.editProfil')}}"><i class="fa  fa-users"></i>  <span>Kelola Profil</span></a></li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-files-o"></i>
                    <span>Sebagai Panziswil</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('panziswil.user')}}"><i class="fa fa-users"></i>  <span>Kelola Pengguna</span></a></li>
                    <li><a href="{{route('panziswil.transaksi')}}"><i class="fa  fa-shopping-cart"></i>  <span>Kelola Transaksi</span></a>
                    <li><a href="{{route('panziswil.lembaga')}}"><i class="fa  fa-list"></i>  <span>Kelola Lembaga</span></a></li>
                    <li><a href="{{route('panziswil.paket')}}"><i class="fa  fa-list"></i>  <span>Kelola Paket Zakat</span></a></li>
                    <li><a href="{{route('panziswil.wilayah')}}"><i class="fa  fa-list"></i>  <span>Kelola Wilayah</span></a></li>
                    <li><a href="{{route('panziswil.jenisTransaksi')}}"><i class="fa  fa-list"></i>  <span>Kelola Jenis Transaksi</span></a></li>
                    <li><a href="{{route('panziswil.rekeningLembaga')}}"><i class="fa  fa-list"></i>  <span>Kelola Rekening Lembaga</span></a></li>
                    <li><a href="{{route('panziswil.distribusi')}}"><i class="fa  fa-list"></i>  <span>Kelola Distribusi</span></a></li>
                </ul>
            </li>
            @if ($data['user_duta'] != NULL)
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-files-o"></i>
                    <span>Sebagai Duta Zakat</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('panziswil.dutaIndex')}}"><i class="fa fa-home"></i>  <span>Beranda</span></a></li>
                    <li><a href="{{route('panziswil.dutaTransaksi')}}"><i class="fa fa-users"></i>  <span>Kelola Transaksi</span></a></li>
                    <li><a href="{{route('panziswil.dutaDonatur')}}"><i class="fa  fa-users"></i>  <span>Kelola Muzakki</span></a></li>
                </ul>
            </li>
            @endif
            @if ($data['user_manajer'] != NULL)
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-files-o"></i>
                    <span>Sebagai Manajer Group</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                </ul>
            </li>
            @endif
            @if ($data['user_manajerarea'] != NULL)
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-files-o"></i>
                    <span>Sebagai Manajer Area</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                </ul>
            </li>
            @endif
            @if ($data['user_panzisda'] != NULL)
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-files-o"></i>
                    <span>Sebagai Panzisda</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                </ul>
            </li>
            @endif
            @if ($data['user_lazis'] != NULL)
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-files-o"></i>
                    <span>Sebagai Lazis</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                </ul>
            </li>
            @endif

            @elseif (Auth::check() && $data['user']->id_jabatan == 2)
            <li><a href="{{route('panzisda.beranda')}}"><i class="fa fa-home"></i>  <span>Beranda</span></a></li>
            <li><a href="{{route('panzisda.editProfil')}}"><i class="fa  fa-users"></i>  <span>Kelola Profil</span></a></li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-files-o"></i>
                    <span>Sebagai Panzisda</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('panzisda.user')}}"><i class="fa fa-users"></i>  <span>Kelola Pengguna</span></a></li>
                    <li><a href="{{route('panzisda.transaksi')}}"><i class="fa fa-shopping-cart"></i>  <span>Kelola Transaksi</span></a></li>
                    <li><a href="{{route('panzisda.group')}}"><i class="fa fa-list"></i>  <span>Kelola Group</span></a></li>
                    <li><a href="{{route('panzisda.donatur')}}"><i class="fa fa-users"></i>  <span>Data Muzakki</span></a></li>
                </ul>
            </li>
            @if ($data['user_duta'] != NULL)
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-files-o"></i>
                    <span>Sebagai Duta Zakat</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('panzisda.dutaIndex')}}"><i class="fa fa-home"></i>  <span>Beranda</span></a></li>
                    <li><a href="{{route('panzisda.dutaTransaksi')}}"><i class="fa fa-users"></i>  <span>Kelola Transaksi</span></a></li>
                    <li><a href="{{route('panzisda.dutaDonatur')}}"><i class="fa  fa-users"></i>  <span>Kelola Muzakki</span></a></li>
                </ul>
            </li>
            @endif
            @if ($data['user_manajer'] != NULL)
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-files-o"></i>
                    <span>Sebagai Manajer Group</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                </ul>
            </li>
            @endif
            @if ($data['user_manajerarea'] != NULL)
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-files-o"></i>
                    <span>Sebagai Manajer Area</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                </ul>
            </li>
            @endif
            @if ($data['user_panziswil'] != NULL)
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-files-o"></i>
                    <span>Sebagai Panziswil</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                </ul>
            </li>
            @endif
            @if ($data['user_lazis'] != NULL)
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-files-o"></i>
                    <span>Sebagai Lazis</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                </ul>
            </li>
            @endif

            @elseif (Auth::check() && $data['user']->id_jabatan == 3)
            <li><a href="{{route('manajerarea.beranda')}}"><i class="fa fa-home"></i>  <span>Beranda</span></a></li>
            <li><a href="{{route('manajerarea.editProfil')}}"><i class="fa  fa-users"></i>  <span>Kelola Profil</span></a></li>
            @if ($data['user_duta'] != NULL)
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-files-o"></i>
                    <span>Sebagai Duta Zakat</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('manajerarea.dutaIndex')}}"><i class="fa fa-home"></i>  <span>Beranda</span></a></li>
                    <li><a href="{{route('manajerarea.dutaTransaksi')}}"><i class="fa fa-users"></i>  <span>Kelola Transaksi</span></a></li>
                    <li><a href="{{route('manajerarea.dutaDonatur')}}"><i class="fa  fa-users"></i>  <span>Kelola Muzakki</span></a></li>
                </ul>
            </li>
            @endif
            @if ($data['user_manajer'] != NULL)
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-files-o"></i>
                    <span>Sebagai Manajer Group</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                </ul>
            </li>
            @endif
            @if ($data['user_panziswil'] != NULL)
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-files-o"></i>
                    <span>Sebagai Panziswil</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                </ul>
            </li>
            @endif
            @if ($data['user_panzisda'] != NULL)
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-files-o"></i>
                    <span>Sebagai Panzisda</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                </ul>
            </li>
            @endif
            @if ($data['user_lazis'] != NULL)
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-files-o"></i>
                    <span>Sebagai Lazis</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                </ul>
            </li>
            @endif

            @elseif (Auth::check() && $data['user']->id_jabatan == 4)
            <li><a href="{{route('manajer.beranda')}}"><i class="fa fa-home"></i>  <span>Beranda</span></a></li>
            <li><a href="{{route('manajer.editProfil')}}"><i class="fa  fa-users"></i>  <span>Kelola Profil</span></a></li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-files-o"></i>
                    <span>Sebagai Manajer</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('manajer.user')}}"><i class="fa fa-users"></i>  <span>Data Duta Zakat</span></a></li>
                    <li><a href="{{route('manajer.transaksi')}}"><i class="fa fa-shopping-cart"></i>  <span>Kelola Transaksi</span></a></li>
                </ul>
            </li>
            
            @if ($data['user_duta'] != NULL)
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-files-o"></i>
                    <span>Sebagai Duta Zakat</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('manajer.dutaIndex')}}"><i class="fa fa-home"></i>  <span>Beranda</span></a></li>
                    <li><a href="{{route('manajer.dutaTransaksi')}}"><i class="fa fa-users"></i>  <span>Kelola Transaksi</span></a></li>
                    <li><a href="{{route('manajer.dutaDonatur')}}"><i class="fa  fa-users"></i>  <span>Kelola Muzakki</span></a></li>
                </ul>
            </li>
            @endif
            @if ($data['user_panziswil'] != NULL)
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-files-o"></i>
                    <span>Sebagai Panziswil</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                </ul>
            </li>
            @endif
            @if ($data['user_manajerarea'] != NULL)
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-files-o"></i>
                    <span>Sebagai Manajer Area</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                </ul>
            </li>
            @endif
            @if ($data['user_panzisda'] != NULL)
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-files-o"></i>
                    <span>Sebagai Panzisda</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                </ul>
            </li>
            @endif
            @if ($data['user_lazis'] != NULL)
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-files-o"></i>
                    <span>Sebagai Lazis</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                </ul>
            </li>
            @endif

            @elseif (Auth::check() && $data['user']->id_jabatan == 5)
            <li><a href="{{route('duta.beranda')}}"><i class="fa fa-home"></i>  <span>Beranda</span></a></li>
            <li><a href="{{route('duta.editProfil')}}"><i class="fa  fa-users"></i>  <span>Kelola Profil</span></a></li>
            <li><a href="{{route('duta.transaksi')}}"><i class="fa fa-users"></i>  <span>Kelola Transaksi</span></a></li>
            <li><a href="{{route('duta.donatur')}}"><i class="fa  fa-users"></i>  <span>Kelola Muzakki</span></a></li>

            @elseif (Auth::check() && $data['user']->id_jabatan == 99)
            <li><a href="{{route('lazis.beranda')}}"><i class="fa fa-home"></i>  <span>Beranda</span></a></li>
            <li><a href="{{route('lazis.transaksi')}}"><i class="fa fa-shopping-cart"></i>  <span>Kelola Transaksi</span></a></li>

            @if ($data['user_duta'] != NULL)
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-files-o"></i>
                    <span>Sebagai Duta Zakat</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{route('manajer.dutaIndex')}}"><i class="fa fa-home"></i>  <span>Beranda</span></a></li>
                    <li><a href="{{route('manajer.dutaTransaksi')}}"><i class="fa fa-users"></i>  <span>Kelola Transaksi</span></a></li>
                    <li><a href="{{route('manajer.dutaDonatur')}}"><i class="fa  fa-users"></i>  <span>Kelola Muzakki</span></a></li>
                </ul>
            </li>
            @endif
            @if ($data['user_panziswil'] != NULL)
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-files-o"></i>
                    <span>Sebagai Panziswil</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                </ul>
            </li>
            @endif
            @if ($data['user_manajerarea'] != NULL)
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-files-o"></i>
                    <span>Sebagai Manajer Area</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                </ul>
            </li>
            @endif
            @if ($data['user_panzisda'] != NULL)
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-files-o"></i>
                    <span>Sebagai Panzisda</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                </ul>
            </li>
            @endif
            @if ($data['user_manajer'] != NULL)
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-files-o"></i>
                    <span>Sebagai Manajer Group</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                </ul>
            </li>
            @endif

            @else
            @endif

            <li><a href="{{route('logout')}}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-sign-out"></i> <span>Logout</span></a></li>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>

            <li class="header"><strong>About</strong></li>
            <li><a href="#"><i class="fa fa-info-circle"></i>  <span>Informasi</span></a></li>
            <li><a href="#"><i class="fa fa-question-circle"></i>  <span>Bantuan</span></a></li>

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