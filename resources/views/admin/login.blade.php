<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — ePaper Parlementaria</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #0a0a0f;
            color: #fff;
            position: relative;
            overflow: hidden;
        }

        /* Animated gradient orbs */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            opacity: 0.3;
            animation: float 8s ease-in-out infinite;
        }
        .orb-1 {
            width: 400px; height: 400px;
            background: #B3131B;
            top: -100px; left: -100px;
        }
        .orb-2 {
            width: 500px; height: 500px;
            background: #B3131B;
            bottom: -150px; right: -150px;
            opacity: 0.15;
            animation-delay: 4s;
        }
        .orb-3 {
            width: 200px; height: 200px;
            background: #ff4444;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.08;
            animation-delay: 2s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) scale(1); }
            50% { transform: translateY(-30px) scale(1.05); }
        }

        /* Grid pattern overlay */
        .grid-pattern {
            position: absolute;
            inset: 0;
            background-image: 
                linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
            background-size: 60px 60px;
        }

        .login-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 420px;
            padding: 0 24px;
        }

        .logo-icon {
            width: 56px; height: 56px;
            margin: 0 auto 24px;
            background: linear-gradient(135deg, #B3131B 0%, #7a0d12 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 24px rgba(179, 19, 27, 0.3);
        }

        .header-text {
            text-align: center;
            margin-bottom: 32px;
        }
        .header-text h1 {
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.5px;
            margin-bottom: 8px;
            color: #ffffff;
        }
        .header-text p {
            font-size: 14px;
            color: #6b7280;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            padding: 32px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
        }

        .error-box {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .error-box svg { flex-shrink: 0; }
        .error-box p { font-size: 13px; color: #fca5a5; }

        .field-group { margin-bottom: 20px; }
        .field-label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #9ca3af;
            margin-bottom: 8px;
        }
        .input-wrapper {
            position: relative;
        }
        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
        }
        .field-input {
            width: 100%;
            padding: 14px 14px 14px 44px;
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: #ffffff;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .field-input::placeholder { color: #4b5563; }
        .field-input:focus {
            border-color: #B3131B;
            box-shadow: 0 0 0 3px rgba(179, 19, 27, 0.2);
        }

        .submit-btn {
            width: 100%;
            padding: 14px;
            margin-top: 8px;
            background: linear-gradient(135deg, #B3131B 0%, #8a0f15 100%);
            color: #ffffff;
            font-size: 14px;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: transform 0.15s, box-shadow 0.15s, opacity 0.15s;
            box-shadow: 0 4px 16px rgba(179, 19, 27, 0.35);
            letter-spacing: 0.3px;
        }
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(179, 19, 27, 0.45);
        }
        .submit-btn:active {
            transform: translateY(0);
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 28px;
            font-size: 12px;
            color: #6b7280;
            text-decoration: none;
            transition: color 0.2s;
        }
        .back-link:hover { color: #d1d5db; }

        .footer-text {
            text-align: center;
            margin-top: 48px;
            font-size: 11px;
            color: #374151;
        }
    </style>
</head>
<body>
    <!-- Background Effects -->
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
    <div class="grid-pattern"></div>

    <div class="login-container">
        <!-- Logo -->
        <div class="logo-icon">
            <svg width="28" height="28" fill="none" stroke="#ffffff" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="3" y1="9" x2="21" y2="9"></line>
                <line x1="9" y1="21" x2="9" y2="9"></line>
            </svg>
        </div>

        <!-- Header -->
        <div class="header-text">
            <h1>Portal Admin</h1>
            <p>ePaper Parlementaria DPRD Kota Tangerang</p>
        </div>

        <!-- Login Card -->
        <div class="glass-card">
            <form action="{{ route('admin.login') }}" method="POST">
                @csrf

                @if($errors->any())
                <div class="error-box">
                    <svg width="20" height="20" fill="none" stroke="#ef4444" viewBox="0 0 24 24" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    <p>{{ $errors->first() }}</p>
                </div>
                @endif

                <div class="field-group">
                    <label class="field-label" for="username">Username</label>
                    <div class="input-wrapper">
                        <div class="input-icon">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        </div>
                        <input class="field-input" id="username" name="username" type="text" required placeholder="Masukkan username" autocomplete="username">
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label" for="password">Password</label>
                    <div class="input-wrapper">
                        <div class="input-icon">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                        </div>
                        <input class="field-input" id="password" name="password" type="password" required placeholder="Masukkan password" autocomplete="current-password">
                    </div>
                </div>

                <button type="submit" class="submit-btn">
                    Masuk ke Sistem
                </button>
            </form>
        </div>

        <a href="{{ route('home') }}" class="back-link">← Kembali ke Portal Publik</a>

        <div class="footer-text">
            &copy; {{ date('Y') }} Sekretariat DPRD Kota Tangerang
        </div>
    </div>
</body>
</html>
