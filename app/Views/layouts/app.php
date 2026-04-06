<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title><?= $title ?? 'SITUS' ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css'); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        /* 🌙 Dark Mode Global */
        body {
            background-color: #120f0fff;
            color: #e5e5e5;
            font-family: 'Inter', sans-serif;
            transition: background 0.3s ease, color 0.3s ease;
        }

        .content {
            background: #040406ff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
            animation: fadeIn 0.6s ease both;
        }

        /* Card style (jika ada section dalam content) */
        .card {
            background: #2a2a3d;
            border: none;
            border-radius: 12px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.6);
        }

        /* Animasi fadeIn */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Link */
        a {
            color: #60a5fa;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #93c5fd;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar (nanti bisa include di sini) -->

        <!-- Content -->
        <main class="content">
            <div class="container-fluid p-0">
                <?= $this->renderSection('content') ?>
            </div>
        </main>
    </div>

    <script src="<?= base_url('assets/js/app.js'); ?>"></script>
</body>

</html>