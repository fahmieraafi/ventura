<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <h2 class="text-white fw-bold mb-4">Daftar Keinginan Saya</h2>

    <?php if (empty($barang)) : ?>
        <div class="alert alert-light text-center py-5 shadow-sm" style="border-radius: 15px;">
            <i class="bi bi-heartbreak text-danger" style="font-size: 3rem;"></i>
            <p class="mt-3 fw-bold">Belum ada alat yang disimpan.</p>
            <a href="<?= base_url('barang') ?>" class="btn btn-primary rounded-pill">Cari Alat Kamping</a>
        </div>
    <?php else : ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            <?php foreach ($barang as $b) :
                $listFoto = explode(',', $b['foto_barang']);
                $fotoUtama = !empty($listFoto[0]) ? $listFoto[0] : 'tenda.jpg';
            ?>
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
                        <img src="<?= base_url('uploads/barang/' . $fotoUtama) ?>" class="card-img-top p-3" style="height: 180px; object-fit: contain;">
                        <div class="card-body text-center pt-0">
                            <h6 class="fw-bold mb-1"><?= $b['nama_barang'] ?></h6>
                            <p class="text-primary small mb-3">Rp <?= number_format($b['harga_sewa'], 0, ',', '.') ?> / Hari</p>

                            <div class="d-grid gap-2">
                                <a href="<?= base_url('barang/' . $b['id_barang']) ?>" class="btn btn-primary btn-sm rounded-pill">Lihat Detail</a>
                                <a href="<?= base_url('wishlist/hapus/' . $b['id_barang']) ?>" class="btn btn-outline-danger btn-sm rounded-pill border-0">
                                    <i class="bi bi-trash"></i> Hapus
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>