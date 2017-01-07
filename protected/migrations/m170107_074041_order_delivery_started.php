<?php

class m170107_074041_order_delivery_started extends CDbMigration
{
  public function safeUp() {
    $this->addColumn("order", "delivery_started", "integer(11)");
  }

  public function safeDown() {
    $this->dropColumn("order", "delivery_started");
  }
}