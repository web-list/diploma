<?php

class Period
{

  public $time;

  public function afterHalfMonth() {

    $day = date("j", $this->time);

    if ($day > 15) {
      $this->time = strtotime("+1 month", $this->time);
      $this->time = strtotime("-15 days", $this->time);
    } else {
      $this->time = strtotime("+15 days", $this->time);
    }

  }

  public function beforeHalfMonth() {
    $day = date("j", $this->time);
    if ($day > 15) {
      $this->time = strtotime("-15 days",  $this->time);
    } else {
      $this->time = strtotime("-1 month",  $this->time);
      $this->time = strtotime("+15 days",  $this->time);
    }
  }

  public function afterMonth() {
    $this->time = strtotime("+1 month", $this->time);
  }

  public function beforeMonth() {
    $this->time = strtotime("-1 month", $this->time);
  }

  public function beforeTwoMonths() {
    $this->time = strtotime("-2 month", $this->time);
  }

  public function afterTwoMonths() {
    $this->time = strtotime("+2 month", $this->time);
  }

  public function beforeDeliveryType($deliveryType) {
    if ($deliveryType == Delivery::DELIVERY_TYPE_ONCE_IN_TWO_MONTHS) {
      $this->beforeTwoMonths();
    } elseif ($deliveryType == Delivery::DELIVERY_TYPE_MONTHLY) {
      $this->beforeMonth();
    } elseif ($deliveryType == Delivery::DELIVERY_TYPE_TWICE_A_MONTH) {
      $this->beforeHalfMonth();
    }
  }

}