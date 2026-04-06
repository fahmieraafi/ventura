<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="card border-0 shadow-lg text-white" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border-radius: 15px;">
        <div class="card-header border-secondary p-3">
            <h4 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit Data Alat</h4>
        </div>
        <div class="card-body p-4">
            <form action="<?= base_url('barang/update/' . $barang['id_barang']) ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field(); ?>
                
                <input type="hidden" name="fotoLama" value="<?= $barang['foto_barang'] ?>">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" name="nama_barang" class="form-control bg-dark text-white border-secondary" value="<?= $barang['nama_barang'] ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Harga Sewa / Hari</label>
                        <input type="number" name="harga_sewa" class="form-control bg-dark text-white border-secondary" value="<?= $barang['harga_sewa'] ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Stok</label>
                        <input type="number" name="stok" class="form-control bg-dark text-white border-secondary" value="<?= $barang['stok'] ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kondisi</label>
                        <select name="kondisi" class="form-select bg-dark text-white border-secondary">
                            <option value="Bagus" <?= $barang['kondisi'] == 'Bagus' ? 'selected' : '' ?>>Bagus</option>
                            <option value="Rusak Ringan" <?= $barang['kondisi'] == 'Rusak Ringan' ? 'selected' : '' ?>>Rusak Ringan</option>
                        </select>
                    </div>

                    <div class="col-md-12 mb-4">
                        <label class="form-label">Foto Barang (Biarkan kosong jika tidak ingin ganti)</label>
                        <div class="d-flex align-items-center gap-3">
                            <img src="<?= base_url('uploads/barang/' . ($barang['foto_barang'] ?: 'tenda.jpg')) ?>" width="80" class="rounded shadow">
                            <input type="file" name="foto_barang" class="form-control bg-dark text-white border-secondary">
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="<?= base_url('barang') ?>" class="btn btn-outline-light">Batal</a>
                    <button type="submit" class="btn btn-warning fw-bold px-4">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>