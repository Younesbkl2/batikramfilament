<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100">

    <div class="flex items-center justify-center min-h-screen">
        <div class="text-center">
            <!-- Logo -->
            <img src="{{ asset('images/batikram_logo.png') }}" alt="SARL Batikram Logo" class="mx-auto mb-4 w-32 h-auto">

            <!-- Company Name -->
            <h1 class="text-4xl font-bold mb-2">SARL Batikram</h1>
            <p class="text-lg text-gray-600 dark:text-gray-400 mb-8">
                Gestion Immobilière
            </p>

            <p class="text-lg text-gray-600 dark:text-gray-400 mb-6">
                Please select your login type.
            </p>

            <!-- Login Buttons -->
            <div class="flex justify-center gap-4">
                <a href="{{ url('/admin/login') }}" 
                   class="px-6 py-2 text-lg font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-md">
                    Login (Admin)
                </a>

                <a href="{{ url('/app/login') }}" 
                   class="px-6 py-2 text-lg font-semibold text-white bg-green-600 hover:bg-green-700 rounded-lg shadow-md">
                    Login (User)
                </a>
            </div>
        </div>
    </div>

</body>
</html>
