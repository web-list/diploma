<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 *
 * @property integer $id
 * @property string $login
 * @property string $password
 *
 * @property Order[] $orders
 */
class User extends CActiveRecord
{

  public function tableName() {
    return 'user';
  }

  public function rules() {
    return [
      ['login, password', 'length', 'max' => 255],
      ['login', 'unique'],
      ['id, login, password', 'safe', 'on' => 'search'],
    ];
  }

  public function relations() {
    return [
      'orders' => [self::HAS_MANY, "Order", "user_id"],
    ];
  }

  public function attributeLabels() {
    return [
      'id' => 'ID',
      'login' => 'Login',
      'password' => 'Password',
    ];
  }

  public function search() {
    $criteria = new CDbCriteria;

    $criteria->compare('id', $this->id);
    $criteria->compare('login', $this->login, true);
    $criteria->compare('password', $this->password, true);

    return new CActiveDataProvider($this, [
      'criteria' => $criteria,
    ]);
  }

  public static function model($className = __CLASS__) {
    return parent::model($className);
  }

  public function getTotalSpent($timestamp = null) {
    if (!$timestamp) $timestamp = time();

    $spent = 0;
    if ($this->orders) foreach ($this->orders as $order) {

      $periodCount = $order->getDelivery()->countOfPeriodElapsed($timestamp);
      $orderSpent = $periodCount * $order->getProduct()->getPrice();

      $spent += $orderSpent;
    }

    return $spent;
  }

}
