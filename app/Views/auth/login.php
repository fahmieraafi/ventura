<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="<?= base_url('assets/img/Logo abstrak hitam putih elegan.png'); ?>" />

    <title>Login - Ventura</title>

    <style>
        body {

            background: url("<?= base_url('assets/img/86c59eea10149bb460d73440425e5d01.jpg'); ?>") no-repeat center center fixed;
            background-size: cover;
            color: #e5e5e5;
            font-family: 'Poppins', sans-serif;
            backdrop-filter: blur(3px);
            transition: background 0.4s ease, color 0.4s ease;

            /* KUNCI POSISI TENGAH */
            display: flex;
            flex-direction: column;
            /* Menyusun teks sambutan di atas kotak login */
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        /* Teks Sambutan di Luar Kotak */
        .header-text {
            text-align: center;
            margin-bottom: 25px;
            animation: fadeInDown 0.8s ease-out;
        }

        .header-text h1 {
            font-size: 28px;
            font-weight: 600;
            /* Ditebalkan sedikit agar terbaca jelas */
            margin: 0;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
        }

        .header-text p {
            font-size: 14px;
            opacity: 0.9;
            font-weight: 300;
        }

        /* Kotak Login Glassmorphism */
        .login-box {
            background: rgba(20, 24, 32, 0.6);
            backdrop-filter: blur(15px);
            padding: 40px;
            width: 100%;
            max-width: 380px;
            /* Responsif untuk mobile */
            border-radius: 25px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.1);
            animation: fadeInUp 0.8s ease-out;
        }

        .logo {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            transition: 0.3s;
        }

        .logo:hover {
            transform: scale(1.05);
        }

        form {
            text-align: left;
        }

        label {
            font-size: 13px;
            color: #ddd;
            margin-bottom: 8px;
            display: block;
            font-weight: 300;
        }

        input {
            width: 100%;
            padding: 12px 15px;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            margin-bottom: 20px;
            font-size: 14px;
            transition: 0.3s;
            box-sizing: border-box;
            /* Mencegah input keluar dari kotak */
        }

        input::placeholder {
            color: #bbb;
        }

        input:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.2);
            border-color: #2e7d32;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #2e7d32;
            border: none;
            color: white;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            font-size: 16px;
            transition: 0.3s;
            margin-top: 10px;
        }

        button:hover {
            background: #1b5e20;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .footer-text {
            margin-top: 20px;
            font-size: 13px;
            color: #ccc;
            text-align: center;
        }

        .footer-text a {
            color: #a5d6a7;
            text-decoration: none;
            font-weight: 600;
        }

        .alert {
            background: rgba(255, 77, 77, 0.2);
            border: 1px solid #ff4d4d;
            color: #ff4d4d;
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 13px;
            text-align: center;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>

    <div class="header-text">
        <h1>Selamat datang, di ventura</h1>
        <p>Masuk ke akun Anda untuk melanjutkan</p>
    </div>

    <div class="login-box">
        <img src="<?= base_url('assets/img/Gemini_Generated_Image_u4oszau4oszau4os-removebg-preview.png') ?>" class="logo">

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('salahpw')): ?>
            <div class="alert"><?= session()->getFlashdata('salahpw') ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert" style="background: rgba(46, 125, 50, 0.2); border: 1px solid #a5d6a7; color: #a5d6a7;">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('/proses-login') ?>" method="post">
            <?= csrf_field() ?> <label>Nama pengguna</label>
            <input type="text" name="username" placeholder="Masukkan nama pengguna Anda" required />

            <label>Kata sandi</label>
            <input type="password" name="password" placeholder="Masukkan kata sandi Anda" required />

            <button type="submit">Masuk</button>

            <div class="footer-text">
                Belum punya akun? <a href="<?= site_url('users/create') ?>">Daftar</a>
            </div>
        </form>
    </div>

</body>

</html>