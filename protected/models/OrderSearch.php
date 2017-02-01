<?php

class OrderSearch extends Order
{

  public function search() {
    $criteria = new CDbCriteria;

    $criteria->compare('id', $this->id);
    $criteria->compare('type', $this->type);
    $criteria->compare('user_id', $this->user_id);

    return new CActiveDataProvider($this, [
      'criteria' => $criteria,
    ]);
  }

}