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
    ]
  ]
]);
?>

<?php if ($user instanceof User): ?>
  <!--<h2>Потрачено средств на --><?//= date("h:i:s d.m.Y", $time); ?><!--: <strong>$--><?//= $user->getTotalSpent($time); ?><!--</strong>-->
  <!--</h2>-->
<?php endif; ?>