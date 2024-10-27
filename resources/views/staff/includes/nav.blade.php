<nav class="pcoded-navbar theme-horizontal">
    <div class="navbar-wrapper">
        <div class="navbar-brand header-logo">
            <a href="#" class="b-brand"> <img src="" alt=""></a>
            <a class="mobile-menu" id="mobile-collapse" href="#!"><span></span></a>
        </div>
        <div class="navbar-content sidenav-horizontal" id="layout-sidenav">
        <ul class="nav pcoded-inner-navbar sidenav-inner" style="margin: auto">
            <li class="nav-item pcoded-menu-caption">  <label>Navigation</label></li>
            <li data-username="dashboard Default Ecommerce CRM Analytics Crypto Project" class="nav-item">
                <a href="/{{ Auth::user()->user_type->title }}/dashboard" class="nav-link"><span class="pcoded-micon"><i class="feather icon-home"></i></span><span class="pcoded-mtext">Dashboard</span></a>
            </li>

            <li data-username="Vertical Horizontal Box Layout RTL fixed static collapse menu color icon dark" class="nav-item pcoded-hasmenu">
                <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="feather icon-shopping-cart"></i></span><span class="pcoded-mtext">Product line</span></a>
                <ul class="pcoded-submenu">
                    <li class="pcoded-hasmenu"><a href="#!" class="">Categories</a>
                    <ul class="pcoded-submenu">
                        <li class=""><a href="{{ route('common.category') }}">Main Category</a></li>
                        <li class=""><a href="layout-fixed.html">Fixed</a></li>
                        <li class=""><a href="layout-menu-fixed.html">Navbar fixed</a></li>
                        <li class=""><a href="layout-mini-menu.html">Collapse menu</a></li>
                    </ul>
                    </li>
                    <li class=""><a href="layout-horizontal.html">Horizontal</a></li>
                    <li class=""><a href="layout-box.html">Box layout</a></li>
                    <li class=""><a href="layout-rtl.html">RTL</a></li>
                    <li class=""><a href="layout-light.html">Light layout</a></li>
                    <li class=""><a href="layout-dark.html">Dark layout <span class="pcoded-badge label label-danger">Hot</span></a></li>
                    <li class=""><a href="layout-menu-icon.html">Color icon</a></li>
                </ul>
            </li>

            <li data-username="Documentation" class="nav-item"><a href="docs.html" class="nav-link"><span class="pcoded-micon"><i class="feather icon-book"></i></span><span class="pcoded-mtext">Documentation</span></a></li>

            </ul>
        </div>
    </div>
</nav>
