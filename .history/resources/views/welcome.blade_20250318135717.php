<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | SARL Batikram</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Dark Mode */
        .dark-mode {
            background: #1C1C1C;
            color: #FACC15; /* Filament Yellow */
        }

        /* Light Mode */
        .light-mode {
            background: #FFFFFF;
            color: #FACC15; /* Filament Yellow */
        }

        /* Glassmorphism Card */
        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(14px);
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            padding: 3rem;
            max-width: 560px; /* Adjusted for balance */
        }

        /* Text Readability */
        .text-muted {
            color: rgba(255, 255, 255, 0.9);
        }

        /* Gradient Buttons */
        .btn-gradient {
            background: linear-gradient(135deg, #FACC15, #EAB308); /* Filament Yellow */
            transition: all 0.3s ease-in-out;
            padding: 12px 24px;
            border-radius: 30px;
            font-weight: 600;
            text-transform: uppercase;
            color: #1C1C1C; /* Dark text on yellow */
            box-shadow: 0 4px 10px rgba(250, 204, 21, 0.3);
        }
        
        .btn-gradient:hover {
            background: linear-gradient(135deg, #EAB308, #D97706); /* Slightly darker yellow */
            transform: scale(1.05);
            box-shadow: 0 6px 15px rgba(250, 204, 21, 0.5);
        }
    </style>
</head>
<body class="dark-mode min-h-screen flex items-center justify-center">

    <div class="glass-card text-center w-full">
        <!-- Logo -->
        <img src="{{ asset('images/batikram_logo.png') }}" alt="SARL Batikram Logo" 
             class="mx-auto mb-6 w-40 h-auto transition-transform duration-300 hover:scale-110">

        <!-- Company Name -->
        <h1 class="text-4xl font-extrabold text-muted mb-2">SARL Batikram</h1>
        <p class="text-lg text-gray-400 mb-6">
            Gestion Immobilière
        </p>

        <p class="text-md text-gray-500 mb-6">
            Please select your login type.
        </p>

        <!-- Login Buttons -->
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ url('/admin/login') }}" 
               class="btn-gradient">
                Login (Admin)
            </a>

            <a href="{{ url('/app/login') }}" 
               class="btn-gradient">
                Login (User)
            </a>
        </div>
    </div>

</body>
</html>
