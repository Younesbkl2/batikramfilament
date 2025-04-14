<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | SARL Batikram</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(45deg, #1a1a1a, #2c2c2c);
            color: #ffffff;
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }

        /* Fade-in animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .glass-card {
            animation: fadeIn 1s ease-out forwards;
            /* existing glass-card styles */
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px) saturate(180%);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 2.5rem;
            max-width: 600px;
            text-align: center;
            position: relative;
            transform: translateY(0);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            margin: 20px;
        }

        /* Particle animations */
        @keyframes float {
            0%, 100% {
                transform: translate(0, 0) rotate(0deg);
            }
            25% {
                transform: translate(100px, 50px) rotate(180deg);
            }
            50% {
                transform: translate(-50px, 100px) rotate(360deg);
            }
            75% {
                transform: translate(-100px, -50px) rotate(540deg);
            }
        }

        .decorative-circle {
            position: absolute;
            background: radial-gradient(circle, rgba(255,157,0,0.1) 0%, transparent 70%);
            border-radius: 50%;
            z-index: -1;
            opacity: 0.6;
            animation: float 20s infinite linear;
        }

        footer {
            animation: fadeIn 1s ease-out 0.5s forwards;
            opacity: 0;
            /* existing footer styles */
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            padding: 1rem;
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(5px);
            font-size: 0.875rem;
            color: rgba(255, 255, 255, 0.5);
        }

        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
        }

        .btn-gradient {
            background: linear-gradient(135deg, #ff9d00, #ff6b00);
            transition: all 0.3s ease;
            padding: 12px 28px;
            border-radius: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            color: #fff;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            position: relative;
            overflow: hidden;
        }

        .btn-gradient::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                120deg,
                transparent,
                rgba(255, 255, 255, 0.2),
                transparent
            );
            transition: 0.5s;
        }

        .btn-gradient:hover::before {
            left: 100%;
        }

        /* Enlarged logo */
    .logo {
        width: 260px;
        height: auto;
        margin: 0 auto 1.5rem;
        filter: drop-shadow(0 4px 8px rgba(255, 157, 0, 0.2));
        transition: transform 0.3s ease, filter 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .logo::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(
            120deg,
            transparent,
            rgba(255, 255, 255, 0.2),
            transparent
        );
        transition: 0.5s;
    }

    .logo:hover {
        transform: scale(1.05);
        filter: drop-shadow(0 6px 12px rgba(255, 157, 0, 0.4));
    }

    .logo:hover::before {
        left: 100%;
    }

        /* Removed title styles */

        /* Adjusted subtitle positioning */
        .subtitle {
            font-size: 1.35rem; /* Slightly larger */
            color: rgba(255, 255, 255, 0.85);
            margin-bottom: 1.5rem; /* Reduced bottom margin */
            font-weight: 300;
            margin-top: -0.5rem; /* Pull up closer to logo */
            letter-spacing: 0.5px;
        }

        .login-text {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 2rem;
            position: relative;
        }

        .login-text::after {
            content: '';
            width: 60px;
            height: 2px;
            background: linear-gradient(90deg, transparent, #ff9d00, transparent);
            position: absolute;
            bottom: -12px;
            left: 50%;
            transform: translateX(-50%);
        }

        .decorative-circle {
            position: absolute;
            background: radial-gradient(circle, rgba(255,157,0,0.15) 0%, transparent 70%);
            border-radius: 50%;
            z-index: -1;
        }

        .bx {
            font-size: 1.2em;
            vertical-align: middle;
        }

        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            padding: 1rem;
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(5px);
            font-size: 0.875rem;
            color: rgba(255, 255, 255, 0.5);
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">
    <div class="glass-card">
        <img src="{{ asset('images/batikram_logo.png') }}" alt="SARL Batikram Logo" class="logo">
        <p class="subtitle">Gestion Immobilière</p>
        <p class="login-text">Sélectionnez votre type de connexion</p>
        <div class="flex flex-col sm:flex-row justify-center gap-5">
            <a href="{{ url('/admin/login') }}" class="btn-gradient">
                <i class='bx bxs-user'></i>
                Espace Admin
            </a>
            <a href="{{ url('/app/login') }}" class="btn-gradient">
                <i class='bx bxs-user'></i>
                Espace Utilisateur
            </a>
        </div>
    </div>

    <footer>
        © 2025 SARL Batikram. Tous droits réservés.
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            createCircles(30);
        });

        function createCircles(count) {
            const container = document.body;
            for (let i = 0; i < count; i++) {
                const circle = document.createElement('div');
                circle.className = 'decorative-circle';
                
                // Random size between 20px and 80px
                const size = Math.random() * 60 + 20;
                circle.style.width = `${size}px`;
                circle.style.height = `${size}px`;
                
                // Random starting position
                circle.style.left = `${Math.random() * 100}%`;
                circle.style.top = `${Math.random() * 100}%`;
                
                // Random animation properties
                const duration = Math.random() * 20 + 10; // 10-30 seconds
                const delay = Math.random() * -20; // Random start point
                circle.style.animation = `float ${duration}s ${delay}s infinite linear`;
                
                container.appendChild(circle);
            }
        }
    </script>
</body>
</html>