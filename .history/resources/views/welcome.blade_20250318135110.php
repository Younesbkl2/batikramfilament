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
            backdrop-filter: blur(10px);
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Button Hover Effects */
        .btn-hover:hover {
            transform: scale(1.05);
            transition: transform 0.2s ease-in-out;
        }
    </style>
</head>
<body class="bg-gradient text-white min-h-screen flex items-center justify-center">

    <div class="glass-card p-10 text-center max-w-lg w-full">
        <!-- Logo -->
        <img src="{{ asset('images/batikram_logo.png') }}" alt="SARL Batikram Logo" 
             class="mx-auto mb-6 w-24 h-auto transition-transform duration-300 hover:scale-110">

        <!-- Company Name -->
        <h1 class="text-4xl font-bold mb-1">SARL Batikram</h1>
        <p class="text-lg text-gray-200 mb-6">
            Gestion Immobilière
        </p>

        <p class="text-lg text-gray-300 mb-6">
            Please select your login type.
        </p>

        <!-- Login Buttons -->
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ url('/admin/login') }}" 
               class="px-6 py-2 text-lg font-semibold text-white bg-blue-500 hover:bg-blue-600 rounded-full shadow-lg btn-hover">
                Login (Admin)
            </a>

            <a href="{{ url('/app/login') }}" 
               class="px-6 py-2 text-lg font-semibold text-white bg-green-500 hover:bg-green-600 rounded-full shadow-lg btn-hover">
                Login (User)
            </a>
        </div>
    </div>

</body>
</html>
