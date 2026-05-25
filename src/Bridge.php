<?php

namespace classicframework\cookie;

use classicframework\core\App;
use classicframework\core\Config;
use classicframework\core\BridgeInterface;

class Bridge implements BridgeInterface
{
  public static function register(App $app)
  {
    $config = Config::extract('cookie');
    $cookie = new Cookie($config);

    $app->set_service('cookie', $cookie);
  }
}