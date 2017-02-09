<?php

class Period
{

  public $time;

  public function plusHalfMonth() {

    $day = date("j", $this->time);

    if ($day > 15) {
      $this->time = strtotime("+1 month", $this->time);
      $this->time = strtotime("-15 days", $this->time);
    } else {
      $this->time = strtotime("+15 days", $this->time);
    }

  }

  public function minusHalfMonth() {
    $day = date("j", $this->time);
    if ($day > 15) {
      $this->time = strtotime("-15 days",  $this->time);
    } else {
      $this->time = strtotime("-1 month",  $this->time);
      $this->time = strtotime("+15 days",  $this->time);
    }
  }

}