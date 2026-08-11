<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="assets/css/wnj-theme.css">
<style>
    .mitra-navbar {
        background: var(--wnj-bg);
        border-bottom: 1px solid var(--wnj-border);
        padding: .75rem 0;
        position: sticky;
        top: 0;
        z-index: 1030;
    }
    .mitra-navbar .brand {
        font-weight: 700;
        color: var(--wnj-text);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }
    .mitra-navbar .brand-logo {
        height: 28px;
        width: auto;
    }
    .mitra-navbar .role-badge {
        background: var(--wnj-bg-accent-soft);
        color: var(--wnj-cta);
        font-size: .68rem;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 999px;
        margin-left: .4rem;
        text-transform: uppercase;
        letter-spacing: .02em;
    }
</style>
<nav class="mitra-navbar">
    <div class="container d-flex align-items-center justify-content-between">
        <a href="index.php" class="brand">
            <img src="../image/wnjid.PNG" alt="WNJ.ID" class="brand-logo mr-1"> WNJ.ID
            <span class="role-badge"><?= htmlspecialchars($mitraCfg['label'] ?? '') ?></span>
        </a>
        <div class="d-flex align-items-center" style="gap: 1.25rem;">
            <?php if (!empty($namaMitra)): ?>
                <span class="d-none d-sm-inline text-muted">Halo, <?= htmlspecialchars($namaMitra) ?></span>
            <?php endif; ?>
            <a href="logout.php" class="text-muted" title="Keluar"><i class="bi bi-box-arrow-right"></i></a>
        </div>
    </div>
</nav>
