<?= $this->extend('layouts/base') ?>
<?= $this->section('content') ?>
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js'></script>
<style>

    #calendarModal .modal-content {
        border-radius: 1.25rem;
        overflow: hidden;
    }

    #calendarModal .modal-header {
        background-color: #ffffff;
    }

    .calendar-container {
        border: 1px solid #e9ecef;
        min-height: 400px;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.02);
    }

    .btn-close:focus {
        box-shadow: none;
    }

    .btn-primary {
        background-color: #4f46e5;
        border-color: #4f46e5;
        transition: all 0.2s ease;
    }

    .btn-primary:hover {
        background-color: #4338ca;
        transform: translateY(-1px);
    }
</style>
<div class="container mt-4">

    <h3 class="mb-4">Staff Dashboard</h3>

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
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">My Appointments</h4>
        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#calendarModal">
            📅 View Calendar
        </button>
    </div>

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
                                <td><?= esc($row->name) ?></td>
                                <td><?= esc($row->mobile) ?></td>
                                <td><?= date('d M Y h:i A', strtotime($row->appointment_datetime)) ?></td>
                                <td><?= esc($row->purpose) ?></td>

                                <td>
                                    <?php if ($row->status == 'Pending'): ?>
                                        <span class="badge bg-warning">Pending</span>
                                    <?php elseif ($row->status == 'Approved'): ?>
                                        <span class="badge bg-success">Approved</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Rejected</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php if ($row->status == 'Pending'): ?>
                                        <a href="<?= base_url('staff/appointment/approve/' . $row->id) ?>"
                                            class="btn btn-success btn-sm">Approve</a>

                                        <a href="<?= base_url('staff/appointment/reject/' . $row->id) ?>"
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
<div class="modal fade" id="calendarModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-dark fs-4">
                    <i class="bi bi-calendar3 me-2 text-primary"></i> Appointment Calendar
                </h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <div class="calendar-container bg-light rounded-3 p-3">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('custom'); ?>

<script>
    let calendar;

    document.addEventListener('DOMContentLoaded', function() {



        if (typeof $ !== 'undefined' && $.fn.DataTable) {

            $("#dtbl").DataTable({
                responsive: true,
                lengthChange: false,
                autoWidth: false,
                buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#dtbl_wrapper .col-md-6:eq(0)');
        }



        let calendarEl = document.getElementById('calendar');

        if (calendarEl && typeof FullCalendar !== 'undefined') {

            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 600,
                events: <?= $calendarEvents ?? '[]' ?>,
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                }
            });

        }

    });
    document.addEventListener('shown.bs.modal', function(event) {

        if (event.target.id === 'calendarModal') {

            if (calendar) {
                calendar.render();
                calendar.updateSize();
            }

        }

    });
</script>
<?= $this->endSection(); ?>