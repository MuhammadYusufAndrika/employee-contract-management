<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <style>
            :root {
                --primary-color: #003DA5;
                --secondary-color: #FF6B00;
                --dark-blue: #002060;
                --light-gray: #F8F9FA;
            }

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Poppins', sans-serif;
                height: 100vh;
                overflow: hidden;
            }

            .auth-container {
                display: flex;
                height: 100vh;
                width: 100%;
            }

            .auth-left {
                flex: 1;
                display: flex;
                align-items: center;
                justify-content: center;
                background: white;
                padding: 40px 20px;
                overflow-y: auto;
                min-height: 100vh;
            }

            .auth-right {
                flex: 1;
                background: url('https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=2070') center/cover;
                position: relative;
                min-width: 50%;
            }

            .auth-right::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: linear-gradient(135deg, rgba(0, 61, 165, 0.7) 0%, rgba(0, 32, 96, 0.8) 100%);
            }

            .auth-box {
                width: 100%;
                max-width: 450px;
                background: white;
                border-radius: 20px;
                padding: 40px;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            }

            .logo-container {
                display: flex;
                align-items: center;
                gap: 15px;
                margin-bottom: 30px;
            }

            .logo-shield {
                width: 60px;
                height: 60px;
                background: linear-gradient(135deg, var(--primary-color), var(--dark-blue));
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 28px;
                border: 3px solid var(--secondary-color);
                flex-shrink: 0;
            }

            .logo-text {
                flex: 1;
            }

            .logo-title {
                font-size: 32px;
                font-weight: 700;
                color: var(--dark-blue);
                letter-spacing: 2px;
                margin: 0;
                line-height: 1.2;
            }

            .logo-subtitle {
                font-size: 11px;
                color: #666;
                margin: 0;
                text-transform: uppercase;
                letter-spacing: 1px;
            }

            .auth-subtitle {
                color: var(--primary-color);
                font-size: 14px;
                font-weight: 500;
                margin-bottom: 30px;
                text-align: center;
            }

            .form-label {
                font-weight: 600;
                color: #333;
                margin-bottom: 8px;
                font-size: 14px;
            }

            .form-control {
                border: 1px solid #ddd;
                border-radius: 8px;
                padding: 12px 16px;
                font-size: 14px;
                transition: all 0.3s;
            }

            .form-control:focus {
                border-color: var(--primary-color);
                box-shadow: 0 0 0 3px rgba(0, 61, 165, 0.1);
            }

            .password-input-wrapper {
                position: relative;
            }

            .password-toggle {
                position: absolute;
                right: 12px;
                top: 50%;
                transform: translateY(-50%);
                background: none;
                border: none;
                color: #999;
                cursor: pointer;
                padding: 5px;
            }

            .password-toggle:hover {
                color: var(--primary-color);
            }

            .forgot-link {
                color: var(--primary-color);
                text-decoration: none;
                font-size: 13px;
                font-weight: 500;
                display: inline-block;
                margin-top: 10px;
            }

            .forgot-link:hover {
                color: var(--dark-blue);
                text-decoration: underline;
            }

            .alert-warning {
                background: #FFF4E5;
                border: 1px solid #FFE5CC;
                border-radius: 8px;
                padding: 12px 15px;
                font-size: 13px;
                color: #856404;
                margin-bottom: 20px;
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .alert-icon {
                width: 24px;
                height: 24px;
                background: var(--secondary-color);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-weight: bold;
                font-size: 16px;
                flex-shrink: 0;
            }

            .btn-login {
                background: linear-gradient(135deg, #004aad 0%, var(--primary-color) 100%);
                border: none;
                border-radius: 8px;
                color: white;
                font-weight: 600;
                padding: 14px;
                width: 100%;
                font-size: 16px;
                transition: all 0.3s;
                margin-top: 10px;
            }

            .btn-login:hover {
                background: linear-gradient(135deg, var(--primary-color) 0%, var(--dark-blue) 100%);
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(0, 61, 165, 0.3);
            }

            .partner-logos {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 25px;
                padding-bottom: 20px;
                border-bottom: 1px solid #eee;
            }

            .partner-logo {
                max-height: 35px;
                opacity: 0.8;
            }

            .register-link {
                text-align: center;
                margin-top: 20px;
                font-size: 14px;
                color: #666;
            }

            .register-link a {
                color: var(--primary-color);
                text-decoration: none;
                font-weight: 600;
            }

            .register-link a:hover {
                text-decoration: underline;
            }

            .invalid-feedback {
                color: #dc3545;
                font-size: 13px;
                margin-top: 5px;
            }

            .form-check-input:checked {
                background-color: var(--primary-color);
                border-color: var(--primary-color);
            }

            .form-check-label {
                font-size: 14px;
                color: #666;
            }

            /* Responsive Design */
            @media (max-width: 992px) {
                .auth-right {
                    display: none;
                }

                .auth-left {
                    flex: 1;
                    width: 100%;
                }

                .auth-box {
                    max-width: 500px;
                }
            }

            @media (max-width: 768px) {
                body {
                    overflow-y: auto;
                }

                .auth-container {
                    min-height: 100vh;
                    height: auto;
                }

                .auth-left {
                    padding: 30px 20px;
                    min-height: 100vh;
                }

                .auth-box {
                    padding: 30px 25px;
                    box-shadow: none;
                    border-radius: 0;
                }

                .logo-title {
                    font-size: 24px;
                    letter-spacing: 1px;
                }

                .logo-shield {
                    width: 50px;
                    height: 50px;
                    font-size: 24px;
                }

                .logo-subtitle {
                    font-size: 10px;
                }

                .auth-subtitle {
                    font-size: 13px;
                    margin-bottom: 25px;
                }

                .partner-logos {
                    flex-direction: column;
                    gap: 15px;
                    align-items: flex-start;
                }

                .partner-logos > div {
                    width: 100%;
                }
            }

            @media (max-width: 576px) {
                .auth-left {
                    padding: 20px 15px;
                }

                .auth-box {
                    padding: 25px 20px;
                }

                .logo-container {
                    gap: 10px;
                    margin-bottom: 25px;
                }

                .logo-title {
                    font-size: 20px;
                }

                .logo-shield {
                    width: 45px;
                    height: 45px;
                    font-size: 20px;
                    border: 2px solid var(--secondary-color);
                }

                .form-control {
                    padding: 10px 14px;
                    font-size: 13px;
                }

                .btn-login {
                    padding: 12px;
                    font-size: 15px;
                }

                .alert-warning {
                    font-size: 12px;
                    padding: 10px 12px;
                }

                .alert-icon {
                    width: 20px;
                    height: 20px;
                    font-size: 14px;
                }

                .partner-logos span {
                    font-size: 12px !important;
                }
            }

            @media (max-width: 400px) {
                .logo-title {
                    font-size: 18px;
                }

                .logo-shield {
                    width: 40px;
                    height: 40px;
                    font-size: 18px;
                }

                .auth-box {
                    padding: 20px 15px;
                }
            }

            /* Landscape mobile fix */
            @media (max-height: 600px) and (orientation: landscape) {
                body {
                    overflow-y: auto;
                }

                .auth-container {
                    height: auto;
                    min-height: 100vh;
                }

                .auth-left {
                    padding: 20px;
                    min-height: auto;
                }

                .auth-box {
                    padding: 20px;
                    margin: 20px 0;
                }

                .logo-container {
                    margin-bottom: 20px;
                }

                .auth-subtitle {
                    margin-bottom: 20px;
                }

                .partner-logos {
                    margin-bottom: 15px;
                    padding-bottom: 15px;
                }
            }
        </style>
    </head>
    <body>
        {{ $slot }}

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
