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

  public function tableName() {
    return 'order';
  }

  public function rules() {
    return [
      ['type, user_id', 'numerical', 'integerOnly' => true],
      ['userLogin, userPassword', 'required', 'on' => 'newOrder'],
      ['userLogin', 'validateLogin', 'on' => 'newOrder'],
      ['delivery_type', 'length', 'max' => 32],
      ['deliveryDayOfTheMonth', 'safe'],
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
      'price' => 'Стоимость',
      'userLogin' => 'Логин',
      'userPassword' => 'Пароль',
      'deliveryDayOfTheMonth' => 'Дата доставки',
      'dayLabel' => 'Дата доставки',
      'created' => 'Дата оформления',
      'delivery_started' => 'Дата первой доставки',
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
    }

    if ($this->deliveryDayOfTheMonth) {
      $this->delivery_started = mktime(
        date("h", $this->created),
        date("i", $this->created),
        date("s", $this->created),
        date("n", $this->created),
        $this->deliveryDayOfTheMonth,
        date("Y", $this->created)
      );

      if ($this->delivery_started < $this->created) {
        $this->delivery_started = $this->getDelivery()->nextDelivery($this->delivery_started) ?: $this->created;
      }
    }

    return parent::beforeSave();
  }


  public function afterFind() {

    if ($this->delivery_started) {
      $this->deliveryDayOfTheMonth = date("j", $this->delivery_started);
    }

    return parent::afterFind();
  }

  public function getDelivery() {
    $Delivery = new Delivery();
    $Delivery->type = $this->delivery_type;
    return $Delivery;
  }

  public function periodElapsed($time = null) {
    if (!$time) $time = time();

    $firstTime = $this->getStartFrom();

    if ($time < $firstTime) return 0;
    if ($this->delivery_type == Delivery::DELIVERY_TYPE_NONE) return 1;

    $count = 0;
    while ($time > $firstTime) {
      $time = $this->getDelivery()->previousDelivery($time) ?: $this->created;
      $count++;
    }
    $count = $count ?: 1;

    return $count;
  }

  public function deliveredInTimestamp($timestamp) {
    if ($this->delivery_type == Delivery::DELIVERY_TYPE_NONE) return false;

    $firstTime = $this->getStartFrom();
    $time = $timestamp;

    while ($time > $firstTime) {
      $time = $this->getDelivery()->previousDelivery($time) ?: $this->created;
    }

    return $time == $firstTime;

  }

  public function stop() {
    $this->delivery_type = Delivery::DELIVERY_TYPE_NONE;
    $this->save(false);
  }

  public function getStartFrom() {
    return $this->delivery_started ?: $this->created;
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

  public function getDayLabel() {
    if (!$this->deliveryDayOfTheMonth) return null;
    return Delivery::getLabelByType($this->deliveryDayOfTheMonth, $this->delivery_type, true);
  }

}
