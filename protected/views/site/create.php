<?php
/**
 * @var $this SiteController
 * @var $model OrderForm
 */

$this->pageTitle = Yii::app()->name;

/** @var CActiveForm $form */
$form = $this->beginWidget("CActiveForm"); ?>

<div class="form p20">

  <?php if (Yii::app()->user->isGuest): ?>
    <div class="row">
      <?= $form->labelEx($model, "userLogin") ?>
      <?= $form->textField($model, "userLogin"); ?>
      <?= $form->error($model, "userLogin"); ?>
    </div>

    <div class="row">
      <?= $form->labelEx($model, "userPassword") ?>
      <?= $form->passwordField($model, "userPassword"); ?>
      <?= $form->error($model, "userPassword"); ?>
    </div>
  <?php endif; ?>

  <div class="row">
    <?= $form->labelEx($model, "type") ?>
    <?= $form->dropDownList($model, "type", Product::$typeTitles); ?>
  </div>

  <div class="row">
    <?= $form->labelEx($model, "deliveryType") ?>
    <?= $form->dropDownList($model, "deliveryType", Delivery::$deliveryTypeTitles); ?>
  </div>

  <?php foreach (Delivery::$deliveryTypeTitles as $type => $title): ?>
    <?php $days = Delivery::getDeliveryDayOfMonth($type); ?>

    <?php if (count($days)): ?>
      <div class="row js-day" data-type="<?= $type ?>">
        <?= $form->labelEx($model, "deliveryDayOfTheMonth") ?>
        <?= $form->dropDownList($model, "deliveryDayOfTheMonth", $days); ?>
      </div>
    <?php endif; ?>

  <?php endforeach; ?>


  <div class="row">
    <?= CHtml::submitButton("Оформить заказ"); ?>
  </div>

</div>
<?php $this->endWidget(); ?>

<script>

  var toggleDeliveryDayField = function () {
    $(".js-day").hide();

    var type = $("#OrderForm_deliveryType").val();

    $(".js-day[data-type='" + type + "']").show();

  };

  $("#OrderForm_deliveryType").on("change", toggleDeliveryDayField);
  toggleDeliveryDayField();

</script>