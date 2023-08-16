<div class="main-header">
    <!-- Logo Header -->
    <div class="logo-header" style="padding-left:0 !important" data-background-color="blue">
        <div class="logo my-auto">
            <img src="{{ asset('img/soughdara_icon.png') }}" width="225" alt="navbar brand" class="navbar-brand">
        </div>
        <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse"
            data-target="collapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon">
                <i class="icon-menu" style="color:#7F453F;"></i>
            </span>
        </button>
        <button class="topbar-toggler more"><i class="icon-options-vertical"></i></button>
        <div class="nav-toggle">
            <button class="btn btn-toggle toggle-sidebar">
                <i class="icon-menu" style="color:#7F453F;"></i>
            </button>
        </div>
    </div>
    <!-- End Logo Header -->
    <!-- Navbar Header -->
    <nav class="navbar navbar-header navbar-expand-lg" data-background-color="blue2">
    </nav>
    <!-- End Navbar -->
</div>
<!-- Sidebar -->
<div class="sidebar sidebar-style-2">
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-primary">
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Recommendation</h4>
                </li>
                <li class="nav-item {{ $title === "Recommendation" ? 'active' : '' }}">
                    <a href="{{ route('menu') }}" aria-expanded="false">
                        <i class="fa fa-thumbs-up"></i>
                        <p>Recommendation</p>
                    </a>
                </li>
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Category</h4>
                </li>
                @foreach($category as $cat)
                <li
                    class="nav-item {{ $title === $cat->category ? 'active' : '' }}">
                    <a href="{{ route('menu.category', $cat->id) }}" class="collapsed" aria-expanded="false">
                        <i class="fas fa-list"></i>
                        <p>{{ $cat->category }}</p>
                    </a>
                </li>
                @endforeach
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Scan Menu</h4>
                </li>
                <li class="nav-item {{ $title === "Scan QR" ? 'active' : '' }}">
                    <a href="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ route('menu') }}" target="_blank" aria-expanded="false">
                        <i class="fa fa-qrcode"></i>
                        <p>Scan QR</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- End Sidebar -->
