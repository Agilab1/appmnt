<?= $this->extend('layouts/base') ?>
<?= $this->section('content') ?>

<div class="container mt-4">

    <h3 class="mb-4">Security Dashboard</h3>

    <!-- DASHBOARD CARDS -->
    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body text-center">
                    <h6>Approved</h6>
                    <h2><?= $total ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-dark bg-warning">
                <div class="card-body text-center">
                    <h6>Pending</h6>
                    <h2><?= $pending ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body text-center">
                    <h6>Checked-In</h6>
                    <h2><?= $checkedin ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-secondary">
                <div class="card-body text-center">
                    <h6>Checked-Out</h6>
                    <h2><?= $exited ?></h2>
                </div>
            </div>
        </div>

    </div>


    <!-- VISITOR TABLE -->
    <h4 class="mb-3">Visitor Check-In</h4>

    <div class="card">
        <div class="card-body p-0">
            <!-- <table class="table table-bordered table-striped mb-0"> -->
            <table id="dtbl" class="table table-striped table-bordered">
                <thead class="table-primary">
                    <tr>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Appointment</th>
                        <th>Agenda</th>
                        <th>Status</th>
                        <th>In Time</th>
                        <th>Out Time</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($appointments)) : ?>
                        <?php foreach ($appointments as $row) : ?>
                            <tr>
                                <td><?= esc($row->name) ?></td>
                                <td><?= esc($row->mobile) ?></td>
                                <td><?= date('d M Y h:i A', strtotime($row->appointment_datetime)) ?></td>
                                <td><?= esc($row->purpose) ?></td>

                                <td>
                                    <?php if (($row->entry_status ?? '') == 'Entered'): ?>
                                        <span class="badge bg-success">checkin</span>
                                    <?php elseif (($row->entry_status ?? '') == 'Exited'): ?>
                                        <span class="badge bg-secondary">checkout</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Waiting</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?= !empty($row->entry_time)
                                        ? date('d M Y h:i A', strtotime($row->entry_time))
                                        : '-' ?>
                                </td>

                                <td>
                                    <?= !empty($row->exit_time)
                                        ? date('d M Y h:i A', strtotime($row->exit_time))
                                        : '-' ?>
                                </td>

                                <td>
                                    <?php if (($row->entry_status ?? '') == 'Entered'): ?>
                                        <a href="<?= base_url('security/checkout/' . $row->id) ?>"
                                            class="btn btn-danger btn-sm">Check-Out</a>

                                    <?php elseif (($row->entry_status ?? '') != 'Exited'): ?>
                                        <a href="<?= base_url('security/checkin/' . $row->id) ?>"
                                            class="btn btn-success btn-sm">Check-In</a>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">No approved visitors</td>
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