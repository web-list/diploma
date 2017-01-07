<?php

class CommonTest extends CTestCase
{

  public function testItWorks() {
    $a = 1;

    $b = $a;

    $this->assertEquals(1, $b);
  }

}