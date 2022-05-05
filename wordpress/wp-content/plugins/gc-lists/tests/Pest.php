<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

use GCLists\Install;

uses()->group('integration')->in('Integration');
uses()->group('unit')->in('Unit');

/**
 * Not sure why, but seems the bootstrap.php specified in phpunit.xml is not available
 * yet when running these tests, resulting in WP_UnitTestCase not found. Including
 * the bootstrap.php here fixes the issue.
 */
require_once('bootstrap.php');

/**
 * Because bootstrap.php loads the plugin in mu-plugins, activation hooks don't fire
 * and database tables don't get created, so we must manually trigger the install.
 */
$installer = Install::getInstance();

uses(\WP_UnitTestCase::class)
	->beforeAll(fn () => $installer->install())
	->in('Integration');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function something()
{
    // ..
}
