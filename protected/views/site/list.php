<?php
/**
 * @var $this SiteController
 * @var $model Order
 */

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
      "name" => "deliveryType",
      "value" => function ($model) {
        return Order::$deliveryTypeTitles[$model->deliveryType];
      }
    ]
  ]
])
?>