<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
    'sms.url' => 'http://smsc.ru',
    'sms.login' => '',
    'sms.password' => '',
    
    'recaptchaSecret' => '',
    'recaptchaSitekey' => '',
    
    'main-slider.uploads.path' => realpath(__DIR__ . '/../../frontend/web/uploads/main-slider'),
    'files.uploads.path' => realpath(__DIR__ . '/../../frontend/web/uploads'),

    'partners.uploads.path' => realpath(__DIR__ . '/../../frontend/web/uploads/partners'),
    'partners_url.uploads.path' => __DIR__ . '/../../frontend/web/uploads/partners',
    'flags' => '/images/flags/16/',
];
