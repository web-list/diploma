<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel
{
  public $username;
  public $password;
  public $rememberMe;

  private $_identity;

  public function rules() {
    return [
      ['username, password', 'required'],
      ['rememberMe', 'boolean'],
      ['password', 'authenticate'],
    ];
  }

  public function attributeLabels() {
    return [
      'username' => 'Логин',
      'password' => 'Пароль',
      'rememberMe' => 'Запомнить меня',
    ];
  }

  public function authenticate($attribute, $params) {
    if (!$this->hasErrors()) {
      $this->_identity = new UserIdentity($this->username, $this->password);
      if (!$this->_identity->authenticate())
        $this->addError('password', 'Incorrect username or password.');
    }
  }

  /**
   * @return boolean whether login is successful
   */
  public function login() {
    if ($this->_identity === null) {
      $this->_identity = new UserIdentity($this->username, $this->password);
      $this->_identity->authenticate();
    }
    if ($this->_identity->errorCode === UserIdentity::ERROR_NONE) {
      $duration = $this->rememberMe ? 3600 * 24 * 30 : 0; // 30 days
      Yii::app()->user->login($this->_identity, $duration);
      return true;
    } else
      return false;
  }
}
