<?php

namespace Modules\SAAS\Utils;

class UrlGenerator
{
    public static function generateFullUrlFromDomain(string $domain): string
    {
        $protocol = request()->isSecure() ? 'https://' : 'http://';
        $domain = str_contains($domain, '.') ? $domain : $domain.'.'.config('app.domain');

        return $protocol.$domain;
    }
}
