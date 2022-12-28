<?php

namespace codexjoshy\sleekmvc\middlewares;

use codexjoshy\sleekmvc\Application;
use codexjoshy\sleekmvc\base\BaseMiddleware;
use codexjoshy\sleekmvc\exceptions\ForbiddenException;

class AuthMiddleware extends BaseMiddleware
{
 /**
  * Undocumented variable
  *
  * @var array
  */
 public array $actions = [];

 public function __construct(array $actions = [])
 {
  $this->actions = $actions;
 }
 public function execute()
 {
  if (Application::isGuest()) {
   if (!$this->actions || in_array(Application::$app->controller->action, $this->actions)) {
    throw new ForbiddenException();
   }
  }
 }
}
