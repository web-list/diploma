<?php

class OrderForm extends CFormModel
{

  public $userLogin;
  public $userPassword;
  public $type;
  public $deliveryType;
  public $deliveryDayOfTheMonth;
  public $userId;
  public $user;

  private function setUser($user) {
    $this->userId = $user->id;
    $this->user = $user;
  }

  public function rules() {
    return [
      ['userLogin, userPassword', 'required', 'on' => 'newOrder'],
      ['userLogin', 'validateLogin', 'safe', 'on' => 'newOrder'],
      ['type, deliveryType, deliveryDayOfTheMonth, userId', 'safe'],
    ];
  }

  public function attributeLabels() {
    return [
      'type' => 'Вариант заказа',
      'deliveryType' => 'Доставка',
      'userLogin' => 'Логин',
      'userPassword' => 'Пароль',
      'deliveryDayOfTheMonth' => 'Дата доставки',
    ];
  }

  public function validateLogin() {

    $user = User::model()->findByAttributes([
      "login" => $this->userLogin,
    ]);

    if ($user instanceof User) {

      if ($this->userPassword) {
        if ($user->password == $this->userPassword) {
          $this->setUser($user);
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
        $this->setUser($user);
      } else {
        $this->addError("userLogin", $user->getError("login"));
        $this->addError("userPassword", $user->getError("password"));
      }
    }

  }

  public function save() {
    if (!$this->validate()) return false;

    $order = new Order($this->scenario);
    $order->type = $this->type;
    $order->delivery_type = $this->deliveryType;
    $order->deliveryDayOfTheMonth = $this->deliveryDayOfTheMonth;
    $order->user_id = $this->userId;

    return $order->save();
  }

}