<?php

/**
 * This is the model class for table "order".
 *
 * The followings are the available columns in table 'order':
 *
 * @property integer $id
 * @property integer $type
 * @property integer $user_id
 * @property integer $delivery_interval
 * @property integer $created
 */
class Order extends CActiveRecord
{

  public $deliveryType;

  const ONE_DAY_SECONDS = 60 * 60 * 24;

  const TYPE_ONE_SHAVER_SET = 0;
  const TYPE_WITH_GEL_SET = 1;
  const TYPE_FULL_SET = 2;

  const DELIVERY_TYPE_NONE = 'none';
  const DELIVERY_TYPE_ONCE_IN_TWO_MONTHS = 'once_in_two_months';
  const DELIVERY_TYPE_MONTHLY = 'monthly';
  const DELIVERY_TYPE_TWICE_A_MONTH = 'twice_a_month';

  public static $typeTitles = [
    self::TYPE_ONE_SHAVER_SET => "только бритвенный станок",
    self::TYPE_WITH_GEL_SET => "бритвенный станок + гель для бритья",
    self::TYPE_FULL_SET => "бритвенный станок + гель + средство после бритья",
  ];

  public static $deliveryTypeTitles = [
    self::DELIVERY_TYPE_NONE => "Не доставлять",
    self::DELIVERY_TYPE_ONCE_IN_TWO_MONTHS => "Раз в два месяца",
    self::DELIVERY_TYPE_MONTHLY => "Раз в месяц",
    self::DELIVERY_TYPE_TWICE_A_MONTH => "Два раза в месяц",
  ];

  public static $typePrices = [
    self::TYPE_ONE_SHAVER_SET => 1,
    self::TYPE_WITH_GEL_SET => 9,
    self::TYPE_FULL_SET => 19,
  ];

  public static $deliveryTypeIntervals = [
    self::DELIVERY_TYPE_ONCE_IN_TWO_MONTHS => 60,
    self::DELIVERY_TYPE_MONTHLY => 30,
    self::DELIVERY_TYPE_TWICE_A_MONTH => 15,
    self::DELIVERY_TYPE_NONE => 0,
  ];

  public function tableName() {
    return 'order';
  }

  public function rules() {
    return [
      ['type, user_id', 'numerical', 'integerOnly' => true],
      ['id, type, user_id', 'safe', 'on' => 'search'],
    ];
  }

  public function relations() {
    return [
    ];
  }

  public function attributeLabels() {
    return [
      'id' => 'ID',
      'type' => 'Type',
      'user_id' => 'User',
    ];
  }

  public function search() {
    $criteria = new CDbCriteria;

    $criteria->compare('id', $this->id);
    $criteria->compare('type', $this->type);
    $criteria->compare('user_id', $this->user_id);

    return new CActiveDataProvider($this, [
      'criteria' => $criteria,
    ]);
  }

  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

  public function getPrice() {
    return self::$typePrices[$this->type];
  }

  public function beforeSave() {
    $this->created = time();
    $this->delivery_interval = self::$deliveryTypeIntervals[$this->deliveryType];

    return parent::beforeSave();
  }

  public function afterFind() {

    foreach (self::$deliveryTypeIntervals as $type => $interval) {
      if ($interval == $this->delivery_interval) {
        $this->deliveryType = $type;
      }
    }

    return parent::afterFind();
  }

  public function deliveredInTimestamp($timestamp) {
    $mod = (($timestamp - $this->created) / self::ONE_DAY_SECONDS) % $this->delivery_interval;

    return $mod === 0;
  }

}
