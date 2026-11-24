<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Arahkan pengguna terautentikasi (termasuk sesi ingat saya) ke dashboard role masing-masing
        $middleware->redirectTo(
            guests: '/login',
            users: function () {
                $user = auth()->user();
                if ($user?->role === 'murid') {
                    return route('student.dashboard');
                } elseif ($user?->role === 'admin') {
                    return route('admin.dashboard');
                }
                return route('dashboard');
            }
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Tangani kedaluwarsa sesi token CSRF (419 Page Expired) agar otomatis dialihkan kembali ke login
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, \Illuminate\Http\Request $request) {
            return redirect()->route('login')->withErrors(['email' => 'Sesi Anda telah berakhir demi keamanan. Halaman telah disegarkan, silakan masuk kembali.']);
        });
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, \Illuminate\Http\Request $request) {
            if ($e->getStatusCode() === 419) {
                return redirect()->route('login')->withErrors(['email' => 'Sesi Anda telah berakhir demi keamanan. Halaman telah disegarkan, silakan masuk kembali.']);
            }
        });
    })->create();
