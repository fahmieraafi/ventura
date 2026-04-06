<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="card border-0 shadow-lg text-white" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border-radius: 15px;">
        <div class="card-header border-secondary">
            <h4 class="mb-0 fw-bold"><i class="bi bi-plus-circle me-2"></i>Tambah Alat Kamping</h4>
        </div>
        <div class="card-body p-4">
            <form action="<?= base_url('barang/store') ?>" method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" name="nama_barang" class="form-control bg-dark text-white border-secondary" placeholder="Contoh: Tenda Dome" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Harga Sewa / Hari</label>
                        <div class="input-group">
                            <span class="input-group-text bg-secondary text-white border-secondary">Rp</span>
                            <input type="number" name="harga_sewa" class="form-control bg-dark text-white border-secondary" placeholder="0" required>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Stok Barang</label>
                        <input type="number" name="stok" class="form-control bg-dark text-white border-secondary" placeholder="Jumlah unit" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kondisi</label>
                        <select name="kondisi" class="form-select bg-dark text-white border-secondary">
                            <option value="Baik">Bagus</option>
                            <option value="Rusak Ringan">Rusak Ringan</option>
                        </select>
                    </div>

                    <div class="col-md-12 mb-4">
                        <label class="form-label">Foto Barang</label>
                        <input type="file" name="foto_barang" class="form-control bg-dark text-white border-secondary">
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="<?= base_url('barang') ?>" class="btn btn-outline-light px-4">Batal</a>
                    <button type="submit" class="btn btn-primary px-4 shadow">Simpan Barang</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>