<?php

namespace app\core;

use app\core\exceptions\NotFoundException;

class Router
{
 public Request $request;
 public Response $response;
 public string $title = "Josh";
 protected array $routes = [];

 public function __construct(Request $request, Response $response)
 {
  $this->request = $request;
  $this->response = $response;
 }

 /**
  * get request
  *
  * @param [type] $path
  * @param [type] $callback
  * @return void
  */
 public function get($path, $callback)
 {
  $this->routes['get'][$path] = $callback;
 }
 /**
  * get request
  *
  * @param [type] $path
  * @param [type] $callback
  * @return void
  */
 public function post($path, $callback)
 {
  $this->routes['post'][$path] = $callback;
 }

 public function resolve()
 {
  $path = $this->request->getPath();
  $method = $this->request->method();
  $callback = $this->routes[$method][$path] ?? false;

  if (!$callback) {
   Application::$app->response->setStatusCode(404);
   throw new NotFoundException();
  }
  if (is_string($callback)) {
   return Application::$app->view->renderView($callback);
  }
  if (is_array($callback)) {
   Application::$app->setController(new $callback[0]);
   Application::$app->controller->action = $callback[1];
   $controller = Application::$app->getController();
   foreach ($controller->getMiddlewares() as $middleware) {
    $middleware->execute();
   }
   $callback[0]  = $controller;
  }

  return call_user_func($callback, $this->request, $this->response);
 }
}