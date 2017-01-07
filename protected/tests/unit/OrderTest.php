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

}