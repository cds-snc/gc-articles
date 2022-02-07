<?php

use CDS\Modules\Users\EmailDomains;

class TestEmailDomains extends \WP_Mock\Tools\TestCase
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
    }

    public function tearDown(): void
    {
        \WP_Mock::tearDown();
    }

    public function badEmails(): array
    {
        return [
            "bad domain" => ['justin.trudeau@gmail.com'],
            "no TLD" => ['justin.trudeau@cds-snc'],
            "no @" => ['justin.trudeaucds-snc.ca'],
            "no username" => ['@cds-snc.ca'],
            'empty' => [''],
        ];
    }

    /**
     * @dataProvider badEmails
     */
    public function testBadEmails(string $email): void
    {
        expect(EmailDomains::isValidDomain($email))->toEqual(false);
    }

    public function goodEmails(): array
    {
        return [
            "CDS domain" => ['justin.trudeau@cds-snc.ca'],
            "TBS domain" => ['justin.trudeau@tbs-sct.gc.ca'],
            "random GC domain" => ['justin.trudeau@my-very-cool.department.gc.ca'],
            "only GC domain" => ['justin.trudeau@gc.ca'],
            'Canada domain' => ['justin.trudeau@canada.ca'],
            'Service Canada domain' => ['justin.trudeau@servicecanada.ca'],
            'random Canada domain' => ['justin.trudeau@hockey.night.in.canada.ca'],
        ];
    }

    /**
     * @dataProvider goodEmails
     */
    public function testGoodemails(string $email): void
    {
        expect(EmailDomains::isValidDomain($email))->toEqual(true);
    }
}
