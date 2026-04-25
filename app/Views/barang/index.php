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

        <?php
        $adaKosong = array_search(null, array_column($listKategori, 'kategori'));
        if ($adaKosong !== false) :
        ?>
            <a href="<?= base_url('barang?kategori='); ?>"
                class="btn btn-sm rounded-pill px-4 <?= ($kategoriAktif === '') ? 'btn-primary' : 'btn-outline-light'; ?>">
                Lainnya
            </a>
        <?php endif; ?>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4" id="containerBarang">
        <?php foreach ($barang as $b) :
            $listFoto = explode(',', $b['foto_barang']);
            $fotoUtama = !empty($listFoto[0]) ? $listFoto[0] : 'tenda.jpg';
        ?>
            <div class="col item-barang">
                <div class="card h-100 border-0 shadow-sm card-hover bg-white" style="border-radius: 15px; overflow: hidden; transition: 0.3s;">

                    <a href="<?= base_url('barang/' . $b['id_barang']) ?>" class="text-center p-3 d-block">
                        <img src="<?= base_url('uploads/barang/' . $fotoUtama) ?>"
                            class="img-fluid"
                            style="height: 180px; width: 100%; object-fit: contain;"
                            alt="<?= $b['nama_barang'] ?>">
                    </a>

                    <div class="card-body text-center pt-0 pb-4">
                        <small class="text-primary fw-bold text-uppercase d-block mb-1" style="font-size: 0.7rem; letter-spacing: 1px;">
                            <?= $b['kategori'] ?: 'Lainnya' ?>
                        </small>

                        <h5 class="card-title fw-bold nama-barang text-dark mb-1"><?= $b['nama_barang'] ?></h5>
                        <p class="text-muted mb-2">Rp <?= number_format($b['harga_sewa'], 0, ',', '.') ?> / Hari</p>

                        <?php if (strtolower(session()->get('role')) == 'admin') : ?>
                            <p class="mt-3 text-muted small">
                                <i class="bi bi-eye"></i> Dilihat <?= $b['views'] ?> kali (Admin Mode)
                            </p>
                        <?php endif; ?>

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
                            <?php if (strtolower(session()->get('role')) == 'admin') : ?>
                                <div class="d-flex gap-2">
                                    <a href="<?= base_url('barang/edit/' . $b['id_barang']) ?>" class="btn btn-outline-warning btn-sm flex-fill fw-bold rounded-3">Edit</a>
                                    <a href="<?= base_url('barang/delete/' . $b['id_barang']) ?>" class="btn btn-outline-danger btn-sm flex-fill fw-bold rounded-3" onclick="return confirm('Hapus?')">Hapus</a>
                                </div>
                            <?php else : ?>
                                <a href="<?= base_url('wishlist/tambah/' . $b['id_barang']) ?>" class="btn btn-sm btn-outline-danger border-0 fw-bold rounded-3 mb-1">
                                    <i class="bi bi-heart-fill me-1"></i> Simpan ke Wishlist
                                </a>

                                <button type="button"
                                    class="btn btn-primary fw-bold py-2 rounded-3 shadow-sm btn-pinjam-sekarang"
                                    <?= ($b['stok'] <= 0) ? 'disabled' : '' ?>
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalPinjam"
                                    data-id="<?= $b['id_barang'] ?>"
                                    data-nama="<?= $b['nama_barang'] ?>"
                                    data-harga="<?= $b['harga_sewa'] ?>"
                                    data-stok="<?= $b['stok'] ?>">
                                    <?= ($b['stok'] <= 0) ? 'Stok Kosong' : 'Pinjam Sekarang' ?>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
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
                            <img src="<?= base_url('assets/img/ventura.png') ?>" alt="QRIS Pembayaran" class="img-fluid shadow-sm" style="max-width: 180px; border-radius: 8px;">
                        </div>
                        <div class="d-grid gap-2 mt-2">
                            <a href="https://link.dana.id/minta?full_url=https://qr.dana.id/v1/281012012021061491765024/assets/img/ventura.png/081212418446" target="_blank" class="btn btn-primary btn-sm fw-bold rounded-pill">
                                <i class="bi bi-wallet2 me-1"></i> Klik: Bayar Langsung ke DANA
                            </a>
                            <button type="button" class="btn btn-light btn-sm border fw-bold rounded-pill" onclick="copyNomorDANA('081212418446')">
                                <i class="bi bi-clipboard me-1"></i> Salin Nomor DANA
                            </button>
                        </div>
                    </div>

                    <input type="hidden" name="id_barang" id="id_barang_modal">

                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label fw-bold text-dark small">Alat yang disewa:</label>
                            <input type="text" id="nama_barang_modal" class="form-control bg-light border-0 fw-bold text-primary" readonly>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold text-dark small">Jumlah</label>
                            <input type="number" name="jumlah" id="jumlah_modal" class="form-control shadow-sm" value="1" min="1" required>
                        </div>
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
    function copyNomorDANA(nomor) {
        navigator.clipboard.writeText(nomor);
        alert("Nomor DANA " + nomor + " berhasil disalin! Silakan lakukan transfer.");
    }

    function validasiTombolKonfirmasi() {
        const inputFoto = document.getElementById('inputBuktiTransfer');
        const tombol = document.getElementById('btnKonfirmasiPinjam');
        tombol.disabled = inputFoto.files.length === 0;
    }

    const searchInput = document.getElementById('inputCariBarang');
    const cards = document.querySelectorAll('.item-barang');

    searchInput.addEventListener('input', function() {
        const searchText = this.value.toLowerCase();
        cards.forEach(card => {
            const namaBarang = card.querySelector('.nama-barang').innerText.toLowerCase();
            card.style.display = namaBarang.includes(searchText) ? "" : "none";
        });
    });

    document.querySelectorAll('.btn-pinjam-sekarang').forEach(button => {
        button.addEventListener('click', function() {
            const stokMax = this.getAttribute('data-stok');
            document.getElementById('id_barang_modal').value = this.getAttribute('data-id');
            document.getElementById('nama_barang_modal').value = this.getAttribute('data-nama');

            const inputJumlah = document.getElementById('jumlah_modal');
            inputJumlah.value = 1;
            inputJumlah.setAttribute('max', stokMax);

            document.getElementById('inputBuktiTransfer').value = '';
            document.getElementById('btnKonfirmasiPinjam').disabled = true;
        });
    });
</script>

<?= $this->endSection() ?>