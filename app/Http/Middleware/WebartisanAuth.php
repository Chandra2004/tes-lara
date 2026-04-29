<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class WebartisanAuth
{
    /**
     * Handle an incoming request.
     *
     * Protects Webartisan routes with a password key.
     * Uses encrypted cookies instead of sessions so it works
     * even when the database is empty (no sessions table).
     */
    public function handle(Request $request, Closure $next): Response
    {
        $passwordHash = config('webartisan.password');

        // If no password is configured, block access entirely in production
        if (empty($passwordHash)) {
            if (app()->environment('production')) {
                abort(403, 'Webartisan access is not configured.');
            }
            return $next($request);
        }

        // Check if already authenticated via cookie
        $cookieValue = $request->cookie('webartisan_auth');
        if ($cookieValue === hash('sha256', $passwordHash . config('app.key'))) {
            return $next($request);
        }

        // Check query parameter key (direct URL access)
        if ($request->has('key') && Hash::check($request->query('key'), $passwordHash)) {
            // Mengatur durasi ke 0 menjadikannya "Session Cookie" yang akan terhapus saat browser ditutup
            $cookie = cookie('webartisan_auth', hash('sha256', $passwordHash . config('app.key')), 0); 
            return redirect()->to($request->url())->withCookie($cookie);
        }

        // Show login form
        return $this->showLoginForm($request);
    }

    /**
     * Render a simple password form for Webartisan access.
     */
    protected function showLoginForm(Request $request): Response
    {
        $prefix = config('webartisan.route_prefix', 'webartisan');
        $error = $request->query('error') ? '<div class="error">Password salah. Coba lagi.</div>' : '';

        $html = <<<HTML
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webartisan - Akses Terkunci</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #0f0f1a;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: #e0e0e0;
        }
        .container {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 48px 40px;
            width: 100%;
            max-width: 420px;
            backdrop-filter: blur(20px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
        }
        .icon {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            font-size: 24px;
        }
        h1 {
            text-align: center;
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #fff;
        }
        .subtitle {
            text-align: center;
            font-size: 14px;
            color: #888;
            margin-bottom: 32px;
        }
        .error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #f87171;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 20px;
            text-align: center;
        }
        label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: #aaa;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        input[type="password"] {
            width: 100%;
            padding: 12px 16px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 10px;
            color: #fff;
            font-size: 15px;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        input[type="password"]:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }
        button {
            width: 100%;
            padding: 12px;
            margin-top: 20px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.2s, transform 0.1s;
        }
        button:hover { opacity: 0.9; }
        button:active { transform: scale(0.98); }
        .footer {
            text-align: center;
            margin-top: 24px;
            font-size: 12px;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">🔒</div>
        <h1>Webartisan</h1>
        <p class="subtitle">Masukkan password untuk mengakses terminal</p>
        {$error}
        <form method="GET" action="/{$prefix}">
            <label for="password">Password</label>
            <input type="password" id="password" name="key" placeholder="••••••••" autofocus required>
            <button type="submit">Buka Akses</button>
        </form>
        <p class="footer">Akses dilindungi • Webartisan</p>
    </div>
</body>
</html>
HTML;

        return response($html, 401);
    }
}
