<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Book Appointment</title>

    <!-- Mobile responsive MUST -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center min-vh-100">

    <div class="container px-3">                         
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-6 col-lg-5">

                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white text-center">
                        <h5 class="mb-0">Book Appointment</h5>
                    </div>

                    <div class="card-body p-4">
                        <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success shadow-sm border-0">
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>



                        <form method="post" action="<?= base_url('appointment/submit') ?>">
                            <?= csrf_field() ?>

                            <input type="hidden" name="admin_id" value="<?= $admin_id ?>">

                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name"
                                    class="form-control form-control-lg"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Mobile</label>
                                <input type="text" name="mobile"
                                    class="form-control form-control-lg"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email"
                                    class="form-control form-control-lg">
                          <div class="mb-3">
                            <label class="form-label">Appointment Date & Time</label>
                            <input type="datetime-local"
                                name="appointment_datetime"
                                class="form-control form-control-lg"
                                step="1800"
                                min="<?= date('Y-m-d\TH:i', ceil(time()/1800)*1800) ?>"

                                required>
                            </div>
                            <div>
                            <label class="form-label">Staff Name</label>
                            <select name="emp_code" class="form-control form-control-lg" required>
                                <option value="">Choose Staff</option>

                                <?php if (!empty($staffs)) : ?>
                                    <?php foreach ($staffs as $staff): ?>
                                        <option value="<?= esc($staff->emp_code) ?>">
                                            <?= esc($staff->first_nm . ' ' . $staff->last_nm) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            </div>


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

</body>

</html>
<!-- <script>
document.getElementById('appointment_datetime')
.addEventListener('change', function () {

    let value = this.value;
    if (!value) return;

    let dt = new Date(value);

    let minutes = dt.getMinutes();
    let rounded = Math.round(minutes / 30) * 30;

    if (rounded === 60) {
        dt.setHours(dt.getHours() + 1);
        dt.setMinutes(0);
    } else {
        dt.setMinutes(rounded);
    }

    dt.setSeconds(0);

    // FIX: format local datetime manually
    let year = dt.getFullYear();
    let month = String(dt.getMonth() + 1).padStart(2, '0');
    let day = String(dt.getDate()).padStart(2, '0');
    let hours = String(dt.getHours()).padStart(2, '0');
    let mins = String(dt.getMinutes()).padStart(2, '0');

    this.value = `${year}-${month}-${day}T${hours}:${mins}`;
});
</script> -->
