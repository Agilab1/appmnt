<?= $this->extend('layouts/base'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid">
    <div class="card">

        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-sm-6">
                    <h5 class="card-title mb-0">Users List</h5>
                </div>
                <div class="col-sm-6 text-end">
                    <a href="<?= site_url('staff/create') ?>" class="btn btn-primary">
                        Create User
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Staff ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Role</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($staffs as $i => $staff): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td>
                                <a href="<?= site_url('staff/edit/'.$staff->emp_code) ?>">
                                    <?= $staff->emp_code ?>
                                </a>
                            </td>
                            <td><?= $staff->first_nm.' '.$staff->last_nm ?></td>
                            <td><?= $staff->email_id ?></td>
                            <td><?= $staff->cell_no ?></td>
                            <td><?= ucfirst($staff->role ?? 'staff') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<?= $this->endSection(); ?>


<?= $this->section('custom'); ?>
<script>
function btnToggle() {

    document.querySelectorAll("#form input, #form select").forEach(el => {
        el.removeAttribute("readonly");
        el.removeAttribute("disabled");
    });

    document.getElementById("saveBtn").classList.remove("d-none");
}
</script>

<?= $this->endsection(); ?>



<?= $this->section('side_bar'); ?>
<div class="row">
    <a href="users">Users</a>
</div>
<?= $this->endSection(); ?>