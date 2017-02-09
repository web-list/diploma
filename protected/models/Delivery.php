<?php

class Delivery
{

  public $type = self::DELIVERY_TYPE_NONE;
  public $startFrom;
  public $created;

  const DELIVERY_TYPE_NONE = 'none';
  const DELIVERY_TYPE_ONCE_IN_TWO_MONTHS = 'once_in_two_months';
  const DELIVERY_TYPE_MONTHLY = 'monthly';
  const DELIVERY_TYPE_TWICE_A_MONTH = 'twice_a_month';

  public static $deliveryTypeTitles = [
    self::DELIVERY_TYPE_NONE => "Не доставлять",
    self::DELIVERY_TYPE_ONCE_IN_TWO_MONTHS => "Раз в два месяца",
    self::DELIVERY_TYPE_MONTHLY => "Раз в месяц",
    self::DELIVERY_TYPE_TWICE_A_MONTH => "Два раза в месяц",
  ];

  public static $deliveryTypeIntervals = [
    self::DELIVERY_TYPE_ONCE_IN_TWO_MONTHS => 60,
    self::DELIVERY_TYPE_MONTHLY => 30,
    self::DELIVERY_TYPE_TWICE_A_MONTH => 15,
    self::DELIVERY_TYPE_NONE => 0,
  ];

  public function getPeriod() {
    $period = new Period();

    return $period;
  }

  public function nextDelivery() {
    $period = $this->getPeriod();

    if ($this->type == self::DELIVERY_TYPE_ONCE_IN_TWO_MONTHS) {
      $period->afterTwoMonths();
    } elseif ($this->type == self::DELIVERY_TYPE_MONTHLY) {
      $period->afterMonth();
    } elseif ($this->type == self::DELIVERY_TYPE_TWICE_A_MONTH) {
      $period->afterHalfMonth();
    } else {
      return null;
    }

    return $period->time;
  }

  public function previousDelivery() {
    $period = $this->getPeriod();

    if ($this->type == self::DELIVERY_TYPE_ONCE_IN_TWO_MONTHS) {
      $period->beforeTwoMonths();
    } elseif ($this->type == self::DELIVERY_TYPE_MONTHLY) {
      $period->beforeMonth();
    } elseif ($this->type == self::DELIVERY_TYPE_TWICE_A_MONTH) {
      $period->beforeHalfMonth();
    } else {
      return null;
    }

    return $period->time;
  }

  public static function getLabelByType($day, $deliveryType, $full = false) {
    $label = null;

    if ($deliveryType == self::DELIVERY_TYPE_ONCE_IN_TWO_MONTHS) {
      $label = "$day числа каждого второго месяца";
    }
    if ($deliveryType == self::DELIVERY_TYPE_MONTHLY) {
      $label = "$day числа каждого месяца";
    }
    if ($deliveryType == self::DELIVERY_TYPE_TWICE_A_MONTH) {
      if ($day <= 15)
        $label = "$day и " . ($day + 15) . " числа каждого месяца";
      if ($day > 15 && $full) {
        $label = ($day - 15) . " и $day числа каждого месяца";
      }
    }

    return $label;
  }

  public static function getDeliveryDayOfMonth($deliveryType) {
    $array = [];

    for ($i = 0; $i++ < 30;) {

      $label = self::getLabelByType($i, $deliveryType);

      if (!$label) continue;

      $array[$i] = $label;
    }

    return $array;
  }

  public function countOfPeriodElapsed($time = null) {
    if (!$time) $time = time();

    $firstTime = $this->startFrom;

    if ($time < $firstTime) return 0;
    if ($this->type == Delivery::DELIVERY_TYPE_NONE) return 1;

    $count = 0;
    while ($time > $firstTime) {
      $time = $this->previousDelivery($time) ?: $this->created;
      $count++;
    }
    $count = $count ?: 1;

    return $count;
  }

  public function makeNow($timestamp) {
    if ($this->type == Delivery::DELIVERY_TYPE_NONE) return false;

    $firstTime = $this->startFrom;
    $time = $timestamp;

    while ($time > $firstTime) {
      $time = $this->previousDelivery($time) ?: $this->created;
    }

    return $time == $firstTime;
  }

}