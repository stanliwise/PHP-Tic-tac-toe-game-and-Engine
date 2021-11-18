<?php
include dirname(__DIR__) . '/core/config.php';
include dirname(__DIR__) . '/Models/BaseModel.php';
include dirname(__DIR__) . './Models/Cars.php';

$view_data = [
    'AvailableCars' => Cars::AvailableCars(),
];


include dirname(__DIR__) . '/views/index.php';