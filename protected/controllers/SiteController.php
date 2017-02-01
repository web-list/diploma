<?php

class SiteController extends Controller
{

  public function filters() {
    return [
      'accessControl', // perform access control for CRUD operations
    ];
  }

  public function accessRules() {
    return [
      [
        'allow',
        'actions' => ["index", "error", "login"],
        "users" => ["*"],
      ],
      [
        'allow',
        "actions" => ["list", "logout", "stop", "update"],
        "users" => ["@"],
      ],
      [
        "deny",
        "users" => ["*"],
      ]
    ];
  }

  public $layout = 'main';

  public function actionIndex() {
    $model = new OrderForm(Yii::app()->user->isGuest ? "newOrder" : "create");
    if (!Yii::app()->user->isGuest) $model->userId = Yii::app()->user->id;

    if ($_POST["OrderForm"]) {
      $model->attributes = $_POST["OrderForm"];

      if ($model->save()) {

        if (Yii::app()->user->isGuest && $model->userId) {
          $form = new LoginForm();
          $form->username = $model->user->login;
          $form->password = $model->user->password;
          $form->login();
        }
        $this->redirect(["list"]);
      }

      //var_dump($model->getErrors());
      //Yii::app()->end();

    }

    $this->render('create', [
      "model" => $model,
    ]);
  }

  public function actionList($time = null) {
    if (!$time) {
      $time = time();
    } else {
      $time = strtotime($time);
    }

    $model = new OrderSearch("search");
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

  public function actionStop($id) {
    $model = $this->loadModel($id);
    $model->stop();
    $this->redirect(["list"]);
  }

  public function actionUpdate($id) {
    $model = $this->loadModel($id);

    if ($_POST["Order"]) {
      $model->attributes = $_POST["Order"];

      if ($model->save()) {
        $this->redirect(["list"]);
      }

    }
    $this->render("update", ["order" => $model]);
  }

  /**
   * @param $id
   * @return Order
   * @throws CHttpException
   */
  private function loadModel($id) {
    $model = Order::model()->findByPk($id);
    if (!$model instanceof Order) throw new CHttpException(404);
    return $model;
  }
}