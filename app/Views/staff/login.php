<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border-radius: 15px;
            padding: 30px;
            width: 100%;
            max-width: 400px;
            color: white;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .login-card h4 {
            text-align: center;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
        }

        .form-control::placeholder {
            color: #ddd;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.3);
            box-shadow: none;
            color: white;
        }

        .btn-custom {
            background: linear-gradient(135deg, #4facfe, #00f2fe);
            border: none;
            font-weight: 500;
        }

        .btn-custom:hover {
            opacity: 0.9;
        }

        .alert {
            background: rgba(255, 0, 0, 0.2);
            border: none;
            color: white;
        }

        label {
            font-size: 14px;
            margin-bottom: 5px;
        }
    </style>
</head>

<body>

    <div class="login-card">

        <h4>🔐 Login</h4>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <p class="mb-0"><?= $error ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- ✅ SAME LOGIC (UNCHANGED) -->
        <form method="post" action="<?= base_url('login') ?>">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label>Email</label>
                <input
                    type="email"
                    name="email_id"
                    class="form-control"
                    value="<?= old('email_id') ?>"
                    required>
            </div>

            <div class="mb-3">
                <label>Password</label>
                <input
                    type="password"
                    name="pass_wd"
                    class="form-control"
                    required>
            </div>

            <button type="submit" class="btn btn-custom w-100 mt-2">
                Login
            </button>
        </form>

    </div>

</body>

</html>