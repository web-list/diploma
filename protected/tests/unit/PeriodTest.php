<?php

class PeriodTest extends CTestCase
{

  public function testFor2stFebruaryAfterHalfMonthIs17February() {
    $time = strtotime("02.02.2017");

    $period = new Period();
    $period->time = $time;
    $period->afterHalfMonth();

    $newTime = $period->time;
    $expectedTime = strtotime("17.02.2017");

    $this->assertEquals($expectedTime, $newTime);
  }

  public function testFor18JuleAfterHalfMonthIs3rdAugust() {

    $period = new Period();
    $period->time = strtotime("18.07.2017");
    $period->afterHalfMonth();

    $this->assertEquals(strtotime("03.08.2017"),  $period->time);
  }

  public function testFor22thNovemberBeforeHalfMonthIs7thNovember() {
    $period = new Period();
    $period->time = strtotime("22.11.2017");
    $period->beforeHalfMonth();

    $this->assertEquals(strtotime("7.11.2017"), $period->time);
  }

  public function testFor4thMarchBeforeHalfMonthIs19thFebruary() {
    $period = new Period();
    $period->time = strtotime("4.03.2017");
    $period->beforeHalfMonth();

    $this->assertEquals(strtotime("19.02.2017"), $period->time);
  }

  public function testFor28FebruaryAfterMonthIs28March() {
    $period = new Period();
    $period->time = strtotime("28.02.2017");
    $period->afterMonth();

    $this->assertEquals(strtotime("28.03.2017"), $period->time);
  }

  public function testFor22SeptemberBeforeMonthIs22August() {
    $period = new Period();
    $period->time = strtotime("22.09.2017");
    $period->beforeMonth();

    $this->assertEquals(strtotime("22.08.2017"), $period->time);
  }

  public function testFor12January2015BeforeTwoMonthsIs12November2014() {
    $period = new Period();

    $period->time = strtotime("12.01.2015");
    $period->beforeTwoMonths();

    $this->assertEquals(strtotime("12.11.2014"), $period->time);
  }

}