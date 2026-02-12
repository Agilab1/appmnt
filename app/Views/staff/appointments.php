<?= $this->extend('layouts/base') ?>
<?= $this->section('content') ?>
<div class="container-fluid">

    <h4 class="mb-3">My Appointments</h4>

    <div class="card">
        <div class="card-body p-0">
             <table id="dtbl" class="table table-striped table-bordered">
            <!-- <table class="table table-bordered table-striped mb-0"> -->
                <thead class="table-primary">
                    <tr>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Appointment</th>
                        <th>Agenda</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($appointments)) : ?>
                        <?php foreach ($appointments as $row) : ?>
                            <tr>
                                <td><?= esc($row['name']) ?></td>
                                <td><?= esc($row['mobile']) ?></td>
                                <td><?= date('d M Y h:i A', strtotime($row['appointment_datetime'])) ?></td>
                                <td><?= esc($row['purpose']) ?></td>

                                <td>
                                    <?php if ($row['status'] == 'Pending'): ?>
                                        <span class="badge bg-warning">Pending</span>
                                    <?php elseif ($row['status'] == 'Approved'): ?>
                                        <span class="badge bg-success">Approved</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Rejected</span>
                                    <?php endif; ?>
                                </td>
                                <td>
<?php if ($row['status'] == 'Pending') : ?>

<a href="<?= base_url('staff/appointment/approve/'.$row['id']) ?>"
   class="btn btn-success btn-sm">Approve</a>

<a href="<?= base_url('staff/appointment/reject/'.$row['id']) ?>"
   class="btn btn-danger btn-sm">Reject</a>

<?php else: ?>
-
<?php endif; ?>
</td>

                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">No appointments found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>

            </table>

        </div>
    </div>

</div>

<?= $this->endSection() ?>
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

