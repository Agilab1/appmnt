<?php $type = $type ?? 'expired'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Appointment Status</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center justify-content-center" style="height:100vh;">

    <div class="card shadow-lg border-0 text-center p-4" style="max-width:400px; width:100%; border-radius:15px;">

        <!-- Icon -->
        <div class="mb-3">
            <?php if ($type == 'expired'): ?>
                <h1 style="font-size:50px;">❌</h1>
            <?php else: ?>
                <h1 style="font-size:50px;">⏰</h1>
            <?php endif; ?>
        </div>

        <!-- Title -->
        <h4 class="mb-3">
            <?php if ($type == 'expired'): ?>
                Appointment Expired
            <?php else: ?>
                Too Early
            <?php endif; ?>
        </h4>

        <!-- Message -->
        <p class="text-muted">
            <?= $msg ?>
        </p>

    </div>

</body>
</html>