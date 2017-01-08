<?php

class UserTest extends CTestCase
{

  public function testWhenUserChooseWithGelSetAndMonthlyDeliveryThenAfterThreeMonthsSpent27Dollars() {

    $user = new User();
    $user->save();

    $order = new Order();
    $order->user_id = $user->id;
    $order->delivery_type = Order::DELIVERY_TYPE_MONTHLY;
    $order->type = Order::TYPE_WITH_GEL_SET;
    $order->save();

    $time = $order->getStartFrom();
    $time = strtotime("+3 month", $time);

    $spent = $user->getTotalSpent($time);

    $expectedPrice = 9 * 3;

    $this->assertEquals($expectedPrice, $spent);
  }

  public function testWhenUserChooseFullSetAndTwiceInMonthDeliveryThenAfterYearSpent456Dollars() {
    $user = new User();
    $user->save();

    $order = new Order();
    $order->user_id = $user->id;
    $order->delivery_type = Order::DELIVERY_TYPE_TWICE_A_MONTH;
    $order->type = Order::TYPE_FULL_SET;
    $order->save();

    $time = $order->getStartFrom();
    $time = strtotime("+1 year", $time);

    $spent = $user->getTotalSpent($time);

    $expectedPrice = 19 * 12 * 2;

    $this->assertEquals($expectedPrice, $spent);
  }

  public function testWhenSetChangedForFullFromWithGelThenTotalSpentAfterMonthMoreThan10Dollars() {

    $user = new User();
    $user->save();

    $order = new Order();
    $order->user_id = $user->id;
    $order->type = Order::TYPE_WITH_GEL_SET;
    $order->delivery_type = Order::DELIVERY_TYPE_MONTHLY;
    $order->save();

    $time = $order->getStartFrom();
    $time = strtotime("+1 month", $time);

    $defaultSpent = $user->getTotalSpent($time);

    $orderId = $order->id;

    $order = Order::model()->findByPk($orderId);

    $order->type = Order::TYPE_FULL_SET;
    $order->save();

    $userId = $user->id;
    $user = User::model()->findByPk($userId);

    $afterChangeSpent = $user->getTotalSpent($time);

    $diff = $afterChangeSpent - $defaultSpent;

    $this->assertEquals(10, $diff);
  }


}