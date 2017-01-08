<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm */

$this->pageTitle = Yii::app()->name . ' - Login';
?>

<div class="p20">
  <h1>Авторизация</h1>


  <div class="form">
    <?php $form = $this->beginWidget('CActiveForm', [
      'id' => 'login-form',
      'enableClientValidation' => true,
      'clientOptions' => [
        'validateOnSubmit' => true,
      ],
    ]); ?>

    <div class="row">
      <?php echo $form->labelEx($model, 'username'); ?>
      <?php echo $form->textField($model, 'username'); ?>
      <?php echo $form->error($model, 'username'); ?>
    </div>

    <div class="row">
      <?php echo $form->labelEx($model, 'password'); ?>
      <?php echo $form->passwordField($model, 'password'); ?>
      <?php echo $form->error($model, 'password'); ?>
    </div>

    <div class="row rememberMe">
      <?php echo $form->checkBox($model, 'rememberMe'); ?>
      <?php echo $form->label($model, 'rememberMe'); ?>
      <?php echo $form->error($model, 'rememberMe'); ?>
    </div>

    <div class="row buttons">
      <?php echo CHtml::submitButton('Войти'); ?>
    </div>

    <?php $this->endWidget(); ?>
  </div><!-- form -->
