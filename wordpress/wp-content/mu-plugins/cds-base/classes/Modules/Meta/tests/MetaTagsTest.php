<?php

use CDS\Modules\Meta\MetaTags;

class TestMetaTags extends PHPUnit\Framework\TestCase
{
    private $desc1 = 'This string is 29 characters.';
    private $desc2 = 'This string is 185 characters. This string is 185 characters. This string is 185 characters. This string is 185 characters. This string is 185 characters. This string is 185 characters.';
    private $desc3 = 'That’s why I long sometimes for another glimpse of the “beautiful Antonia” (or can it be the Other?) moving in the dimness of the great cathedral, saying a short prayer at the tomb.'; // 181 chars
    private $desc4 = 'This is a short sentence. That’s why I long sometimes for another glimpse of the “beautiful Antonia” (or can it be the Other?) moving in the dimness of the great cathedral, saying a short prayer at the tomb.';
    private $desc5 = 'That’s why I long sometimes for another glimpse of the “beautiful Antonia” (or can it be the Other?) moving in the dimness of the great cathedral, saying a short prayer at the tomb. This is a short sentence.';

    private $meta;

    public function setUp(): void
    {
        $this->meta = new MetaTags();
    }

    public function tearDown(): void
    {
        $this->meta = null;
    }

    public function testShortStringIsReturned()
    {
        expect($this->meta->getMetaFromContent($this->desc1))->toEqual($this->desc1);
    }

    public function testLongerStringsAreReturned()
    {
        expect($this->meta->getMetaFromContent($this->desc2))->toEqual(substr($this->desc2, 0, 154));
    }

    public function testSingleLongSentenceIsReturned()
    {
        expect($this->meta->getMetaFromContent($this->desc3))->toEqual($this->desc3);
    }

    public function testSecondLongSentenceIsReturned()
    {
        expect($this->meta->getMetaFromContent($this->desc4))->toEqual($this->desc4);
    }

    public function testFirstLongSentenceIsReturned()
    {
        expect($this->meta->getMetaFromContent($this->desc5))->toEqual($this->desc3);
    }
}
