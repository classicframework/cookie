<?php

namespace classicframework\cookie;

class Cookie
{
  protected $config = array();

  public function __construct($config = array())
  {
    $this->config = is_array($config) ? $config : array();
  }

  public function get($name, $default = null)
  {
    $name = (string) $name;

    if (array_key_exists($name, $_COOKIE)) {
      return $_COOKIE[$name];
    }

    return $default;
  }

  public function set($name, $value, $expire = null, $path = null, $domain = null, $secure = null, $httponly = null)
  {
    if (headers_sent()) {
      return false;
    }

    $name = (string) $name;
    $value = (string) $value;

    if ($expire === null) {
      $expire = isset($this->config['expire']) ? (int) $this->config['expire'] : 0;
    }

    if ($expire > 0) {
      $expire = time() + $expire;
    }

    $path = $path !== null ? (string) $path : $this->option('path', '/');
    $domain = $domain !== null ? (string) $domain : $this->option('domain', '');
    $secure = $secure !== null ? (bool) $secure : (bool) $this->option('secure', false);
    $httponly = $httponly !== null ? (bool) $httponly : (bool) $this->option('httponly', true);

    $result = setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);

    if ($result) {
      $_COOKIE[$name] = $value;
    }

    return $result;
  }

  public function has($name)
  {
    return array_key_exists((string) $name, $_COOKIE);
  }

  public function delete($name, $path = null, $domain = null, $secure = null, $httponly = null)
  {
    if (headers_sent()) {
      return false;
    }

    $name = (string) $name;

    $path = $path !== null ? (string) $path : $this->option('path', '/');
    $domain = $domain !== null ? (string) $domain : $this->option('domain', '');
    $secure = $secure !== null ? (bool) $secure : (bool) $this->option('secure', false);
    $httponly = $httponly !== null ? (bool) $httponly : (bool) $this->option('httponly', true);

    $result = setcookie($name, '', time() - 42000, $path, $domain, $secure, $httponly);

    if (array_key_exists($name, $_COOKIE)) {
      unset($_COOKIE[$name]);
    }

    return $result;
  }

  public function all()
  {
    return $_COOKIE;
  }

  protected function option($name, $default = null)
  {
    $name = (string) $name;

    if (array_key_exists($name, $this->config)) {
      return $this->config[$name];
    }

    return $default;
  }
}