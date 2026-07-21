<div class="quixnav">
    <div class="quixnav-scroll">
        <ul class="metismenu" id="menu">
            <li class="nav-label first">
                <p class="font-weight-bold mb-0
                        <?php if ($role == '1') : ?>
                            text-success
                        <?php endif; ?>
                    "
                ><?= $username; ?></p>
            </li>
            <li class="nav-label">Main Menu</li>
            <li class="">
                <a href="home.php">
                    <i class="icon icon-single-04"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>
            <?php if ($role == '2') : ?>
                <li class="">
                    <a href="vendor.php">
                        <i class="bi bi-people"></i>
                        <span class="nav-text">Vendor</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</div>