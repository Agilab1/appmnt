<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Book Appointment</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
 body {
    background: #eef2f7;
}

/* CARD */
.card {
    border-radius: 18px;
    box-shadow: 0 20px 50px rgba(0,0,0,0.08);
}

/* HEADER (MODERN) */
.card-header {
    background: linear-gradient(135deg, #4f46e5, #6366f1);
    border: none;
    border-radius: 18px 18px 0 0;
}

.card-header h5 {
    font-weight: 600;
    letter-spacing: 0.5px;
}

/* INPUT */
.form-control {
    border-radius: 10px;
    border: 1px solid #e5e7eb;
    transition: 0.2s;
}

.form-control:focus {
    border-color: #4f46e5;
    box-shadow: 0 0 0 2px rgba(79,70,229,0.1);
}

/* LABEL */
.form-label {
    font-weight: 500;
    color: #374151;
}

/* SLOT BUTTON */
.slot-btn {
    min-width: 85px;
    font-size: 12px;
    border-radius: 8px;
}

/* BUTTON MAIN */
.btn-primary {
    background: linear-gradient(135deg,#4f46e5,#6366f1);
    border: none;
}

.btn-primary:hover {
    opacity: 0.9;
}

/* APPROVE / REJECT */
.approve-btn {
    background: #3b82f6;
    color: white;
    border-radius: 10px;
}

.reject-btn {
    background: #ef4444;
    color: white;
    border-radius: 10px;
}

/* INLINE */
.inline-wrapper {
    display: flex;
    gap: 8px;
}

.spinner-input {
    width: 70px;
    text-align: center;
}

/* SLOT CONTAINER */
#slotsContainer {
    background: #f8fafc;
    padding: 10px;
    border-radius: 10px;
}

/* TEXTAREA */
textarea {
    border-radius: 10px;
}
</style>

</head>

<body class="bg-light d-flex align-items-center min-vh-100">

    <?php
    $mode = $mode ?? 'create';
    $isEdit = ($mode === 'edit');
    // $isView = ($mode === 'view');
    $isView = ($mode === 'view' || $mode === 'security_view');
    $appointment = $appointment ?? null;
    ?>

    <div class="container px-3">

        <div class="row justify-content-center">

            <div class="col-12 col-sm-10 col-md-6 col-lg-5">

                <div class="card shadow-lg border-0">

                    <div class="card-header bg-primary text-white text-center">
                        <h5 class="mb-0">
                            <?= $isEdit ? 'Edit Appointment' : ($isView ? 'View Appointment' : 'Book Appointment') ?>
                        </h5>
                    </div>

                    <div class="card-body p-4">
                        <!-- <?php
                                // echo "ROLE = " . session()->get('role');
                                ?> -->



                        <?php if (session()->getFlashdata('success')): ?>
                            <div class="alert alert-success">
                                <?= nl2br(session()->getFlashdata('success')) ?>
                            </div>
                        <?php endif; ?>

                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger">
                                <?= session()->getFlashdata('error') ?>
                            </div>
                        <?php endif; ?>

                        <form method="post"
                            action="<?= $isEdit ? base_url('appointment/update/' . $appointment->id) : base_url('appointment/submit') ?>">

                            <input type="hidden" name="admin_id" value="<?= $admin_id ?? 1  ?>">

                            <!-- Name -->
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name"
                                    value="<?= $appointment->name ?? '' ?>"
                                    class="form-control form-control-lg"
                                    <?= $isView ? 'readonly' : '' ?>
                                    required>
                            </div>

                            <!-- Mobile -->
                            <div class="mb-3">
                                <label class="form-label">Mobile</label>
                                <input type="text" name="mobile"
                                    value="<?= $appointment->mobile ?? '' ?>"
                                    class="form-control form-control-lg"
                                    <?= $isView ? 'readonly' : '' ?>
                                    required>
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email"
                                    value="<?= $appointment->email ?? '' ?>"
                                    class="form-control form-control-lg"
                                    <?= $isView ? 'readonly' : '' ?>>
                            </div>

                            <!-- Date -->
                            <div class="mb-3">
                                <label class="form-label">Appointment Date</label>

                                <div class="input-group">

                                    <input type="text"
                                        id="appointment_date"
                                        name="appointment_date"
                                        class="form-control form-control-lg"
                                        value="<?= isset($appointment->appointment_datetime) ? date('Y-m-d', strtotime($appointment->appointment_datetime)) : '' ?>"
                                        <?= $isView ? 'readonly' : '' ?>
                                        required>

                                    <span class="input-group-text">📅</span>

                                </div>
                            </div>

                            <!-- Staff -->
                            <div class="mb-3">

                                <label class="form-label">Staff Name</label>

                                <select name="emp_code"
                                    id="staffSelect"
                                    class="form-control form-control-lg"
                                    <?= ($isView || session()->get('role') === 'staff') ? 'disabled' : '' ?>
                                    required>

                                    <option value="">Choose Staff</option>

                                    <?php foreach ($staffs as $staff): ?>

                                        <option value="<?= esc($staff->emp_code) ?>"
                                            <?= (
                                                (isset($appointment->emp_code) && $appointment->emp_code == $staff->emp_code)  ||
                                                    (session()->get('role') === 'staff'
                                                    && session()->get('emp_code') == $staff->emp_code)
                                                )  ? 'selected' : '' ?>>

                                            <?= esc($staff->first_nm . ' ' . $staff->last_nm) ?>

                                        </option>

                                    <?php endforeach; ?>

                                </select>
                                <?php if (session()->get('role') === 'staff'): ?>
                                <input type="hidden"
                                    name="emp_code"
                                    value="<?= session()->get('emp_code') ?>">

                            <?php endif; ?>

                            </div>

                            <!-- Available Slots -->
                            <div class="mb-3">

                                <label class="form-label">Available Time Slots</label>

                                <div id="slotsContainer" class="d-flex flex-wrap gap-2 text-muted">

                                    Select staff and date to see available slots

                                </div>

                            </div>


                            <!-- Selected Time -->
                            <div class="mb-3">

                                <label class="form-label">Selected Time</label>

                                <input type="text"
                                    id="appointment_time"
                                    name="appointment_time"
                                    class="form-control form-control-lg"
                                    value="<?= isset($appointment->appointment_datetime) ? date('H:i', strtotime($appointment->appointment_datetime)) : '' ?>"
                                    readonly
                                    required>

                            </div>

                            <!-- Duration -->
                            <div class="mb-3">

                                <label class="form-label">Duration</label>

                                <div class="inline-wrapper">

                                    <input type="number"
                                        name="duration_hour"
                                        class="form-control spinner-input"
                                        min="1"
                                        max="12"
                                        value="<?= $appointment->duration_hour ?? 1 ?>"
                                        <?= $isView ? 'readonly' : '' ?>
                                        required>

                                    <span class="unit-text">hr</span>

                                    <input type="number"
                                        name="duration_minute"
                                        class="form-control spinner-input"
                                        min="0"
                                        max="45"
                                        step="15"
                                        value="<?= $appointment->duration_minute ?? 0 ?>"
                                        <?= $isView ? 'readonly' : '' ?>
                                        required>

                                    <span class="unit-text">min</span>

                                </div>

                            </div>

                            <!-- Purpose -->
                            <div class="mb-4">

                                <label class="form-label">Purpose</label>

                                <textarea name="purpose"
                                    class="form-control form-control-lg"
                                    <?= $isView ? 'readonly' : '' ?>
                                    required><?= $appointment->purpose ?? '' ?></textarea>

                            </div>
                            <!-- </form> -->
                            <?php if (!$isView): ?>

                                <button class="btn btn-primary btn-lg w-100">

                                    <?= $isEdit ? 'Update Appointment' : 'Submit Appointment' ?>

                                </button>

                            <?php endif; ?>


                            <?php if (
                                $isEdit &&
                                $appointment->status === 'Pending' &&
                                session()->get('isLoggedIn') &&
                                in_array(session()->get('role'), ['staff', 'admin'])
                            ): ?>

                                <div class="d-flex gap-2 mt-3">

                                    <?php if (session()->get('role') === 'staff'): ?>

                                        <button type="submit"
                                            formaction="<?= base_url('staff/appointment/approve/' . $appointment->id) ?>"
                                            formmethod="post"
                                            class="btn approve-btn w-50 ">
                                            Approve
                                        </button>

                                        <button type="submit"
                                            formaction="<?= base_url('staff/appointment/reject/' . $appointment->id) ?>"
                                            formmethod="post"
                                            class="btn reject-btn w-50">
                                            Reject
                                        </button>

                                    <?php elseif (session()->get('role') === 'admin'): ?>

                                        <button type="submit"
                                            formaction="<?= base_url('admin/appointment/approve/' . $appointment->id) ?>"
                                            formmethod="post"
                                            class="btn btn-success w-50">
                                            Approve
                                        </button>

                                        <button type="submit"
                                            formaction="<?= base_url('admin/appointment/reject/' . $appointment->id) ?>"
                                            formmethod="post"
                                            class="btn btn-danger w-50">
                                            Reject
                                        </button>

                                    <?php endif; ?>

                                </div>

                            <?php endif; ?>

                            <!-- < -->
                            <!-- ?php if ( -->
                            <!-- // $isView && -->
                            <!-- // session()->get('isLoggedIn') && -->
                            <!-- // session()->get('role') === 'security' -->
                            <!-- // in_array(session()->get('role'), ['security']) -->
                            <!-- ): -->
                            <!-- ?> -->
                            <?php if (session()->get('role') === 'security' && $appointment && $mode !== 'security_view'): ?>

                                <div class="mt-3 d-flex gap-2">

                                    <?php if (($appointment->entry_status === 'Waiting' || empty($appointment->entry_status)) && ($time_valid ?? true)): ?>
                                        <a href="<?= base_url('security/checkin/' . $appointment->id) ?>"
                                            class="btn btn-success w-50">
                                            Check-In
                                        </a>

                                    <?php elseif ($appointment->entry_status === 'Entered' && session()->get('qr_action') === 'checkout'): ?>

                                        <a href="<?= base_url('security/checkout/' . $appointment->id) ?>"
                                            class="btn btn-danger w-50">
                                            Check-Out
                                        </a>

                                    <?php endif; ?>

                                </div>

                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (!$isView): ?>

        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

        <script>
            flatpickr("#appointment_date", {
                dateFormat: "Y-m-d",
                minDate: "today",
                onChange: loadSlots
            });

            document.getElementById("staffSelect").addEventListener("change", loadSlots);
            window.onload = function () {
                loadSlots();
            };

            function loadSlots() {

                let date = document.getElementById("appointment_date").value;
                let staff = document.getElementById("staffSelect").value;
                let container = document.getElementById("slotsContainer");

                if (!date || !staff) {

                    container.innerHTML = '<span class="text-muted">Select staff and date to see available slots</span>';

                    return;

                }

                fetch("<?= base_url('appointment/available-slots') ?>", {

                        method: "POST",
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: "date=" + date + "&emp_code=" + staff

                    })

                    .then(res => res.json())

                    .then(data => {

                        container.innerHTML = "";

                        data.slots.forEach(slot => {

                            let btn = document.createElement("button");

                            btn.type = "button";
                            btn.className = "btn btn-sm slot-btn";

                            if (data.booked.includes(slot)) {

                                btn.className += " btn-danger";
                                btn.innerText = slot + " Booked";
                                btn.disabled = true;

                            } else {

                                btn.className += " btn-success";
                                btn.innerText = slot;

                                btn.onclick = function() {

                                    document.getElementById("appointment_time").value = slot;

                                };

                            }

                            container.appendChild(btn);

                        });

                    });

            }
        </script>

    <?php endif; ?>

</body>

</html>