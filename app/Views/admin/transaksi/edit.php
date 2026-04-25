<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>
<div class="container mt-4">
    <div class="card bg-dark text-white border-secondary shadow mx-auto" style="max-width: 500px;">
        <div class="card-header border-secondary">
            <h5 class="mb-0">Edit Detail Transaksi</h5>
        </div>
        <div class="card-body">
            <form action="<?= base_url('admin/transaksi/update/' . $transaksi['id_transaksi']) ?>" method="post">

                <?= csrf_field() ?>

                <input type="hidden" name="status_transaksi" value="<?= $transaksi['status_transaksi'] ?>">


                <div class="mb-3">
                    <label class="text-muted small">Penyewa / Alat</label>
                    <p class="fw-bold text-info"><?= $transaksi['username'] ?> / <?= $transaksi['nama_barang'] ?></p>
                </div>

                <div class="mb-3">
                    <label class="form-label text-danger fw-bold">Input Denda (Rp)</label>
                    <input type="number" name="denda" class="form-control bg-dark text-white border-secondary"
                        value="<?= $transaksi['denda'] ?>">
                    <small class="text-muted">Mengubah nilai ini akan memperbarui angka di lonceng notifikasi user secara otomatis.</small>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="<?= base_url('admin/transaksi') ?>" class="btn btn-link text-muted">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>