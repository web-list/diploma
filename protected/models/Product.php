<?php

class Product
{

  public $type;

  const TYPE_ONE_SHAVER_SET = 0;
  const TYPE_WITH_GEL_SET = 1;
  const TYPE_FULL_SET = 2;

  public static $typeTitles = [
    self::TYPE_ONE_SHAVER_SET => "только бритвенный станок",
    self::TYPE_WITH_GEL_SET => "бритвенный станок + гель для бритья",
    self::TYPE_FULL_SET => "бритвенный станок + гель + средство после бритья",
  ];

  public static $typePrices = [
    self::TYPE_ONE_SHAVER_SET => 1,
    self::TYPE_WITH_GEL_SET => 9,
    self::TYPE_FULL_SET => 19,
  ];

  public function getPrice() {
    return self::$typePrices[$this->type];
  }

}