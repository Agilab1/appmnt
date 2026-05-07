<?= $this->extend('layouts/base') ?>
<?= $this->section('content') ?>

<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css' rel='stylesheet' />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    html,
    body {
        overflow-x: hidden !important;
        background: #eef2f7;
    }

    * {
        box-sizing: border-box;
    }

    .content-header {
        display: none !important;
    }

    .container-fluid {
        padding-bottom: 10px !important;
    }

    .stat-card {
        border-radius: 16px;
        padding: 22px;
        color: #fff;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
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

    .filter-card {
        background: #fff;
        border-radius: 16px;
        padding: 16px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    }

    .table-card {
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        background: #fff;
    }

    .table-header {
        background: #fff;
        border-bottom: 1px solid #edf2f7;
        padding: 16px 18px;
        font-weight: 600;
    }

    .custom-table thead th {
        background: #4a7bdc;
        color: #fff;
        font-size: 13px;
        font-weight: 600;
        border: none;
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
        padding: 5px 12px;
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

    .chart-card canvas {
        max-height: 320px !important;
    }

    .activity-scroll {
        max-height: 420px;
        overflow-y: auto;
    }

    .equal-card {
        height: 100%;
    }

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
        cursor: pointer;
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

    @media(max-width:768px) {

        .activity-scroll {
            max-height: unset;
        }

    }
</style>

<div class="container-fluid">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-3">

        <h3 class="fw-bold mb-0">
            Welcome, <?= esc(session()->get('admin_name')) ?>
        </h3>

        <button class="btn btn-primary px-4 py-2"
            data-bs-toggle="modal"
            data-bs-target="#calendarModal">
            View Calendar
        </button>

    </div>

    <!-- TOP CARDS -->
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

    <!-- CHARTS -->
    <div class="row g-3 mb-3">

        <div class="col-md-8">

            <div class="card border-0 table-card chart-card equal-card">

                <div class="card-header table-header">
                    Monthly Appointments
                </div>

                <div class="card-body">
                    <canvas id="barChart"></canvas>
                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card border-0 table-card chart-card equal-card">

                <div class="card-header table-header">
                    Status Analytics
                </div>

                <div class="card-body d-flex align-items-center justify-content-center">
                    <canvas id="pieChart"></canvas>
                </div>

            </div>

        </div>

    </div>

    <!-- STAFF + ACTIVITY -->
    <div class="row g-3 mb-3">

        <div class="col-md-8">

            <div class="card border-0 table-card equal-card">

                <div class="card-header table-header">
                    Staff Performance
                </div>

                <div class="table-responsive">

                    <table class="table custom-table mb-0">

                        <thead>
                            <tr>
                                <th>Staff Code</th>
                                <th>Total</th>
                                <th>Approved</th>
                                <th>Rejected</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php foreach($staffAnalytics as $s): ?>

                            <tr>

                                <td><?= $s->emp_code ?></td>

                                <td><?= $s->total ?></td>

                                <td>
                                    <span class="badge bg-success">
                                        <?= $s->approved ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="badge bg-danger">
                                        <?= $s->rejected ?>
                                    </span>
                                </td>

                            </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card border-0 table-card equal-card">

                <div class="card-header table-header">
                    Recent Activity
                </div>

                <div class="card-body activity-scroll">

                    <?php foreach($activities as $a): ?>

                    <div class="border-bottom pb-2 mb-2">

                        <div class="fw-bold">
                            <?= esc($a->name) ?>
                        </div>

                        <small>
                            <?= esc($a->status) ?> Appointment
                        </small>

                        <br>

                        <small class="text-muted">
                            <?= date('d M Y h:i A', strtotime($a->appointment_datetime)) ?>
                        </small>

                    </div>

                    <?php endforeach; ?>

                </div>

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

                    <a href="<?= base_url('admin/dashboard') ?>"
                        class="btn btn-secondary px-4">
                        Reset
                    </a>

                </div>

            </div>

        </form>

    </div>

    <!-- TABLE -->
    <div class="card border-0 table-card">

        <div class="card-header table-header">
            All Appointments
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

<!-- EVENT DETAIL MODAL -->
<div class="modal fade" id="eventModal">
    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content p-4"
            style="border-radius:18px;box-shadow:0 30px 80px rgba(0,0,0,0.2);">

            <div class="d-flex justify-content-between mb-2">

                <h5 class="fw-bold">
                    Appointment Detail
                </h5>

                <button class="btn-close"
                    data-bs-dismiss="modal"></button>

            </div>

            <div style="background:#f8fafc;padding:12px;border-radius:10px;">

                <p><b>Visitor:</b> <span id="e_name"></span></p>

                <p><b>Visitor ID:</b> <span id="e_id"></span></p>

                <p><b>Date:</b> <span id="e_date"></span></p>

                <p><b>Purpose:</b> <span id="e_purpose"></span></p>

                <p><b>Contact:</b> <span id="e_mobile"></span></p>

                <p><b>Status:</b> <span id="e_status"></span></p>

            </div>

            <div class="d-flex gap-2 mt-3">

                <a id="viewBtn"
                    class="btn btn-primary w-100"
                    target="_blank">
                    View
                </a>

                <a id="approveBtn"
                    class="btn btn-success w-100">
                    Approve
                </a>

                <a id="rejectBtn"
                    class="btn btn-danger w-100">
                    Reject
                </a>

            </div>

        </div>

    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('custom'); ?>

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js'></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>

flatpickr("#singleDate", {

    dateFormat: "Y-m-d",

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

        // CLICK EVENT
        eventClick: function(info) {

            let e = info.event.extendedProps;

            document.getElementById('e_name').innerText =
                info.event.title;

            document.getElementById('e_id').innerText =
                e.visitor_id ?? '-';

            document.getElementById('e_date').innerText =
                info.event.start.toLocaleString();

            document.getElementById('e_purpose').innerText =
                e.purpose ?? '-';

            document.getElementById('e_mobile').innerText =
                e.mobile ?? '-';

            document.getElementById('e_status').innerText =
                e.status ?? '-';

            // IMPORTANT FIX
            let appointmentId = info.event.id;

            // VIEW
            document.getElementById('viewBtn').href =
                "<?= base_url('appointment/view/') ?>" + appointmentId;

            // APPROVE
            document.getElementById('approveBtn').href =
                "<?= base_url('admin/appointment/approve/') ?>" + appointmentId;

            // REJECT
            document.getElementById('rejectBtn').href =
                "<?= base_url('admin/appointment/reject/') ?>" + appointmentId;

            // STATUS CHECK
            if (e.status !== 'Pending') {

                document.getElementById('approveBtn')
                    .style.display = 'none';

                document.getElementById('rejectBtn')
                    .style.display = 'none';

            } else {

                document.getElementById('approveBtn')
                    .style.display = 'block';

                document.getElementById('rejectBtn')
                    .style.display = 'block';
            }

            // SHOW MODAL
            new bootstrap.Modal(
                document.getElementById('eventModal')
            ).show();
        },

        // EVENT COLORS
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

    calendar.render();

});

document.addEventListener('shown.bs.modal', function(e) {

    if (e.target.id === 'calendarModal')
        calendar.updateSize();

});

// BAR CHART
new Chart(document.getElementById('barChart'), {

    type: 'bar',

    data: {

        labels: <?= $chartLabels ?>,

        datasets: [{
            label: 'Appointments',
            data: <?= $chartValues ?>,
            borderWidth: 1,
            borderRadius: 8
        }]
    },

    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

// PIE CHART
new Chart(document.getElementById('pieChart'), {

    type: 'doughnut',

    data: {

        labels: ['Approved', 'Pending', 'Rejected'],

        datasets: [{
            data: <?= $pieData ?>
        }]
    },

    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

</script>

<?= $this->endSection(); ?>