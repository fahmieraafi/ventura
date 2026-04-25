<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-warning">
            <h4 class="mb-0">Edit User</h4>
        </div>

        <div class="card-body">

            <form action="<?= base_url('users/update/' . $user['id_user']) ?>" method="post" enctype="multipart/form-data">

                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" value="<?= $user['nama'] ?>" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" value="<?= $user['username'] ?>" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password (kosongkan jika tidak diubah)</label>
                    <input type="password" name="password" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Nomor WhatsApp</label>
                    <input type="number" name="no_wa" value="<?= $user['no_wa'] ?>" class="form-control" required>
                </div>


                <div class="mb-3">
                    <label class="form-label">Foto Profil</label>
                    <input type="file" name="foto" class="form-control">
                    <p class="text-muted mt-2">Foto profil sekarang:</p>

                    <?php if ($user['foto']): ?>
                        <img src="<?= base_url('uploads/users/' . $user['foto']) ?>" width="80" class="rounded border">
                    <?php else: ?>
                        <span class="badge bg-secondary">Tidak ada foto profil</span>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label">Foto KTP (Jaminan)</label>
                    <input type="file" name="ktp" class="form-control">
                    <p class="text-muted mt-2">KTP sekarang:</p>

                    <?php if ($user['ktp']): ?>
                        <img src="<?= base_url('uploads/ktp/' . $user['ktp']) ?>" width="150" class="rounded border">
                    <?php else: ?>
                        <span class="badge bg-danger">Belum upload KTP</span>
                    <?php endif; ?>
                    <small class="d-block text-muted mt-1 italic">*Kosongkan jika tidak ingin mengganti file KTP</small>
                </div>

                <button type="submit" class="btn btn-success">Update Data</button>
                <?php if (session()->get('role') == 'admin') : ?>
                    <a href="<?= base_url('users') ?>" class="btn btn-secondary">Kembali</a>
                <?php else : ?>
                    <a href="<?= base_url('dashboard') ?>" class="btn btn-secondary">Kembali</a>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>