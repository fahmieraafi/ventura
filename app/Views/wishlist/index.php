<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-white fw-bold m-0">Daftar Keinginan Saya</h2>
        <a href="<?= base_url('wishlist' . (isset($_GET['mode']) ? '' : '?mode=pilih_cepat')) ?>"
            class="btn <?= isset($_GET['mode']) ? 'btn-warning' : 'btn-outline-light' ?> rounded-pill px-4 shadow-sm">
            <i class="bi <?= isset($_GET['mode']) ? 'bi-x-circle' : 'bi-check2-all' ?> me-2"></i>
            <?= isset($_GET['mode']) ? 'Batal Pilih' : 'Pilih Cepat' ?>
        </a>
    </div>

    <?php if (empty($barang)) : ?>
        <div class="alert alert-light text-center py-5 shadow-sm" style="border-radius: 15px;">
            <i class="bi bi-heartbreak text-danger" style="font-size: 3rem;"></i>
            <p class="mt-3 fw-bold">Belum ada alat yang disimpan.</p>
            <a href="<?= base_url('barang') ?>" class="btn btn-primary rounded-pill px-4">Cari Alat Kamping</a>
        </div>
    <?php else : ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4" id="containerBarang">
            <?php foreach ($barang as $b) :
                $listFoto = explode(',', $b['foto_barang']);
                $fotoUtama = !empty($listFoto[0]) ? $listFoto[0] : 'default.jpg';
            ?>
                <div class="col item-barang">
                    <div class="card h-100 border-0 shadow-sm card-hover bg-white position-relative" style="border-radius: 15px; overflow: hidden; transition: 0.3s;">
                        <?php if (isset($_GET['mode']) && $_GET['mode'] == 'pilih_cepat') : ?>
                            <div class="position-absolute top-0 start-0 m-3" style="z-index: 10;">
                                <input class="form-check-input border-primary shadow-sm select-cepat" type="checkbox"
                                    data-id="<?= $b['id_barang'] ?>"
                                    data-nama="<?= $b['nama_barang'] ?>"
                                    style="width: 25px; height: 25px; cursor: pointer;">
                            </div>
                        <?php endif; ?>

                        <img src="<?= base_url('uploads/barang/' . $fotoUtama) ?>" class="card-img-top p-3" style="height: 180px; object-fit: contain;">

                        <div class="card-body text-center pt-0">
                            <h6 class="fw-bold mb-1 nama-barang"><?= $b['nama_barang'] ?></h6>
                            <p class="text-primary small mb-3">Rp <?= number_format($b['harga_sewa'], 0, ',', '.') ?> / Hari</p>
                            <div class="d-grid gap-2">
                                <a href="<?= base_url('barang/' . $b['id_barang']) ?>" class="btn btn-outline-primary btn-sm rounded-pill">Lihat Detail</a>
                                <button type="button" class="btn btn-primary btn-sm fw-bold rounded-pill btn-pinjam-sekarang"
                                    data-bs-toggle="modal" data-bs-target="#modalPinjam"
                                    data-id="<?= $b['id_barang'] ?>" data-nama="<?= $b['nama_barang'] ?>">
                                    <i class="bi bi-cart-plus me-1"></i> Pinjam Sekarang
                                </button>
                                <a href="<?= base_url('wishlist/hapus/' . $b['id_barang']) ?>" class="btn btn-link text-danger btn-sm text-decoration-none border-0 mt-1">
                                    <i class="bi bi-trash"></i> Hapus
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>


<?php if (isset($_GET['mode']) && $_GET['mode'] == 'pilih_cepat') : ?>
    <div class="fixed-bottom p-4 text-end" style="z-index: 3000; right: 20px; bottom: 20px;">
        <button id="btnSelesaiPilih" class="btn btn-success btn-lg shadow-lg rounded-pill px-5 fw-bold animate__animated animate__bounceInUp">
            <i class="bi bi-check2-circle me-2"></i> Selesai & Sewa Sekaligus
        </button>
    </div>
<?php endif; ?>

<div class="modal fade" id="modalPinjam" tabindex="-1" aria-hidden="true" style="z-index: 9999;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold">Form Sewa Alat</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('transaksi/simpan') ?>" method="post" enctype="multipart/form-data" id="formSewa">
                <?= csrf_field() ?>
                <div class="modal-body p-4">
                    <div class="alert alert-warning border-0 shadow-sm mb-4 text-center">
                        <p class="mb-0 fw-bold small">Total Biaya Booking:</p>
                        <h4 class="fw-bold text-primary mb-1" id="displayTotalBooking">Rp 15.000</h4>
                        <p class="text-muted small mb-2">(Rp 15.000 x <span id="displayJumlahBarang">1</span> barang)</p>

                        <img src="<?= base_url('assets/img/ventura Qriss.png') ?>" alt="QRIS" class="img-fluid my-2" style="max-width: 150px;">
                        <div class="d-grid gap-2 mt-2">
                            <a href="https://link.dana.id/minta?full_url=https://qr.dana.id/v1/281012012021061491765024/assets/img/acb46e61e5cc574b2b66ea75964e5e04.jpg/081212418446" target="_blank" class="btn btn-primary btn-sm fw-bold rounded-pill">
                                <i class="bi bi-wallet2 me-1"></i> Klik: Bayar Langsung ke DANA
                            </a>
                            <button type="button" class="btn btn-light btn-sm border fw-bold rounded-pill" onclick="copyNomorDANA('081212418446')">
                                <i class="bi bi-clipboard me-1"></i> Salin Nomor DANA
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark small">Alat yang disewa:</label>
                        <div id="container-alat-terpilih" class="mb-2 d-flex flex-wrap gap-1"></div>
                    </div>

                    <input type="hidden" name="id_barang" id="id_barang_modal">
                    <div id="hidden-inputs-tambahan"></div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-dark small">Tanggal Pinjam</label>
                            <input type="date" name="tgl_pinjam" id="tgl_pinjam" class="form-control" required min="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-dark small">Tanggal Kembali</label>
                            <input type="date" name="tgl_kembali" id="tgl_kembali" class="form-control" required min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-danger small">Bukti Pembayaran (Wajib)</label>
                        <input type="file" name="bukti_bayar" id="inputBuktiTransfer" class="form-control" accept="image/*" required>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light" style="position: relative; z-index: 10000 !important;">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="btnKonfirmasiPinjam" class="btn btn-primary px-4 fw-bold" style="pointer-events: auto !important; cursor: not-allowed; opacity: 0.6;">
                        Konfirmasi Pinjam
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    #btnKonfirmasiPinjam {
        cursor: pointer !important;
        pointer-events: auto !important;
    }

    .modal-footer {
        position: relative;
        z-index: 10000 !important;
    }

    .card-hover:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2) !important;
    }
</style>

<script>
    function copyNomorDANA(nomor) {
        navigator.clipboard.writeText(nomor);
        alert("Nomor DANA " + nomor + " berhasil disalin!");
    }

    document.addEventListener('DOMContentLoaded', function() {
        const btnKonfirmasi = document.getElementById('btnKonfirmasiPinjam');
        const inputBukti = document.getElementById('inputBuktiTransfer');
        const formSewa = document.getElementById('formSewa');
        const biayaBookingPerAlat = 15000;

        // Fungsi Update UI Nominal
        function updateNominalBooking(jumlah) {
            const total = jumlah * biayaBookingPerAlat;
            document.getElementById('displayTotalBooking').innerText = 'Rp ' + total.toLocaleString('id-ID');
            document.getElementById('displayJumlahBarang').innerText = jumlah;
        }

        inputBukti.addEventListener('change', function() {
            if (this.files.length > 0) {
                btnKonfirmasi.style.opacity = "1";
                btnKonfirmasi.style.cursor = "pointer";
                btnKonfirmasi.classList.remove('disabled');
            } else {
                btnKonfirmasi.style.opacity = "0.6";
                btnKonfirmasi.style.cursor = "not-allowed";
            }
        });

        btnKonfirmasi.addEventListener('click', function() {
            if (inputBukti.files.length === 0) {
                alert("Silakan upload bukti pembayaran terlebih dahulu!");
                return;
            }
            if (formSewa.checkValidity()) {
                formSewa.submit();
            } else {
                formSewa.reportValidity();
            }
        });

        document.querySelectorAll('.btn-pinjam-sekarang').forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('id_barang_modal').value = this.dataset.id;
                document.getElementById('container-alat-terpilih').innerHTML = `<div class="badge bg-primary p-2">${this.dataset.nama}</div>`;
                document.getElementById('hidden-inputs-tambahan').innerHTML = '';
                inputBukti.value = '';
                updateNominalBooking(1); // Set ke 1 barang
            });
        });

        document.getElementById('btnSelesaiPilih')?.addEventListener('click', function() {
            const terpilih = document.querySelectorAll('.select-cepat:checked');
            if (terpilih.length === 0) return alert('Pilih minimal satu alat!');

            const containerAlat = document.getElementById('container-alat-terpilih');
            const hiddenArea = document.getElementById('hidden-inputs-tambahan');
            containerAlat.innerHTML = '';
            hiddenArea.innerHTML = '';

            terpilih.forEach((cb, index) => {
                if (index === 0) {
                    document.getElementById('id_barang_modal').value = cb.dataset.id;
                } else {
                    hiddenArea.innerHTML += `<input type="hidden" name="barang_tambahan[]" value="${cb.dataset.id}">`;
                }
                containerAlat.innerHTML += `<div class="badge bg-success p-2 me-1">${cb.dataset.nama}</div>`;
            });

            updateNominalBooking(terpilih.length); // Update sesuai jumlah yang dicentang

            const modalSewa = new bootstrap.Modal(document.getElementById('modalPinjam'));
            modalSewa.show();
        });
    });
</script>
<?= $this->endSection() ?>