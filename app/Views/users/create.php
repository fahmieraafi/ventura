<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Ventura - Petualangan Dimulai</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    
    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/bootstrap-icons-1.13.1/bootstrap-icons.css') ?>" rel="stylesheet">

    <style>
        body {
            /* Ganti dengan foto pegunungan/kamping yang Anda miliki */
            background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), 
                        url('https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 40px 0;
        }

        .card {
            background: rgba(255, 255, 255, 0.15); /* Transparan */
            backdrop-filter: blur(15px); /* Efek Kaca */
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            color: white;
        }

        .card-header {
            background: rgba(46, 125, 50, 0.8) !important; /* Hijau Hutan */
            border-radius: 20px 20px 0 0 !important;
            border-bottom: none;
            padding: 20px;
            text-align: center;
        }

        .card-header h4 {
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .form-label {
            font-weight: 400;
            font-size: 0.9rem;
            color: #f8f9fa;
        }

        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 10px;
            padding: 12px;
            transition: 0.3s;
        }

        .form-control:focus {
            background: #fff;
            box-shadow: 0 0 15px rgba(46, 125, 50, 0.4);
            transform: scale(1.01);
        }

        .btn-success {
            background-color: #2e7d32;
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-success:hover {
            background-color: #1b5e20;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .btn-link-custom {
            color: #f8f9fa;
            text-decoration: none;
            font-size: 0.9rem;
            transition: 0.3s;
        }

        .btn-link-custom:hover {
            color: #a5d6a7;
        }

        .input-group-text {
            background: #2e7d32;
            color: white;
            border: none;
        }

        /* Animasi Muncul */
        .container {
            animation: fadeInDown 0.8s ease-out;
        }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                
                <div class="card shadow-lg">
                    <div class="card-header">
                        <h4 class="mb-0"><i class="bi bi-person-plus-fill me-2"></i>Gabung Ventura</h4>
                        <small>Mulai perjalanan kamping Anda dengan menyewa alat di sini</small>
                    </div>
                    <div class="card-body p-4">

                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger border-0" style="background: rgba(220, 53, 69, 0.8); color: white;">
                                <?= session()->getFlashdata('error') ?>
                            </div>
                        <?php endif; ?>

                        <form action="<?= base_url('users/store') ?>" method="post" enctype="multipart/form-data">

                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <div class="input-group">
                                    <input type="text" name="nama" class="form-control" placeholder="Contoh: Fahmi Raafi'ulhaqq" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" placeholder="Username unik Anda" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Kata sandi rahasia" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Role</label>
                                <select name="role" class="form-select" required>
                                    <option value="" selected disabled>-- Pilih Status Anda --</option>
                                    <option value="admin">Administrator</option>
                                    <option value="user">Peminjam (User)</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Foto Profil</label>
                                <input type="file" name="foto" class="form-control" accept="image/*">
                                <small class="text-white-50 mt-1 d-block"><i class="bi bi-info-circle me-1"></i>Abaikan jika belum ada foto</small>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-mountain me-2"></i>Daftar Sekarang
                                </button>
                                <a href="<?= base_url('login') ?>" class="text-center btn-link-custom mt-2">
                                    Sudah punya akun? Masuk di sini
                                </a>
                            </div>

                        </form>

                    </div>
                </div>
                
                <p class="text-center mt-4 text-white-50" style="font-size: 0.8rem;">
                    &copy; <?= date('Y') ?> Ventura Camping System. Explore with us.
                </p>

            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

</body>

</html>