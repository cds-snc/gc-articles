<?php

use CDS\Modules\Users\Users;

class TestUsers extends \WP_Mock\Tools\TestCase
{
    private $badEmails = [
        'justin.trudeau@gmail.com',
        'justin.trudeau@cds-snc',
        'justin.trudeaucds-snc.ca',
        'cds-snc.ca',
        '@cds-snc.ca',
        ''
    ];

    private $goodEmails = [
        'justin.trudeau@cds-snc.ca',
        'justin.trudeau@tbs-sct.gc.ca',
        'admin@cds-snc.ca'
    ];

    private $users;

    public function setUp(): void
    {
        \WP_Mock::setUp();
        \WP_Mock::userFunction('is_email', array(
            'return' => true,
        ));

        $this->users = new Users();
    }

    public function tearDown(): void
    {
        \WP_Mock::tearDown();
        $this->users = null;
    }

    public function testBadEmails()
    {
        foreach ($this->badEmails as $email) {
            expect($this->users->isAllowedDomain($email))->toBe(false);
        }
    }

    public function testGoodEmails()
    {
        foreach ($this->goodEmails as $email) {
            expect($this->users->isAllowedDomain($email))->toBe(true);
        }
    }
}
