<!DOCTYPE html>
<html>

<head>
    <title>QR Scanner</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- QR LIB -->
    <script src="https://unpkg.com/html5-qrcode"></script>

    <style>
        body {
            background: linear-gradient(135deg, #eef2f7, #e0e7ff);
            font-family: 'Segoe UI', sans-serif;
        }

        /* WRAPPER */
        .scanner-wrapper {
            max-width: 420px;
            margin: auto;
            margin-top: 70px;
        }

        /* CARD */
        .scanner-card {
            border-radius: 20px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
        }

        /* HEADER */
        .scanner-header {
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            color: white;
            padding: 18px;
            text-align: center;
            font-weight: 600;
            font-size: 18px;
            letter-spacing: 0.5px;
        }

        /* SCANNER */
        #reader {
            width: 100% !important;
            border-radius: 15px;
            overflow: hidden;
        }

        /* INFO TEXT */
        .scanner-info {
            font-size: 13px;
            color: #6b7280;
            text-align: center;
            margin-top: 12px;
        }

        /* GLOW EFFECT */
        .scanner-glow {
            border-radius: 15px;
            padding: 5px;
            background: linear-gradient(135deg, #6366f1, #4f46e5);
        }
    </style>

</head>

<body>

    <div class="container">

        <div class="scanner-wrapper">

            <div class="scanner-card border-0">

                <!-- HEADER -->
                <div class="scanner-header">
                    📷 Scan Visitor QR Code
                </div>

                <!-- BODY -->
                <div class="card-body p-3">

                    <div class="scanner-glow">
                        <div id="reader"></div>
                    </div>

                    <div class="scanner-info">
                        Align QR code inside the box to scan
                    </div>

                </div>

            </div>

        </div>

    </div>

    <script>
        function onScanSuccess(decodedText) {
            window.location.href = decodedText;
        }

        new Html5QrcodeScanner("reader", {
            fps: 10,
            qrbox: 250
        }).render(onScanSuccess);
    </script>

</body>

</html>