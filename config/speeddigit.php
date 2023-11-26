<?php

return [
    'name' => env('APP_NAME'),
    'show_app_logo' => env('SHOW_APP_LOGO', true),
    'app_logo' => env('APP_LOGO_PATH', 'assets/images/app_logo.png'),
    'app_logo_alt' => env('APP_LOGO_ALT', config('app.name')),
    'version' => env('APP_VERSION', 'v1.0.0'),
    'address' => env('ADDRESS', 'Uttara, Dhaka, Bangladesh'),
    'website' => env('WEBSITE', 'www.speeddigit.com'),
    'support_email' => env('SUPPORT_EMAIL', 'support@speeddigit.com'),
    'facebook' => env('FACEBOOK'),
    'twitter' => env('TWITTER'),
    'youtube' => env('YOUTUBE'),
    'slogan' => env('SLOGAN'),
];
