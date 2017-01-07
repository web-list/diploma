<?php

/**
 * This is the model class for table "order".
 *
 * The followings are the available columns in table 'order':
 *
 * @property integer $id
 * @property integer $type
 * @property integer $user_id
 */
class Order extends CActiveRecord
{

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
}
