<?php

/**
 * This is the model class for table "order".
 *
 * The followings are the available columns in table 'order':
 *
 * @property integer $id
 * @property integer $type
 * @property integer $user_id
 * @property integer $delivery_type
 * @property integer $created
 * @property integer $delivery_started
 *
 * @property User $user
 */
class Order extends CActiveRecord
{

  public $userLogin;
  public $userPassword;

  public $deliveryDayOfTheMonth;

  const ONE_DAY_SECONDS = 86400; // 60 * 60 * 24

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
      ['type, user_id, deliveryDayOfTheMonth', 'numerical', 'integerOnly' => true],
      ['userLogin, userPassword', 'required', 'on' => 'newOrder'],
      ['userLogin', 'validateLogin', 'on' => 'newOrder'],
      ['delivery_type', 'length', 'max' => 32],
      ['id, type, user_id', 'safe', 'on' => 'search'],
    ];
  }

  public function relations() {
    return [
      'user' => [self::BELONGS_TO, "User", "user_id"],
    ];
  }

  public function attributeLabels() {
    return [
      'id' => 'ID',
      'type' => 'Вариант заказа',
      'delivery_type' => 'Доставка',
      'user_id' => 'Пользователь',
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

    if ($this->isNewRecord) {
      $this->created = time();

      if ($this->deliveryDayOfTheMonth) {
        $this->delivery_started = mktime(
          date("h"),
          date("i"),
          date("s"),
          date("n") + 1,
          $this->deliveryDayOfTheMonth,
          date("Y")
        );
      }
    }

    return parent::beforeSave();
  }

  public function previousDelivery($time) {
    if ($this->delivery_type == self::DELIVERY_TYPE_ONCE_IN_TWO_MONTHS) {
      return strtotime("-2 month", $time);
    } elseif ($this->delivery_type == self::DELIVERY_TYPE_MONTHLY) {
      return strtotime("-1 month", $time);
    } elseif ($this->delivery_type == self::DELIVERY_TYPE_TWICE_A_MONTH) {
      $day = date("j", $time);
      if ($day > 15) {
        return strtotime("-15 days", $time);
      } else {
        $time = strtotime("-1 month", $time);
        return strtotime("+15 days", $time);
      }
    } else {
      return $this->created;
    }

  }

  public function periodElapsed($time = null) {
    if (!$time) $time = time();

    $firstTime = $this->getStartFrom();

    $count = 0;
    while ($time > $firstTime) {
      $time = $this->previousDelivery($time);
      $count++;
    }

    return $count;
  }

  public function deliveredInTimestamp($timestamp) {
    if ($this->delivery_type == self::DELIVERY_TYPE_NONE) return false;

    $firstTime = $this->getStartFrom();
    $time = $timestamp;

    while ($time > $firstTime) {
      $time = $this->previousDelivery($time);
    }

    return $time == $firstTime;

  }

  public function stop() {
    $this->delivery_type = self::DELIVERY_TYPE_NONE;
    $this->save(false);
  }

  public function getStartFrom() {
    return $this->delivery_started ?: $this->created;
  }

  public static function getDeliveryDayOfMonth($deliveryType) {
    $array = [];

    for ($i = 0; $i++ < 30;) {

      $label = null;

      if ($deliveryType == self::DELIVERY_TYPE_ONCE_IN_TWO_MONTHS) {
        $label = "$i числа каждого второго месяца";
      }
      if ($deliveryType == self::DELIVERY_TYPE_MONTHLY) {
        $label = "$i числа каждого месяца";
      }
      if ($deliveryType == self::DELIVERY_TYPE_TWICE_A_MONTH) {
        if ($i > 15) continue;
        $label = "$i и " . ($i + 15) . " числа каждого месяца";
      }

      if (!$label) continue;

      $array[$i] = $label;
    }

    return $array;
  }

  public function validateLogin() {

    $user = User::model()->findByAttributes([
      "login" => $this->userLogin,
    ]);

    if ($user instanceof User) {

      if ($this->userPassword) {
        if ($user->password == $this->userPassword) {
          $this->user_id = $user->id;
        } else {

          $this->addError("userPassword", "Неверный пароль");

        }
      } else {
        $this->addError("userPassword", "Логин занят");
      }

    } else {
      $user = new User();
      $user->login = $this->userLogin;
      $user->password = $this->userPassword;
      if ($user->save()) {
        $this->user_id = $user->id;
      } else {
        $this->addError("userLogin", $user->getError("login"));
        $this->addError("userPassword", $user->getError("password"));
      }
    }

  }

}
