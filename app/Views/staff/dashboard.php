<?= $this->extend('layouts/base') ?>
<?= $this->section('content') ?>

<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js'></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<style>
    #calendarModal .modal-content {
        border-radius: 1.25rem;
        overflow: hidden;
    }

    .calendar-container {
        border: 1px solid #e9ecef;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, .02);
    }

    .fc-toolbar-title {
        font-weight: 600;
    }
</style>

<div class="container mt-4">

    <h3 class="mb-4">Staff Dashboard</h3>

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


    <!-- DATE FILTER -->

    <div class="card mb-4 shadow-sm">
        <div class="card-body">

            <form method="get" action="<?= base_url('staff/dashboard') ?>">

                <div class="row g-3 align-items-end">

                    <div class="col-md-3">
                        <label>From Date</label>
                        <input type="text" id="fromDate" name="from" class="form-control" value="<?= $_GET['from'] ?? '' ?>">
                    </div>

                    <div class="col-md-3">
                        <label>To Date</label>
                        <input type="text" id="toDate" name="to" class="form-control" value="<?= $_GET['to'] ?? '' ?>">
                    </div>

                    <div class="col-md-3">
                        <button class="btn btn-primary w-100">Filter</button>
                    </div>

                    <div class="col-md-3">
                        <a href="<?= base_url('staff/dashboard') ?>" class="btn btn-secondary w-100">Reset</a>
                    </div>

                </div>

            </form>

        </div>
    </div>


    <div class="d-flex justify-content-between align-items-center mb-3">

        <h4 class="mb-0">My Appointments</h4>

        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#calendarModal">
            📅 View Calendar
        </button>

    </div>


    <!-- APPOINTMENT TABLE -->

    <div class="card">
        <div class="card-body p-0">

            <table class="table table-striped table-bordered datatable mb-0">

                <thead class="table-primary">

                    <tr>
                        <th>Visitor ID</th>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Appointment</th>
                        <th>Agenda</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>

                </thead>

                <tbody>

                    <?php if (!empty($appointments)): foreach ($appointments as $row): ?>

                            <tr>

                                <td>
                                    <a href="<?= base_url('appointment/view/' . $row->id) ?>">
                                        <?= esc($row->visitor_id) ?>
                                    </a>
                                </td>

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

                                    <?php else: ?> - <?php endif; ?>

                                </td>

                            </tr>

                        <?php endforeach;
                    else: ?>

                        <tr>
                            <td colspan="7" class="text-center">No appointments found</td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>
    </div>

</div>


<!-- CALENDAR MODAL -->

<div class="modal fade" id="calendarModal">

    <div class="modal-dialog modal-xl modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header">
                <h5 class="fw-bold">Professional Scheduler</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <div class="d-flex gap-2">
                        <input type="text" id="calendarDate" class="form-control" style="width:200px">
                        <button class="btn btn-primary" onclick="goToDate()">Go</button>
                    </div>

                    <div>
                        <span class="badge bg-success">Approved</span>
                        <span class="badge bg-warning text-dark">Pending</span>
                        <span class="badge bg-danger">Rejected</span>
                    </div>

                </div>

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
    $(document).ready(function() {

        flatpickr("#fromDate", {
            dateFormat: "Y-m-d",
            maxDate: "today"
        });
        flatpickr("#toDate", {
            dateFormat: "Y-m-d",
            maxDate: "today"
        });
        flatpickr("#calendarDate", {
            dateFormat: "Y-m-d",
            defaultDate: new Date()
        });

    });


    let calendar;

    document.addEventListener('DOMContentLoaded', function() {

        let calendarEl = document.getElementById('calendar');

        if (calendarEl) {

            calendar = new FullCalendar.Calendar(calendarEl, {

                initialView: 'timeGridWeek',

                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'timeGridDay,timeGridWeek,dayGridMonth'
                },

                height: 650,
                nowIndicator: true,
                allDaySlot: false,
                slotMinTime: "09:00:00",
                slotMaxTime: "20:00:00",
                expandRows: true,

                events: <?= $calendarEvents ?? '[]' ?>,

                eventClick: function(info) {

                    let e = info.event.extendedProps;

                    alert(
                        "Visitor: " + info.event.title +
                        "\nMobile: " + e.mobile +
                        "\nPurpose: " + e.purpose +
                        "\nStatus: " + e.status
                    );

                }

            });

        }

    });


    function goToDate() {

        let d = document.getElementById('calendarDate').value;

        if (calendar) calendar.gotoDate(d);

    }


    document.addEventListener('shown.bs.modal', function(e) {

        if (e.target.id === 'calendarModal' && calendar) {

            calendar.render();

        }

    });
</script>

<?= $this->endSection(); ?>