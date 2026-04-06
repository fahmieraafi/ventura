<ul class="nav flex-column mt-3">
   
    <li class="nav-item">
        <a class="nav-link" href="<?= base_url('/dashboard') ?>">
            <i class="bi bi-house"></i> <span>Dashboard</span>

        </a>
    </li>
</ul>



<?php if (session()->get('role') == 'Admin') : ?>
    <a href="<?= base_url('users') ?>" class="sidebar-item">
        <i class="bi bi-people me-2"></i> Users
    </a>
<?php endif; ?>

<a href="<?= base_url('barang') ?>" class="sidebar-item">
    <i class="bi bi-box-seam me-2"></i> Data Barang
</a>

 <li class="nav-item">
        <a class="nav-link" href="<?= base_url('/users') ?>">
            <i class="bi bi-people"></i> <span>Users</span>
        </a>
    </li>

        <?php $idu = session('id_user'); ?>
    

    
    

</ul>

   <li class="nav-item mt-3">
    <span class="nav-link disabled">Masuk sebagai: <b><?= session('nama'); ?> (<?= session('role'); ?>)</b></span>
</li>

<center>
    <img src="<?= base_url('uploads/users/' . session()->get('foto')) ?>" height="80" class="mt-3 rounded-circle" />
</center>

