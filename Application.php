<?php

namespace app\core;

use app\core\base\BaseController;
use app\core\db\Database;
use app\core\db\DbModel;

class Application
{
 /**
  * Undocumented variable
  *
  * @var string
  */
 public static string $ROOT_DIR;
 /**
  * Undocumented variable
  *
  * @var Router
  */
 public Router $router;
 /**
  * Undocumented variable
  *
  * @var Request
  */
 public Request $request;
 /**
  * Undocumented variable
  *
  * @var Response
  */
 public Response $response;
 /**
  * Undocumented variable
  *
  * @var Session
  */
 public Session $session;

 /**
  * Undocumented variable
  *
  * @var Application
  */
 public static Application $app;

 /**
  * Undocumented variable
  *
  * @var Database
  */
 public Database $db;

 /**
  * Undocumented variable
  *
  * 
  */
 public  $userClass;


 public  ?UserModel $user;
 public string $layout = "main";
 public ?BaseController $controller = null;
 public View $view;

 public function __construct($rootPath, array $config)
 {
  self::$ROOT_DIR = $rootPath;
  self::$app = $this;
  $this->request = new Request;
  $this->response = new Response;
  $this->session = new Session;
  $this->router = new Router($this->request, $this->response);
  $this->view = new View;
  $this->db = new Database($config['db']);
  $this->userClass = $config['userClass'] ?? null;
  if ($this->userClass && class_exists($this->userClass)) {
   $this->userClass = new $this->userClass;
  }
  $primaryKey = $this->userClass->primaryKey() ?? null;
  $primaryKeyValue = $this->session->get('user');
  $this->user = null;
  if ($primaryKey && $primaryKeyValue) {
   $this->user = $this->userClass->findOne([$primaryKey => $primaryKeyValue]);
  }
 }
 public function getController(): BaseController
 {
  return $this->controller;
 }

 /**
  * Undocumented function
  *
  * @return void
  */
 public function setController(BaseController $controller): void
 {
  $this->controller = $controller;
 }

 public function run()
 {
  try {
   echo $this->router->resolve();
  } catch (\Throwable $th) {
   $this->response->setStatusCode($th->getCode());
   echo Application::$app->view->renderView('errors/_error', ["exception" => $th]);
  }
 }

 public function login(UserModel $user)
 {
  $this->user = $user;
  $primaryKey = $user->primaryKey();
  $primaryKeyValue = $user->{$primaryKey};
  $this->session->set('user', $primaryKeyValue);
 }
 public function logout(DbModel $user)
 {
  $this->user = null;
  $this->session->remove('user');
 }

 public static function isGuest()
 {
  return !self::$app->user;
 }
}