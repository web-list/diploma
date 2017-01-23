<?php
/**
 * @var $this SiteController
 * @var $model Order
 * @var $time integer
 */

/** @var User $user */
$user = Yii::app()->user->getModel();
$this->widget("zii.widgets.grid.CGridView", [
  "dataProvider" => $model->search(),
  "columns" => [
    [
      'name' => 'created',
      'value' => function ($model) {
        return date("h:i:s d.m.Y", $model->created);
      }
    ],
    [
      'name' => 'delivery_started',
      'value' => function ($model) {
        return date("h:i:s d.m.Y", $model->delivery_started);
      }
    ],
    [
      "name" => "type",
      "value" => function ($model) {
        return Order::$typeTitles[$model->type];
      }
    ],
    "price",
    [
      "name" => "delivery_type",
      "value" => function ($model) {
        return Delivery::$deliveryTypeTitles[$model->delivery_type];
      }
    ],
    "dayLabel",
    [
      "type" => "raw",
      "value" => function ($model) {
        return CHtml::link("Редактировать", ["update", "id" => $model->id]);
      }
    ],
    [
      "type" => "raw",
      "value" => function ($model) {
        return $model->delivery_type == Delivery::DELIVERY_TYPE_NONE ? "" : CHtml::link("Остановить", ["stop", "id" => $model->id]);
      }
    ]
  ]
]);
?>

<hr>

<div class="p20">

  <h2>Отчет:</h2>

  <form style="margin-bottom: 20px;">
    <div class="row">
      <input type="hidden" name="r" value="site/list">
      <input type="text" name="time" value="<?= date("h:i d.m.Y", $time); ?>">
      <input type="submit" value="Посчитать">
    </div>
  </form>

  <?php if ($user instanceof User): ?>
    <h3>Потрачено средств на <?= date("h:i:s d.m.Y", $time); ?>: <strong>$<?= $user->getTotalSpent($time); ?></strong>
    </h3>
  <?php endif; ?>

  <script>
    $(document).ready(function () {
      $.noConflict();
      $("[name=time]").datetimepicker({
        format: "H:i d.m.Y"
      });
    });
  </script>


</div>