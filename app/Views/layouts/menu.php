<ul class="nav flex-column mt-3">

    <li class="nav-item">
        <a class="nav-link <?= (uri_string() == 'dashboard') ? 'active' : '' ?>" href="<?= base_url('/dashboard') ?>">
            <i class="bi bi-house me-2"></i> <span>Dashboard</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= (uri_string() == 'barang') ? 'active' : '' ?>" href="<?= base_url('/barang') ?>">
            <i class="bi bi-box-seam me-2"></i> <span>Data Barang</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link <?= (uri_string() == 'gunung') ? 'active' : '' ?>" href="<?= base_url('/gunung') ?>">
            <i class="bi bi-geo-alt me-2"></i> <span>Explore</span>
        </a>
    </li>

    <?php if (strtolower(session()->get('role')) == 'admin') : ?>
        <hr class="text-secondary mx-3">
        <li class="nav-item">
            <a class="nav-link text-info" href="<?= base_url('admin/transaksi') ?>">
                <i class="bi bi-receipt me-2"></i> <span>Kelola Transaksi</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('/users') ?>">
                <i class="bi bi-people me-2"></i> <span>Data Users</span>
            </a>
        </li>
    <?php endif; ?>

    <?php if (strtolower(session()->get('role')) == 'user') : ?>
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('/riwayat') ?>">
                <i class="bi bi-clock-history me-2"></i> <span>Riwayat Pinjam</span>
            </a>
        </li>
    <?php endif; ?>

    <hr class="text-secondary mx-3">

    <li class="nav-item px-3 mb-2">
        <button class="btn btn-sm w-100 text-start text-white shadow-none p-0 d-flex align-items-center" id="darkModeToggleSidebar" style="text-decoration: none;">
            <i class="bi bi-moon-stars-fill me-2" id="themeIconSidebar"></i>
            <span>Mode Layar</span>
        </button>
    </li>

    <li class="nav-item mt-2">
        <div class="text-center mb-2">
            <img src="<?= base_url('uploads/users/' . (session()->get('foto') ?: 'default.png')) ?>"
                style="width: 80px; height: 80px; object-fit: cover;"
                class="rounded-circle border border-2 border-primary shadow-sm"
                alt="Profil" />

            <div class="mt-2 text-white small">
                <span class="d-block opacity-75">Masuk sebagai:</span>
                <b class="text-warning"><?= session('nama'); ?></b>
                <span class="badge bg-secondary d-block mt-1 mx-4"><?= session('role'); ?></span>
            </div>
        </div>
    </li>

</ul>

<script>
    const themeBtnSidebar = document.getElementById('darkModeToggleSidebar');
    const themeIconSidebar = document.getElementById('themeIconSidebar');
    const rootHtml = document.documentElement;

    themeBtnSidebar.addEventListener('click', () => {
        const currentTheme = rootHtml.getAttribute('data-bs-theme');
        const newTheme = currentTheme === 'light' ? 'dark' : 'light';

        rootHtml.setAttribute('data-bs-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        updateSidebarIcon(newTheme);
    });

    function updateSidebarIcon(theme) {
        if (theme === 'dark') {
            themeIconSidebar.classList.replace('bi-moon-stars-fill', 'bi-sun-fill');
            themeIconSidebar.style.color = '#ffc107';
        } else {
            themeIconSidebar.classList.replace('bi-sun-fill', 'bi-moon-stars-fill');
            themeIconSidebar.style.color = '#ffffff';
        }
    }

    // Jalankan saat pertama kali load agar ikon sinkron dengan storage
    updateSidebarIcon(localStorage.getItem('theme') || 'light');
</script>