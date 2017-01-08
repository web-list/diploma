<?php

class m170108_033121_delivery_period_fix extends CDbMigration
{

  public function safeUp() {
    $this->addColumn("order", "delivery_type", "varchar(16) not null default 0");
    $this->dropColumn("order", "delivery_interval");
  }

  public function safeDown() {
    $this->addColumn("order", "delivery_interval", "integer(11) not null default 0");
    $this->dropColumn("order", "delivery_type");
  }

}