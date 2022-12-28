<?php

namespace codexjoshy\sleekmvc\base;

use codexjoshy\sleekmvc\Application;

class BaseController
{
 /**
  * Undocumented variable
  *
  * @var string
  */
 public string $layout = "main";
 /**
  * Undocumented variable
  *
  * @var string
  */
 public string $action = "";
 /**
  * Undocumented variable
  *
  * @var BaseMiddleware[]
  */
 public array $middlewares = [];

 /**
  * used to set layout
  *
  * @param [type] $layout
  * @return void
  */
 public function setLayout($layout)
 {
  $this->layout = $layout;
 }

 /**
  * used to get application layout
  *
  * @return string
  */
 public function getLayout(): string
 {
  return $this->layout;
 }



 public function render(string $view, $params = [])
 {
  return Application::$app->view->renderView($view, $params);
 }

 public function registerMiddleware(BaseMiddleware $middleware)
 {
  $this->middlewares[] = $middleware;
 }

 public function getMiddlewares()
 {
  return $this->middlewares;
 }
}
