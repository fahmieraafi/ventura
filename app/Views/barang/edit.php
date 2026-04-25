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
                        <label class="form-label">Nama Alat</label>
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
                        <label class="form-label">Kategori</label>
                        <select name="kategori_pilih" id="kategoriSelect" class="form-select bg-dark text-white border-secondary" required>
                            <?php foreach ($listKategori as $lk) : ?>
                                <?php if (!empty($lk['kategori'])) : ?>
                                    <option value="<?= $lk['kategori']; ?>" <?= $barang['kategori'] == $lk['kategori'] ? 'selected' : '' ?>>
                                        <?= ucfirst($lk['kategori']); ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <option value="baru" class="text-info fw-bold">+ Ganti Kategori Baru</option>
                        </select>

                        <div id="inputKategoriBaru" class="mt-2 d-none">
                            <input type="text" name="kategori_baru" class="form-control bg-dark text-info border-info" placeholder="Ketik nama kategori baru...">
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kondisi</label>
                        <select name="kondisi" class="form-select bg-dark text-white border-secondary">
                            <option value="Baik" <?= $barang['kondisi'] == 'Baik' ? 'selected' : '' ?>>Baik</option>
                            <option value="Rusak Ringan" <?= $barang['kondisi'] == 'Rusak Ringan' ? 'selected' : '' ?>>Rusak Ringan</option>
                            <option value="Rusak Berat" <?= $barang['kondisi'] == 'Rusak Berat' ? 'selected' : '' ?>>Rusak Berat</option>
                        </select>
                    </div>

                    <div class="col-md-12 mb-4">
                        <label class="form-label d-block">Foto Alat Saat Ini (Klik X untuk menghapus)</label>
                        <div class="d-flex flex-wrap gap-3 mb-3 p-3 rounded" style="background: rgba(0,0,0,0.2);">
                            <?php
                            $fotos = explode(',', $barang['foto_barang']);
                            foreach ($fotos as $key => $f) :
                                if ($f != '' && $f != 'tenda.jpg') : ?>
                                    <div class="position-relative foto-wrapper">
                                        <img src="<?= base_url('uploads/barang/' . $f) ?>" width="100" height="100" class="rounded object-fit-cover border border-secondary shadow">
                                        <button type="button"
                                            class="btn btn-danger btn-sm position-absolute top-0 end-0 rounded-circle p-0"
                                            style="width:22px; height:22px; transform: translate(30%, -30%);"
                                            onclick="hapusFotoSatu('<?= $f ?>', '<?= $barang['id_barang'] ?>', this)">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                            <?php endif;
                            endforeach; ?>
                        </div>

                        <label class="form-label">Tambah Foto Baru (Opsional)</label>
                        <input type="file" name="foto_barang[]" class="form-control bg-dark text-white border-secondary" multiple>
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

<script>
    // Script untuk kontrol Input Kategori Baru
    document.getElementById('kategoriSelect').addEventListener('change', function() {
        const divBaru = document.getElementById('inputKategoriBaru');
        const inputBaru = divBaru.querySelector('input');
        if (this.value === 'baru') {
            divBaru.classList.remove('d-none');
            inputBaru.setAttribute('required', 'required');
            inputBaru.focus();
        } else {
            divBaru.classList.add('d-none');
            inputBaru.removeAttribute('required');
        }
    });

    /**
     * Fungsi AJAX untuk menghapus foto satu per satu
     */
    function hapusFotoSatu(namaFile, idBarang, btnElement) {
        if (confirm("Hapus foto ini secara permanen?")) {
            let formData = new FormData();
            formData.append('nama_file', namaFile);
            formData.append('id_barang', idBarang);

            let csrfTokenName = '<?= csrf_token() ?>';
            let csrfTokenHash = '<?= csrf_hash() ?>';
            formData.append(csrfTokenName, csrfTokenHash);

            fetch('<?= base_url('barang/hapusFotoSatuan') ?>', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (response.status === 403) throw new Error('Sesi habis, refresh halaman');
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        btnElement.closest('.foto-wrapper').remove();
                    } else {
                        alert("Gagal: " + data.msg);
                    }
                })
                .catch(error => alert(error.message));
        }
    }
</script>
<?= $this->endSection() ?>