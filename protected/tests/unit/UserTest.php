<?php

class UserTest extends CTestCase
{

  public function testWhenUserChooseWithGelSetAndMonthlyDeliveryThenAfterThreeMonthsSpent27Dollars() {

    $user = new User();
    $user->save();

    $order = new Order();
    $order->user_id = $user->id;
    $order->deliveryType = Order::DELIVERY_TYPE_MONTHLY;
    $order->type = Order::TYPE_WITH_GEL_SET;
    $order->save();

    $time = mktime(
      date("h"),
      date("i"),
      date("s"),
      date("n") + 3,
      date("j"),
      date("Y")
    );

    $spent = $user->getTotalSpent($time);

    $expectedPrice = 9 * 3;

    $this->assertEquals($expectedPrice, $spent);
  }

  public function testWhenUserChooseFullSetAndTwiceInMonthDeliveryThenAfterYearSpent456Dollars() {
    $user = new User();
    $user->save();

    $order = new Order();
    $order->user_id = $user->id;
    $order->deliveryType = Order::DELIVERY_TYPE_TWICE_A_MONTH;
    $order->type = Order::TYPE_FULL_SET;
    $order->save();

    $time = mktime(
      date("h"),
      date("i"),
      date("s"),
      date("n"),
      date("j"),
      date("Y") + 1
    );

    $spent = $user->getTotalSpent($time);

    $expectedPrice = 19 * 12 * 2;

    $this->assertEquals($expectedPrice, $spent);
  }

}