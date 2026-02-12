<style>
    body {
        overflow-x: hidden;
    }
</style>

<?= $this->extend('layouts/base'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid">
    <div class="card">
        <div class="card-header">
        <div class="row">
            <?= $this->include('layouts/messages');  ?>
        </div>
        <div class="row align-items-center">
                <div class="col-sm-4">
                    <h5 class="card-title mb-0">List of Users</h5>
                </div>
                <div class="col-sm-8 text-end">
                    <a href="staff/create" class="btn btn-primary">Create User</a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                 <table id="dtbl" class="table table-striped table-bordered">
                <!-- <table class="table table-striped table-bordered mb-0"> -->
                    <thead>
                        <tr>
                            <th>SR No</th>
                            <th>Staff ID</th>
                            <th>Staff Name</th>
                            <th>Email ID</th>
                            <th>Mobile No</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($staffs as $count => $staff): ?>
                            <tr>
                                <td><?= ++$count ?></td>
                                <td>
                                    <a href="staff/update/<?= $staff->emp_code ?>">
                                        <?= $staff->emp_code ?>
                                    </a>
                                </td>
                                <td><?= $staff->first_nm . ' ' . $staff->last_nm ?></td>
                                <td><?= $staff->email_id ?></td>
                                <td><?= $staff->cell_no ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('custom'); ?>
<script>
    $(function() {
        $("#dtbl").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#dtbl_wrapper .col-md-6:eq(0)');

        function closeWindow() {
            window.close();
        }



        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,  
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
    });
</script>
<?= $this->endsection(); ?>



<?= $this->section('side_bar'); ?>
<div class="row">
    <a href="users">Users</a>
</div>
<?= $this->endSection(); ?>