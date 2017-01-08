<?php

class SiteController extends Controller
{

  public $layout = 'main';

  public function actionIndex() {
    $order = new Order(Yii::app()->user->isGuest ? "newOrder" : "create");
    if (!Yii::app()->user->isGuest) $order->user_id = Yii::app()->user->id;

    if ($_POST["Order"]) {
      $order->attributes = $_POST["Order"];

      if ($order->save()) {
        $this->redirect(["success", "orderId" => $order->id]);
      }

    }

    $this->render('index', [
      "order" => $order,
    ]);
  }

  public function actionList($time = null) {
    if (!$time) $time = time();

    $model = new Order("search");
    $model->unsetAttributes();
    $model->user_id = Yii::app()->user->id;

    $this->render("list", [
      "model" => $model,
      "time" => $time,
    ]);
  }

  public function actionError() {
    if ($error = Yii::app()->errorHandler->error) {
      if (Yii::app()->request->isAjaxRequest)
        echo $error['message'];
      else
        $this->render('error', $error);
    }
  }

  public function actionLogin() {
    $model = new LoginForm;

    if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
      echo CActiveForm::validate($model);
      Yii::app()->end();
    }

    if (isset($_POST['LoginForm'])) {
      $model->attributes = $_POST['LoginForm'];
      if ($model->validate() && $model->login())
        $this->redirect(Yii::app()->user->returnUrl);
    }
    $this->render('login', ['model' => $model]);
  }

  public function actionLogout() {
    Yii::app()->user->logout();
    $this->redirect(Yii::app()->homeUrl);
  }

  public function actionSuccess($orderId) {
    $order = Order::model()->findByPk($orderId);

    $this->render("success", [
      "order" => $order,
    ]);
  }

}