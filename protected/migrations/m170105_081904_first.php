<?php

class m170105_081904_first extends CDbMigration
{

  public function safeUp() {
    $this->createTable("user", [
      "id" => "pk",
      "login" => "varchar(255)",
      "password" => "varchar(255)",
    ]);

    $this->createTable("order", [
      "id" => "pk",
      "type" => "integer(11)",
      "user_id" => "integer(11)",
    ]);
  }

  public function safeDown() {
    $this->dropTable("order");
    $this->dropTable("user");
  }

}