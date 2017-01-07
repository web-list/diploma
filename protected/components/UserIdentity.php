<?php

class UserIdentity extends CUserIdentity
{

  protected $_id;

  /**
   * @return boolean whether authentication succeeds.
   */
  public function authenticate() {

    $userSearch = User::model()->findByAttributes([
      "login" => $this->username,
    ]);

    if (!$userSearch) {
      $this->errorCode = self::ERROR_USERNAME_INVALID;
    } else {
      if ($userSearch->password != $this->password) {
        $this->errorCode = self::ERROR_PASSWORD_INVALID;
      } else {
        $this->errorCode = self::ERROR_NONE;
        $this->_id = $userSearch->id;
      }
    }

    return !$this->errorCode;
  }

  public function getId() {
    return $this->_id;
  }

}