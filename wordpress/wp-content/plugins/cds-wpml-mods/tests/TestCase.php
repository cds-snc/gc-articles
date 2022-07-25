<?php

declare(strict_types=1);

namespace CDS\Wpml\Tests;

use CDS\Wpml\Wpml;

/**
 * Note for future self: The WP_Mock library is incompatible with the wordpress core
 * test framework that we are using here:
 * https://github.com/10up/wp_mock/issues/125#issuecomment-414352645
 */
class TestCase extends \WP_UnitTestCase
{
	public function set_up()
	{
		$plugin = Wpml::getInstance();
		$plugin->setup();
		
		parent::set_up();
	}

	public function tear_down()
	{
		parent::tear_down();
	}
}
