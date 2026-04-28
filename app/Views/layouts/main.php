<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> Ventura</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/bootstrap-icons-1.13.1/bootstrap-icons.css') ?>" rel="stylesheet">
    <link rel="icon" href="<?= base_url('assets/img/logo ventura.png') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/htmx.org@1.9.10"></script>



    <style>
        body {
            background: linear-gradient(rgba(0, 0, 0, 0.42), rgba(171, 172, 174, 0.33)),
                url('<?= base_url("assets/img/Desain tanpa judul.png") ?>');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            font-family: "Poppins", sans-serif;
            color: #e4e4e4;
            margin: 0;
            min-height: 100vh;
        }

        .navbar-ventura {
            background: rgba(143, 148, 158, 0.11);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(237, 232, 232, 0.15);
            padding: 10px 40px;
            position: sticky;
            top: 0;
            z-index: 1050;
        }

        .navbar-brand-ventura {
            font-size: 24px;
            font-weight: 700;
            color: #fff !important;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .nav-link-ventura {
            color: rgba(255, 255, 255, 0.7) !important;
            font-weight: 500;
            padding: 10px 20px !important;
            transition: 0.3s;
            border-radius: 8px;
        }

        .nav-link-ventura:hover,
        .nav-link-ventura.active {
            color: #fff !important;
            background: rgba(255, 255, 255, 0.1);
        }

        .user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        .main-container {
            padding: 40px 20px;
            max-width: 1300px;
            margin: 0 auto;
        }

        .content-card {
            background: rgba(255, 255, 255, 0.05);
            padding: 30px;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            animation: fadeIn 0.8s ease;
        }

        .dropdown-menu-dark {
            background-color: #848484b1;
            border: 1px solid rgba(212, 211, 211, 0.94);
        }

        #chat-window {
            bottom: 90px;
            right: 20px;
            width: 350px;
            height: 450px;
            z-index: 2000;
            border-radius: 15px;
            background: #1e293b;
            border: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            flex-direction: column;
        }

        #chat-body {
            flex: 1;
            overflow-y: auto;
            padding: 15px;
            background: rgba(15, 23, 42, 0.5);
        }

        .ai-msg,
        .user-msg {
            margin-bottom: 15px;
            max-width: 85%;
            padding: 10px;
            font-size: 0.85rem;
        }

        .ai-msg {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            border-radius: 15px 15px 15px 0;
            align-self: flex-start;
        }

        .user-msg {
            background: #0ea5e9;
            color: #fff;
            border-radius: 15px 15px 0 15px;
            align-self: flex-end;
            margin-left: auto;
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


        .floating-announcement {
            position: fixed;
            bottom: 20px;
            left: 50% !important;
            transform: translateX(-50%) !important;
            z-index: 9999;
            background: rgba(214, 214, 214, 0.6);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 6px 16px;
            border-radius: 30px;
            color: #ffffff;
            text-decoration: none;
            font-size: 11px;
            font-weight: 400;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .floating-announcement:hover {
            background: rgba(243, 48, 102, 0.9);
            color: #fff;
            bottom: 25px;
        }

        @media (max-width: 480px) {
            .floating-announcement {
                padding: 4px 12px;
                font-size: 10px;
                bottom: 15px;
            }
        }

        @media (max-width: 991px) {
            .navbar-ventura {
                padding: 10px 20px;
            }

            .navbar-collapse {
                background: rgba(30, 41, 59, 0.95);
                backdrop-filter: blur(10px);
                margin-top: 10px;
                padding: 20px;
                border-radius: 15px;
                border: 1px solid rgba(255, 255, 255, 0.1);
            }

            .nav-link-ventura {
                margin-bottom: 5px;
                padding-left: 15px !important;
            }
        }
    </style>


</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-ventura">
        <div class="container-fluid">
            <a class="navbar-brand-ventura" href="<?= base_url('dashboard') ?>">
                <i class="bi bi-mountain"></i> ventura
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="bi bi-list text-white fs-2"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav gap-2 mx-auto">
                    <li class="nav-item">
                        <a class="nav-link nav-link-ventura" href="<?= base_url('dashboard') ?>">
                            <i class="bi bi-house-door me-2"></i>Dashboard
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link nav-link-ventura" href="<?= base_url('barang') ?>">
                            <i class="bi bi-box-seam me-2"></i>Data Alat
                        </a>
                    </li>

                    <?php if (session()->get('role') == 'user' || session()->get('role') == 'User') : ?>
                        <li class="nav-item">
                            <a class="nav-link nav-link-ventura" href="<?= base_url('wishlist') ?>">
                                <i class="bi bi-heart-fill text-danger me-1"></i> Wishlist
                            </a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <a class="nav-link nav-link-ventura text-info" href="<?= base_url('gunung') ?>">
                            <i class="bi bi-geo-alt me-2"></i> Explore
                        </a>
                    </li>

                    <?php if (session()->get('role') == 'admin' || session()->get('role') == 'Admin') : ?>
                        <li class="nav-item">
                            <a class="nav-link nav-link-ventura text-info" href="<?= base_url('admin/transaksi') ?>">
                                <i class="bi bi-receipt me-2"></i>Kelola Transaksi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-link-ventura" href="<?= base_url('users') ?>">
                                <i class="bi bi-people me-2"></i>Users
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (session()->get('role') == 'user' || session()->get('role') == 'User') : ?>
                        <li class="nav-item">
                            <a class="nav-link nav-link-ventura text-warning" href="<?= base_url('riwayat') ?>">
                                <i class="bi bi-clock-history me-2"></i>Riwayat Pinjam
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>

                <div class="d-flex align-items-center gap-4 justify-content-center justify-content-lg-end mt-3 mt-lg-0">
                    <div class="dropdown">
                        <a class="text-white text-decoration-none position-relative" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-bell fs-5"></i>
                            <?php
                            $current_notif_count = isset($notif_count) ? $notif_count : 0;
                            $current_terlambat = isset($total_terlambat) ? $total_terlambat : 0;
                            $total_badge = $current_notif_count + $current_terlambat;
                            if ($total_badge > 0) : ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 10px;">
                                    <?= $total_badge ?>
                                </span>
                            <?php endif; ?>
                        </a>



                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark p-2 animate__animated animate__fadeIn" style="width: 300px; max-height: 400px; overflow-y: auto;">
                            <?php if (session()->get('role') == 'admin' || session()->get('role') == 'Admin') : ?>
                                <li class="px-3 py-2 border-bottom border-secondary mb-2">
                                    <h6 class="mb-0 small fw-bold text-info">Pesanan Baru</h6>
                                </li>
                                <?php if ($current_notif_count > 0 && isset($notif_list)) : ?>
                                    <?php foreach ($notif_list as $n) : ?>
                                        <li class="p-2 border-bottom border-secondary">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <small class="d-block fw-bold text-info"><?= $n['nama_user'] ?? 'User' ?></small>
                                                    <small class="fw-bold" style="font-size: 11px;">Melakukan penyewaan baru.</small>
                                                </div>
                                                <a href="<?= base_url('admin/transaksi/markAsRead/' . ($n['id_transaksi'] ?? '')) ?>" class="btn btn-sm btn-outline-light py-0 px-1">
                                                    <i class="bi bi-check2"></i>
                                                </a>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <li class="text-center py-2 text-muted small">Tidak ada pesanan baru</li>
                                <?php endif; ?>



                                <li class="px-3 py-2 border-bottom border-secondary my-2">
                                    <h6 class="mb-0 small fw-bold text-danger">Keterlambatan</h6>
                                </li>
                                <?php if ($current_terlambat > 0 && isset($list_terlambat)) : ?>
                                    <?php foreach ($list_terlambat as $lt) : ?>
                                        <li class="p-2 border-bottom border-secondary bg-danger bg-opacity-10">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>
                                                <div>
                                                    <small class="d-block fw-bold text-white"><?= $lt['nama_user'] ?></small>
                                                    <small class="text-white-50" style="font-size: 11px;">Telat: <b class="text-white"><?= $lt['nama_barang'] ?></b></small>
                                                </div>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <li class="text-center py-2 text-muted small">Tepat waktu</li>
                                <?php endif; ?>
                            <?php else : ?>
                                <li class="px-3 py-2 border-bottom border-secondary mb-2">
                                    <h6 class="mb-0 small fw-bold">Pemberitahuan</h6>
                                </li>
                                <?php if ($current_notif_count > 0) : ?>
                                    <li class="p-2">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-exclamation-circle text-danger me-2"></i>
                                            <div><small class="text-muted">Kamu memiliki <?= $current_notif_count ?> denda.</small></div>
                                        </div>
                                    </li>
                                <?php else : ?>
                                    <li class="text-center py-3 text-muted small">Tidak ada notifikasi</li>
                                <?php endif; ?>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <div class="dropdown">
                        <a class="d-flex align-items-center gap-3 text-white text-decoration-none dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <div class="text-end d-none d-sm-block">
                                <small class="d-block fw-bold" style="font-size: 10px;">Masuk sebagai:</small>
                                <span class="fw-bold"><?= session()->get('username') ?></span>
                            </div>
                            <img src="<?= base_url('uploads/users/' . (session()->get('foto') ?: 'default.png')) ?>" class="user-avatar" alt="User">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark">
                            <li><a class="dropdown-item" href="<?= base_url('users/edit/' . session()->get('id_user')) ?>"><i class="bi bi-person me-2"></i>Profil</a></li>
                            <li>
                                <hr class="dropdown-divider border-secondary">
                            </li>
                            <?php if (session()->get('role') == 'admin') : ?>
                                <li><a class="dropdown-item" href="<?= base_url('backup') ?>"><i class="bi bi-download me-2"></i>Backup</a></li>
                            <?php endif; ?>

                            <li><a class="dropdown-item text-danger" href="<?= base_url('logout') ?>"><i class="bi bi-box-arrow-right me-2"></i>Keluar</a></li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>


    <div class="main-container">
        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeInDown" role="alert" style="background: rgba(21, 128, 61, 0.2); border: 1px solid #22c55e; color: #fff; border-radius: 12px;">
                <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger alert-dismissible fade show animate__animated animate__shakeX" role="alert" style="background: rgba(185, 28, 28, 0.2); border: 1px solid #ef4444; color: #fff; border-radius: 12px;">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="content-card">
            <?= $this->renderSection('content') ?>
        </div>
    </div>

    <?php $urlPesan = (session()->get('role') == 'admin') ? base_url('pesan') : base_url('pesan/index/2'); ?>
    <a href="<?= $urlPesan ?>" class="btn btn-primary shadow-lg text-white btn-floating-custom rounded-circle position-fixed animate__animated animate__bounceInUp"
        style="bottom: 90px; right: 20px;">
        <i class="bi bi-chat-dots-fill fs-3"></i>
        <span id="badge-notif-chat" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none" style="font-size: 10px; z-index: 2001;">0</span>
    </a>

    <button id="chat-toggle" class="btn btn-info shadow-lg text-white btn-floating-custom rounded-circle position-fixed animate__animated animate__bounceInUp"
        style="bottom: 20px; right: 20px;">
        <i class="bi bi-robot fs-3"></i>
    </button>

    <div id="chat-window" class="card shadow-lg position-fixed d-none animate__animated animate__fadeInUp">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center" style="border-radius: 15px 15px 0 0;">
            <span class="fw-bold small"><i class="bi bi-stars me-2"></i>Ventura AI Assistant</span>
            <button type="button" class="btn-close btn-close-white" id="chat-close"></button>
        </div>
        <div class="card-body d-flex flex-column" id="chat-body">
            <div class="ai-msg">
                Halo <?= session()->get('username') ?>! Ada yang bisa saya bantu soal pendakian atau stok alat hari ini?
            </div>
        </div>
        <div class="card-footer bg-transparent border-top border-secondary">
            <div class="input-group">
                <input type="text" id="chat-input" class="form-control border-0 bg-dark text-white small" placeholder="Tanyakan stok tenda...">
                <button class="btn btn-info text-white" id="chat-send"><i class="bi bi-send"></i></button>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>

    <script>
        $(document).ready(function() {
            function updateNotifChat() {
                $.ajax({
                    url: "<?= base_url('pesan/countUnread') ?>",
                    method: "GET",
                    success: function(response) {
                        if (response.total > 0) {
                            $('#badge-notif-chat').text(response.total).removeClass('d-none');
                        } else {
                            $('#badge-notif-chat').addClass('d-none');
                        }
                    }
                });
            }
            setInterval(updateNotifChat, 10000);
            updateNotifChat();

            function scrollToBottom() {
                let chatBody = $('#chat-body');
                if (chatBody.length) {
                    chatBody.animate({
                        scrollTop: chatBody[0].scrollHeight
                    }, 300);
                }
            }

            function sendChat() {
                let inputField = $('#chat-input');
                let btnSend = $('#chat-send');
                let pesan = inputField.val().trim();

                if (pesan === '') return;

                inputField.prop('disabled', true);
                btnSend.prop('disabled', true);

                $('#chat-body').append(`<div class="user-msg">${pesan}</div>`);
                inputField.val('');
                scrollToBottom();

                let loadingId = "ai-loading-" + Date.now();
                $('#chat-body').append(`<div id="${loadingId}" class="ai-msg small">
                <i class="bi bi-three-dots animate__animated animate__flash animate__infinite"></i> Ventura sedang berpikir...
            </div>`);
                scrollToBottom();

                $.ajax({
                    url: "<?= base_url('chat/tanyaAi') ?>",
                    method: "POST",
                    dataType: "json",
                    data: {
                        pesan: pesan,
                        "<?= csrf_token() ?>": "<?= csrf_hash() ?>"
                    },
                    success: function(response) {
                        $(`#${loadingId}`).remove();
                        let jawaban = response.jawaban ? response.jawaban : "Gagal mengambil jawaban.";
                        $('#chat-body').append(`<div class="ai-msg">${jawaban}</div>`);

                        inputField.prop('disabled', false).focus();
                        btnSend.prop('disabled', false);
                        scrollToBottom();
                    },
                    error: function(xhr) {
                        $(`#${loadingId}`).remove();
                        $('#chat-body').append(`<div class="text-danger small px-3">Koneksi terputus atau Quota Habis.</div>`);

                        inputField.prop('disabled', false);
                        btnSend.prop('disabled', false);
                        scrollToBottom();
                    }
                });
            }

            $('#chat-send').on('click', function(e) {
                e.preventDefault();
                sendChat();
            });

            $('#chat-input').on('keypress', function(e) {
                if (e.which == 13) {
                    e.preventDefault();
                    sendChat();
                }
            });

            $('#chat-toggle, #chat-close').click(function() {
                $('#chat-window').toggleClass('d-none');
                scrollToBottom();
            });
        });
    </script>

    <a href="https://www.instagram.com/fhmiraafi?igsh=MWp5bW4xamJ2NDFyZQ==" target="_blank" class="floating-announcement animate__animated animate__fadeInUp">
        <i class="bi bi-globe" style="color: #22c55e;"></i>
        <span>Kunjungi development Kami Untuk Info Lebih Lanjut!</span>
    </a>
</body>

</html>