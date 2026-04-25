<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-lg p-4" style="border-radius: 20px;">

                <div class="text-center mb-4">
                    <?php
                    $listFoto = explode(',', $barang['foto_barang']);
                    ?>

                    <?php if (count($listFoto) > 1) : ?>
                        <div id="carouselDetail" class="carousel slide shadow-sm rounded" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <?php foreach ($listFoto as $key => $f) : ?>
                                    <div class="carousel-item <?= ($key == 0) ? 'active' : '' ?>">
                                        <img src="<?= base_url('uploads/barang/' . ($f ?: 'tenda.jpg')) ?>"
                                            class="d-block w-100 rounded"
                                            style="max-height: 400px; object-fit: contain;"
                                            alt="Foto <?= $key ?>">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselDetail" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon bg-dark rounded-circle" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselDetail" data-bs-slide="next">
                                <span class="carousel-control-next-icon bg-dark rounded-circle" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    <?php else : ?>
                        <img src="<?= base_url('uploads/barang/' . ($listFoto[0] ?: 'tenda.jpg')) ?>"
                            class="img-fluid rounded"
                            style="max-height: 400px; width: 100%; object-fit: contain;"
                            alt="<?= $barang['nama_barang'] ?>">
                    <?php endif; ?>
                </div>

                <div class="text-center">
                    <h2 class="fw-bold text-dark"><?= $barang['nama_barang'] ?></h2>
                    <h3 class="text-primary fw-bold">Rp <?= number_format($barang['harga_sewa'], 0, ',', '.') ?> / Hari</h3>

                    <div class="d-flex justify-content-center gap-3 my-3">
                        <span class="text-muted">Sisa Stok: <strong><?= $barang['stok'] ?></strong></span>
                        <span class="vr"></span>
                        <span class="text-muted">Kondisi: <span class="badge bg-light text-dark border"><?= $barang['kondisi'] ?></span></span>
                    </div>

                    <hr>

                    <div class="d-grid gap-2 mt-4">

                        <?php if (strtolower(session()->get('role')) == 'admin') : ?>
                            <div class="d-flex gap-2">
                                <a href="<?= base_url('barang/edit/' . $barang['id_barang']) ?>" class="btn btn-outline-warning btn-sm flex-fill fw-bold rounded-3">Edit</a>
                                <a href="<?= base_url('barang/delete/' . $barang['id_barang']) ?>" class="btn btn-outline-danger btn-sm flex-fill fw-bold rounded-3" onclick="return confirm('Hapus?')">Hapus</a>
                            </div>
                        <?php endif; ?>

                        <?php if (strtolower(session()->get('role')) == 'user') : ?>
                            <button type="button"
                                class="btn btn-primary fw-bold py-2 rounded-3 shadow-sm btn-pinjam-sekarang"
                                <?= ($barang['stok'] <= 0) ? 'disabled' : '' ?>
                                data-bs-toggle="modal"
                                data-bs-target="#modalPinjam"
                                data-id="<?= $barang['id_barang'] ?>"
                                data-nama="<?= $barang['nama_barang'] ?>"
                                data-harga="<?= $barang['harga_sewa'] ?>">
                                <?= ($barang['stok'] <= 0) ? 'Stok Kosong' : 'Pinjam Sekarang' ?>
                            </button>
                        <?php endif; ?>
                        <a href="<?= base_url('barang') ?>" class="btn btn-outline-secondary rounded-pill">Kembali</a>
                    </div>

                    <?php if (strtolower(session()->get('role')) == 'admin') : ?>
                        <p class="mt-3 text-muted small">
                            <i class="bi bi-eye"></i> Dilihat <?= $barang['views'] ?> kali (Admin Mode)
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPinjam" tabindex="-1" aria-hidden="true" style="z-index: 1070;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="bi bi-calendar-check me-2"></i>Form Sewa Alat</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form action="<?= base_url('transaksi/simpan') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="alert alert-warning border-0 shadow-sm mb-4">
                        <p class="mb-1 fw-bold small"><i class="bi bi-info-circle-fill me-1"></i> Jasa Booking: Rp 15.000</p>
                        <div class="text-center my-3 p-2 bg-white d-inline-block w-100" style="border-radius: 12px;">
                            <p class="text-dark small fw-bold mb-2">Scan QRIS Ventura di bawah ini:</p>
                            <img src="<?= base_url('assets/img/Desain tanpa judul.png') ?>" alt="QRIS Pembayaran" class="img-fluid shadow-sm" style="max-width: 180px; border-radius: 8px;">
                        </div>
                        <div class="d-grid gap-2 mt-2">
                            <a href="https://link.dana.id/minta?full_url=https://qr.dana.id/v1/281012012021061491765024/assets/img/acb46e61e5cc574b2b66ea75964e5e04.jpg/081212418446" target="_blank" class="btn btn-primary btn-sm fw-bold rounded-pill">
                                <i class="bi bi-wallet2 me-1"></i> Klik: Bayar Langsung ke DANA
                            </a>
                            <button type="button" class="btn btn-light btn-sm border fw-bold rounded-pill" onclick="copyNomorDANA('081212418446')">
                                <i class="bi bi-clipboard me-1"></i> Salin Nomor DANA
                            </button>
                        </div>
                    </div>

                    <input type="hidden" name="id_barang" id="id_barang_modal">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark">Alat yang disewa:</label>
                        <input type="text" id="nama_barang_modal" class="form-control bg-light border-0 fw-bold text-primary" readonly>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-dark small">Tanggal Pinjam</label>
                            <input type="date" name="tgl_pinjam" class="form-control shadow-sm" required min="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-dark small">Tanggal Kembali</label>
                            <input type="date" name="tgl_kembali" class="form-control shadow-sm" required min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-danger small">Bukti Pembayaran (Wajib)</label>
                        <input type="file" name="bukti_bayar" id="inputBuktiTransfer" class="form-control shadow-sm" accept="image/*" required onchange="validasiTombolKonfirmasi()">
                        <small class="text-muted" style="font-size: 0.7rem;">*Upload screenshot bukti transfer booking 15rb.</small>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" id="btnKonfirmasiPinjam" class="btn btn-primary px-4 fw-bold" disabled>Konfirmasi Pinjam</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Mengisi data modal saat tombol "Pinjam Sekarang" diklik
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-pinjam-sekarang')) {
            const id = e.target.getAttribute('data-id');
            const nama = e.target.getAttribute('data-nama');

            document.getElementById('id_barang_modal').value = id;
            document.getElementById('nama_barang_modal').value = nama;
        }
    });

    // Validasi agar tombol submit aktif hanya jika file sudah dipilih
    function validasiTombolKonfirmasi() {
        const fileInput = document.getElementById('inputBuktiTransfer');
        const btnSubmit = document.getElementById('btnKonfirmasiPinjam');
        btnSubmit.disabled = fileInput.files.length === 0;
    }

    // Fungsi salin nomor
    function copyNomorDANA(nomor) {
        navigator.clipboard.writeText(nomor);
        alert("Nomor DANA " + nomor + " berhasil disalin!");
    }
</script>

<?= $this->endSection(); ?>