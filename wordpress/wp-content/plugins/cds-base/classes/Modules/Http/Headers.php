<?php

declare(strict_types=1);

namespace CDS\Modules\Http;

class Headers
{
    public static function register()
    {
        $instance = new self();
        add_filter('wp_headers', [$instance, 'addHeaders']);
    }

    public function addHeaders($headers)
    {
        $headers['X-XSS-Protection'] = '1; mode=block';
        $headers['X-Content-Type-Options'] = 'nosniff';
        $headers['X-Frame-Options'] = 'SAMEORIGIN';
        $headers['Strict-Transport-Security'] = 'max-age=31536000; includeSubdomains; preload';

        // Only add CSP for front-end not-logged-in users
        if (!is_admin() && !is_user_logged_in()) {
            $headers['X-Content-Security-Policy'] = $this->getCSPHeaders();
        }

        return $headers;
    }

    protected function getCSPHeaders(): string
    {
        $csp = "base-uri 'self';";
        $csp .= "connect-src 'self';";
        $csp .= "default-src 'self';";
        $csp .= "font-src 'self' https://fonts.gstatic.com https://use.fontawesome.com https://www.canada.ca;";
        $csp .= "frame-src 'self';";
        $csp .= "img-src 'self' https://canada.ca https://wet-boew.github.io https://www.canada.ca;";
        $csp .= "manifest-src 'self';";
        $csp .= "media-src 'self';";
        $csp .= "object-src 'none';";
        $csp .= "script-src 'self' https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.js https://www.canada.ca/etc/designs/canada/wet-boew/js/wet-boew.min.js;";
        $csp .= "style-src 'unsafe-inline' 'self' https://use.fontawesome.com https://www.canada.ca;";
        $csp .= "worker-src 'none';";

        return $csp;
    }
}
