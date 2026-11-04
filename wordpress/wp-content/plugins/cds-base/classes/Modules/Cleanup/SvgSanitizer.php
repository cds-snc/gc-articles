<?php

declare(strict_types=1);

namespace CDS\Modules\Cleanup;

use enshrined\svgSanitize\Sanitizer;

class SvgSanitizer
{
    public const SVG_MIME = 'image/svg+xml';

    public function __construct()
    {
        add_filter('upload_mimes', [$this, 'allowSvg']);
        add_filter('wp_handle_upload_prefilter', [$this, 'sanitizeUpload']);
        add_filter('wp_handle_sideload_prefilter', [$this, 'sanitizeUpload']);
    }

    public function allowSvg($mimes)
    {
        if (self::isAvailable()) {
            $mimes['svg'] = self::SVG_MIME;
        }

        return $mimes;
    }

    public function sanitizeUpload($file)
    {
        if (!is_array($file) || !empty($file['error']) || !self::isSvg($file)) {
            return $file;
        }

        $tmpName = $file['tmp_name'] ?? '';
        if (!self::isAvailable() || !is_string($tmpName) || !is_readable($tmpName)) {
            $file['error'] = self::errorMessage();
            return $file;
        }

        $dirty = file_get_contents($tmpName);
        $clean = $dirty === false ? false : self::sanitize($dirty);

        if (!is_string($clean) || $clean === '' || file_put_contents($tmpName, $clean) === false) {
            $file['error'] = self::errorMessage();
            return $file;
        }

        $file['size'] = strlen($clean);

        return $file;
    }

    public static function sanitize(string $svg)
    {
        if (!self::isAvailable()) {
            return false;
        }

        $sanitizer = new Sanitizer();
        $sanitizer->removeRemoteReferences(true);

        return $sanitizer->sanitize($svg);
    }

    public static function isAvailable(): bool
    {
        return class_exists(Sanitizer::class);
    }

    private static function isSvg(array $file): bool
    {
        $name = is_string($file['name'] ?? null) ? $file['name'] : '';
        $type = is_string($file['type'] ?? null) ? $file['type'] : '';

        return strtolower(pathinfo($name, PATHINFO_EXTENSION)) === 'svg'
            || strtolower($type) === self::SVG_MIME;
    }

    private static function errorMessage(): string
    {
        return __('This SVG file could not be verified as safe and was not uploaded.', 'cds-snc');
    }
}
