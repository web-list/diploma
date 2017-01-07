<?php

class CommonTest extends CTestCase
{

  public function testItWorks() {
    $a = 1;

    $b = $a;

    $this->assertEquals(1, $b);
  }

  public function testDatabaseWorks() {
    $user = new User;
    $user->setAttributes([
      "login" => "test",
    ]);

    $user->save(false);

    $search = User::model()->findByAttributes([
      'login' => "test"
    ]);

    $this->assertTrue($search instanceof User);
  }

}