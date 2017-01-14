<?php

class OrderBuild extends Order
{

  /**
   * @return OrderBuild
   */
  public static function construct() {
    return new self;
  }

  /**
   * @param $user
   * @return $this self
   */
  public function byUser($user) {
    $this->user_id = $user->id;
    return $this;
  }

  /**
   * @return $this self
   */
  public function withMonthlyDelivery() {
    $this->delivery_type = Order::DELIVERY_TYPE_MONTHLY;
    return $this;
  }

  /**
   * @return $this self
   */
  public function withTwiceInMonthDelivery() {
    $this->delivery_type = Order::DELIVERY_TYPE_TWICE_A_MONTH;
    return $this;
  }

  /**
   * @return $this self
   */
  public function setProductWithGelSet() {
    $this->type = Order::TYPE_WITH_GEL_SET;
    return $this;
  }

  /**
   * @return $this self
   */
  public function setProductFullSet() {
    $this->type = Order::TYPE_FULL_SET;
    return $this;
  }

  /**
   * @return $this self
   */
  public function build() {
    $this->save();
    return $this;
  }

  public function getTimeAfter($after) {
    return strtotime($after, $this->getStartFrom());
  }

}