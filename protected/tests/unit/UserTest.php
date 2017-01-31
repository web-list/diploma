<?php

class UserTest extends CTestCase
{

  public function setUp() {
    require_once "dsl/OrderBuild.php";
    return parent::setUp();
  }

  public function testWhenUserChooseWithGelSetAndMonthlyDeliveryThenAfterThreeMonthsSpent27Dollars() {

    $user = new User();
    $user->save();

    $order = OrderBuild::construct()
      ->byUser($user)
      ->withMonthlyDelivery()
      ->setProductWithGelSet()
      ->build();

    $spent = $user->getTotalSpent($order->getTimeAfter("+3 month"));

    $this->assertEquals(9 * 3, $spent);
  }

  public function testWhenUserChooseFullSetAndTwiceInMonthDeliveryThenAfterYearSpent456Dollars() {
    $user = new User();
    $user->save();

    $order = OrderBuild::construct()
      ->byUser($user)
      ->withTwiceInMonthDelivery()
      ->setProductFullSet()
      ->build();

    $spent = $user->getTotalSpent($order->getTimeAfter("+1 year"));

    $this->assertEquals(19 * 12 * 2, $spent);
  }

  public function testWhenSetChangedForFullFromWithGelThenTotalSpentAfterMonthMoreThan10Dollars() {

    $user = new User();
    $user->save();

    $order = OrderBuild::construct()
      ->byUser($user)
      ->withMonthlyDelivery()
      ->setProductWithGelSet()
      ->build();

    $time = $order->getTimeAfter("+1 month");
    $defaultSpent = $user->getTotalSpent($time);

    $order->refresh();
    $order->type = Order::TYPE_FULL_SET;
    $order->save(false, ["type"]);

    $user->refresh();
    $afterChangeSpent = $user->getTotalSpent($time);

    $this->assertEquals(10, $afterChangeSpent - $defaultSpent);
  }


}