<?php

return [
    'app_name_label_name' => env('APP_NAME_LABEL_NAME', 'Software By'),
    'name' => env('APP_NAME', 'GPOS SYSTEM'),
    'show_app_logo' => env('SHOW_APP_LOGO', true),
    'app_logo' => env('APP_LOGO_PATH', 'assets/images/app_logo.png'),
    'app_logo_alt' => env('APP_LOGO_ALT', config('app.name')),
    'version' => env('APP_VERSION', 'v1.0.0'),
    'address' => env('ADDRESS', 'Uttara, Dhaka, Bangladesh'),
    'phone' => env('PHONE', '01792288555'),
    'email' => env('EMAIL', 'example@email.com'),
    'website' => env('WEBSITE', 'https://gposs.com'),
    'contact_us' => env('CONTACT_US_URL', 'https://gposs.com/contact/'),
    'support_email' => env('SUPPORT_EMAIL', 'support@speeddigit.com'),
    'facebook' => env('FACEBOOK_LINK', 'https://www.facebook.com/'),
    'twitter' => env('TWITTER_LINK', 'https://twitter.com/'),
    'Instagram' => env('INSTAGRAM_LINK', 'https://twitter.com/'),
    'youtube' => env('YOUTUBE_LINK', 'https://www.youtube.com/'),
    'linkedin' => env('LINKEDIN_LINK','https://www.linkedin.com/'),
    'slogan' => env('SLOGAN', 'Slogan'),
    'show_app_info_in_print' => env('SHOW_APP_INFO_IN_PRINT', true),
    'test' => tenant('id'),
];
