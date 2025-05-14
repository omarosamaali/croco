<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // استثناء مسارات API من التحقق من CSRF
        $middleware->validateCsrfTokens(except: [
            'api/*', // يمكنك استثناء جميع مسارات API إذا أردت
            'subscriptions/*', // أو تحديد المسار الخاص بك بشكل أدق
            // مثال: 'your/other/api/route/*'
        ]);

        // يمكنك أيضًا إضافة middlewares أخرى هنا إذا لزم الأمر
        // $middleware->append(MyGlobalMiddleware::class);

        // إذا كنت تستخدم Laravel Sanctum (موصى به للـ API)،
        // فإنه يتعامل مع حماية API بطرق أخرى (مثل tokens).
        // تأكد من أن Sanctum (أو Passport) مهيأ بشكل صحيح إذا كنت تعتمد عليه للمصادقة.
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // ...
    })->create();
