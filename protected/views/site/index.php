<?php
/**
 * @var $this SiteController
 * @var $order Order
 */

$this->pageTitle = Yii::app()->name;

/** @var CActiveForm $form */
$form = $this->beginWidget("CActiveForm"); ?>

<div class="form p20">

  <div class="row">
    <?= $form->labelEx($order, "userLogin") ?>
    <?= $form->textField($order, "userLogin"); ?>
    <?= $form->error($order, "userLogin"); ?>
  </div>

  <div class="row">
    <?= $form->labelEx($order, "userPassword") ?>
    <?= $form->passwordField($order, "userPassword"); ?>
    <?= $form->error($order, "userPassword"); ?>
  </div>

  <div class="row">
    <?= $form->labelEx($order, "type") ?>
    <?= $form->dropDownList($order, "type", Order::$typeTitles); ?>
  </div>

  <div class="row">
    <?= $form->labelEx($order, "deliveryType") ?>
    <?= $form->dropDownList($order, "deliveryType", Order::$deliveryTypeTitles); ?>
  </div>

  <?php foreach (Order::$deliveryTypeTitles as $type => $title): ?>
    <?php $days = Order::getDeliveryDayOfMonth($type); ?>

    <?php if (count($days)): ?>
      <div class="row js-day" data-type="<?= $type ?>">
        <?= $form->labelEx($order, "deliveryDayOfTheMonth") ?>
        <?= $form->dropDownList($order, "deliveryDayOfTheMonth", $days); ?>
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

    var type = $("#Order_deliveryType").val();

    $(".js-day[data-type='" + type + "']").show();

  };

  $("#Order_deliveryType").on("change", toggleDeliveryDayField);
  toggleDeliveryDayField();

</script>
