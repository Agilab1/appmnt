<?= $this->extend('layouts/base'); ?>
<?=  $this->section("content"); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Appointments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <h3>My Appointments</h3>

    <table class="table table-bordered mt-3">
        <thead class="table-primary">
            <tr>
                <th>Name</th>
                <th>Mobile</th>
                <th>Date</th>
                 <th>Time</th>
                <th>Purpose</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($appointments as $row): ?>
                <tr>
                    <td><?= esc($row['name']) ?></td>
                    <td><?= esc($row['mobile']) ?></td>
                    <td><?= esc($row['appointment_date']) ?></td>
                     <td><?= esc($row['appointment_time']) ?></td>

                    <td><?= esc($row['purpose']) ?></td>
                    <td>
                        <span class="badge
                            <?= $row['status']=='Pending' ? 'bg-warning' : ($row['status']=='Approved' ? 'bg-success' : 'bg-danger') ?>">
                            <?= $row['status'] ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($row['status'] == 'Pending'): ?>
                            <a href="<?= base_url('admin/appointment/approve/'.$row['id']) ?>" class="btn btn-success btn-sm">Approve</a>
                            <a href="<?= base_url('admin/appointment/reject/'.$row['id']) ?>" class="btn btn-danger btn-sm">Reject</a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="<?= base_url('admin/logout') ?>">Logout</a>
</div>

</body>
</html>
<?= $this->endSection(); ?>
