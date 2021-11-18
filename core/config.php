<?php
 define('APP_CONFIG', [
    'database' => [

    ],
 ]);

 define('VIEW_PATH', dirname(__DIR__) . '/views');
 define('ASSET_PATH', "http://{$_SERVER['SERVER_NAME']}:{$_SERVER['SERVER_PORT']}/assets");