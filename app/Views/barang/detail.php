<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-lg p-4" style="border-radius: 20px;">

                <div class="text-center mb-4">
                    <?php
                    $listFoto = explode(',', $barang['foto_barang']); ?>
                    <?php if (count($listFoto) > 1) : ?>
                        <div id="carouselDetail" class="carousel slide shadow-sm rounded" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <?php foreach ($listFoto as $key => $f) : ?>
                                    <div class="carousel-item <?= ($key == 0) ? 'active' : '' ?>">
                                        <img src="<?= base_url('uploads/barang/' . ($f ?: 'tenda.jpg')) ?>"
                                            class="d-block w-100 rounded"
                                            style="max-height: 400px; object-fit: contain;"
                                            alt="Foto <?= $key ?>">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselDetail" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon bg-dark rounded-circle" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselDetail" data-bs-slide="next">
                                <span class="carousel-control-next-icon bg-dark rounded-circle" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    <?php else : ?>
                        <img src="<?= base_url('uploads/barang/' . ($listFoto[0] ?: 'tenda.jpg')) ?>"
                            class="img-fluid rounded"
                            style="max-height: 400px; width: 100%; object-fit: contain;"
                            alt="<?= $barang['nama_barang'] ?>">
                    <?php endif; ?>
                </div>

                <div class="text-center">
                    <h2 class="fw-bold text-dark"><?= $barang['nama_barang'] ?></h2>
                    <h3 class="text-primary fw-bold">Rp <?= number_format($barang['harga_sewa'], 0, ',', '.') ?> / Hari</h3>

                    <div class="d-flex justify-content-center gap-3 my-3">
                        <span class="text-muted">Sisa Stok: <strong><?= $barang['stok'] ?></strong></span>
                        <span class="vr"></span>
                        <span class="text-muted">Kondisi: <span class="badge bg-light text-dark border"><?= $barang['kondisi'] ?></span></span>
                    </div>

                    <hr>

                    <div class="d-grid gap-2 mt-4">

                        <?php if (strtolower(session()->get('role')) == 'admin') : ?>
                            <div class="d-flex gap-2">
                                <a href="<?= base_url('barang/edit/' . $barang['id_barang']) ?>" class="btn btn-outline-warning btn-sm flex-fill fw-bold rounded-3">Edit</a>
                                <a href="<?= base_url('barang/delete/' . $barang['id_barang']) ?>" class="btn btn-outline-danger btn-sm flex-fill fw-bold rounded-3" onclick="return confirm('Hapus?')">Hapus</a>
                            </div>
                        <?php endif; ?>
                        <a href="<?= base_url('barang') ?>" class="btn btn-outline-secondary rounded-pill">Kembali</a>
                    </div>

                    <?php if (strtolower(session()->get('role')) == 'admin') : ?>
                        <p class="mt-3 text-muted small">
                            <i class="bi bi-eye"></i> Dilihat <?= $barang['views'] ?> kali (Admin Mode)
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>