<?php
/**
 * Class TestSetup
 *
 * @package Gc_Lists
 */

/**
 * Sample test case.
 */
class TestSetup extends WP_UnitTestCase {

	/**
	 * A single example test.
	 */
	public function test_sample() {
		$this->assertTrue( true );

		$site_url = get_site_url();
		$login_url = wp_login_url();

		$this->assertEquals($login_url, $site_url . '/wp-login.php');
		$this->assertEquals($site_url, 'http://example.org');
	}
}
