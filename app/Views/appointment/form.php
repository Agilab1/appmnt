<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Book Appointment</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        .inline-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .spinner-input {
            width: 70px;
            text-align: center;
        }

        .unit-text {
            font-size: 14px;
        }

        .slot-btn {
            min-width: 85px;
            font-size: 13px;
        }

        #slotsContainer {
            min-height: 40px;
        }
    </style>

</head>

<body class="bg-light d-flex align-items-center min-vh-100">

    <?php
    $mode = $mode ?? 'create';
    $isEdit = ($mode === 'edit');
    $isView = ($mode === 'view');
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

                            <input type="hidden" name="admin_id" value="<?= $admin_id ?>">

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
                                    <?= $isView ? 'disabled' : '' ?>
                                    required>

                                    <option value="">Choose Staff</option>

                                    <?php foreach ($staffs as $staff): ?>

                                        <option value="<?= esc($staff->emp_code) ?>"
                                            <?= (isset($appointment->emp_code) && $appointment->emp_code == $staff->emp_code) ? 'selected' : '' ?>>

                                            <?= esc($staff->first_nm . ' ' . $staff->last_nm) ?>

                                        </option>

                                    <?php endforeach; ?>

                                </select>

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

<<<<<<< HEAD
=======
                            <!-- </form> -->
>>>>>>> bdbc4da6ef69054068ea2d0e7e74229fe626c7d7
                            <?php if (!$isView): ?>

                                <button class="btn btn-primary btn-lg w-100">

                                    <?= $isEdit ? 'Update Appointment' : 'Submit Appointment' ?>

                                </button>

                            <?php endif; ?>

<<<<<<< HEAD
                        </form>

=======
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
                                            class="btn btn-success w-50">
                                            Approve
                                        </button>

                                        <button type="submit"
                                            formaction="<?= base_url('staff/appointment/reject/' . $appointment->id) ?>"
                                            formmethod="post"
                                            class="btn btn-danger w-50">
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
                        </form>
>>>>>>> bdbc4da6ef69054068ea2d0e7e74229fe626c7d7
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