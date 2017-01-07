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

  public function getPrice() {
    return self::$typePrices[$this->type];
  }

}
