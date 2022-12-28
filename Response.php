<?php

namespace app\core;

class Response
{
 protected string $redirectUrl = '/';

 public function setStatusCode(int $code)
 {
  http_response_code($code);
 }

 public function redirect(string $url)
 {
  $this->redirectUrl = $url;
  return $this;
 }
 public function with(string $key, string $value)
 {
  Application::$app->session->setFlash($key, $value);
  header("Location: {$this->redirectUrl}");
  exit;
 }
}