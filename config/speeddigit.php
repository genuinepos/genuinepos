<?php

return [
    'show_app_logo' => env('SHOW_APP_LOGO', true),
    'app_logo' => env('APP_LOGO_PATH', 'assets/images/app_logo.png'),
    'app_logo_alt' => env('APP_LOGO_ALT', config('app.name')),
];
