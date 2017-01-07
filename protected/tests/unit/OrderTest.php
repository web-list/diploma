<?php

class OrderTest extends CTestCase
{

  public function testWhenOneShaverSetChooseThenPriceIsOneDollar() {
    $order = new Order();
    $order->type = Order::TYPE_ONE_SHAVER_SET;

    $price = $order->getPrice();

    $this->assertEquals(1, $price);
  }

  public function testWhenWithGelSetChooseThenPriceIsNineDollars() {
    $order = new Order();
    $order->type = Order::TYPE_WITH_GEL_SET;

    $price = $order->getPrice();

    $this->assertEquals(9, $price);
  }

  public function testWhenFullSetChooseThenPriceIsNineteenDollars() {
    $order = new Order();
    $order->type = Order::TYPE_FULL_SET;

    $price = $order->getPrice();

    $this->assertEquals(19, $price);
  }

  public function testWhenOneInTwoMonthsDeliveryChooseThenMakeDeliveryAfter60Days() {
    $order = new Order();
    $order->deliveryType = Order::DELIVERY_TYPE_ONCE_IN_TWO_MONTHS;
    $order->save();

    $time = time() + 60 * 60 * 24 * 60;
    $delivered = $order->deliveredInTimestamp($time);

    $this->assertTrue($delivered);
  }

  public function testWhenMonthlyDeliveryChooseThenMakeDeliveryAfter30Days() {
    $order = new Order();
    $order->deliveryType = Order::DELIVERY_TYPE_MONTHLY;
    $order->save();

    $time = time() + 60 * 60 * 24 * 30;
    $delivered = $order->deliveredInTimestamp($time);

    $this->assertTrue($delivered);
  }

  public function testWhenMonthlyDeliveryChooseThenMakeDeliveryAfter90Days() {
    $order = new Order();
    $order->deliveryType = Order::DELIVERY_TYPE_MONTHLY;
    $order->save();

    $time = time() + 60 * 60 * 24 * 90;
    $delivered = $order->deliveredInTimestamp($time);

    $this->assertTrue($delivered);
  }

  public function testWhenTwiceInMonthDeliveryChooseThenMakeDeliveryAfter15Days() {
    $order = new Order();
    $order->deliveryType = Order::DELIVERY_TYPE_TWICE_A_MONTH;
    $order->save();

    $time = time() + 60 * 60 * 24 * 15;
    $delivered = $order->deliveredInTimestamp($time);

    $this->assertTrue($delivered);
  }

  public function testWhenTwiceInMonthDeliveryChooseThenMakeDeliveryAfter75Days() {
    $order = new Order();
    $order->deliveryType = Order::DELIVERY_TYPE_TWICE_A_MONTH;
    $order->save();

    $time = time() + 60 * 60 * 24 * 75;
    $delivered = $order->deliveredInTimestamp($time);

    $this->assertTrue($delivered);
  }

  public function testWhenMonthlyDeliveryAndEvery7DayChooseThenMakeDelivery7OfTheNextMonth() {
    $dayOfMonth = 7;

    $order = new Order();
    $order->deliveryDayOfTheMonth = $dayOfMonth;
    $order->deliveryType = Order::DELIVERY_TYPE_MONTHLY;
    $order->save();

    $time = mktime(
      date("h"),
      date("i"),
      date("s"),
      date("n") + 1,
      $dayOfMonth,
      date("Y")
    );

    $delivered = $order->deliveredInTimestamp($time);

    $this->assertTrue($delivered);
  }

  public function testWhenTwiceInMonthDeliveryAndEvery9And24DaysChooseThenMakeDelivery24FebruaryInTheNextYear() {
    $firstDayOfMonth = 9;
    $lastDayOfMonth = 24;
    $february = 2;

    $order = new Order();
    $order->deliveryDayOfTheMonth = $firstDayOfMonth;
    $order->deliveryType = Order::DELIVERY_TYPE_TWICE_A_MONTH;
    $order->save();

    $time = mktime(
      date("h"),
      date("i"),
      date("s"),
      $february,
      $lastDayOfMonth,
      date("Y") + 1
    );

    $delivered = $order->deliveredInTimestamp($time);

    $this->assertTrue($delivered);
  }

  public function testWhenDeliveryStoppedThenNotMakeDeliveryAfterDeliveryPeriod() {

    $order = new Order();
    $order->deliveryType = Order::DELIVERY_TYPE_MONTHLY;
    $order->save();

    $order->stop();

    $time = time() + 30 * Order::ONE_DAY_SECONDS;

    $delivered = $order->deliveredInTimestamp($time);

    $this->assertFalse($delivered);
  }


  public function testWhenDeliveryChangedForTwiceInMonthFromMonthlyThenMakeDelivery15DaysBefore() {

    $order = new Order();
    $order->deliveryType = Order::DELIVERY_TYPE_MONTHLY;
    $order->save();

    $searchId = $order->id;

    $order = Order::model()->findByPk($searchId);

    $order->deliveryType = Order::DELIVERY_TYPE_TWICE_A_MONTH;
    $order->save();

    $time = mktime(
      date("h"),
      date("i"),
      date("s"),
      date("n"),
      date("j") + 15,
      date("Y")
    );

    $delivered = $order->deliveredInTimestamp($time);

    $this->assertTrue($delivered);
  }
}