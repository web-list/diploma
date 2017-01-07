<?php

return CMap::mergeArray(
  require(dirname(__FILE__) . '/main.php'),
  [
    'components' => [
      'fixture' => [
        'class' => 'system.test.CDbFixtureManager',
      ],
      'db' => [
        'connectionString' => 'sqlite:' . dirname(__FILE__) . '/../data/testdrive.db',
      ],
    ],
  ]
);
