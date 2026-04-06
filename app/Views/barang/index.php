<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-white fw-bold">Katalog Alat Kamping</h2>
        
        <?php if (session()->get('role') == 'Admin') : ?>
            <a href="<?= base_url('barang/create') ?>" class="btn btn-primary shadow">
                <i class="bi bi-plus-lg me-2"></i>Tambah Barang
            </a>
        <?php endif; ?>
    </div>

    <div class="card border-0 shadow-lg" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border-radius: 15px; border: 1px solid rgba(255, 255, 255, 0.2);">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" style="color: rgba(255, 255, 255, 0.8);">
                    <thead style="background: rgba(0, 0, 0, 0.3); color: #fff;">
                        <tr class="text-center border-bottom border-secondary">
                            <th class="py-3">No</th>
                            <th class="py-3">Foto</th>
                            <th class="py-3">Nama Barang</th>
                            <th class="py-3">Stok</th>
                            <th class="py-3">Harga Sewa</th>
                            <th class="py-3">Kondisi</th>
                            <th class="py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($barang as $b) : ?>
                        <tr class="align-middle text-center border-bottom border-secondary" style="background: transparent;">
                            <td class="fw-bold"><?= $no++ ?></td>
                            <td>
                                <img src="<?= base_url('uploads/barang/' . ($b['foto_barang'] ?: 'tenda.jpg')) ?>" 
                                     class="rounded shadow-sm" 
                                     style="width: 60px; height: 60px; object-fit: cover; border: 1px solid rgba(255, 255, 255, 0.3);">
                            </td>
                            <td class="text-start ps-4"><?= $b['nama_barang'] ?></td>
                            <td><span class="badge bg-dark"><?= $b['stok'] ?> Unit</span></td>
                            <td class="fw-bold text-warning">Rp <?= number_format($b['harga_sewa'], 0, ',', '.') ?></td>
                            <td>
                                <span class="badge <?= $b['kondisi'] == 'Bagus' ? 'bg-success' : 'bg-danger' ?> opacity-75">
                                    <?= $b['kondisi'] ?>
                                </span>
                            </td>
                            <td class="text-center">
    <div class="d-flex flex-column align-items-center gap-2">
        <?php if (session()->get('role') == 'Admin') : ?>
            <a href="<?= base_url('barang/edit/' . $b['id_barang']) ?>" 
               class="btn btn-warning btn-sm fw-bold shadow-sm w-75" 
               style="border-radius: 8px;">
                <i class="bi bi-pencil-square me-1"></i> Edit
            </a>

            <a href="<?= base_url('barang/delete/' . $b['id_barang']) ?>" 
               class="btn btn-danger btn-sm fw-bold shadow-sm w-75" 
               style="border-radius: 8px;"
               onclick="return confirm('Yakin ingin menghapus barang ini?')">
                <i class="bi bi-trash-fill me-1"></i> Hapus
            </a>
        <?php else : ?>
            <button class="btn btn-light btn-sm fw-bold shadow-sm px-3" style="border-radius: 8px;">
                <i class="bi bi-cart-plus me-1"></i> Pinjam
            </button>
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

<style>
    .table-hover tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.05) !important;
        color: #fff !important;
    }
    .table td, .table th {
        border-color: rgba(255, 255, 255, 0.1) !important;
    }
</style>

<?= $this->endSection() ?>