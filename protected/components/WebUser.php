<?php

/**
 * Текущий пользователь
 *
 * @property User $model
 */
class WebUser extends CWebUser
{
  private $_model = null;

  function getUsername() {
    if ($user = $this->getModel()) {
      return $user->login ?: $user->first_name;
    } else {
      return false;
    }
  }

  /**
   * @return User
   */
  public function getModel() {
    if (!$this->isGuest && $this->_model === null) {
      $this->_model = User::model()->findByPk($this->id);
    }
    return $this->_model;
  }
}
