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

  public function testWhenOneInTwoMonthsDeliveryChooseThenMakeDeliveryAfterTwoMonths() {
    $order = new Order();
    $order->delivery_type = Order::DELIVERY_TYPE_ONCE_IN_TWO_MONTHS;
    $order->save();

    $time = $order->created;
    $time = strtotime("+2 month", $time);
    $delivered = $order->deliveredInTimestamp($time);

    $this->assertTrue($delivered);
  }

  public function testWhenMonthlyDeliveryChooseThenMakeDeliveryAfterOneMonth() {
    $order = new Order();
    $order->delivery_type = Order::DELIVERY_TYPE_MONTHLY;
    $order->save();

    $time = $order->created;
    $time = strtotime("+1 month", $time);

    $delivered = $order->deliveredInTimestamp($time);

    $this->assertTrue($delivered);
  }

  public function testWhenMonthlyDeliveryChooseThenMakeDeliveryAfter3Months() {
    $order = new Order();
    $order->delivery_type = Order::DELIVERY_TYPE_MONTHLY;
    $order->save();

    $time = $order->created;
    $time = strtotime("+3 month", $time);

    $delivered = $order->deliveredInTimestamp($time);

    $this->assertTrue($delivered);
  }

  public function testWhenTwiceInMonthDeliveryChooseThenMakeDeliveryAfter15Days() {
    $order = new Order();
    $order->delivery_type = Order::DELIVERY_TYPE_TWICE_A_MONTH;
    $order->save();

    $time = $order->created;

    $time = strtotime("+15 day", $time);

    $delivered = $order->deliveredInTimestamp($time);

    $this->assertTrue($delivered);
  }

  public function testWhenMonthlyDeliveryAndEvery7DayChooseThenMakeDelivery7OfTheNextMonth() {
    $dayOfMonth = 7;

    $order = new Order();
    $order->deliveryDayOfTheMonth = $dayOfMonth;
    $order->delivery_type = Order::DELIVERY_TYPE_MONTHLY;
    $order->save();

    $time = $order->delivery_started;
    $time = strtotime("+1 month", $time);

    $delivered = $order->deliveredInTimestamp($time);

    $this->assertTrue($delivered);
  }

  public function testWhenTwiceInMonthDeliveryAndEvery9And24DaysChooseThenMakeDelivery24FebruaryInTheNextYear() {
    $firstDayOfMonth = 9;
    $lastDayOfMonth = 24;
    $february = 2;

    $order = new Order();
    $order->deliveryDayOfTheMonth = $firstDayOfMonth;
    $order->delivery_type = Order::DELIVERY_TYPE_TWICE_A_MONTH;
    $order->save();

    $time = $order->delivery_started;
    $time = mktime(
      date("h", $time),
      date("i", $time),
      date("s", $time),
      $february,
      $lastDayOfMonth,
      date("Y", $time) + 1
    );

    $delivered = $order->deliveredInTimestamp($time);

    $this->assertTrue($delivered);
  }

  public function testWhenDeliveryStoppedThenNotMakeDeliveryAfterDeliveryPeriod() {

    $order = new Order();
    $order->delivery_type = Order::DELIVERY_TYPE_MONTHLY;
    $order->save();

    $order->stop();

    $time = time() + 30 * Order::ONE_DAY_SECONDS;

    $delivered = $order->deliveredInTimestamp($time);

    $this->assertFalse($delivered);
  }

  public function testWhenDeliveryChangedForTwiceInMonthFromMonthlyThenMakeDelivery15DaysBefore() {

    $order = new Order();
    $order->delivery_type = Order::DELIVERY_TYPE_MONTHLY;
    $order->save();

    $searchId = $order->id;

    /** @var Order $order */
    $order = Order::model()->findByPk($searchId);

    $order->delivery_type = Order::DELIVERY_TYPE_TWICE_A_MONTH;
    $order->save();

    $time = $order->getStartFrom();
    $time = strtotime("+15 days", $time);

    $delivered = $order->deliveredInTimestamp($time);

    $this->assertTrue($delivered);
  }
}