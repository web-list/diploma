<?php

class CommonTest extends PHPUnit_Framework_TestCase
{

  public function testItWorks() {
    $a = 1;

    $b = $a;

    $this->assertEquals(1, $b);
  }

}