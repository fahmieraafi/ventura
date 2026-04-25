<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <h3 class="text-white mb-4 fw-bold">Riwayat Peminjaman Alat</h3>

    <div class="row mb-4">
        <div class="col-md-10">
            <form action="" method="get">
                <div class="input-group shadow-sm">
                    <input type="text" name="cari" class="form-control bg-white text-dark border-secondary"
                        placeholder="Cari nama alat..." value="<?= esc($cari ?? '') ?>">

                    <input type="date" name="tgl" class="form-control bg-white text-dark border-secondary"
                        value="<?= esc($tgl ?? '') ?>">

                    <button class="btn btn-info px-4 fw-bold" type="submit">
                        <i class="bi bi-search"></i> Cari
                    </button>

                    <?php if (!empty($cari) || !empty($tgl)) : ?>
                        <a href="<?= base_url('riwayat') ?>" class="btn btn-outline-danger border-secondary bg-white text-dark">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>



    <div class="card border-0 shadow-lg overflow-hidden" style="border-radius: 15px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-center">
                    <thead style="background-color: #343a40; color: white;">
                        <tr class="text-uppercase small">
                            <th class="ps-3 py-3">No</th>
                            <th>Alat</th>
                            <th>Tgl Pinjam</th>
                            <th>Tgl Kembali</th>
                            <th>Total</th>
                            <th>Denda</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        <?php if (!empty($transaksi)) : ?>
                            <?php $no = 1;
                            foreach ($transaksi as $t) : ?>
                                <tr>
                                    <td class="ps-3 fw-bold text-muted"><?= $no++ ?></td>
                                    <td class="text-start">
                                        <span class="fw-bold text-dark d-block"><?= esc($t['nama_barang']) ?></span>
                                        <small class="text-muted">Durasi:
                                            <?php
                                            $awal  = strtotime($t['tgl_pinjam']);
                                            $akhir = strtotime($t['tgl_kembali']);
                                            $hari  = ceil(($akhir - $awal) / (60 * 60 * 24));
                                            echo ($hari <= 0 ? 1 : $hari) . " Hari";
                                            ?>
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border"><i class="bi bi-calendar-event me-1"></i> <?= date('d M Y', strtotime($t['tgl_pinjam'])) ?></span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border"><i class="bi bi-calendar-check me-1"></i> <?= date('d M Y', strtotime($t['tgl_kembali'])) ?></span>
                                    </td>
                                    <td class="fw-bold text-dark text-nowrap">
                                        Rp <?= number_format($t['total_harga'], 0, ',', '.') ?>
                                    </td>
                                    <td>
                                        <?php if ($t['denda'] > 0) : ?>
                                            <span class="badge bg-danger">Rp <?= number_format($t['denda'], 0, ',', '.') ?></span>
                                            <?php if ($t['status_denda'] == 1) : ?>
                                                <span class="badge bg-success d-block mt-1" style="font-size: 0.65rem;">Lunas</span>
                                            <?php endif; ?>
                                        <?php else : ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($t['status_transaksi'] == 'Waiting') : ?>
                                            <span class="badge bg-info text-dark rounded-pill px-3">Menunggu Konfirmasi</span>
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
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            <?php if ($t['status_transaksi'] == 'Booking') : ?>
                                                <a href="https://www.google.com/maps/search/5VRR+C28, Gunturmekar, Tanjungkerta, Sumedang Regency, West Java 45354" target="_blank" class="btn btn-sm btn-success rounded-pill px-3 shadow-sm">
                                                    <i class="bi bi-geo-alt-fill"></i> Lokasi
                                                </a>
                                                <a href="<?= base_url('transaksi/batal/' . $t['id_transaksi']) ?>"
                                                    class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                                    onclick="return confirm('Apakah Anda yakin ingin membatalkan booking ini?')">
                                                    <i class="bi bi-slash-circle me-1"></i> Batal
                                                </a>
                                            <?php else : ?>
                                                <button class="btn btn-sm btn-light disabled rounded-pill opacity-50 text-muted">No Action</button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="8" class="text-center py-5 bg-white text-muted">
                                    <i class="bi bi-file-earmark-text d-block mb-2" style="font-size: 3rem; opacity: 0.3;"></i>
                                    <?php if (!empty($cari) || !empty($tgl)) : ?>
                                        Data tidak ditemukan untuk pencarian tersebut.
                                    <?php else : ?>
                                        Belum ada riwayat peminjaman alat.
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .table thead th {
        font-weight: 600;
        letter-spacing: 0.5px;
        border: none;
    }

    .table tbody td {
        padding: 1rem 0.75rem;
        border-bottom: 1px solid #f2f2f2;
    }

    .badge {
        font-weight: 600;
    }
</style>
<?= $this->endSection() ?>