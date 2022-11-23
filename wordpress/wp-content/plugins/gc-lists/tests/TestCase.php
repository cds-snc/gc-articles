<?php

declare(strict_types=1);

namespace GCLists\Tests;

use GCLists\Database\Factories\MessageFactory;
use GCLists\Install;

/**
 * Note for future self: The WP_Mock library is incompatible with the wordpress core
 * test framework that we are using here:
 * https://github.com/10up/wp_mock/issues/125#issuecomment-414352645
 */
class TestCase extends \WP_UnitTestCase
{
    public function set_up()
    {
        /**
         * Because bootstrap.php loads the plugin in mu-plugins, activation hooks don't fire
         * and database tables don't get created, so we must manually trigger the install.
         */
        $installer = Install::getInstance();

        $installer->install();
        
        $this->factory()->message = new MessageFactory( $this->factory() );
        
        parent::set_up();
    }

    public function tear_down()
    {
        parent::tear_down();
    }
}
