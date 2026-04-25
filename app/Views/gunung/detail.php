<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <a href="<?= base_url('gunung') ?>" class="btn btn-light rounded-pill mb-3 shadow-sm px-4">
                <i class="bi bi-arrow-left me-2"></i>Kembali ke Jelajah
            </a>

            <div class="card border-0 shadow-lg" style="border-radius: 25px; overflow: hidden;">
                <div class="row g-0">
                    <div class="col-md-6">
                        <img src="<?= base_url('uploads/gunung/' . $gunung['foto']) ?>"
                            class="img-fluid h-100 w-100"
                            style="object-fit: cover; min-height: 400px;"
                            alt="<?= $gunung['nama_gunung'] ?>">
                    </div>

                    <div class="col-md-6 p-5">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h2 class="fw-bold text-dark mb-0"><?= $gunung['nama_gunung'] ?></h2>
                            <span class="badge rounded-pill <?= $gunung['status'] == 'Buka' ? 'bg-success' : 'bg-danger' ?> px-3 py-2">
                                <?= $gunung['status'] ?>
                            </span>
                        </div>

                        <p class="text-muted mb-4"><i class="bi bi-geo-alt-fill me-2 text-primary"></i><?= $gunung['lokasi'] ?></p>

                        <div class="row mb-4">
                            <div class="col-6">
                                <div class="p-3 bg-light rounded-4 text-center">
                                    <small class="text-muted d-block mb-1">Ketinggian</small>
                                    <span class="fw-bold h5 mb-0"><?= number_format($gunung['ketinggian'], 0, ',', '.') ?> mdpl</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-light rounded-4 text-center">
                                    <small class="text-muted d-block mb-1">Ditambahkan</small>
                                    <span class="fw-bold mb-0" style="font-size: 0.9rem;">
                                        <?= date('d M Y', strtotime($gunung['created_at'])) ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5 class="fw-bold">Deskripsi</h5>
                            <p class="text-secondary" style="line-height: 1.8; text-align: justify;">
                                <?= nl2br(esc($gunung['deskripsi'])) ?>
                            </p>
                        </div>

                        <?php if (session()->get('role') == 'admin') : ?>
                            <hr>
                            <div class="d-flex gap-2 pt-2">
                                <a href="<?= base_url('gunung/edit/' . $gunung['id_gunung']) ?>" class="btn btn-warning text-white rounded-pill px-4">
                                    <i class="bi bi-pencil-square me-2"></i>Edit Data
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>