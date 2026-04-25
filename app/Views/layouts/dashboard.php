<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4 mt-4">

    <div class="mb-5 animate__animated animate__fadeInDown">
        <h2 class="text-white fw-bold display-6 mb-1">Dashboard Ventura</h2>
        <p class="text-white-50">
            Selamat datang kembali, <span class="text-info fw-bold"><?= session()->get('username'); ?></span>!
            <span class="ms-2 badge bg-danger rounded-pill px-3 py-1 text-uppercase fs-10">Role: <?= session()->get('role'); ?></span>
        </p>

        <?php if (session()->get('role') === 'admin' && isset($pesanan_baru) && $pesanan_baru > 0) : ?>
            <div class="mt-3 animate__animated animate__fadeInUp">
                <a href="<?= base_url('admin/transaksi') ?>" class="text-decoration-none">
                    <div class="alert border-0 shadow-lg d-flex align-items-center justify-content-between p-3"
                        style="background: rgba(13, 202, 240, 0.15); backdrop-filter: blur(10px); border-radius: 15px; border-left: 5px solid #0dcaf0 !important;">
                        <div class="d-flex align-items-center">
                            <div class="icon-shape bg-gradient-info shadow-info me-3" style="width: 45px; height: 45px; border-radius: 12px;">
                                <i class="bi bi-bell-fill text-white fs-5"></i>
                            </div>
                            <div>
                                <strong class="text-white d-block">Ada Pesanan Baru!</strong>
                                <span class="text-white-50 small">Terdapat <span class="text-info fw-bold"><?= $pesanan_baru ?></span> pesanan yang perlu diproses.</span>
                            </div>
                        </div>
                        <i class="bi bi-chevron-right text-white-50"></i>
                    </div>
                </a>
            </div>
        <?php endif; ?>



        <?php if (session()->get('role') === 'admin' && isset($total_terlambat) && $total_terlambat > 0) : ?>
            <a href="<?= base_url('admin/transaksi') ?>" class="text-decoration-none">
                <div class="alert alert-danger d-flex align-items-center mt-3 py-2" style="background: rgba(255, 0, 0, 0.2); border-left: 5px solid #ff0000; transition: 0.3s;">
                    <div class="icon-shape bg-danger text-white p-2 rounded me-3">
                        <i class="fas fa-exclamation-triangle fa-sm"></i>
                    </div>
                    <div>
                        <h6 class="mb-0 text-white fw-bold">Ada Keterlambatan!</h6>
                        <p class="mb-0 text-white-50" style="font-size: 0.75rem;">
                            Terdapat <?= $total_terlambat ?> orang yang belum mengembalikan alat.
                        </p>
                    </div>
                    <div class="ms-auto text-white-50">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </div>
            </a>
        <?php endif; ?>


    </div>

    <?php if (session()->get('role') === 'user' && isset($total_denda) && $total_denda > 0) : ?>
        <div class="alert alert-danger border-0 shadow-lg animate__animated animate__shakeX d-flex align-items-center"
            style="background: rgba(220, 53, 69, 0.2); backdrop-filter: blur(10px); border-radius: 15px; color: #ff8787;">
            <i class="bi bi-exclamation-triangle-fill me-3 fs-3"></i>
            <div>
                <strong class="d-block">Peringatan Denda!</strong>
                Kamu memiliki total denda sebesar <span class="fw-bold text-white">Rp <?= number_format($total_denda, 0, ',', '.'); ?></span>. Silakan hubungi admin untuk penyelesaian.
            </div>
        </div>
    <?php endif; ?>


    <?php if (session()->get('role') === 'user') : ?>

        <?php if (isset($transaksi_aktif) && !empty($transaksi_aktif)) : ?>
            <div class="mb-4 animate__animated animate__fadeIn">
                <a href="<?= base_url('riwayat') ?>" class="text-decoration-none">
                    <div class="card card-glass border-0 shadow-lg" style="border-left: 5px solid #ffc107 !important; border-radius: 15px;">
                        <div class="card-body py-3 px-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center flex-grow-1 overflow-hidden">
                                    <div class="me-4 d-none d-md-block">
                                        <div class="icon-shape bg-warning shadow-warning" style="width: 40px; height: 40px; border-radius: 10px;">
                                            <i class="bi bi-clock-history text-dark fs-5"></i>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center gap-4 overflow-auto custom-scrollbar" style="white-space: nowrap;">
                                        <?php foreach ($transaksi_aktif as $ta) : ?>
                                            <div class="item-aktif">
                                                <h6 class="text-white fw-bold mb-0" style="font-size: 0.95rem;">
                                                    <?= $ta['nama_barang'] ?>
                                                </h6>
                                                <small class="text-warning" style="font-size: 0.8rem;">
                                                    <i class="bi bi-calendar-check me-1"></i>Kembalikan pada tanggal: <?= date('d M Y', strtotime($ta['tgl_kembali'])) ?>
                                                </small>
                                            </div>
                                            <?php if (count($transaksi_aktif) > 1) : ?>
                                                <div class="vr bg-white opacity-25" style="height: 30px;"></div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <div class="ms-3 d-flex align-items-center text-white-50">
                                    <span class="small me-2 d-none d-sm-inline">Riwayat</span>
                                    <i class="bi bi-chevron-right fs-5"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        <?php endif; ?>


        <div class="row mb-5 animate__animated animate__fadeIn">
            <div class="col-12">
                <div class="card border-0 overflow-hidden shadow-lg" style="border-radius: 25px; background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('<?= base_url('assets/img/photo-1504280390367-361c6d9f38f4.avif') ?>'); background-size: cover; background-position: center;">
                    <div class="card-body p-5 text-center">
                        <h2 class="text-white fw-bold mb-2">Siap berpetualang hari ini?</h2>
                        <p class="text-white-50 mb-4">Temukan alat kamping terbaik untuk pengalaman tak terlupakan.</p>
                        <a href="<?= base_url('barang') ?>" class="btn btn-info btn-lg px-5 shadow-sm fw-bold" style="border-radius: 12px;">
                            <i class="bi bi-search me-2"></i>Cari Alat Kamping
                        </a>
                    </div>
                </div>
            </div>
        </div>


        <?php if (session()->get('role') === 'user' && !empty($rekomendasi_barang)) : ?>
            <div class="mb-5 animate__animated animate__fadeInUp">
                <h4 class="text-white fw-bold mb-4 d-flex align-items-center">
                    <i class="bi bi-stars me-2 text-info"></i> Untuk Kamu
                </h4>
                <div class="row g-4">
                    <?php foreach ($rekomendasi_barang as $rb) : ?>
                        <div class="col-6 col-md-3">
                            <a href="<?= base_url('barang') ?>" class="text-decoration-none">
                                <div class="card card-glass border-0 h-100 card-kategori overflow-hidden">
                                    <?php
                                    $fotoRecom = explode(',', $rb['foto_barang']);
                                    $fotoTampilRecom = !empty($fotoRecom[0]) ? $fotoRecom[0] : 'default.jpg';
                                    ?>
                                    <img src="<?= base_url('uploads/barang/' . $fotoTampilRecom) ?>" class="card-img-top" style="height: 160px; object-fit: cover;">
                                    <div class="card-body p-3">
                                        <p class="text-white-50 small mb-1 text-uppercase fs-10 fw-bold"><?= $rb['kategori'] ?></p>
                                        <h6 class="text-white fw-bold mb-2 text-truncate"><?= $rb['nama_barang'] ?></h6>
                                        <p class="text-info fw-bold mb-0 small">Rp <?= number_format($rb['harga_sewa'], 0, ',', '.') ?>/hari</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>


    <?php endif; ?>



    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success border-0 shadow-sm animate__animated animate__fadeIn" style="border-radius: 12px;">
            <i class="bi bi-check-circle-fill me-2"></i>
            <?= session()->getFlashdata('success'); ?>
        </div>
    <?php endif; ?>

    <div class="row g-4 mb-5">
        <div class="col-xl-4 animate__animated animate__fadeInLeft">
            <a href="<?= base_url('barang') ?>" class="text-decoration-none">
                <div class="card card-glass border-0 shadow-lg h-100">
                    <div class="card-body p-4 d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-white-50 text-uppercase fw-bold mb-1 fs-12 tracking-wider">Data Alat</p>
                            <h1 class="text-white fw-bold display-5 mb-1 counter-value" data-target="<?= $totalBarang; ?>">0</h1>
                            <p class="text-white-50 mb-0 small">Total alat kamping</p>
                        </div>
                        <div class="icon-shape bg-gradient-info shadow-info">
                            <i class="bi bi-box-seam-fill fs-2 text-white"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <?php if (session()->get('role') === 'admin') : ?>
            <div class="col-xl-4 animate__animated animate__fadeInUp">
                <a href="<?= base_url('users') ?>" class="text-decoration-none">
                    <div class="card card-glass border-0 shadow-lg h-100">
                        <div class="card-body p-4 d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-white-50 text-uppercase fw-bold mb-1 fs-12 tracking-wider">Total Users</p>
                                <h1 class="text-white fw-bold display-5 mb-1 counter-value" data-target="<?= $totalUser; ?>">0</h1>
                                <p class="text-white-50 mb-0 small">User terdaftar sistem</p>
                            </div>
                            <div class="icon-shape bg-gradient-info shadow-info">
                                <i class="bi bi-people-fill fs-2 text-white"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-xl-4 animate__animated animate__fadeInRight">
                <a href="<?= base_url('admin/transaksi') ?>" class="text-decoration-none">
                    <div class="card card-glass border-0 shadow-lg h-100" style="background: linear-gradient(135deg, rgba(0, 210, 255, 0.1), rgba(255, 255, 255, 0.05)) !important;">
                        <div class="card-body p-4 d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-info text-uppercase fw-bold mb-1 fs-12 tracking-wider">Total Pendapatan</p>
                                <h1 class="text-white fw-bold display-6 mb-1 counter-value" data-target="<?= $totalPendapatan; ?>">0</h1>
                                <p class="text-white-50 mb-0 small">Klik untuk melihat detail keuangan</p>
                            </div>
                            <div class="icon-shape bg-gradient-info shadow-info" style="filter: hue-rotate(45deg);">
                                <i class="bi bi-cash-stack fs-2 text-white"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        <?php else : ?>
            <div class="col-xl-8 animate__animated animate__fadeInRight">
                <div class="card card-glass border-0 shadow-lg h-100">
                    <div class="card-body p-4 d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-white-50 text-uppercase fw-bold mb-1 fs-12 tracking-wider">Status Member</p>
                            <h1 class="text-white fw-bold display-5 mb-1">Aktif</h1>
                            <p class="text-white-50 mb-0 small">Siap melakukan petualangan</p>
                        </div>
                        <div class="icon-shape bg-gradient-info shadow-info">
                            <i class="bi bi-shield-check fs-1 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>



    <?php if (session()->get('role') === 'admin') : ?>
        <div class="row mb-5 g-4 animate__animated animate__fadeInUp">
            <div class="col-lg-8">
                <div class="card card-glass border-0 shadow-lg h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h4 class="text-white fw-bold mb-0">Grafik Revenue</h4>
                                <p class="text-white-50 small mb-0">Tren bulanan tahun <?= date('Y'); ?></p>
                            </div>
                        </div>
                        <div style="height: 350px; position: relative;">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card card-glass border-0 shadow-lg h-100">
                    <div class="card-body p-4">
                        <h4 class="text-white fw-bold mb-0">Proporsi Pendapatan</h4>
                        <p class="text-white-50 small mb-4">Bulan <?= date('F'); ?></p>
                        <div style="height: 280px; position: relative;">
                            <canvas id="categoryPieChart"></canvas>
                        </div>
                        <div class="mt-3 text-center">
                            <small class="text-white-50 italic">Berdasarkan kategori alat yang disewa</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="row g-4 mb-5">
        <div class="col-xl-6 animate__animated animate__fadeInLeft">
            <a href="<?= base_url('barang') ?>" class="text-decoration-none">
                <div class="card card-glass border-0 shadow-lg h-100">
                    <div class="card-body p-4 d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-white-50 text-uppercase fw-bold mb-1 fs-12 tracking-wider">Total Stok</p>
                            <h1 class="text-white fw-bold display-5 mb-1 counter-value" data-target="<?= $totalStok; ?>">0</h1>
                            <p class="text-white-50 mb-0 small">Total stok alat kamping tersedia</p>
                        </div>
                        <div class="icon-shape bg-gradient-info shadow-info">
                            <i class="bi bi-archive-fill fs-2 text-white"></i>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <?php if (session()->get('role') === 'admin') : ?>
            <div class="col-xl-6 animate__animated animate__fadeInRight">
                <a href="<?= base_url('admin/transaksi') ?>" class="text-decoration-none">
                    <div class="card card-glass border-0 shadow-lg h-100">
                        <div class="card-body p-4 d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-warning text-uppercase fw-bold mb-1 fs-12 tracking-wider">Alat Dipinjam</p>
                                <?php
                                $db = \Config\Database::connect();
                                $totalDipinjam = $db->table('transaksi')->where('status_transaksi', 'Dipinjam')->countAllResults();
                                ?>
                                <h1 class="text-white fw-bold display-5 mb-1 counter-value" data-target="<?= $totalDipinjam; ?>">0</h1>
                                <p class="text-white-50 mb-0 small">Klik untuk mengelola transaksi aktif</p>
                            </div>
                            <div class="icon-shape bg-warning shadow-warning" style="background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);">
                                <i class="bi bi-cart-check-fill fs-2 text-white"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        <?php endif; ?>
    </div>

    <hr class="border-secondary opacity-25 mb-5">

    <div class="mb-4 animate__animated animate__fadeInUp">
        <h4 class="text-white fw-bold mb-4 d-flex align-items-center">
            <i class="bi bi-grid-3x3-gap me-3 text-info"></i> Rincian Per Kategori
        </h4>

        <div class="row g-3">
            <?php if (!empty($rincianKategori)) : ?>
                <?php foreach ($rincianKategori as $rk) : ?>
                    <div class="col-md-6 col-lg-3">
                        <a href="<?= base_url('barang?cari=' . urlencode($rk['kategori'])) ?>" class="text-decoration-none">
                            <div class="card card-glass-sm border-0 h-100 card-kategori">
                                <div class="card-body p-3 d-flex align-items-center">
                                    <div class="me-3">
                                        <?php
                                        $fotoArray = explode(',', $rk['foto_barang']);
                                        $fotoTampil = !empty($fotoArray[0]) ? $fotoArray[0] : 'tenda.jpg';
                                        ?>
                                        <img src="<?= base_url('uploads/barang/' . $fotoTampil) ?>"
                                            alt="<?= $rk['kategori'] ?>"
                                            class="shadow-sm"
                                            style="width: 45px; height: 45px; object-fit: cover; border-radius: 50%; border: 2px solid rgba(13, 202, 240, 0.5);">
                                    </div>
                                    <div>
                                        <p class="text-white-50 text-uppercase mb-0 fs-11 fw-bold">
                                            <?= ($rk['kategori']) ? esc($rk['kategori']) : 'Lainnya'; ?>
                                        </p>
                                        <h4 class="text-white mb-0 fw-bold">
                                            <span class="counter-value" data-target="<?= $rk['total']; ?>">0</span> <span class="fs-12 fw-normal text-white-50">Item</span>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="col-12 text-center py-4 card-glass">
                    <p class="text-white-50 mb-0 italic">Belum ada rincian kategori tersedia.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // --- ANIMASI COUNTER UP ---
        const counters = document.querySelectorAll('.counter-value');
        const speed = 100; // Kecepatan animasi

        counters.forEach(counter => {
            const animate = () => {
                const target = +counter.getAttribute('data-target');
                const count = +counter.innerText.replace(/[^0-9]/g, '');
                const increment = Math.ceil(target / speed);

                if (count < target) {
                    const nextCount = count + increment > target ? target : count + increment;

                    // Format khusus untuk Pendapatan
                    if (target > 1000 && counter.closest('.card-body').innerText.includes('Pendapatan')) {
                        counter.innerText = 'Rp ' + nextCount.toLocaleString('id-ID');
                    } else {
                        counter.innerText = nextCount.toLocaleString('id-ID');
                    }
                    setTimeout(animate, 20);
                } else {
                    if (target > 1000 && counter.closest('.card-body').innerText.includes('Pendapatan')) {
                        counter.innerText = 'Rp ' + target.toLocaleString('id-ID');
                    } else {
                        counter.innerText = target.toLocaleString('id-ID');
                    }
                }
            }
            animate();
        });

        // --- LOGIKA GRAFIK REVENUE (LINE) ---
        <?php if (isset($pendapatan_bulanan)) : ?>
            const ctx = document.getElementById('revenueChart').getContext('2d');
            const shadowPlugin = {
                beforeDraw: (chart) => {
                    const {
                        ctx
                    } = chart;
                    ctx.shadowColor = 'rgba(0, 210, 255, 0.5)';
                    ctx.shadowBlur = 15;
                    ctx.shadowOffsetX = 0;
                    ctx.shadowOffsetY = 10;
                },
                afterDraw: (chart) => {
                    chart.ctx.shadowColor = 'rgba(0, 0, 0, 0)';
                    chart.ctx.shadowBlur = 0;
                }
            };

            new Chart(ctx, {
                type: 'line',
                plugins: [shadowPlugin],
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [{
                        label: 'Revenue',
                        data: <?= $pendapatan_bulanan; ?>,
                        borderColor: '#00d2ff',
                        borderWidth: 4,
                        tension: 0.4,
                        fill: true,
                        backgroundColor: (context) => {
                            const bg = context.chart.ctx.createLinearGradient(0, 0, 0, 400);
                            bg.addColorStop(0, 'rgba(0, 210, 255, 0.2)');
                            bg.addColorStop(1, 'rgba(0, 210, 255, 0)');
                            return bg;
                        },
                        pointBackgroundColor: '#00d2ff',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#161b22',
                            padding: 12,
                            callbacks: {
                                label: (item) => ' Rp ' + item.raw.toLocaleString('id-ID')
                            }
                        }
                    },
                    scales: {
                        y: {
                            grid: {
                                color: 'rgba(255, 255, 255, 0.05)'
                            },
                            ticks: {
                                color: '#8b949e',
                                callback: (v) => v >= 1000 ? 'Rp ' + (v / 1000) + 'K' : 'Rp ' + v
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#8b949e'
                            }
                        }
                    }
                }
            });
        <?php endif; ?>

        // --- LOGIKA GRAFIK PIE/DOUGHNUT (KATEGORI) ---
        <?php if (isset($label_pie)) : ?>
            const ctxPie = document.getElementById('categoryPieChart').getContext('2d');
            new Chart(ctxPie, {
                type: 'doughnut',
                data: {
                    labels: <?= $label_pie; ?>,
                    datasets: [{
                        data: <?= $data_pie; ?>,
                        backgroundColor: [
                            '#0dcaf0', '#0099ff', '#6610f2', '#fd7e14', '#20c997', '#ffc107'
                        ],
                        hoverOffset: 20,
                        borderWidth: 0,
                        borderRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#8b949e',
                                padding: 20,
                                font: {
                                    size: 11
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: '#161b22',
                            callbacks: {
                                label: (item) => ' ' + item.label + ': Rp ' + item.raw.toLocaleString('id-ID')
                            }
                        }
                    }
                }
            });
        <?php endif; ?>
    });
</script>

<style>
    /* Efek hover supaya admin tahu kalau ini bisa diklik */
    .alert-danger:hover {
        background: rgba(255, 0, 0, 0.3) !important;
        transform: translateX(5px);
    }


    .card-glass {
        background: rgba(255, 255, 255, 0.05) !important;
        backdrop-filter: blur(12px) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        border-radius: 24px !important;
        transition: all 0.3s ease;
    }

    .card-glass-sm {
        background: rgba(255, 255, 255, 0.03) !important;
        backdrop-filter: blur(10px) !important;
        border: 1px solid rgba(255, 255, 255, 0.05) !important;
        border-radius: 16px !important;
        transition: all 0.3s ease;
    }

    .card-kategori:hover {
        background: rgba(13, 202, 240, 0.15) !important;
        transform: translateY(-5px);
        border: 1px solid rgba(13, 202, 240, 0.3) !important;
    }

    .icon-shape {
        width: 70px;
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 20px;
    }

    .bg-gradient-info {
        background: linear-gradient(135deg, #0dcaf0 0%, #0099ff 100%);
    }

    .shadow-info {
        box-shadow: 0 8px 25px rgba(13, 202, 240, 0.4);
    }

    .shadow-warning {
        box-shadow: 0 8px 25px rgba(255, 193, 7, 0.4);
    }

    .fs-10 {
        font-size: 10px;
    }

    .fs-11 {
        font-size: 11px;
    }

    .fs-12 {
        font-size: 12px;
    }

    .tracking-wider {
        letter-spacing: 1.5px;
    }
</style>
<?= $this->endSection() ?>