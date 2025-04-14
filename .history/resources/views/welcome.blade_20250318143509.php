<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | SARL Batikram</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Background Gradient */
        body {
            background: linear-gradient(to bottom, #201c1c, #000000);
            color: #ffffff;
        }

        /* Glassmorphism Card */
        .glass-card {
            background: rgba(32, 28, 28, 0.85);
            backdrop-filter: blur(5px);
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
            padding: 3rem;
            max-width: 560px;
            text-align: center;
        }

        /* Buttons */
        .btn-gradient {
            background: linear-gradient(135deg, #f89c0c, #d87c05);
            transition: all 0.3s ease-in-out;
            padding: 8px 24px;
            border-radius: 30px;
            font-weight: 600;
            text-transform: uppercase;
            color: #fff;
            box-shadow: 0 4px 10px rgba(248, 156, 12, 0.3);
        }

        .btn-gradient:hover {
            background: linear-gradient(135deg, #d87c05, #b96504);
            transform: scale(1.05);
            box-shadow: 0 6px 15px rgba(248, 156, 12, 0.5);
        }

        /* Logo */
        .logo {
            width: 150px;
            height: auto;
            margin: 0 auto 1.5rem;
            transition: transform 0.3s ease-in-out;
        }

        .logo:hover {
            transform: scale(1.1);
        }

        /* Text */
        .title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #f89c0c;
            margin-bottom: 0.5rem;
        }

        .subtitle {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 1.5rem;
        }

        .login-text {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 1rem;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">

    <div class="glass-card">
        <!-- Logo -->
        <img src="{{ asset('images/batikram_logo.png') }}" alt="SARL Batikram Logo" class="logo">

        <!-- Company Name -->
        <h1 class="title">SARL Batikram</h1>
        <p class="subtitle">Gestion Immobilière</p>

        <p class="login-text">Please select your login type.</p>

        <!-- Login Buttons -->
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ url('/admin/login') }}" class="btn-gradient">
                Login (Admin)
            </a>

            <a href="{{ url('/app/login') }}" class="btn-gradient">
                Login (User)
            </a>
        </div>
    </div>

</body>
</html>
