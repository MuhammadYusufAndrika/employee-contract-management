<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>503 - Service Unavailable</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #003DA5 0%, #002060 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .error-container {
            text-align: center;
            color: white;
            padding: 40px;
            max-width: 700px;
            animation: fadeInUp 0.8s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .error-code {
            font-size: 10rem;
            font-weight: 900;
            line-height: 1;
            margin: 0;
            background: linear-gradient(135deg, #FF6B00 0%, #ffc107 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        .error-icon {
            font-size: 8rem;
            color: #FF6B00;
            margin-bottom: 20px;
            animation: blink 2s ease-in-out infinite;
        }

        @keyframes blink {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.3;
            }
        }

        .error-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 20px 0;
            color: white;
        }

        .error-message {
            font-size: 1.2rem;
            margin: 20px 0 40px;
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.6;
        }

        .error-actions {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #FF6B00 0%, #e55d00 100%);
            color: white;
            padding: 15px 35px;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 1.1rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(255, 107, 0, 0.4);
        }

        .btn-primary-custom:hover {
            background: linear-gradient(135deg, #e55d00 0%, #cc5200 100%);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(255, 107, 0, 0.5);
        }

        .btn-secondary-custom {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 15px 35px;
            border: 2px solid white;
            border-radius: 10px;
            font-weight: 700;
            font-size: 1.1rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .btn-secondary-custom:hover {
            background: white;
            color: #003DA5;
            transform: translateY(-3px);
        }

        .floating-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }

        .shape {
            position: absolute;
            opacity: 0.1;
            animation: float 20s ease-in-out infinite;
        }

        .shape:nth-child(1) {
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            top: 60%;
            left: 80%;
            animation-delay: 5s;
        }

        .shape:nth-child(3) {
            top: 80%;
            left: 20%;
            animation-delay: 10s;
        }

        @keyframes float {
            0%, 100% {
                transform: translate(0, 0) rotate(0deg);
            }
            33% {
                transform: translate(30px, -30px) rotate(120deg);
            }
            66% {
                transform: translate(-30px, 30px) rotate(240deg);
            }
        }

        .error-container {
            position: relative;
            z-index: 1;
        }

        .maintenance-info {
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 20px;
            margin-top: 30px;
            backdrop-filter: blur(10px);
        }

        .maintenance-info p {
            margin: 0;
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.9);
        }
    </style>
</head>
<body>
    <div class="floating-shapes">
        <i class="bi bi-cone-striped shape" style="font-size: 15rem;"></i>
        <i class="bi bi-wrench-adjustable shape" style="font-size: 12rem;"></i>
        <i class="bi bi-hourglass-split shape" style="font-size: 18rem;"></i>
    </div>

    <div class="error-container">
        <div class="error-icon">
            <i class="bi bi-cone-striped"></i>
        </div>
        <h1 class="error-code">503</h1>
        <h2 class="error-title">Service Unavailable</h2>
        <p class="error-message">
            We're currently performing scheduled maintenance.<br>
            We'll be back online shortly. Thank you for your patience.
        </p>
        
        <div class="maintenance-info">
            <p><strong>Estimated Time:</strong> The service will be available soon.</p>
        </div>

        <div class="error-actions" style="margin-top: 40px;">
            <a href="javascript:location.reload()" class="btn-primary-custom">
                <i class="bi bi-arrow-clockwise"></i>
                Refresh Page
            </a>
        </div>
    </div>
</body>
</html>
