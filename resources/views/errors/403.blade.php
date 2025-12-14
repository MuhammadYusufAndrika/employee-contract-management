<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Access Forbidden</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
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
            background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
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
            color: #ffc107;
            margin-bottom: 20px;
            animation: shake 3s ease-in-out infinite;
        }

        @keyframes shake {
            0%, 100% {
                transform: rotate(0deg);
            }
            25% {
                transform: rotate(-10deg);
            }
            75% {
                transform: rotate(10deg);
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
            background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
            color: #333;
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
            box-shadow: 0 8px 25px rgba(255, 193, 7, 0.4);
        }

        .btn-primary-custom:hover {
            background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
            color: #333;
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(255, 193, 7, 0.5);
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
            color: #dc3545;
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
    </style>
</head>
<body>
    <div class="floating-shapes">
        <i class="bi bi-shield-x shape" style="font-size: 15rem;"></i>
        <i class="bi bi-lock-fill shape" style="font-size: 12rem;"></i>
        <i class="bi bi-exclamation-octagon shape" style="font-size: 18rem;"></i>
    </div>

    <div class="error-container">
        <div class="error-icon">
            <i class="bi bi-shield-lock"></i>
        </div>
        <h1 class="error-code">403</h1>
        <h2 class="error-title">Access Forbidden</h2>
        <p class="error-message">
            You don't have permission to access this resource.<br>
            Please contact your administrator if you believe this is an error.
        </p>
        <div class="error-actions">
            <a href="{{ url('/dashboard') }}" class="btn-primary-custom">
                <i class="bi bi-house-door"></i>
                Go to Dashboard
            </a>
            <a href="javascript:history.back()" class="btn-secondary-custom">
                <i class="bi bi-arrow-left"></i>
                Go Back
            </a>
        </div>
    </div>
</body>
</html>
