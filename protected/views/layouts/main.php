<?php /* @var $this Controller */

Yii::app()->clientScript->registerCoreScript("jquery");

?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta name="language" content="en">

  <!-- blueprint CSS framework -->
  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection">
  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print">
  <!--[if lt IE 8]>
  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection">
  <![endif]-->

  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css">
  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css">

  <title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="container" id="page">

  <div id="header">
    <div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
  </div><!-- header -->

  <div id="mainmenu">
    <?php $this->widget('zii.widgets.CMenu', [
      'items' => [
        ['label' => 'Заказ', 'url' => ['/site/index']],
        ['label' => 'Войти', 'url' => ['/site/login'], 'visible' => Yii::app()->user->isGuest],
        ['label' => 'Выйти (' . Yii::app()->user->name . ')', 'url' => ['/site/logout'], 'visible' => !Yii::app()->user->isGuest]
      ],
    ]); ?>
  </div><!-- mainmenu -->
  <?php if (isset($this->breadcrumbs)): ?>
    <?php $this->widget('zii.widgets.CBreadcrumbs', [
      'links' => $this->breadcrumbs,
    ]); ?><!-- breadcrumbs -->
  <?php endif ?>

  <?php echo $content; ?>

  <div class="clear"></div>

</div><!-- page -->

</body>
</html>