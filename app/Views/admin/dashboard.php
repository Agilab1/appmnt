<?= $this->extend('layouts/base') ?>
<?= $this->section('content') ?>

<div class="container mt-4">

    <h3 class="mb-4">
        Welcome, <?= esc(session()->get('admin_name')) ?>
    </h3>

    <!-- DASHBOARD CARDS -->
    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body text-center">
                    <h6>Total</h6>
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
            <div class="card text-white bg-success">
                <div class="card-body text-center">
                    <h6>Approved</h6>
                    <h2><?= $approved ?></h2>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-danger">
                <div class="card-body text-center">
                    <h6>Rejected</h6>
                    <h2><?= $rejected ?></h2>
                </div>
            </div>
        </div>

    </div>

    <!-- APPOINTMENTS TABLE -->
    <h4 class="mb-3">All Appointments</h4>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-bordered table-striped mb-0">
                <thead class="table-primary">
                    <tr>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Appointment</th>
                        <th>Purpose</th>
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
                                        <a href="<?= base_url('admin/appointment/approve/'.$row['id']) ?>"
                                           class="btn btn-success btn-sm">Approve</a>

                                        <a href="<?= base_url('admin/appointment/reject/'.$row['id']) ?>"
                                           class="btn btn-danger btn-sm">Reject</a>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No appointments found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        <a href="<?= base_url('logout') ?>" class="btn btn-outline-danger">
            Logout
        </a>
    </div>

</div>

<?= $this->endSection() ?>
