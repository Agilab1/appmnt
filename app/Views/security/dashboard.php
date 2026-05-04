<?= $this->extend('layouts/base') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<!-- ✅ DATATABLE CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

<style>

/* GLOBAL */
html,body{overflow-x:hidden!important;}
*{box-sizing:border-box;}
.content-header{display:none!important;}

/* CARDS */
.stat-card{
    border-radius:14px;
    padding:20px;
    color:#fff;
    box-shadow:0 8px 20px rgba(0,0,0,0.08);
}

.bg-approved{background:linear-gradient(135deg,#22c55e,#16a34a);}
.bg-pending{background:linear-gradient(135deg,#facc15,#f59e0b);}
.bg-checkin{background:linear-gradient(135deg,#4f46e5,#6366f1);}
.bg-checkout{background:linear-gradient(135deg,#6b7280,#374151);}

/* FILTER */
.filter-card{
    background:#fff;
    border-radius:12px;
    border:1px solid #e5e7eb;
    padding:15px;
}

/* TABLE */
.table-card{
    border-radius:16px;
    box-shadow:0 10px 25px rgba(0,0,0,0.06);
}

.table-header{
    background:#f8fafc;
    border-bottom:1px solid #e5e7eb;
}

.custom-table thead th{
    background:#4f46e5;
    color:#fff;
    font-size:13px;
    font-weight:600;
}

.custom-table tbody tr:hover{
    background:#f9fafb;
}

/* STATUS */
.status-badge{
    padding:4px 10px;
    border-radius:20px;
    font-size:12px;
}

.status-badge.checkin{background:#dcfce7;color:#16a34a;}
.status-badge.checkout{background:#e5e7eb;color:#374151;}
.status-badge.pending{background:#fef3c7;color:#d97706;}

</style>

<div class="container-fluid">

<!-- HEADER -->
<div class="d-flex justify-content-between mb-3">
<h4 class="fw-bold">Security Dashboard</h4>

<a href="<?= base_url('security/scan') ?>" class="btn btn-primary">
Scan QR
</a>
</div>

<!-- CARDS -->
<div class="row g-3 mb-3">

<div class="col-md-3">
<div class="stat-card bg-approved">
<h6>Approved</h6>
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
<div class="stat-card bg-checkin">
<h6>Checked-In</h6>
<h2><?= $checkedin ?></h2>
</div>
</div>

<div class="col-md-3">
<div class="stat-card bg-checkout">
<h6>Checked-Out</h6>
<h2><?= $exited ?></h2>
</div>
</div>

</div>

<!-- FILTER -->
<div class="filter-card mb-3 d-flex gap-2 align-items-center">

<input type="text" id="filterDate" class="form-control" style="width:200px;">

<div class="dropdown">
<button class="btn btn-dark dropdown-toggle" id="statusBtn" data-bs-toggle="dropdown">
Status
</button>

<ul class="dropdown-menu">
<li><a class="dropdown-item status-filter" data-type="checkin">Check-In</a></li>
<li><a class="dropdown-item status-filter" data-type="checkout">Check-Out</a></li>
<li><a class="dropdown-item status-filter" data-type="pending">Pending</a></li>
<li><a class="dropdown-item status-filter" data-type="all">Show All</a></li>
</ul>
</div>

</div>

<!-- TABLE -->
<div class="card table-card border-0">

<div class="card-header table-header">
<b>Security Logs</b>
</div>

<div class="table-responsive">

<table id="dtbl" class="table custom-table mb-0">

<thead>
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
<a href="<?= base_url('security/view/' . $row->id) ?>">
<?= esc($row->visitor_id) ?>
</a>
</td>

<td><?= esc($row->name) ?></td>
<td><?= esc($row->mobile) ?></td>

<td><?= date('d M Y h:i A', strtotime($row->appointment_datetime)) ?></td>
<td><?= esc($row->purpose) ?></td>

<td>
<?php if ($row->entry_status == 'Entered'): ?>
<span class="status-badge checkin">Check-In</span>
<?php elseif ($row->entry_status == 'Exited'): ?>
<span class="status-badge checkout">Check-Out</span>
<?php else: ?>
<span class="status-badge pending">Pending</span>
<?php endif; ?>
</td>

<td><?= $row->entry_time ? date('d M Y h:i A', strtotime($row->entry_time)) : '-' ?></td>
<td><?= $row->exit_time ? date('d M Y h:i A', strtotime($row->exit_time)) : '-' ?></td>

<td>
<?php if ($row->entry_status == 'Entered'): ?>
<a href="<?= base_url('security/checkout/' . $row->id) ?>" class="btn btn-danger btn-sm">Check-Out</a>
<?php elseif ($row->entry_status != 'Exited'): ?>
<a href="<?= base_url('security/checkin/' . $row->id) ?>" class="btn btn-success btn-sm">Check-In</a>
<?php else: ?>-<?php endif; ?>
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

<!-- ✅ DATATABLE JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
$(document).ready(function(){

    // ✅ PAGINATION ENABLE
    var table = $("#dtbl").DataTable({
        pageLength: 10,   // 🔥 10 rows per page
        lengthMenu: [10, 25, 50, 100],
        ordering: false
    });

    flatpickr("#filterDate", {
        dateFormat: "Y-m-d",
        defaultDate: "today"
    });

});
</script>

<?= $this->endSection(); ?>