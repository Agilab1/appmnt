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
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Appointment Date</label>
                                <input type="date" name="appointment_date"
                                    class="form-control form-control-lg"
                                    required>
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