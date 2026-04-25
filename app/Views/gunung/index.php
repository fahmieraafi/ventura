<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid p-0 mb-5">
    <div class="position-relative shadow-lg" style="height: 350px; overflow: hidden; border-bottom: 5px solid #0dcaf0;">
        <img src="<?= base_url('assets/img/Tipe-Gunung-Api-di-Indonesia-A-B-dan-C_2-1536x957.jpeg') ?>" class="w-100 h-100" style="object-fit: cover; filter: brightness(0.6);">

        <div class="position-absolute top-50 start-50 translate-middle text-center text-white w-100 px-3">
            <h1 class="display-5 fw-bold animate__animated animate__fadeInDown">EXPLORE INDONESIA</h1>
            <p class="lead animate__animated animate__fadeInUp animate__delay-1s">Temukan informasi jalur pendakian dan status gunung secara real-time.</p>

            <?php if (strtolower(session()->get('role')) == 'admin') : ?>
                <a href="<?= base_url('gunung/create') ?>" class="btn btn-info btn-sm rounded-pill px-4 shadow">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Info Gunung
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="container">
    <div class="row justify-content-center mb-5" style="margin-top: -80px;">
        <div class="col-md-7">
            <div class="card border-0 shadow-lg rounded-pill p-2">
                <form action="<?= base_url('gunung') ?>" method="get">
                    <div class="input-group">
                        <input type="text" name="cari" class="form-control border-0 px-4 rounded-pill"
                            placeholder="Cari nama gunung atau lokasi..."
                            value="<?= $keyword ?? '' ?>">
                        <button class="btn btn-info rounded-pill px-4 text-white" type="submit">
                            <i class="bi bi-search me-2"></i>Cari
                        </button>
                    </div>
                </form>
            </div>
            <?php if (isset($keyword) && $keyword != '') : ?>
                <div class="text-center mt-3">
                    <small class="text-muted">Hasil pencarian untuk: <strong>"<?= esc($keyword) ?>"</strong></small>
                    <a href="<?= base_url('gunung') ?>" class="ms-2 text-decoration-none small text-danger">Hapus Pencarian</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <?php if (!empty($list_gunung)) : ?>
            <?php foreach ($list_gunung as $row) :
                // Pecah string foto menjadi array untuk fitur geser
                $fotos = explode(',', $row['foto']);
                $carouselId = 'carouselGunung' . $row['id_gunung'];
            ?>
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm h-100 animate__animated animate__zoomIn" style="border-radius: 20px; overflow: hidden;">

                        <div class="position-relative">
                            <div id="<?= $carouselId ?>" class="carousel slide" data-bs-ride="false">
                                <div class="carousel-inner">
                                    <?php foreach ($fotos as $key => $f) : ?>
                                        <div class="carousel-item <?= $key === 0 ? 'active' : '' ?>">
                                            <img src="<?= base_url('uploads/gunung/' . trim($f)) ?>"
                                                class="d-block w-100"
                                                style="height: 220px; object-fit: cover;"
                                                alt="<?= $row['nama_gunung'] ?>">
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <?php if (count($fotos) > 1) : ?>
                                    <button class="carousel-control-prev" type="button" data-bs-target="#<?= $carouselId ?>" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true" style="width: 1.2rem; height: 1.2rem;"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#<?= $carouselId ?>" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true" style="width: 1.2rem; height: 1.2rem;"></span>
                                        <span class="visually-hidden">Next</span>
                                    </button>
                                <?php endif; ?>
                            </div>

                            <div class="position-absolute top-0 end-0 m-3" style="z-index: 5;">
                                <span class="badge <?= $row['status'] == 'Buka' ? 'bg-success' : 'bg-danger' ?> px-3 py-2 rounded-pill shadow-sm">
                                    <?= $row['status'] ?>
                                </span>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-1"><?= $row['nama_gunung'] ?></h5>

                            <p class="text-muted small mb-3">
                                <a href="https://www.google.com/maps/search/<?= urlencode($row['nama_gunung'] . ' ' . $row['lokasi']) ?>"
                                    target="_blank"
                                    class="text-decoration-none text-muted link-primary-hover">
                                    <i class="bi bi-geo-alt-fill text-danger me-1"></i> <?= $row['lokasi'] ?>
                                </a>
                            </p>

                            <div class="d-flex justify-content-between align-items-center bg-light p-3 rounded-3 mb-3">
                                <div class="text-center">
                                    <small class="text-muted d-block small">Ketinggian</small>
                                    <span class="fw-bold text-primary"><?= number_format($row['ketinggian'], 0, ',', '.') ?> <small>mdpl</small></span>
                                </div>

                            </div>

                            <div class="d-grid gap-2">
                                <a href="<?= base_url('gunung/detail/' . $row['id_gunung']) ?>" class="btn btn-outline-info rounded-pill fw-bold">
                                    Lihat Jalur & Cuaca
                                </a>

                                <?php if (strtolower(session()->get('role')) == 'admin') : ?>
                                    <div class="d-flex gap-2">
                                        <a href="<?= base_url('gunung/edit/' . $row['id_gunung']) ?>" class="btn btn-light btn-sm flex-grow-1 border">Edit</a>
                                        <a href="<?= base_url('gunung/delete/' . $row['id_gunung']) ?>" class="btn btn-light btn-sm text-danger border" onclick="return confirm('Hapus data ini?')"><i class="bi bi-trash"></i></a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <div class="col-12 text-center py-5">
                <i class="bi bi-search text-muted display-1"></i>
                <p class="mt-3 lead text-muted">Gunung tidak ditemukan.</p>
                <a href="<?= base_url('gunung') ?>" class="btn btn-info text-white rounded-pill px-4">Tampilkan Semua</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .link-primary-hover:hover {
        color: #0dcaf0 !important;
        text-decoration: underline !important;
    }

    /* Mencegah panah carousel terlalu besar di card index */
    .carousel-control-prev,
    .carousel-control-next {
        width: 10%;
    }
</style>

<?= $this->endSection() ?>