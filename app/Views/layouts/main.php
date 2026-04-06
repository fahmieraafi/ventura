<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Ventura</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/bootstrap-icons-1.13.1/bootstrap-icons.css') ?>" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)),
                url('https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            font-family: "Poppins", sans-serif;
            color: #e4e4e4;
            display: flex;
            height: 100vh;
            margin: 0;
            /* overflow: hidden;  <-- HAPUS INI agar modal bisa berfungsi normal */
        }

        /* --- PERBAIKAN SIDEBAR --- */
        .sidebar {
            width: 260px;
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(15px);
            padding: 20px;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            flex-direction: column;
            z-index: 1000;
            /* Turunkan sedikit agar tidak balapan dengan modal */
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            left: 0;
            position: relative;
        }

        .sidebar.active {
            margin-left: -260px;
            opacity: 0;
        }

        /* --- PERBAIKAN WRAPPER --- */
        .main-wrapper {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            /* Mencegah overflow table merusak layout */
            position: relative;
        }

        .content-scroll {
            padding: 30px;
            overflow-y: auto;
            flex-grow: 1;
            /* Hapus atau pastikan z-index tidak mengunci modal */
        }

        /* --- SOLUSI AMPUH UNTUK MODAL --- */
        .modal {
            z-index: 2000 !important;
            /* Paksa modal di depan segala hal */
        }

        .modal-backdrop {
            z-index: 1900 !important;
            /* Backdrop di bawah modal sedikit */
        }

        /* Styling tambahan bawaan kamu */
        .sidebar-brand {
            font-size: 22px;
            font-weight: 600;
            color: #fff;
            padding: 10px 15px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            border-radius: 10px;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            margin-bottom: 5px;
            transition: all 0.3s ease;
        }

        .sidebar-menu a:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            padding-left: 20px;
        }

        .top-navbar {
            height: 70px;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            z-index: 1010;
        }

        .user-profile-nav {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            color: white;
            text-decoration: none;
        }

        .user-avatar {
            width: 35px;
            height: 35px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .content-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            padding: 25px;
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            animation: fadeIn 0.8s ease;
        }

        @keyframes fadeIn {
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
    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <i class="bi bi-mountain"></i> ventura
        </div>
        <div class="sidebar-menu">
            <?php include(APPPATH . 'Views/layouts/menu.php'); ?>
        </div>
    </div>

    <div class="main-wrapper">
        <nav class="top-navbar">
            <button class="btn text-white p-0" id="toggleSidebar">
                <i class="bi bi-list fs-4"></i>
            </button>

            <div class="dropdown">
                <a class="user-profile-nav dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                    <img src="<?= base_url('uploads/users/' . (session()->get('foto') ?: 'default.png')) ?>" class="user-avatar" alt="User">
                    <span class="d-none d-md-inline"><?= session()->get('username') ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark">
                    <li><a class="dropdown-item" href="<?= base_url('users/edit/' . session()->get('id_user')) ?>"><i class="bi bi-person me-2"></i>Profil</a></li>
                    <li>
                        <hr class="dropdown-divider border-secondary">
                    </li>
                    <li><a class="dropdown-item text-danger" href="<?= base_url('logout') ?>"><i class="bi bi-box-arrow-right me-2"></i>Keluar</a></li>
                </ul>
            </div>
        </nav>

        <div class="content-scroll">
            <div class="content-card">
                <?= $this->renderSection('content') ?>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggleSidebar');
        const icon = toggleBtn.querySelector('i');

        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('active');
            icon.classList.toggle('rotate-icon');
        });
    </script>
</body>

</html>