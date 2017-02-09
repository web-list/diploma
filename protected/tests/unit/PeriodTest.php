<?php

class PeriodTest extends CTestCase
{

  public function testFor2stFebruaryAfterHalfMonthIs17February() {
    $time = strtotime("02.02.2017");

    $period = new Period();
    $period->time = $time;
    $period->plusHalfMonth();

    $newTime = $period->time;
    $expectedTime = strtotime("17.02.2017");

    $this->assertEquals($expectedTime, $newTime);
  }

}