<?= $this->extend('layouts/base') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

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

    <a href="<?= base_url('security/scan') ?>" class="btn btn-primary mb-3">
        Scan QR
    </a>

    <!-- FILTER -->
    <div class="d-flex align-items-center gap-2 mb-3">

        <input type="text" id="filterDate" class="form-control" placeholder="Select Date" style="width:200px;">

        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" id="statusBtn" data-bs-toggle="dropdown">
                Status
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item status-filter" data-type="checkin">Check-In</a></li>
                <li><a class="dropdown-item status-filter" data-type="checkout">Check-Out</a></li>

                <!-- ✅ NEW PENDING OPTION -->
                <li><a class="dropdown-item status-filter" data-type="pending">Pending</a></li>

                <li><a class="dropdown-item status-filter" data-type="all">Show All</a></li>
            </ul>
        </div>

    </div>

    <!-- TABLE -->
    <div class="card">
        <div class="card-body p-0">
            <table id="dtbl" class="table table-striped table-bordered">

                <thead class="table-primary">
                    <tr>
                        <th>Visitor ID</th>
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
                    <?php foreach ($appointments as $row): ?>
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
                                <?php if ($row->entry_status == 'Entered'): ?>
                                    <span class="badge bg-success">checkin</span>
                                <?php elseif ($row->entry_status == 'Exited'): ?>
                                    <span class="badge bg-secondary">checkout</span>
                                <?php else: ?>
                                    <span class="badge bg-warning">Pending</span>
                                <?php endif; ?>
                            </td>

                            <td><?= $row->entry_time ? date('d M Y h:i A', strtotime($row->entry_time)) : '-' ?></td>
                            <td><?= $row->exit_time ? date('d M Y h:i A', strtotime($row->exit_time)) : '-' ?></td>

                            <td>
                                <?php if ($row->entry_status == 'Entered'): ?>
                                    <a href="<?= base_url('security/checkout/' . $row->id) ?>" class="btn btn-danger btn-sm">Check-Out</a>
                                <?php elseif ($row->entry_status != 'Exited'): ?>
                                    <a href="<?= base_url('security/checkin/' . $row->id) ?>" class="btn btn-success btn-sm">Check-In</a>
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

<?= $this->endSection() ?>

<?= $this->section('custom'); ?>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
$(document).ready(function () {

    var table = $("#dtbl").DataTable({
        responsive: true,
        lengthChange: false,
        autoWidth: false,
        buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
    });

    table.buttons().container().appendTo('#dtbl_wrapper .col-md-6:eq(0)');

    flatpickr("#filterDate", {
        dateFormat: "Y-m-d",
        defaultDate: "today"
    });

    let today = new Date().toISOString().split('T')[0];
    $('#filterDate').val(today);

    window.filterType = "all";

    $.fn.dataTable.ext.search.push(function (settings, data) {

        try {
            let selectedDate = $('#filterDate').val();
            let appointmentDate = data[3];

            if (selectedDate && appointmentDate) {
                let parts = appointmentDate.split(" ");
                let formatted = parts[1] + " " + parts[0] + ", " + parts[2];

                let rowDate = new Date(formatted);
                let filterDate = new Date(selectedDate);

                if (
                    rowDate.getFullYear() !== filterDate.getFullYear() ||
                    rowDate.getMonth() !== filterDate.getMonth() ||
                    rowDate.getDate() !== filterDate.getDate()
                ) {
                    return false;
                }
            }

            let inTime = data[6];
            let outTime = data[7];

            if (window.filterType === 'checkin') {
                return (inTime && inTime !== "-") && (!outTime || outTime === "-");
            }

            if (window.filterType === 'checkout') {
                return outTime && outTime !== "-";
            }

            // ✅ NEW PENDING FILTER
            if (window.filterType === 'pending') {
                return (!inTime || inTime === "-") && (!outTime || outTime === "-");
            }

            return true;

        } catch (e) {
            console.log(e);
            return true;
        }
    });

    $(document).on('click', '.status-filter', function () {
        window.filterType = $(this).data('type');
        $('#statusBtn').text($(this).text());
        table.draw();
    });

    $('#filterDate').on('change', function () {
        table.draw();
    });

});
</script>

<?= $this->endSection() ?>