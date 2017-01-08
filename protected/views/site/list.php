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
        return Order::$deliveryTypeTitles[$model->delivery_type];
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
        return $model->delivery_type == Order::DELIVERY_TYPE_NONE ? "" : CHtml::link("Остановить", ["stop", "id" => $model->id]);
      }
    ]
  ]
]);
?>

<?php if ($user instanceof User): ?>
  <h2>Потрачено средств на <?= date("h:i:s d.m.Y", $time); ?>: <strong>$<?= $user->getTotalSpent($time); ?></strong>
  </h2>
<?php endif; ?>