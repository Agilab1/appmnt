<?= $this->extend('layouts/base') ?>
<?= $this->section('content') ?>

<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css' rel='stylesheet' />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<style>
    /* GLOBAL */
    html,
    body {
        overflow-x: hidden !important;
    }

    * {
        box-sizing: border-box;
    }

    .content-header {
        display: none !important;
    }

    /* CARDS */
    .stat-card {
        border-radius: 14px;
        padding: 20px;
        color: #fff;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    }

    .bg-total {
        background: linear-gradient(135deg, #4f46e5, #6366f1);
    }

    .bg-pending {
        background: linear-gradient(135deg, #facc15, #f59e0b);
    }

    .bg-approved {
        background: linear-gradient(135deg, #22c55e, #16a34a);
    }

    .bg-rejected {
        background: linear-gradient(135deg, #ef4444, #dc2626);
    }

    /* FILTER */
    .filter-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        padding: 15px;
    }

    /* TABLE */
    .table-card {
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.06);
    }

    .table-header {
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
    }

    .custom-table thead th {
        background: #4a7bdc;
        color: #ffffff;
        font-weight: 600;
        font-size: 13px;
    }

    .custom-table tbody tr:hover {
        background: #f9fafb;
    }

    .date-pill {
        background: #eef2ff;
        color: #4f46e5;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 12px;
    }

    .status-badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
    }

    .status-badge.approved {
        background: #dcfce7;
        color: #16a34a;
    }

    .status-badge.pending {
        background: #fef3c7;
        color: #d97706;
    }

    .status-badge.rejected {
        background: #fee2e2;
        color: #dc2626;
    }

    /* CALENDAR */
    .fc {
        background: #fff;
        border-radius: 16px;
        padding: 15px;
    }

    .fc-toolbar-title {
        font-weight: 600;
    }

    .fc-button {
        background: #1f2937 !important;
        border: none !important;
        border-radius: 8px !important;
    }

    .fc-today-button {
        background: #9ca3af !important;
    }

    .fc-day-today {
        background: #fef9c3 !important;
    }

    .fc-event {
        border-radius: 10px !important;
        padding: 6px;
        font-size: 12px;
    }

    .event-approved {
        background: #22c55e !important;
        color: #fff;
    }

    .event-pending {
        background: #f59e0b !important;
        color: #fff;
    }

    .event-rejected {
        background: #ef4444 !important;
        color: #fff;
    }
</style>

<div class="container-fluid">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-3">

        <h3 class="mb-0 fw-bold">
            Welcome, <?= esc(session()->get('admin_name')) ?>
        </h3>

        <button class="btn btn-primary py-2 px-3"
            data-bs-toggle="modal"
            data-bs-target="#calendarModal">
            View Calendar
        </button>

    </div>

    <!-- CARDS -->
    <div class="row g-3 mb-3">

        <div class="col-md-3">
            <div class="stat-card bg-total">
                <h6>Total</h6>
                <h2><?= $total ?></h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card bg-pending">
                <h6>Pending</h6>
                <h2><?= $pending ?></h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card bg-approved">
                <h6>Approved</h6>
                <h2><?= $approved ?></h2>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card bg-rejected">
                <h6>Rejected</h6>
                <h2><?= $rejected ?></h2>
            </div>
        </div>

    </div>

    <!-- FILTER -->
    <div class="filter-card mb-3">

        <form method="get">

            <div class="row g-2 align-items-center">

                <div class="col-md-6">

                    <input type="text"
                        id="singleDate"
                        class="form-control"
                        value="<?= $_GET['from'] ?? '' ?>"
                        placeholder="Select Date">

                    <input type="hidden" name="from" id="fromDate">
                    <input type="hidden" name="to" id="toDate">

                </div>

                <div class="col-md-6 d-flex justify-content-end gap-2">

                    <button class="btn btn-dark px-4">
                        Apply
                    </button>

                    <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-secondary px-4">
                        Reset
                    </a>

                </div>

            </div>

        </form>

    </div>

    <!-- TABLE -->
    <div class="card table-card border-0">

        <div class="card-header table-header">
            <b>All Appointments</b>
        </div>

        <div class="table-responsive">

            <table class="table custom-table datatable mb-0">

                <thead>
                    <tr>
                        <th>Visitor ID</th>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Appointment</th>
                        <th>Purpose</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($appointments as $row): ?>

                        <tr>

                            <td>
                                <a href="<?= base_url('appointment/view/' . $row->id) ?>">
                                    <?= esc($row->visitor_id) ?>
                                </a>
                            </td>

                            <td><?= esc($row->name) ?></td>

                            <td><?= esc($row->mobile) ?></td>

                            <td>
                                <span class="date-pill">
                                    <?= date('d M Y h:i A', strtotime($row->appointment_datetime)) ?>
                                </span>
                            </td>

                            <td><?= esc($row->purpose) ?></td>

                            <td>
                                <span class="status-badge <?= strtolower($row->status) ?>">
                                    <?= $row->status ?>
                                </span>
                            </td>

                            <td>

                                <?php if ($row->status == 'Pending'): ?>

                                    <a href="<?= base_url('admin/appointment/approve/' . $row->id) ?>"
                                        class="btn btn-success btn-sm">
                                        Approve
                                    </a>

                                    <a href="<?= base_url('admin/appointment/reject/' . $row->id) ?>"
                                        class="btn btn-danger btn-sm">
                                        Reject
                                    </a>

                                <?php else: ?>
                                    -
                                <?php endif; ?>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<!-- CALENDAR -->
<div class="modal fade" id="calendarModal">
    <div class="modal-dialog modal-xl modal-dialog-centered">

        <div class="modal-content p-3">

            <div class="modal-header border-0">
                <h5>Professional Scheduler</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div id="calendar"></div>
            </div>

        </div>

    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('custom'); ?>

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js'></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    // DATE FILTER
    flatpickr("#singleDate", {
        dateFormat: "Y-m-d",
        defaultDate: "today",
        onChange: function(selectedDates, dateStr) {
            fromDate.value = dateStr;
            toDate.value = dateStr;
        }
    });

    let calendar;

    document.addEventListener('DOMContentLoaded', function() {

        calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {

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

            events: <?= $calendarEvents ?? '[]' ?>,

            eventDidMount: function(info) {

                let s = info.event.extendedProps.status;

                if (s == 'Approved')
                    info.el.classList.add('event-approved');

                if (s == 'Pending')
                    info.el.classList.add('event-pending');

                if (s == 'Rejected')
                    info.el.classList.add('event-rejected');
            }

        });

    });

    document.addEventListener('shown.bs.modal', function(e) {

        if (e.target.id === 'calendarModal')
            calendar.render();

    });
</script>

<?= $this->endSection(); ?>