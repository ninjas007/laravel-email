<!--**********************************
            Sidebar start
        ***********************************-->
<div class="nk-sidebar">
    <div class="nk-nav-scroll">
        <ul class="metismenu" id="menu">
            <li class="nav-label">MENU</li>
            <li>
                <a href="{{ url('/home') }}" aria-expanded="false">
                    <i class="fa fa-dashboard menu-icon"></i><span class="nav-text">Dashboard</span>
                </a>
            </li>
            {{-- <li class="nav-label">Kontak</li> --}}
            <li>
                <a href="{{ url('/lists') }}" aria-expanded="false">
                    <i class="fa fa-list-ul menu-icon"></i><span class="nav-text">List</span>
                </a>
            </li>
            <li>
                <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                    <i class="icon-people menu-icon"></i> <span class="nav-text">Kontak</span>
                </a>
                <ul aria-expanded="false">
                    <li>
                        <a href="{{ url('/fields') }}">Fields</a>
                    </li>
                    <li>
                        <a href="{{ url('/contacts') }}">Daftar Kontak</a>
                    </li>
                </ul>
            </li>
            <li>
                <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                    <i class="icon-envelope menu-icon"></i> <span class="nav-text">Pesan</span>
                </a>
                <ul aria-expanded="false">
                    <li>
                        <a href="{{ url('/templates') }}">Template Email</a>
                    </li>
                    <li>
                        {{-- relasikan list dengan template --}}
                        <a href="{{ url('/messages') }}">Daftar Pesan</a>
                    </li>
                    <li>
                        {{-- lihat broadcast sukses dan gagal dari daftar pesan --}}
                        <a href="{{ url('/broadcasts') }}">Broadcast</a>
                    </li>
                </ul>
            </li>
            <li class="nav-label">Tagihan</li>
            <li>
                <a href="{{ url('/pesan') }}" aria-expanded="false">
                    <i class="fa fa-credit-card menu-icon"></i><span class="nav-text">Langganan</span>
                </a>
                <a href="{{ url('/pesan') }}" aria-expanded="false">
                    <i class="fa fa-money menu-icon"></i><span class="nav-text">Pembayaran</span>
                </a>
            </li>
            <li class="nav-label">Setting</li>
            <li>
                <a href="{{ url('/pesan') }}" aria-expanded="false">
                    <i class="fa fa-user menu-icon"></i><span class="nav-text">Akun</span>
                </a>
                <a href="{{ url('/pesan') }}" aria-expanded="false">
                    <i class="fa fa-cogs menu-icon"></i><span class="nav-text">Pengaturan</span>
                </a>
            </li>
        </ul>
    </div>
</div>
<!--**********************************
            Sidebar end
        ***********************************-->
