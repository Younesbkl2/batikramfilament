<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | SARL Batikram</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Gradient Background */
        .bg-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        /* Glassmorphism Card */
        .glass-card {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(12px);
            border-radius: 16px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
            padding: 4rem;
        }

        /* Button Hover Effects */
        .btn-hover:hover {
            transform: scale(1.05);
            transition: transform 0.2s ease-in-out;
        }
    </style>
</head>
<body class="bg-gradient text-white min-h-screen flex items-center justify-center">

    <div class="glass-card text-center max-w-2xl w-full">
        <!-- Logo -->
        <img src="{{ asset('images/batikram_logo.png') }}" alt="SARL Batikram Logo" 
             class="mx-auto mb-8 w-60 h-auto transition-transform duration-300 hover:scale-110">

        <!-- Company Name -->
        <h1 class="text-5xl font-extrabold mb-3">SARL Batikram</h1>
        <p class="text-2xl text-gray-200 mb-8">
            Gestion Immobilière
        </p>

        <p class="text-xl text-gray-300 mb-8">
            Please select your login type.
        </p>

        <!-- Login Buttons -->
        <div class="flex flex-col sm:flex-row justify-center gap-6">
            <a href="{{ url('/admin/login') }}" 
               class="px-8 py-3 text-xl font-semibold text-white bg-blue-500 hover:bg-blue-600 rounded-full shadow-lg btn-hover">
                Login (Admin)
            </a>

            <a href="{{ url('/app/login') }}" 
               class="px-8 py-3 text-xl font-semibold text-white bg-green-500 hover:bg-green-600 rounded-full shadow-lg btn-hover">
                Login (User)
            </a>
        </div>
    </div>

</body>
</html>
