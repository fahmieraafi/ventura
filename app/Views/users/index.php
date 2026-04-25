<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid mt-4 px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-white fw-bold"><i class="bi bi-person-lines-fill me-2 text-info"></i> Data Users</h2>

        <div class="col-md-4">
            <form action="<?= base_url('users') ?>" method="get">
                <div class="input-group shadow-sm">
                    <input type="text" name="cari" class="form-control border-0"
                        placeholder="Cari nama, username, atau WA..."
                        value="<?= esc($cari ?? '') ?>" style="border-radius: 10px 0 0 10px;">
                    <button class="btn btn-info text-white px-3" type="submit" style="border-radius: 0 10px 10px 0;">
                        <i class="bi bi-search"></i>
                    </button>
                    <?php if (!empty($cari)) : ?>
                        <a href="<?= base_url('users') ?>" class="btn btn-light ms-2 rounded-pill" title="Reset">
                            <i class="bi bi-arrow-clockwise"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-lg overflow-hidden" style="border-radius: 15px;">
        <div class="table-responsive">
            <table class="table table-hover table-light mb-0 align-middle">
                <thead class="table-dark">
                    <tr class="text-center text-uppercase fs-12 tracking-wider">
                        <th class="ps-3 py-3" width="50">No</th>
                        <th>Nama / Username</th>
                        <th>WhatsApp</th>
                        <th>Role</th>
                        <th>Foto Profil</th>
                        <th>Foto KTP</th>
                        <?php if (session()->get('role') == 'admin') : ?>
                            <th width="120">Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php $no = 1;
                        foreach ($users as $u): ?>
                            <tr class="text-center">
                                <td class="text-muted"><?= $no++ ?></td>

                                <td class="text-start ps-4">
                                    <div class="text-primary fw-bold fs-6"><?= esc($u['nama']) ?></div>
                                    <small class="text-muted">@<?= esc($u['username']) ?></small>
                                </td>

                                <td>
                                    <?php if ($u['no_wa']): ?>
                                        <a href="https://wa.me/<?= $u['no_wa'] ?>" target="_blank" class="btn btn-sm btn-outline-success rounded-pill px-3">
                                            <i class="bi bi-whatsapp me-1"></i> <?= esc($u['no_wa']) ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted small italic">N/A</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php if ($u['role'] == 'admin') : ?>
                                        <span class="badge bg-danger rounded-pill px-3 py-2">Admin</span>
                                    <?php else : ?>
                                        <span class="badge bg-secondary rounded-pill px-3 py-2">User</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <div class="ratio ratio-1x1 shadow-sm rounded-circle overflow-hidden border border-2 border-white mx-auto" style="width: 45px;">
                                        <img src="<?= $u['foto'] ? base_url('uploads/users/' . $u['foto']) : base_url('assets/img/default.jpg') ?>"
                                            class="img-fluid object-fit-cover"
                                            onerror="this.src='<?= base_url('assets/img/default.jpg') ?>'">
                                    </div>
                                </td>

                                <td>
                                    <?php if ($u['ktp']): ?>
                                        <a href="<?= base_url('uploads/ktp/' . $u['ktp']) ?>" target="_blank">
                                            <img src="<?= base_url('uploads/ktp/' . $u['ktp']) ?>"
                                                class="rounded border shadow-sm object-fit-cover"
                                                style="width: 65px; height: 40px;"
                                                onerror="this.src='<?= base_url('assets/img/no-image.jpg') ?>'">
                                        </a>
                                    <?php else: ?>
                                        <span class="badge bg-light text-danger border">Belum Upload</span>
                                    <?php endif; ?>
                                </td>

                                <?php if (session()->get('role') == 'admin') : ?>
                                    <td>
                                        <div class="d-flex gap-2 justify-content-center">
                                            <a href="<?= base_url('users/edit/' . $u['id_user']) ?>"
                                                class="btn btn-sm btn-outline-warning border-2" title="Edit">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                            <a href="<?= base_url('users/delete/' . $u['id_user']) ?>"
                                                onclick="return confirm('Hapus user ini?')"
                                                class="btn btn-sm btn-outline-danger border-2" title="Hapus">
                                                <i class="bi bi-trash-fill"></i>
                                            </a>
                                        </div>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-search d-block mb-2" style="font-size: 2rem;"></i>
                                <?php if (!empty($cari)) : ?>
                                    User dengan kata kunci "<b><?= esc($cari) ?></b>" tidak ditemukan.
                                <?php else : ?>
                                    Belum ada data user.
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .table-light {
        background-color: #ffffff !important;
    }

    .fs-12 {
        font-size: 12px;
    }

    .tracking-wider {
        letter-spacing: 1px;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05) !important;
        transition: all 0.2s ease;
    }

    .object-fit-cover {
        object-fit: cover;
    }

    .btn-sm {
        border-radius: 8px;
    }
</style>

<?= $this->endSection() ?>