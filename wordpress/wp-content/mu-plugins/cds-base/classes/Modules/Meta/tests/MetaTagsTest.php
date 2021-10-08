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

    public function setUp() : void {
        $this->meta = new MetaTags();
      }
     
      public function tearDown() : void  {
        $this->meta = null;
      }
     

    public function test_short_string_is_returned() {
        expect($this->meta->getMetaFromContent($this->desc1))->toEqual($this->desc1);
    }

    public function test_longer_strings_are_shortened() {
        expect($this->meta->getMetaFromContent($this->desc2))->toEqual(substr($this->desc2, 0, 154));
    }

    public function test_one_long_sentence_is_returned() {
        expect($this->meta->getMetaFromContent($this->desc3))->toEqual($this->desc3);
    }

    public function test_second_long_sentence_is_returned() {
        expect($this->meta->getMetaFromContent($this->desc4))->toEqual($this->desc4);
    }

    public function test_first_long_sentence_is_returned() {
        expect($this->meta->getMetaFromContent($this->desc5))->toEqual($this->desc3);
    }

}
