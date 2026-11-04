<?php

namespace CDS\Modules\BlocksPHP;

function is_admin()
{
    return $GLOBALS['cds_blocks_php_test']['is_admin'];
}

function is_super_admin()
{
    return $GLOBALS['cds_blocks_php_test']['is_super_admin'];
}

function register_block_type($path, $args)
{
    $GLOBALS['cds_blocks_php_test']['blocks'][$path] = $args;
}

function wp_count_sites()
{
    return ['all' => 42];
}

beforeEach(function () {
    $GLOBALS['cds_blocks_php_test'] = ['blocks' => []];
});

afterEach(function () {
    unset($GLOBALS['cds_blocks_php_test']);
});

test('only the site-counter block is registered', function (bool $isAdmin, bool $isSuperAdmin) {
    $GLOBALS['cds_blocks_php_test']['is_admin'] = $isAdmin;
    $GLOBALS['cds_blocks_php_test']['is_super_admin'] = $isSuperAdmin;

    $blocks = (new \ReflectionClass(BlocksPHP::class))->newInstanceWithoutConstructor();
    $blocks->registerBlocks();

    $registered = $GLOBALS['cds_blocks_php_test']['blocks'];
    $siteCounterPath = dirname(__DIR__) . '/build/site-counter/';

    expect(array_keys($registered))->toBe([$siteCounterPath]);
    expect($registered[$siteCounterPath]['render_callback']([], '', null))
        ->toBe("<div class='site-counter'>42</div>");
})->with([
    'public page' => [false, false],
    'super admin editor' => [true, true],
]);
