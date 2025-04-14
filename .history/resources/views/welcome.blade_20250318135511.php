<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | SARL Batikram</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Filament-Inspired Gradient Background */
        .bg-gradient {
            background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
        }

        /* Glassmorphism Card */
        .glass-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(14px);
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            padding: 3rem;
            max-width: 580px; /* Slightly smaller */
        }

        /* Text Opacity for Better Readability */
        .text-muted {
            color: rgba(255, 255, 255, 0.85);
        }

        /* Gradient Buttons */
        .btn-gradient {
            background: linear-gradient(135deg, #6366F1, #4F46E5);
            transition: all 0.3s ease-in-out;
            padding: 12px 24px;
            border-radius: 30px;
            font-weight: 600;
            text-transform: uppercase;
            box-shadow: 0 4px 10px rgba(99, 102, 241, 0.3);
        }
        
        .btn-gradient:hover {
            background: linear-gradient(135deg, #4F46E5, #3730A3);
            transform: scale(1.05);
            box-shadow: 0 6px 15px rgba(99, 102, 241, 0.5);
        }
    </style>
</head>
<body class="bg-gradient text-white min-h-screen flex items-center justify-center">

    <div class="glass-card text-center w-full">
        <!-- Logo -->
        <img src="{{ asset('images/batikram_logo.png') }}" alt="SARL Batikram Logo" 
             class="mx-auto mb-6 w-40 h-auto transition-transform duration-300 hover:scale-110">

        <!-- Company Name -->
        <h1 class="text-4xl font-extrabold text-muted mb-2">SARL Batikram</h1>
        <p class="text-lg text-gray-300 mb-6">
            Gestion Immobilière
        </p>

        <p class="text-md text-gray-400 mb-6">
            Please select your login type.
        </p>

        <!-- Login Buttons -->
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ url('/admin/login') }}" 
               class="btn-gradient text-white">
                Login (Admin)
            </a>

            <a href="{{ url('/app/login') }}" 
               class="btn-gradient text-white">
                Login (User)
            </a>
        </div>
    </div>

</body>
</html>
