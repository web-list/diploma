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

  public $deliveryDayOfTheMonth;

  const ONE_DAY_SECONDS = 86400; // 60 * 60 * 24

  public function tableName() {
    return 'order';
  }

  public function rules() {
    return [
      ['type, user_id', 'numerical', 'integerOnly' => true],
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
      'price' => 'Стоимость',
      'userLogin' => 'Логин',
      'userPassword' => 'Пароль',
      'deliveryDayOfTheMonth' => 'Дата доставки',
      'dayLabel' => 'Дата доставки',
      'created' => 'Дата оформления',
      'delivery_started' => 'Дата первой доставки',
    ];
  }

  public function getDayLabel() {
    if (!$this->deliveryDayOfTheMonth) return null;
    return Delivery::getLabelByType($this->deliveryDayOfTheMonth, $this->delivery_type, true);
  }

  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

  private function determineDeliveryStart() {
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
  }

  public function beforeSave() {

    if ($this->isNewRecord) {
      $this->created = time();
    }

    $this->determineDeliveryStart();

    return parent::beforeSave();
  }


  public function afterFind() {

    if ($this->delivery_started) {
      $this->deliveryDayOfTheMonth = date("j", $this->delivery_started);
    }

    return parent::afterFind();
  }

  public function stop() {
    $this->delivery_type = Delivery::DELIVERY_TYPE_NONE;
    $this->save(false);
  }

  public function getStartFrom() {
    return $this->delivery_started ?: $this->created;
  }

  public function getDelivery() {
    $delivery = new Delivery();
    $delivery->type = $this->delivery_type;
    $delivery->created = $this->created;
    $delivery->startFrom = $this->getStartFrom();
    return $delivery;
  }

  public function getProduct() {
    $product = new Product();
    $product->type = $this->type;
    return $product;
  }

}
