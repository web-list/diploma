<?php

class m170107_070833_order_interval extends CDbMigration
{

  public function safeUp() {
    $this->addColumn("order", "created", "integer(11)");
    $this->addColumn("order", "delivery_interval", "integer(11) not null default 0");
  }

  public function safeDown() {
    $this->dropColumn("order", "created");
    $this->dropColumn("order", "delivery_interval");
  }

}