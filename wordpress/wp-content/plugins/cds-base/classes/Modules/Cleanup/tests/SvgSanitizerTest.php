<?php

use CDS\Modules\Cleanup\SvgSanitizer;

if (!function_exists('add_filter')) {
    function add_filter($hook, $callback, $priority = 10, $accepted_args = 1) {
        return true;
    }
}

if (!function_exists('__')) {
    function __($text, $domain = 'default') {
        return $text;
    }
}

function svgUpload(string $contents, string $name = 'upload.svg', string $type = 'image/svg+xml'): array
{
    $tmp = tempnam(sys_get_temp_dir(), 'svgtest');
    file_put_contents($tmp, $contents);

    $result = (new SvgSanitizer())->sanitizeUpload([
        'name' => $name,
        'type' => $type,
        'tmp_name' => $tmp,
        'error' => 0,
        'size' => strlen($contents),
    ]);

    $after = file_get_contents($tmp);
    unlink($tmp);

    return [$result, $after];
}

const SAFE_SVG = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 10"><circle cx="5" cy="5" r="4" fill="red"/></svg>';

test('allows svg mime type when the sanitizer is available', function () {
    $mimes = (new SvgSanitizer())->allowSvg(['png' => 'image/png']);

    expect($mimes)->toHaveKey('svg', 'image/svg+xml');
    expect($mimes)->toHaveKey('png', 'image/png');
});

test('keeps a benign svg intact', function () {
    [$result, $after] = svgUpload(SAFE_SVG);

    expect($result['error'])->toBe(0);
    expect($after)->toContain('<svg')
        ->toContain('<circle')
        ->toContain('fill="red"');
});

test('updates the reported size to the sanitized size', function () {
    [$result, $after] = svgUpload('<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script><circle r="1"/></svg>');

    expect($result['size'])->toBe(strlen($after));
});

test('removes dangerous content', function (string $svg, array $forbidden) {
    [$result, $after] = svgUpload($svg);

    expect($result['error'])->toBe(0);
    expect($after)->toContain('<svg');

    foreach ($forbidden as $needle) {
        expect(strtolower($after))->not->toContain($needle);
    }
})->with([
    'script element' => [
        '<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script><circle r="1"/></svg>',
        ['<script', 'alert(1)'],
    ],
    'onload attribute' => [
        '<svg xmlns="http://www.w3.org/2000/svg" onload="alert(1)"><circle r="1"/></svg>',
        ['onload', 'alert(1)'],
    ],
    'onclick attribute on child' => [
        '<svg xmlns="http://www.w3.org/2000/svg"><circle r="1" onclick="alert(1)"/></svg>',
        ['onclick', 'alert(1)'],
    ],
    'javascript href' => [
        '<svg xmlns="http://www.w3.org/2000/svg"><a href="javascript:alert(1)"><circle r="1"/></a></svg>',
        ['javascript:'],
    ],
    'javascript xlink:href' => [
        '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><a xlink:href="javascript:alert(1)"><circle r="1"/></a></svg>',
        ['javascript:'],
    ],
    'foreignObject with html' => [
        '<svg xmlns="http://www.w3.org/2000/svg"><foreignObject><div xmlns="http://www.w3.org/1999/xhtml"><script>alert(1)</script></div></foreignObject></svg>',
        ['foreignobject', '<script', 'alert(1)'],
    ],
    'animate setting href to javascript' => [
        '<svg xmlns="http://www.w3.org/2000/svg"><a><animate attributeName="href" values="javascript:alert(1)"/><circle r="1"/></a></svg>',
        ['javascript:'],
    ],
    'remote image reference' => [
        '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><image xlink:href="https://evil.example/track.png"/><circle r="1"/></svg>',
        ['evil.example'],
    ],
]);

test('does not resolve external entities', function () {
    $svg = '<?xml version="1.0"?><!DOCTYPE svg [<!ENTITY xxe SYSTEM "file:///etc/passwd">]>'
        . '<svg xmlns="http://www.w3.org/2000/svg"><text>&xxe;</text></svg>';

    [$result, $after] = svgUpload($svg);

    $rejected = is_string($result['error']) && $result['error'] !== '';
    expect($rejected || (!str_contains($after, 'root:') && !str_contains($after, '<!ENTITY')))->toBeTrue();
    expect($after)->not->toContain('root:');
});

test('rejects malformed svg', function () {
    [$result] = svgUpload('<svg xmlns="http://www.w3.org/2000/svg"><circle r="1"');

    expect($result['error'])->toBeString()->not->toBeEmpty();
});

test('rejects empty svg', function () {
    [$result] = svgUpload('');

    expect($result['error'])->toBeString()->not->toBeEmpty();
});

test('sanitizes by extension even when the browser reports another mime type', function () {
    [$result, $after] = svgUpload(
        '<svg xmlns="http://www.w3.org/2000/svg" onload="alert(1)"><circle r="1"/></svg>',
        'evil.SVG',
        'application/octet-stream'
    );

    expect($after)->not->toContain('onload');
});

test('sanitizes by mime type even when the extension is different', function () {
    [$result, $after] = svgUpload(
        '<svg xmlns="http://www.w3.org/2000/svg" onload="alert(1)"><circle r="1"/></svg>',
        'evil.txt',
        'image/svg+xml'
    );

    expect($after)->not->toContain('onload');
});

test('leaves non-svg uploads untouched', function () {
    $contents = "\x89PNG\r\n\x1a\n not really a png <script>alert(1)</script>";

    [$result, $after] = svgUpload($contents, 'photo.png', 'image/png');

    expect($result['error'])->toBe(0);
    expect($after)->toBe($contents);
});

test('does not touch uploads that already have an error', function () {
    $file = ['name' => 'a.svg', 'type' => 'image/svg+xml', 'tmp_name' => '/does/not/exist', 'error' => 'Existing error'];

    expect((new SvgSanitizer())->sanitizeUpload($file))->toBe($file);
});

test('rejects an svg whose temp file cannot be read', function () {
    $result = (new SvgSanitizer())->sanitizeUpload([
        'name' => 'a.svg',
        'type' => 'image/svg+xml',
        'tmp_name' => '/does/not/exist.svg',
        'error' => 0,
    ]);

    expect($result['error'])->toBeString()->not->toBeEmpty();
});
