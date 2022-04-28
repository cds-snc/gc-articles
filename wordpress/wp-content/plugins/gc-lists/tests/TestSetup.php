<?php

test('Just test something with some wordpress functions', function () {
	$this->assertTrue( true );

	$site_url = get_site_url();
	$login_url = wp_login_url();

	$this->assertEquals($login_url, $site_url . '/wp-login.php');
	$this->assertEquals($site_url, 'http://example.org');
});
