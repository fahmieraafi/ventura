<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-white fw-bold"><i class="bi bi-receipt me-2 text-info"></i> Kelola Transaksi</h2>
</div>

<div class="row mb-4 g-3">
    <div class="col-md-5">
        <form action="<?= base_url('admin/transaksi'); ?>" method="get">
            <div class="input-group shadow-lg">
                <span class="input-group-text bg-white border-0 text-muted">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" name="cari" class="form-control border-0 text-dark py-2"
                    placeholder="Cari nama penyewa atau alat..." value="<?= $cari ?? '' ?>" style="background: rgba(255,255,255,0.9);">
                <button class="btn btn-info px-4 fw-bold shadow-sm" type="submit">Cari</button>
            </div>
        </form>
    </div>

    <div class="col-md-4">
        <form action="<?= base_url('admin/transaksi'); ?>" method="get" class="d-flex gap-2">
            <input type="date" name="tgl" class="form-control border-0 shadow-sm" value="<?= $tgl ?? '' ?>">
            <button type="submit" class="btn btn-primary fw-bold px-3">Filter</button>
            <?php if (!empty($cari) || !empty($tgl)) : ?>
                <a href="<?= base_url('admin/transaksi'); ?>" class="btn btn-danger">
                    <i class="bi bi-arrow-clockwise"></i>
                </a>
            <?php endif; ?>
        </form>
    </div>
</div>



<div class="card border-0 shadow-lg overflow-hidden" style="border-radius: 15px;">
    <div class="table-responsive">
        <table class="table table-hover table-light mb-0 align-middle">
            <thead class="table-dark">
                <tr class="text-uppercase fs-12 tracking-wider">
                    <th class="ps-3 py-3" width="5%">No</th>
                    <th>Penyewa</th>
                    <th class="text-center">Kontak</th>
                    <th>Alat</th>
                    <th>Tgl Pinjam</th>
                    <th>Tgl Kembali</th>
                    <th class="text-center">Bukti Transfer</th>
                    <th>Total</th>
                    <th class="text-center">Denda</th>
                    <th class="text-center">Status</th>
                    <th width="18%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-dark">
                <?php $i = 1;
                foreach ($transaksi as $t) : ?>
                    <tr>
                        <td class="ps-3 text-muted"><?= $i++; ?></td>
                        <td><b class="text-primary"><?= $t['nama_user'] ?? 'User Dihapus'; ?></b></td>

                        <td class="text-center">
                            <?php if (!empty($t['no_wa'])) : ?>
                                <a href="https://wa.me/<?= $t['no_wa'] ?>?text=Halo%20<?= urlencode($t['nama_user'] ?? '') ?>,%20kami%20dari%20Ventura%20ingin%20mengonfirmasi%20penyewaan%20<?= urlencode($t['nama_barang']) ?>."
                                    target="_blank"
                                    class="btn btn-sm btn-success rounded-pill px-3 shadow-sm">
                                    <i class="bi bi-whatsapp me-1"></i> Hubungi
                                </a>
                            <?php else : ?>
                                <span class="badge bg-light text-muted border">N/A</span>
                            <?php endif; ?>
                        </td>

                        <td class="fw-semibold text-secondary"><?= $t['nama_barang']; ?>
                            <small class="text-muted">Durasi:
                                <?php
                                $awal  = strtotime($t['tgl_pinjam']);
                                $akhir = strtotime($t['tgl_kembali']);
                                $hari  = ceil(($akhir - $awal) / (60 * 60 * 24));
                                echo ($hari <= 0 ? 1 : $hari) . " /H";
                                ?>
                            </small>
                        </td>
                        <td><span class="badge bg-light text-dark border"><i class="bi bi-calendar-event me-1"></i> <?= date('d M Y', strtotime($t['tgl_pinjam'])); ?></span></td>
                        <td><span class="badge bg-light text-dark border"><i class="bi bi-calendar-event me-1"></i> <?= date('d M Y', strtotime($t['tgl_kembali'])); ?></span></td>

                        <td class="text-center">
                            <?php if (!empty($t['total_harga'])) : ?>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#modalBukti<?= $t['id_transaksi'] ?>">
                                    <img src="<?= base_url('uploads/bukti_bayar/' . $t['bukti_bayar']) ?>"
                                        class="rounded shadow-sm border" width="45" height="45" style="object-fit: cover;"
                                        alt="Bukti Transfer">
                                </a>
                            <?php else : ?>
                                <small class="text-muted italic">Belum Ada</small>
                            <?php endif; ?>
                        </td>

                        <td><span class="text-dark fw-bold">Rp <?= number_format($t['total_harga'], 0, ',', '.'); ?></span></td>

                        <td class="text-center">
                            <?php if (isset($t['denda']) && $t['denda'] > 0) : ?>
                                <div class="d-flex flex-column align-items-center gap-1">
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 fw-bold">
                                        Rp <?= number_format($t['denda'], 0, ',', '.'); ?>
                                    </span>

                                    <?php if (isset($t['status_denda']) && $t['status_denda'] == 1) : ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle py-0 px-2" style="font-size: 10px;">
                                            <i class="bi bi-check-circle-fill"></i> Lunas
                                        </span>
                                    <?php else : ?>
                                        <a href="<?= base_url('admin/transaksi/lunaskan_denda/' . $t['id_transaksi']) ?>"
                                            class="btn btn-xs btn-warning py-0 px-2 fw-bold"
                                            style="font-size: 10px; border-radius: 4px;"
                                            onclick="return confirm('Tandai denda sebagai lunas?')">
                                            Tandai Lunas
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php else : ?>
                                <span class="text-muted small">-</span>
                            <?php endif; ?>
                        </td>

                        <td class="text-center">
                            <?php if ($t['status_transaksi'] == 'Waiting') : ?>
                                <span class="badge bg-info text-dark rounded-pill px-3 animate-pulse">Konfirmasi</span>
                            <?php elseif ($t['status_transaksi'] == 'Booking') : ?>
                                <span class="badge bg-warning text-dark rounded-pill px-3">Booking</span>
                            <?php elseif ($t['status_transaksi'] == 'Dipinjam') : ?>
                                <span class="badge bg-primary rounded-pill px-3">Dipinjam</span>
                            <?php elseif ($t['status_transaksi'] == 'Dibatalkan') : ?>
                                <span class="badge bg-secondary rounded-pill px-3">Dibatalkan</span>
                            <?php else : ?>
                                <span class="badge bg-success rounded-pill px-3">Selesai</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <div class="d-flex gap-2 justify-content-center">
                                <?php if ($t['status_transaksi'] == 'Waiting') : ?>
                                    <a href="<?= base_url('admin/transaksi/konfirmasi_bayar/' . $t['id_transaksi']) ?>"
                                        class="btn btn-sm btn-info fw-bold text-dark shadow-sm"
                                        onclick="return confirm('Konfirmasi pembayaran user ini?')">
                                        Konfirmasi
                                    </a>
                                <?php elseif ($t['status_transaksi'] == 'Booking') : ?>
                                    <a href="<?= base_url('admin/transaksi/updateStatus/' . $t['id_transaksi'] . '/Dipinjam') ?>"
                                        class="btn btn-sm btn-outline-primary fw-bold"
                                        onclick="return confirm('Alat sudah diambil?')">
                                        Ambil
                                    </a>
                                <?php elseif ($t['status_transaksi'] == 'Dipinjam') : ?>
                                    <a href="<?= base_url('admin/transaksi/updateStatus/' . $t['id_transaksi'] . '/Selesai') ?>"
                                        class="btn btn-sm btn-outline-success fw-bold"
                                        onclick="return confirm('Alat sudah kembali?')">
                                        Selesai
                                    </a>
                                <?php endif; ?>

                                <a href="<?= base_url('admin/transaksi/edit/' . $t['id_transaksi']) ?>"
                                    class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <a href="<?= base_url('admin/transaksi/delete/' . $t['id_transaksi']) ?>"
                                    class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Yakin ingin menghapus?')">
                                    <i class="bi bi-trash3"></i>
                                </a>
                            </div>
                        </td>
                    </tr>

                    <?php if (!empty($t['bukti_bayar'])) : ?>
                        <div class="modal fade" id="modalBukti<?= $t['id_transaksi'] ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content bg-dark border-secondary">
                                    <div class="modal-header border-secondary">
                                        <h5 class="modal-title text-white">Bukti Bayar: <?= $t['nama_user'] ?></h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body text-center p-4">
                                        <img src="<?= base_url('uploads/bukti_bayar/' . $t['bukti_bayar']) ?>" class="img-fluid rounded shadow shadow-lg border border-secondary">
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                <?php endforeach; ?>

                <?php if (empty($transaksi)) : ?>
                    <tr>
                        <td colspan="11" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox mb-2 d-block fs-1"></i>
                            Data transaksi tidak ditemukan.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    .fs-12 {
        font-size: 12px;
    }

    .tracking-wider {
        letter-spacing: 0.5px;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.05) !important;
        transition: 0.2s;
    }

    .btn-success {
        background-color: #25D366;
        border: none;
    }

    .btn-success:hover {
        background-color: #128C7E;
    }

    .bg-danger-subtle {
        background-color: #f8d7da !important;
    }

    .border-danger-subtle {
        border-color: #f5c2c7 !important;
    }

    .bg-success-subtle {
        background-color: #d1e7dd !important;
    }

    .border-success-subtle {
        border-color: #badbcc !important;
    }

    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: .6;
        }
    }
</style>
<?= $this->endSection(); ?>