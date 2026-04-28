<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <h2 class="text-white fw-bold m-0">Katalog Alat Kamping</h2>

        <div class="d-flex gap-3 align-items-center">
            <div class="input-group" style="width: 300px;">
                <span class="input-group-text bg-white border-0 shadow-sm" style="border-radius: 10px 0 0 10px;">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text" id="inputCariBarang" class="form-control border-0 shadow-sm"
                    placeholder="Cari alat kamping..." style="border-radius: 0 10px 10px 0;">
            </div>

            <?php if (strtolower(session()->get('role')) == 'admin') : ?>
                <a href="<?= base_url('barang/create') ?>" class="btn btn-primary shadow-sm px-4" style="border-radius: 10px;">
                    <i class="bi bi-plus-lg me-2"></i>Tambah Alat
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="d-flex flex-wrap gap-2 mb-4 overflow-auto pb-2 scrollbar-hidden">
        <a href="<?= base_url('barang'); ?>"
            class="btn btn-sm rounded-pill px-4 <?= (!$kategoriAktif) ? 'btn-primary' : 'btn-outline-light'; ?>">
            Semua
        </a>

        <?php foreach ($listKategori as $lk) : ?>
            <?php if ($lk['kategori']) : ?>
                <a href="<?= base_url('barang?kategori=' . urlencode($lk['kategori'])); ?>"
                    class="btn btn-sm rounded-pill px-4 <?= ($kategoriAktif == $lk['kategori']) ? 'btn-primary' : 'btn-outline-light'; ?>">
                    <?= ucfirst($lk['kategori']); ?>
                </a>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4" id="containerBarang">
        <?php foreach ($barang as $b) :
            $listFoto = explode(',', $b['foto_barang']);
            $fotoUtama = !empty($listFoto[0]) ? $listFoto[0] : 'tenda.jpg';
        ?>
            <div class="col item-barang">
                <div class="card h-100 border-0 shadow-sm card-hover bg-white position-relative <?= ($b['stok'] <= 0) ? 'opacity-75' : '' ?>" style="border-radius: 15px; overflow: hidden; transition: 0.3s;">

                    <?php if (isset($_GET['mode']) && $_GET['mode'] == 'pilih_cepat') : ?>
                        <div class="position-absolute top-0 start-0 m-3" style="z-index: 10;">
                            <input class="form-check-input border-primary shadow-sm select-cepat" type="checkbox"
                                data-id="<?= $b['id_barang'] ?>"
                                data-nama="<?= $b['nama_barang'] ?>"
                                <?= ($b['stok'] <= 0) ? 'disabled title="Stok Habis"' : '' ?>
                                style="width: 25px; height: 25px; cursor: <?= ($b['stok'] <= 0) ? 'not-allowed' : 'pointer' ?>;">
                        </div>
                    <?php endif; ?>

                    <a href="<?= base_url('barang/' . $b['id_barang']) ?>" class="text-center p-3 d-block">
                        <img src="<?= base_url('uploads/barang/' . $fotoUtama) ?>"
                            class="img-fluid"
                            style="height: 180px; width: 100%; object-fit: contain; <?= ($b['stok'] <= 0) ? 'filter: grayscale(1);' : '' ?>"
                            alt="<?= $b['nama_barang'] ?>">
                    </a>

                    <div class="card-body text-center pt-0 pb-4">
                        <small class="text-primary fw-bold text-uppercase d-block mb-1" style="font-size: 0.7rem; letter-spacing: 1px;">
                            <?= $b['kategori'] ?: 'Lainnya' ?>
                        </small>

                        <h5 class="card-title fw-bold nama-barang text-dark mb-1"><?= $b['nama_barang'] ?></h5>
                        <p class="text-muted mb-2">Rp <?= number_format($b['harga_sewa'], 0, ',', '.') ?> / Hari</p>

                        <div class="mb-2">
                            <p class="small text-secondary mb-1">Stok Tersedia: <b><?= $b['stok']; ?></b></p>

                            <?php if ($b['stok'] <= 0) : ?>
                                <span class="badge bg-danger px-3 py-2" style="border-radius: 8px;">Habis</span>
                            <?php elseif ($b['stok'] <= 3) : ?>
                                <span class="badge bg-warning text-dark px-3 py-2" style="border-radius: 8px;">Hampir Habis</span>
                            <?php else : ?>
                                <span class="badge bg-success px-3 py-2" style="border-radius: 8px;">Tersedia</span>
                            <?php endif; ?>
                        </div>

                        <div class="d-grid gap-2 px-2 mt-3">
                            <?php if (strtolower(session()->get('role')) != 'admin') : ?>
                                <button type="button"
                                    class="btn btn-primary fw-bold py-2 rounded-3 shadow-sm btn-pinjam-sekarang"
                                    <?= ($b['stok'] <= 0) ? 'disabled' : '' ?>
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalPinjam"
                                    data-id="<?= $b['id_barang'] ?>"
                                    data-nama="<?= $b['nama_barang'] ?>">
                                    <?= ($b['stok'] <= 0) ? 'Stok Kosong' : 'Pinjam Sekarang' ?>
                                </button>
                            <?php endif; ?>
                            <?php if (session()->get('role') == 'user' || session()->get('role') == 'User') : ?>
                                <div class="position-absolute top-0 end-0 m-3" style="z-index: 10;">
                                    <a href="<?= base_url('wishlist/tambah/' . $b['id_barang']) ?>"
                                        class="btn btn-light shadow-sm rounded-circle p-2 d-flex align-items-center justify-content-center"
                                        style="width: 40px; height: 40px; transition: 0.3s;"
                                        title="Simpan ke Wishlist">
                                        <i class="bi bi-heart-fill text-danger"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php if (isset($_GET['mode']) && $_GET['mode'] == 'pilih_cepat') : ?>
    <div class="fixed-bottom p-4 text-center" style="z-index: 3000;">
        <button id="btnSelesaiPilih" class="btn btn-success btn-lg shadow-lg rounded-pill px-5 fw-bold border-white border-2 animate__animated animate__bounceInUp">
            <i class="bi bi-check2-circle me-2"></i> Selesai & Kembali ke Form
        </button>
    </div>
<?php endif; ?>

<div class="modal fade" id="modalPinjam" tabindex="-1" aria-hidden="true" style="z-index: 1080;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold"><i class="bi bi-calendar-check me-2"></i>Form Sewa Alat</h5>
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
                        <div id="container-alat-terpilih" class="mb-2 d-flex flex-wrap gap-1">
                        </div>
                        <a href="<?= base_url('barang?mode=pilih_cepat') ?>" class="btn btn-outline-primary btn-sm w-100 rounded-pill border-2 fw-bold" id="btnTambahBarang">
                            <i class="bi bi-plus-circle me-1"></i> Ingin menambah barang?
                        </a>
                    </div>

                    <input type="hidden" name="id_barang" id="id_barang_modal">
                    <input type="hidden" id="nama_barang_modal">
                    <div id="hidden-inputs-tambahan"></div>

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
                        <input type="file" name="bukti_bayar" id="inputBuktiTransfer" class="form-control shadow-sm" accept="image/*" required>
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

<style>
    .card-hover:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3) !important;
    }

    .scrollbar-hidden::-webkit-scrollbar {
        display: none;
    }
</style>

<script>
    // Fungsi salin nomor
    function copyNomorDANA(nomor) {
        navigator.clipboard.writeText(nomor);
        alert("Nomor DANA " + nomor + " berhasil disalin!");
    }

    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const modalElement = document.getElementById('modalPinjam');
        const modalSewa = new bootstrap.Modal(modalElement);
        const inputBukti = document.getElementById('inputBuktiTransfer');
        const btnKonfirmasi = document.getElementById('btnKonfirmasiPinjam');
        const biayaBookingPerBarang = 15000;

        // Fungsi Update Nominal
        function updateNominalBooking(jumlah) {
            const total = jumlah * biayaBookingPerBarang;
            document.getElementById('displayTotalBooking').innerText = 'Rp ' + total.toLocaleString('id-ID');
            document.getElementById('displayJumlahBarang').innerText = jumlah;
        }

        // 1. Validasi Input File
        inputBukti.addEventListener('change', function() {
            btnKonfirmasi.disabled = this.files.length === 0;
        });

        // 2. Logic Re-open Modal (Setelah Tambah Barang)
        if (urlParams.get('action') === 'reopen') {
            const data = JSON.parse(localStorage.getItem('temp_sewa'));
            if (data) {
                document.getElementById('id_barang_modal').value = data.id_awal;
                document.getElementById('nama_barang_modal').value = data.nama_awal;

                const containerAlat = document.getElementById('container-alat-terpilih');
                const hiddenArea = document.getElementById('hidden-inputs-tambahan');

                containerAlat.innerHTML = `<div class="badge bg-primary p-2"><i class="bi bi-star-fill me-1"></i> ${data.nama_awal}</div>`;
                hiddenArea.innerHTML = '';

                let count = 1;
                data.tambahan.forEach(item => {
                    if (item.id !== data.id_awal) {
                        containerAlat.innerHTML += `<div class="badge bg-success p-2"><i class="bi bi-plus-lg me-1"></i> ${item.nama}</div>`;
                        hiddenArea.innerHTML += `<input type="hidden" name="barang_tambahan[]" value="${item.id}">`;
                        count++;
                    }
                });

                updateNominalBooking(count);
                modalSewa.show();
            }
        }

        // 3. Simpan data awal sebelum ke mode pilih cepat
        document.getElementById('btnTambahBarang')?.addEventListener('click', function() {
            const currentData = {
                id_awal: document.getElementById('id_barang_modal').value,
                nama_awal: document.getElementById('nama_barang_modal').value,
                tambahan: []
            };
            localStorage.setItem('temp_sewa', JSON.stringify(currentData));
        });

        // 4. Selesai Pilih (Mode Checkbox)
        document.getElementById('btnSelesaiPilih')?.addEventListener('click', function() {
            let data = JSON.parse(localStorage.getItem('temp_sewa')) || {
                tambahan: []
            };
            document.querySelectorAll('.select-cepat:checked').forEach(cb => {
                data.tambahan.push({
                    id: cb.dataset.id,
                    nama: cb.dataset.nama
                });
            });
            localStorage.setItem('temp_sewa', JSON.stringify(data));
            window.location.href = "<?= base_url('barang?action=reopen') ?>";
        });

        // 5. Klik Pinjam Sekarang (Bersihkan data lama)
        document.querySelectorAll('.btn-pinjam-sekarang').forEach(button => {
            button.addEventListener('click', function() {
                localStorage.removeItem('temp_sewa');
                const id = this.dataset.id;
                const nama = this.dataset.nama;

                document.getElementById('id_barang_modal').value = id;
                document.getElementById('nama_barang_modal').value = nama;
                document.getElementById('hidden-inputs-tambahan').innerHTML = '';
                document.getElementById('container-alat-terpilih').innerHTML = `<div class="badge bg-primary p-2"><i class="bi bi-star-fill me-1"></i> ${nama}</div>`;

                updateNominalBooking(1);
                inputBukti.value = '';
                btnKonfirmasi.disabled = true;
            });
        });

        // 6. Fitur Cari
        document.getElementById('inputCariBarang').addEventListener('input', function() {
            const search = this.value.toLowerCase();
            document.querySelectorAll('.item-barang').forEach(card => {
                const nama = card.querySelector('.nama-barang').innerText.toLowerCase();
                card.style.display = nama.includes(search) ? "" : "none";
            });
        });
    });
</script>

<?= $this->endSection() ?>F