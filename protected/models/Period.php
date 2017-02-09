<?php

class Period
{

  public $time;

  public function afterHalfMonth() {

    $day = date("j", $this->time);

    if ($day > 15) {
      $this->time = strtotime("+1 month", $this->time);
      $this->time = strtotime("-15 days", $this->time);
    } else {
      $this->time = strtotime("+15 days", $this->time);
    }

  }

  public function beforeHalfMonth() {
    $day = date("j", $this->time);
    if ($day > 15) {
      $this->time = strtotime("-15 days",  $this->time);
    } else {
      $this->time = strtotime("-1 month",  $this->time);
      $this->time = strtotime("+15 days",  $this->time);
    }
  }

  public function afterMonth() {
    $this->time = strtotime("+1 month", $this->time);
  }

  public function beforeMonth() {
    $this->time = strtotime("-1 month", $this->time);
  }

}