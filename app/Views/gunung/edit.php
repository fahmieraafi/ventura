<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h4 class="fw-bold"><i class="bi bi-pencil-square me-2 text-warning"></i>Edit Gunung</h4>
                </div>

                <form action="<?= base_url('gunung/update/' . $gunung['id_gunung']) ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <input type="hidden" name="foto_lama" value="<?= $gunung['foto'] ?>">

                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Nama Gunung</label>
                                <input type="text" name="nama_gunung" class="form-control rounded-pill" value="<?= $gunung['nama_gunung'] ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Lokasi</label>
                                <input type="text" name="lokasi" class="form-control rounded-pill" value="<?= $gunung['lokasi'] ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Ketinggian</label>
                                <input type="number" name="ketinggian" class="form-control rounded-pill" value="<?= $gunung['ketinggian'] ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Status Jalur</label>
                                <select name="status" class="form-select rounded-pill">
                                    <option value="Buka" <?= $gunung['status'] == 'Buka' ? 'selected' : '' ?>>Buka</option>
                                    <option value="Tutup" <?= $gunung['status'] == 'Tutup' ? 'selected' : '' ?>>Tutup</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold">Ganti Foto (Bisa pilih banyak sekaligus)</label>
                                <input type="file" name="foto[]" class="form-control" accept="image/*" multiple>
                                <small class="text-muted d-block mt-1">
                                    <b>Foto saat ini:</b> <?= $gunung['foto'] ?>
                                </small>
                                <small class="text-info" style="font-size: 0.8rem;">
                                    *Mengunggah foto baru akan menghapus semua foto lama untuk gunung ini.
                                </small>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold">Deskripsi</label>
                                <textarea name="deskripsi" class="form-control" rows="5" style="border-radius: 15px;"><?= $gunung['deskripsi'] ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light border-0 p-4 text-end">
                        <a href="<?= base_url('gunung') ?>" class="btn btn-light rounded-pill px-4">Batal</a>
                        <button type="submit" class="btn btn-warning text-white rounded-pill px-4">Update Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>