<?php

namespace codexjoshy\sleekmvc;

class View
{

 /**
  * Undocumented variable
  *
  * @var string
  */
 public string $title = "";
 /**
  * Undocumented function
  *
  * @param [type] $view
  * @return void
  */
 public function renderView($view, $params = [])
 {
  $viewContents = $this->getViewContents($view, $params);
  $layoutContents = $this->layoutContent();
  return str_replace("{{ content }}", $viewContents, $layoutContents);
  include_once Application::$ROOT_DIR . "/views/$view.php";
 }
 /**
  * Undocumented function
  *
  * @param [type] $view
  * @return void
  */
 public function renderContent($content)
 {
  $layoutContents = $this->layoutContent();
  return str_replace("{{ content }}", $content, $layoutContents);
 }

 /**
  * Undocumented function
  *
  * @return string
  */
 protected function layoutContent()
 {
  $layout = Application::$app->layout;
  if (Application::$app->controller) {
   $layout = Application::$app->controller->layout;
  }
  ob_start(); //caches output
  include_once Application::$ROOT_DIR . "/views/layouts/$layout.php";
  return ob_get_clean();
 }
 /**
  * get content of a file in the view folder
  *
  * @param [type] $view
  * @return array|string
  */
 protected function getViewContents($view, $params = []): array|string
 {

  // foreach ($params as $key => $value) {
  //  $$key = $value;
  // }
  extract($params);
  ob_start();
  include_once Application::$ROOT_DIR . "/views/$view.php";
  return ob_get_clean();
 }
}
