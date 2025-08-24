<?php
return [
    'name' => $_ENV['APP_NAME'] ?? 'MyApp',
    'env' => $_ENV['APP_ENV'] ?? 'production',
    'debug' => filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN),
    'url' => $_ENV['APP_URL'] ?? 'http://localhost',
    'secret' => $_ENV['SECRET_KEY'] ?? '',
    'mail' => [
        'host' => $_ENV['MAIL_HOST'] ?? 'smtp.mailtrap.io',
        'port' => $_ENV['MAIL_PORT'] ?? 2525,
        'username' => $_ENV['MAIL_USERNAME'] ?? null,
        'password' => $_ENV['MAIL_PASSWORD'] ?? null,
        'encryption' => $_ENV['MAIL_ENCRYPTION'] ?? null,
    ],
];
