<?php

class OrderTest extends CTestCase
{

  public function testWhenOneShaverSetChooseThenPriceIsOneDollar() {
    $order = new Order();
    $order->type = Product::TYPE_ONE_SHAVER_SET;

    $price = $order->getProduct()->getPrice();

    $this->assertEquals(1, $price);
  }

  public function testWhenWithGelSetChooseThenPriceIsNineDollars() {
    $order = new Order();
    $order->type = Product::TYPE_WITH_GEL_SET;

    $price = $order->getProduct()->getPrice();

    $this->assertEquals(9, $price);
  }

  public function testWhenFullSetChooseThenPriceIsNineteenDollars() {
    $order = new Order();
    $order->type = Product::TYPE_FULL_SET;

    $price = $order->getProduct()->getPrice();

    $this->assertEquals(19, $price);
  }

  public function testWhenOneInTwoMonthsDeliveryChooseThenMakeDeliveryAfterTwoMonths() {
    $order = new Order();
    $order->delivery_type = Delivery::DELIVERY_TYPE_ONCE_IN_TWO_MONTHS;
    $order->save();

    $time = $order->created;
    $time = strtotime("+2 month", $time);
    $delivered = $order->getDelivery()->makeNow($time);

    $this->assertTrue($delivered);
  }

  public function testWhenMonthlyDeliveryChooseThenMakeDeliveryAfterOneMonth() {
    $order = new Order();
    $order->delivery_type = Delivery::DELIVERY_TYPE_MONTHLY;
    $order->save();

    $time = $order->created;
    $time = strtotime("+1 month", $time);

    $delivered = $order->getDelivery()->makeNow($time);

    $this->assertTrue($delivered);
  }

  public function testWhenMonthlyDeliveryChooseThenMakeDeliveryAfter3Months() {
    $order = new Order();
    $order->delivery_type = Delivery::DELIVERY_TYPE_MONTHLY;
    $order->save();

    $time = $order->created;
    $time = strtotime("+3 month", $time);

    $delivered = $order->getDelivery()->makeNow($time);

    $this->assertTrue($delivered);
  }

  public function testWhenTwiceInMonthDeliveryChooseThenMakeDeliveryAfterHalfMonth() {
    $order = new Order();
    $order->delivery_type = Delivery::DELIVERY_TYPE_TWICE_A_MONTH;
    $order->save();

    $delivery = $order->getDelivery();
    $period =  $delivery->getPeriod();
    $period->afterHalfMonth();

    $delivered = $delivery->makeNow($period->time);

    $this->assertTrue($delivered);
  }

  public function testWhenMonthlyDeliveryAndEvery7DayChooseThenMakeDelivery7OfTheNextMonth() {
    $dayOfMonth = 7;

    $order = new Order();
    $order->deliveryDayOfTheMonth = $dayOfMonth;
    $order->delivery_type = Delivery::DELIVERY_TYPE_MONTHLY;
    $order->save();

    $time = $order->delivery_started;
    $time = strtotime("+1 month", $time);

    $delivered = $order->getDelivery()->makeNow($time);

    $this->assertTrue($delivered);
  }

  public function testWhenTwiceInMonthDeliveryAndEvery9And24DaysChooseThenMakeDelivery24FebruaryInTheNextYear() {
    $firstDayOfMonth = 9;
    $lastDayOfMonth = 24;
    $february = 2;

    $order = new Order();
    $order->deliveryDayOfTheMonth = $firstDayOfMonth;
    $order->delivery_type = Delivery::DELIVERY_TYPE_TWICE_A_MONTH;
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

    $delivered = $order->getDelivery()->makeNow($time);

    $this->assertTrue($delivered);
  }

  public function testWhenDeliveryStoppedThenNotMakeDeliveryAfterDeliveryPeriod() {

    $order = new Order();
    $order->delivery_type = Delivery::DELIVERY_TYPE_MONTHLY;
    $order->save();

    $order->stop();

    $time = time() + 30 * Order::ONE_DAY_SECONDS;

    $delivered = $order->getDelivery()->makeNow($time);

    $this->assertFalse($delivered);
  }

  public function testWhenDeliveryChangedForTwiceInMonthFromMonthlyThenMakeDeliveryAfterHalfMonth() {

    $order = new Order();
    $order->delivery_type = Delivery::DELIVERY_TYPE_MONTHLY;
    $order->save();

    $searchId = $order->id;

    /** @var Order $order */
    $order = Order::model()->findByPk($searchId);

    $order->delivery_type = Delivery::DELIVERY_TYPE_TWICE_A_MONTH;
    $order->save();

    $delivery = $order->getDelivery();
    $period = $delivery->getPeriod();
    $period->afterHalfMonth();

    $delivered = $delivery->makeNow($period->time);

    $this->assertTrue($delivered);
  }
}