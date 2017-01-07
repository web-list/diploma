<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 *
 * @property integer $id
 * @property string $login
 * @property string $password
 */
class User extends CActiveRecord
{

  public function tableName() {
    return 'user';
  }

  public function rules() {
    return [
      ['login, password', 'length', 'max' => 255],
      ['id, login, password', 'safe', 'on' => 'search'],
    ];
  }

  public function relations() {
    return [
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
}
