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
    </style>
</head>

<body class="bg-light d-flex align-items-center min-vh-100">

    <div class="container px-3">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-6 col-lg-5">

                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white text-center">
                        <h5 class="mb-0">Book Appointment</h5>
                    </div>

                    <div class="card-body p-4  ">

                        <?php if (session()->getFlashdata('success')): ?>
                            <div class="alert alert-success auto-flash">
                                <?= nl2br(session()->getFlashdata('success')) ?>
                            </div>
                        <?php endif; ?>

                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger auto-flash">
                                <?= session()->getFlashdata('error') ?>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="<?= base_url('appointment/submit') ?>">
                            <?= csrf_field() ?>

                            <input type="hidden" name="admin_id" value="<?= $admin_id ?>">

                            <!-- Visitor ID
<div class="mb-3">
<label class="form-label">Visitor ID</label>
<input type="text"
name="visitor_id"
value="<?= $visitor_id ?>"
class="form-control form-control-lg"
readonly>
</div> -->

                            <!-- Name -->
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control form-control-lg" required>
                            </div>

                            <!-- Mobile -->
                            <div class="mb-3">
                                <label class="form-label">Mobile</label>
                                <input type="text" name="mobile" class="form-control form-control-lg" required>
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control form-control-lg">
                            </div>

                            <!-- Date -->
                            <div class="mb-3">
                                <label class="form-label">Appointment Date</label>

                                <div class="input-group">
                                    <input type="text"
                                        id="appointment_date"
                                        name="appointment_date"
                                        class="form-control form-control-lg"
                                        placeholder="Select Date"
                                        required>

                                    <span class="input-group-text" style="cursor:pointer;" onclick="document.getElementById('appointment_date').click();">
                                        📅
                                    </span>
                                </div>
                            </div>




                            <!-- Time + Duration -->
                            <div class="mb-3">
                                <div class="row">

                                    <!-- TIME -->
                                    <div class="col-md-6">
                                        <label class="form-label">Time</label>
                                        <input type="text"
                                            id="appointment_time"
                                            name="appointment_time"
                                            class="form-control form-control-lg"
                                            placeholder="Select Time"
                                            required>
                                    </div>

                                    <!-- Duration -->
                                    <div class="col-md-6">
                                        <label class="form-label">Duration</label>

                                        <div class="inline-wrapper">

                                            <input type="number"
                                                name="duration_hour"
                                                class="form-control spinner-input"
                                                min="1"
                                                max="12"
                                                value="1"
                                                required>

                                            <span class="unit-text">hr</span>

                                            <input type="number"
                                                name="duration_minute"
                                                class="form-control spinner-input"
                                                min="0"
                                                max="30"
                                                step="30"
                                                value="0"
                                                required>

                                            <span class="unit-text">min</span>

                                        </div>
                                    </div>

                                </div>
                            </div>

                            <!-- Staff -->
                            <div class="mb-3">
                                <label class="form-label">Staff Name</label>
                                <select name="emp_code" class="form-control form-control-lg" required>
                                    <option value="">Choose Staff</option>
                                    <?php if (!empty($staffs)): ?>
                                        <?php foreach ($staffs as $staff): ?>
                                            <option value="<?= esc($staff->emp_code) ?>">
                                                <?= esc($staff->first_nm . ' ' . $staff->last_nm) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <!-- Purpose -->
                            <div class="mb-4">
                                <label class="form-label">Purpose</label>
                                <textarea name="purpose"
                                    rows="3"
                                    class="form-control form-control-lg"
                                    required></textarea>
                            </div>

                            <button class="btn btn-primary btn-lg w-100">
                                Submit Appointment
                            </button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        /* CLEAN DATE PICKER */
        flatpickr("#appointment_date", {
            dateFormat: "Y-m-d",
            minDate: "today",
            defaultDate: new Date(),
            disableMobile: true,

            onDayCreate: function(dObj, dStr, fp, dayElem) {

                // Sunday only red
                if (dayElem.dateObj.getDay() === 0) {
                    dayElem.style.color = "#dc3545";
                    dayElem.style.fontWeight = "600";
                }
            }
        });

        /* TIME PICKER */
        flatpickr("#appointment_time", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "h:i K",
            minuteIncrement: 30,
            time_24hr: false,
            defaultDate: "12:00 PM"
        });

        /* Duration minute only 00 or 30 */
        document.querySelector("[name='duration_minute']")
            .addEventListener("input", function() {
                if (this.value != 0 && this.value != 30) {
                    this.value = 0;
                }
            });
    </script>

</body>

</html>