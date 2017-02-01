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
    <?= $form->labelEx($order, "type") ?>
    <?= $form->dropDownList($order, "type", Product::$typeTitles); ?>
  </div>

  <div class="row">
    <?= $form->labelEx($order, "delivery_type") ?>
    <?= $form->dropDownList($order, "delivery_type", Delivery::$deliveryTypeTitles); ?>
  </div>

  <?php foreach (Delivery::$deliveryTypeTitles as $type => $title): ?>
    <?php $days = Delivery::getDeliveryDayOfMonth($type); ?>

    <?php if (count($days)): ?>
      <div class="row js-day" data-type="<?= $type ?>">
        <?= $form->labelEx($order, "deliveryDayOfTheMonth") ?>
        <?= $form->dropDownList($order, "deliveryDayOfTheMonth", $days); ?>
      </div>
    <?php endif; ?>

  <?php endforeach; ?>


  <div class="row">
    <?= CHtml::submitButton("Сохранить изменения"); ?>
  </div>

</div>
<?php $this->endWidget(); ?>

<script>

  var toggleDeliveryDayField = function () {
    $(".js-day").hide();

    var type = $("#Order_delivery_type").val();

    $(".js-day[data-type='" + type + "']").show();

  };

  $("#Order_delivery_type").on("change", toggleDeliveryDayField);
  toggleDeliveryDayField();

</script>