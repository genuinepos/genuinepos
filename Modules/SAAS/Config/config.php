<?php

return [
    'name' => 'SAAS',
    'relative_path' => 'Modules/SAAS',
    'asset_path' => 'Modules/SAAS/Resources/assets',
    'trial_min_days' => env('TRIAL_MIN_DAYS', 3),
    'trial_max_days' => env('TRIAL_MAX_DAYS', 7),
];
