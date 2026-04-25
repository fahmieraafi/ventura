<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h4 class="fw-bold"><i class="bi bi-plus-circle me-2 text-info"></i>Tambah Informasi Gunung</h4>
                    <p class="text-muted">Masukkan detail data gunung baru untuk ditampilkan di Explore.</p>
                </div>

                <form action="<?= base_url('gunung/tambah') ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Nama Gunung</label>
                                <input type="text" name="nama_gunung" class="form-control rounded-pill" placeholder="Contoh: Gunung Gede" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Lokasi</label>
                                <input type="text" name="lokasi" class="form-control rounded-pill" placeholder="Contoh: Jawa Barat" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Ketinggian (mdpl)</label>
                                <input type="number" name="ketinggian" class="form-control rounded-pill" placeholder="Contoh: 2958" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Status Jalur</label>
                                <select name="status" class="form-select rounded-pill" required>
                                    <option value="Buka">Buka</option>
                                    <option value="Tutup">Tutup</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-bold">Foto Gunung (Bisa pilih banyak)</label>
                                <input type="file" name="foto[]" class="form-control" accept="image/*" multiple required>
                                <small class="text-muted">Format: JPG, PNG, JPEG. Maks 2MB per file. Anda bisa memilih lebih dari satu foto.</small>
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-bold">Deskripsi Pendakian</label>
                                <textarea name="deskripsi" class="form-control" rows="5" placeholder="Tuliskan info lengkap jalur pendakian..." style="border-radius: 15px;"></textarea>
                            </div>

                        </div>
                    </div>

                    <div class="card-footer bg-light border-0 p-4 text-end" style="border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;">
                        <a href="<?= base_url('gunung') ?>" class="btn btn-light rounded-pill px-4 me-2">Batal</a>
                        <button type="submit" class="btn btn-info text-white rounded-pill px-4 shadow-sm">Simpan Data</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>