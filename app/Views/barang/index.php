<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <h2 class="text-white fw-bold m-0">Katalog Alat Kamping</h2>

        <div class="d-flex gap-3 align-items-center">
            <div class="input-group" style="width: 300px;">
                <span class="input-group-text bg-transparent border-secondary text-white-50" style="border-radius: 10px 0 0 10px;">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" id="inputCariBarang" class="form-control bg-transparent border-secondary text-white"
                    placeholder="Cari alat kamping..." style="border-radius: 0 10px 10px 0;">
            </div>

            <?php if (session()->get('role') == 'admin') : ?>
                <a href="<?= base_url('barang/create') ?>" class="btn btn-primary shadow-sm" style="border-radius: 10px;">
                    <i class="bi bi-plus-lg me-2"></i>Tambah Barang
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="card border-0 shadow-lg" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border-radius: 15px; border: 1px solid rgba(255, 255, 255, 0.2);">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="tabelBarang" style="color: rgba(255, 255, 255, 0.8);">
                    <thead style="background: rgba(0, 0, 0, 0.3); color: #fff;">
                        <tr class="text-center border-bottom border-secondary">
                            <th class="py-3">No</th>
                            <th class="py-3">Foto Utama</th>
                            <th class="py-3">Nama Barang</th>
                            <th class="py-3">Stok</th>
                            <th class="py-3">Harga Sewa</th>
                            <th class="py-3">Kondisi</th>
                            <th class="py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        foreach ($barang as $b) :
                            // LOGIKA: Pecah string foto menjadi array
                            $listFoto = explode(',', $b['foto_barang']);
                            // Ambil foto pertama untuk tampilan tabel
                            $fotoUtama = $listFoto[0] ?: 'tenda.jpg';
                        ?>
                            <tr class="align-middle text-center border-bottom border-secondary row-barang" style="background: transparent;">
                                <td class="fw-bold"><?= $no++ ?></td>
                                <td>
                                    <a href="javascript:void(0)"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalFoto<?= $b['id_barang'] ?>">
                                        <img src="<?= base_url('uploads/barang/' . $fotoUtama) ?>"
                                            class="rounded shadow-sm img-hover"
                                            style="width: 60px; height: 60px; object-fit: cover; border: 1px solid rgba(255, 255, 255, 0.3); cursor: pointer;">
                                    </a>
                                </td>
                                <td class="text-start ps-4 nama-barang"><?= $b['nama_barang'] ?></td>
                                <td><span class="badge bg-dark"><?= $b['stok'] ?> Unit</span></td>
                                <td class="fw-bold text-warning">Rp <?= number_format($b['harga_sewa'], 0, ',', '.') ?></td>
                                <td>
                                    <span class="badge <?= ($b['kondisi'] == 'Bagus' || $b['kondisi'] == 'Baik') ? 'bg-success' : 'bg-danger' ?> opacity-75">
                                        <?= $b['kondisi'] ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex flex-column align-items-center gap-2">
                                        <?php if (session()->get('role') == 'admin') : ?>
                                            <a href="<?= base_url('barang/edit/' . $b['id_barang']) ?>" class="btn btn-warning btn-sm fw-bold shadow-sm w-75">Edit</a>
                                            <a href="<?= base_url('barang/delete/' . $b['id_barang']) ?>" class="btn btn-danger btn-sm fw-bold shadow-sm w-75" onclick="return confirm('Hapus?')">Hapus</a>
                                        <?php else : ?>
                                            <button class="btn btn-light btn-sm fw-bold shadow-sm px-3">Pinjam</button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php foreach ($barang as $b) :
    $listFoto = explode(',', $b['foto_barang']);
?>
    <div class="modal fade" id="modalFoto<?= $b['id_barang'] ?>" data-bs-backdrop="true" tabindex="-1" aria-hidden="true" style="z-index: 9999;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background: rgba(15, 23, 42, 0.98); border: 1px solid rgba(255,255,255,0.2); border-radius: 20px;">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-white fw-bold"><?= $b['nama_barang'] ?></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-4">

                    <div id="carousel<?= $b['id_barang'] ?>" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php foreach ($listFoto as $key => $f) : ?>
                                <div class="carousel-item <?= ($key == 0) ? 'active' : '' ?>">
                                    <img src="<?= base_url('uploads/barang/' . ($f ?: 'tenda.jpg')) ?>"
                                        class="img-fluid rounded shadow-lg"
                                        style="max-height: 400px; width: 100%; object-fit: contain; border: 2px solid rgba(255,255,255,0.1);">
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <?php if (count($listFoto) > 1) : ?>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carousel<?= $b['id_barang'] ?>" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carousel<?= $b['id_barang'] ?>" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            </button>
                        <?php endif; ?>
                    </div>

                    <div class="mt-3 text-start text-white pt-3 border-top border-secondary">
                        <p class="mb-1 text-white-50">Harga: <span class="text-warning fw-bold">Rp <?= number_format($b['harga_sewa'], 0, ',', '.') ?></span></p>
                        <p class="mb-0 text-white-50">Kondisi: <span class="badge bg-info"><?= $b['kondisi'] ?></span></p>
                        <p class="mt-2 text-white-50 small italic">Geser foto untuk melihat detail lainnya.</p>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-light px-4" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<script>
    // FITUR PENCARIAN
    document.getElementById('inputCariBarang').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let baris = document.querySelectorAll('.row-barang');
        baris.forEach(row => {
            let nama = row.querySelector('.nama-barang').innerText.toLowerCase();
            row.style.display = nama.includes(filter) ? "" : "none";
        });
    });
</script>

<style>
    /* Mengatasi modal yang sering tidak muncul/tertutup backdrop */
    .modal {
        background: rgba(0, 0, 0, 0.7);
    }

    .modal-backdrop {
        display: none !important;
    }

    /* Hover effect pada gambar tabel */
    .img-hover {
        transition: 0.3s ease;
    }

    .img-hover:hover {
        transform: scale(1.15);
        border-color: #ffc107 !important;
        box-shadow: 0 0 15px rgba(255, 193, 7, 0.5) !important;
    }

    /* Mempercantik tampilan Carousel */
    .carousel-control-prev,
    .carousel-control-next {
        width: 10%;
        background: rgba(0, 0, 0, 0.2);
        border-radius: 10px;
    }
</style>

<?= $this->endSection() ?>