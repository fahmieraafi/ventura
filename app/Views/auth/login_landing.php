<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="icon" href="<?= base_url('assets/img/logo ventura.png') ?>" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(rgba(0, 0, 0, 0.42), rgba(171, 172, 174, 0.33)),
                url('<?= base_url("assets/img/Desain tanpa judul.png") ?>');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
        }

        /* CSS NOTIFIKASI MELAYANG */
        .notification-container {
            position: fixed;
            top: 100px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            width: 90%;
            max-width: 400px;
            text-align: center;
        }

        .alert-custom {
            padding: 12px 25px;
            border-radius: 50px;
            backdrop-filter: blur(10px);
            margin-bottom: 10px;
            font-weight: 500;
            font-size: 0.9rem;
            display: inline-block;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .alert-error {
            background: rgba(255, 82, 82, 0.2);
            border: 1px solid rgba(255, 82, 82, 0.5);
            color: #ff8a80;
        }

        .alert-success {
            background: rgba(76, 175, 80, 0.2);
            border: 1px solid rgba(76, 175, 80, 0.5);
            color: #b9f6ca;
        }

        /* Navigasi Transparan */
        .floating-nav {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            padding: 20px 0;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(8px);
        }

        .navbar-brand img {
            height: 50px;
            filter: brightness(0) invert(1);
        }

        .navbar-brand span {
            font-size: 1.6rem;
            font-weight: 800;
            letter-spacing: 2px;
            color: white;
        }

        /* Hero Tulisan Melayang */
        .hero-floating {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            min-height: 70vh;
            padding-top: 100px;
        }

        .hero-floating h1 {
            font-family: 'Times New Roman', Times, serif;
            font-size: 4.5rem;
            font-weight: bold;
            color: white;
            text-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
            margin-bottom: 10px;
        }

        .hero-floating p {
            font-size: 1.3rem;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.4);
            max-width: 700px;
        }

        /* Search Bar Transparan */
        .search-container {
            width: 100%;
            max-width: 600px;
            margin-top: 30px;
        }

        .search-container input {
            height: 60px;
            border-radius: 50px;
            padding-left: 30px;
            background: rgba(255, 255, 255, 0.2) !important;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3) !important;
            color: white !important;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .search-container input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        /* Kategori Transparan */
        .category-scroll {
            display: flex;
            gap: 25px;
            overflow-x: auto;
            padding: 20px 0;
            scrollbar-width: none;
            justify-content: center;
        }

        .category-item {
            text-align: center;
            cursor: pointer;
            transition: 0.3s;
            color: white;
        }

        .category-circle {
            width: 75px;
            height: 75px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
            border: 2px solid rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            transition: 0.3s;
        }

        .category-circle i {
            font-size: 1.8rem;
            color: white;
        }

        .category-item:hover .category-circle {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.3);
            border-color: white;
        }

        /* Card Produk */
        .card-barang {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            overflow: hidden;
            transition: 0.3s;
        }

        .img-wrapper {
            width: 100%;
            height: 200px;
            overflow: hidden;
        }

        .img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .card-body h6 {
            color: white;
            font-weight: 600;
        }

        .price-tag {
            color: white;
            font-weight: 700;
            opacity: 0.9;
        }

        .section-title {
            color: white;
            font-weight: 800;
            margin: 50px 0 30px 0;
            text-align: center;
            text-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        }

        .btn-login-floating {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            color: white;
            padding: 8px 25px;
            border-radius: 50px;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-login-floating:hover {
            background: white;
            color: black;
        }

        .footer-text a {
            color: white;
            font-weight: bold;
            text-decoration: none;
        }

        /* --- ANIMASI LOGIN BARU --- */
        .modal-content {
            overflow: hidden;
            position: relative;
        }

        /* Efek Input Bercahaya */
        .form-control:focus {
            background: rgba(255, 255, 255, 0.25) !important;
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.3);
            border-color: white !important;
            transform: scale(1.02);
            transition: all 0.3s ease;
        }

        /* Efek Tombol Masuk Bergetar Tipis (Glow) */
        .btn-light:hover {
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.8);
            transform: translateY(-2px);
            letter-spacing: 1px;
        }

        /* Animasi Latar Belakang Modal (Partikel Sederhana) */
        .login-bg-shapes div {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            z-index: -1;
            animation: float 10s infinite linear;
        }

        @keyframes float {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 0;
            }

            50% {
                opacity: 0.5;
            }

            100% {
                transform: translateY(-100px) rotate(360deg);
                opacity: 0;
            }
        }
    </style>
</head>

<body>

    <div class="notification-container">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert-custom alert-error animate__animated animate__fadeInDown">
                <i class="bi bi-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('salahpw')): ?>
            <div class="alert-custom alert-error animate__animated animate__fadeInDown">
                <i class="bi bi-shield-lock me-2"></i><?= session()->getFlashdata('salahpw') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert-custom alert-success animate__animated animate__fadeInDown">
                <i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
    </div>

    <header class="floating-nav">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="<?= base_url('assets/img/logo ventura.png') ?>" alt="Logo">
                <span class="ms-2">VENTURA</span>
            </a>
            <button class="btn btn-login-floating" data-bs-toggle="modal" data-bs-target="#loginModal">
                LOGIN
            </button>
        </div>
    </header>

    <div class="container">
        <div class="hero-floating animate__animated animate__fadeIn">
            <h1>Eksplorasi Tanpa Batas</h1>
            <p>Sewa alat kamping kualitas premium untuk petualangan yang tak terlupakan.</p>

            <div class="search-container">
                <input type="text" id="searchInput" class="form-control" placeholder="Mau cari alat apa hari ini?">
            </div>

            <div class="category-scroll animate__animated animate__fadeInUp">
                <div class="category-item" onclick="filterCategory('')">
                    <div class="category-circle"><i class="bi bi-grid"></i></div>
                    <p>Semua</p>
                </div>
                <div class="category-item" onclick="filterCategory('Tenda')">
                    <div class="category-circle"><i class="bi bi-house"></i></div>
                    <p>Tenda</p>
                </div>
                <div class="category-item" onclick="filterCategory('Tas')">
                    <div class="category-circle"><i class="bi bi-backpack"></i></div>
                    <p>Carrier</p>
                </div>
                <div class="category-item" onclick="filterCategory('Sepatu')">
                    <div class="category-circle"><i class="bi bi-universal-access"></i></div>
                    <p>Sepatu</p>
                </div>
            </div>
        </div>

        <h4 class="section-title">Peralatan Pilihan</h4>

        <div class="row g-4 pb-5" id="barangContainer">
            <?php foreach ($barang as $b) : ?>
                <?php
                $foto_list = explode(',', $b['foto_barang']);
                $foto_utama = trim($foto_list[0]);
                ?>
                <div class="col-6 col-md-4 col-lg-3 product-item">
                    <div class="card card-barang h-100" onclick="triggerLogin()">
                        <div class="img-wrapper">
                            <?php if (!empty($foto_utama)) : ?>
                                <img src="<?= base_url('uploads/barang/' . $foto_utama) ?>" alt="<?= $b['nama_barang'] ?>">
                            <?php else : ?>
                                <img src="<?= base_url('img/no-image.png') ?>" alt="No Image">
                            <?php endif; ?>
                        </div>
                        <div class="card-body p-3 text-center">
                            <h6 class="mb-1"><?= $b['nama_barang'] ?></h6>
                            <p class="price-tag mb-0">Rp <?= number_format($b['harga_sewa'], 0, ',', '.') ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 animate__animated animate__zoomIn" style="border-radius: 20px; background: rgba(0,0,0,0.85); backdrop-filter: blur(15px); color: white;">

                <div class="login-bg-shapes">
                    <div style="width: 50px; height: 50px; top: 10%; left: 10%; animation-delay: 0s;"></div>
                    <div style="width: 30px; height: 30px; top: 70%; left: 80%; animation-delay: 2s;"></div>
                    <div style="width: 20px; height: 20px; top: 40%; left: 60%; animation-delay: 4s;"></div>
                </div>

                <div class="modal-body p-5 text-center">
                    <div class="animate__animated animate__fadeInDown animate__delay-1s">
                        <i class="bi bi-person-circle" style="font-size: 3rem;"></i>
                        <h4 class="fw-bold mt-2">Selamat Datang</h4>
                        <p class="opacity-75 small">Silakan login untuk mulai menyewa</p>
                    </div>

                    <form action="<?= base_url('proses-login') ?>" method="post" class="mt-4 animate__animated animate__fadeInUp animate__delay-1s">
                        <div class="mb-3">
                            <input type="text" name="username" class="form-control bg-transparent text-white border-secondary py-2" placeholder="Username" required>
                        </div>
                        <div class="mb-3">
                            <input type="password" name="password" class="form-control bg-transparent text-white border-secondary py-2" placeholder="Password" required>
                        </div>
                        <button type="submit" class="btn btn-light w-100 fw-bold py-2 rounded-pill mt-3 shadow-sm">Masuk Sekarang</button>
                    </form>

                    <div class="footer-text mt-3 animate__animated animate__fadeIn animate__delay-2s">
                        Belum punya akun? <a href="<?= site_url('users/create') ?>" class="text-info">Daftar</a>
                    </div>
                    <p class="text-center mt-3 small animate__animated animate__fadeIn animate__delay-2s">
                        Lupa password? <a href="https://wa.me/+6289502918001?text=Halo%20Admin,%20saya%20lupa%20password%20akun%20Ventura%20saya" target="_blank" class="text-success">Hubungi Admin</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // AUTO HIDE NOTIFIKASI
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert-custom');
            alerts.forEach(alert => {
                alert.classList.add('animate__fadeOutUp');
                setTimeout(() => alert.remove(), 1000);
            });
        }, 4000);

        const searchInput = document.getElementById('searchInput');
        const items = document.querySelectorAll('.product-item');

        function filterProducts() {
            const query = searchInput.value.toLowerCase();
            items.forEach(item => {
                const text = item.querySelector('h6').innerText.toLowerCase();
                item.style.display = text.includes(query) ? "" : "none";
            });
        }

        searchInput.addEventListener('input', filterProducts);

        function filterCategory(cat) {
            searchInput.value = cat;
            filterProducts();
        }

        function triggerLogin() {
            Swal.fire({
                icon: 'info',
                title: 'Mau Sewa?',
                text: 'Silakan login terlebih dahulu.',
                confirmButtonColor: '#000',
                background: '#fff',
                color: '#000'
            }).then((result) => {
                if (result.isConfirmed) {
                    new bootstrap.Modal(document.getElementById('loginModal')).show();
                }
            });
        }
    </script>
</body>

</html>