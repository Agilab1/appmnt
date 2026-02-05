<?= $this->extend('layouts/base') ?>
<?= $this->section('content') ?>

<div class="container mt-4">

    <h3 class="mb-4">
        Welcome, <?= esc(session()->get('admin_name')) ?>
    </h3>

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

    <a href="<?= base_url('admin/appointments') ?>" class="btn btn-dark me-2">
        View Appointments
    </a>

    <!-- ✅ CORRECT LOGOUT -->
    <a href="<?= base_url('logout') ?>" class="btn btn-outline-danger">
        Logout
    </a>

</div>

<?= $this->endSection() ?>