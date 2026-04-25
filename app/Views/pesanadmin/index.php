<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>

<style>
    /* Reset & Base Dark Mode */
    body {
        background-color: #000;
        color: #fff;
    }

    .chat-card {
        background: #000;
        border: 1px solid #333;
        border-radius: 12px;
        overflow: hidden;
        margin-top: 2rem;
    }

    .chat-header {
        background: #000;
        border-bottom: 1px solid #333;
        padding: 1.5rem;
    }

    .search-wrapper {
        padding: 0 1.5rem 1rem 1.5rem;
        background: #000;
    }

    .search-input {
        background: #1a1a1a;
        border: 1px solid #333;
        border-radius: 8px;
        color: #fff;
        padding: 8px 12px;
        width: 100%;
        font-size: 14px;
        outline: none;
    }

    .search-input:focus {
        border-color: #0095f6;
    }

    .list-group-chat {
        background: transparent;
        max-height: 600px;
        overflow-y: auto;
    }

    .chat-item {
        background: transparent;
        border: none;
        border-bottom: 1px solid #1a1a1a;
        padding: 1.2rem 1.5rem;
        transition: 0.3s ease;
        text-decoration: none;
        display: block;
        position: relative;
    }

    .chat-item:hover {
        background: #121212;
    }

    /* CSS BARU: Tombol Hapus */
    .btn-delete-chat {
        background: transparent;
        border: none;
        color: #737373;
        transition: 0.2s;
        padding: 5px;
        z-index: 10;
        opacity: 0;
        /* Sembunyi secara default */
    }

    .chat-item:hover .btn-delete-chat {
        opacity: 1;
        /* Muncul saat baris di-hover */
    }

    .btn-delete-chat:hover {
        color: #ed4956;
        transform: scale(1.1);
    }

    .avatar-circle {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
        padding: 2px;
        flex-shrink: 0;
    }

    .avatar-inner {
        background: #262626;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1.2rem;
        border: 2px solid #000;
    }

    .user-name {
        color: #efefef;
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 2px;
    }

    .last-message {
        color: #a8a8a8;
        font-size: 13px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .chat-time {
        color: #737373;
        font-size: 12px;
    }

    .new-badge {
        width: 10px;
        height: 10px;
        background-color: #0095f6;
        border-radius: 50%;
        box-shadow: 0 0 5px #0095f6;
        display: inline-block;
        vertical-align: middle;
        flex-shrink: 0;
    }

    .list-group-chat::-webkit-scrollbar {
        width: 4px;
    }

    .list-group-chat::-webkit-scrollbar-thumb {
        background: #333;
        border-radius: 10px;
    }

    .chat-footer {
        background: #000;
        border-top: 1px solid #333;
        color: #737373;
        font-size: 12px;
    }


    .avatar-inner img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
        /* Agar foto tidak ketarik/gepeng */
    }

    /* Opsional: Tambahkan efek hover agar user tahu bisa diklik */
    .avatar-inner:hover {
        opacity: 0.8;
        transform: scale(1.05);
        transition: 0.2s;
    }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="chat-card shadow-lg">

                <div class="chat-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0 fw-bold text-white">Pesan</h5>
                        <small class="text-primary" style="font-size: 11px; letter-spacing: 1px; text-transform: uppercase;">Customer Service Mode</small>
                    </div>
                    <a href="<?= base_url('/') ?>" class="btn-close btn-close-white" aria-label="Close"></a>
                </div>

                <div class="search-wrapper">
                    <input type="text" id="searchUser" class="search-input" placeholder="Cari nama user...">
                </div>

                <div class="list-group-chat" id="chatList">
                    <?php if (empty($daftar_chat)) : ?>
                        <div class="text-center py-5">
                            <i class="bi bi-chat-dots text-secondary" style="font-size: 3rem; opacity: 0.3;"></i>
                            <p class="text-secondary mt-3">Kotak masuk kosong</p>
                        </div>
                    <?php else : ?>
                        <?php foreach ($daftar_chat as $chat) : ?>
                            <div class="chat-item-wrapper position-relative">
                                <a href="<?= base_url('pesan/index/' . $chat['id_user']) ?>" class="chat-item">
                                    <div class="d-flex align-items-center">

                                        <div class="avatar-circle me-3">
                                            <div class="avatar-inner" style="overflow: hidden; cursor: pointer;" onclick="viewImage('<?= base_url('uploads/users/' . ($chat['foto'] ? $chat['foto'] : 'default.jpg')) ?>')">
                                                <?php if (!empty($chat['foto']) && file_exists(FCPATH . 'uploads/users/' . $chat['foto'])) : ?>
                                                    <img src="<?= base_url('uploads/users/' . $chat['foto']) ?>"
                                                        style="width: 100%; height: 100%; object-fit: cover;">
                                                <?php else : ?>
                                                    <?= strtoupper(substr($chat['nama'], 0, 1)) ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <div class="flex-grow-1 overflow-hidden">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="user-name"><?= esc($chat['nama']) ?></span>
                                                <span class="chat-time"><?= date('H:i', strtotime($chat['created_at'])) ?></span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="last-message <?= ($chat['status_baca'] == '0' && $chat['id_pengirim'] != session()->get('id_user')) ? 'fw-bold text-white' : '' ?>">

                                                    <span style="color: #0095f6;">
                                                        <?= ($chat['id_pengirim'] == session()->get('id_user')) ? 'Anda: ' : esc($chat['nama']) . ': ' ?>
                                                    </span>

                                                    <?= $chat['tipe_pesan'] == 'image' ? '📷 Mengirim gambar' : ($chat['tipe_pesan'] == 'audio' ? '🎤 Pesan suara' : esc($chat['isi_pesan'])) ?>

                                                </div>

                                                <div class="d-flex align-items-center">
                                                    <?php if ($chat['status_baca'] == '0' && $chat['id_pengirim'] != session()->get('id_user')) : ?>
                                                        <div class="new-badge ms-2"></div>
                                                    <?php endif; ?>

                                                    <button type="button" class="btn-delete-chat ms-2 btn-hapus" data-id="<?= $chat['id_pesan'] ?>" onclick="event.preventDefault(); hapusPesan(<?= $chat['id_pesan'] ?>)">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <div class="chat-footer text-center py-3">
                    Gunakan daftar ini untuk membalas pertanyaan user secara cepat.
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Fungsi Pencarian Real-time
    document.getElementById('searchUser').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let items = document.querySelectorAll('.chat-item-wrapper');

        items.forEach(function(item) {
            let name = item.querySelector('.user-name').textContent.toLowerCase();
            if (name.indexOf(filter) > -1) {
                item.style.display = "";
            } else {
                item.style.display = "none";
            }
        });
    });

    // FUNGSI BARU: Hapus Pesan via AJAX
    function hapusPesan(id) {
        if (confirm('Hapus percakapan terakhir ini dari daftar?')) {
            $.ajax({
                url: "<?= base_url('pesan/hapus') ?>/" + id,
                type: "POST",
                dataType: "json",
                success: function(response) {
                    if (response.status === 'success') {
                        location.reload(); // Refresh untuk update daftar
                    } else {
                        alert('Gagal menghapus: ' + response.msg);
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan saat menghapus pesan.');
                }
            });
        }
    }

    // Refresh daftar chat secara otomatis setiap 5 detik 
    setInterval(function() {
        if (document.getElementById('searchUser').value === "") {
            $("#chatList").load(location.href + " #chatList > *");
        }
    }, 5000);


    function viewImage(url) {
        // Membuka foto profil di tab baru
        window.open(url, '_blank');
    }
</script>

<?= $this->endSection(); ?>