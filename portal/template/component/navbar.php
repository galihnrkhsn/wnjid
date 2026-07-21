<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="fa fa-cog"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header"><p class="text-uppercase font-weight-bold mb-0"><?= $username ?></p></span>
                <div class="dropdown-divider"></div>
                <a href="logout.php" class="dropdown-item dropdown-footer d-flex align-items-center">
                    <i class="fas fa-door-open mr-2"></i>
                    <p class="mb-0">Logout</p>
                </a>
            </div>
        </li>
    </ul>
</nav>
<!-- /.navbar -->