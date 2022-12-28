<?php

namespace app\core\db;

use app\core\Application;

class Database
{
 public \PDO $pdo;

 public function __construct(array $config)
 {
  $dsn = $config['dsn'] ?? '';
  $user = $config['user'] ?? '';
  $password = $config['password'] ?? '';
  try {
   $this->pdo = new \PDO($dsn, $user, $password);
   $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
  } catch (\Throwable $th) {
   throw $th;
  }
 }

 public function applyMigrations()
 {
  $this->createMigrationsTable();
  $appliedMigrations = $this->getAppliedMigrations();
  $files = scandir(Application::$ROOT_DIR . '/migrations');

  $migrationFiles = [];
  $newMigrations = array_diff($files, $appliedMigrations);
  foreach ($newMigrations as $migration) {
   if (in_array($migration, ['.', '..'])) continue;
   require_once Application::$ROOT_DIR . '/migrations/' . $migration;
   $className = pathinfo($migration, PATHINFO_FILENAME);
   // $className = ucwords($className);
   $classInstance = new $className();
   $this->log("Applying migration $migration...");

   if (method_exists($classInstance, 'up')) {
    $classInstance->up();
    $this->log("Migration $migration applied successfully  🚀 ");
    $migrationFiles[]  = $migration;
   }
  }
  if ($migrationFiles) {
   $this->saveMigrations($migrationFiles);
  } else {
   $this->log("No new migration files 😥 ");
  }
 }

 public function saveMigrations(array $migrations)
 {
  $migrationString = implode(", ", array_map(fn ($m) => "('$m')", $migrations));
  $statement = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES $migrationString");
  $statement->execute();
 }

 public function prepare(string $sql)
 {
  return Application::$app->db->pdo->prepare($sql);
 }

 /**
  * Undocumented function
  *
  * @return void
  */
 public function createMigrationsTable()
 {
  $this->pdo->exec(
   "CREATE TABLE IF NOT EXISTS migrations (
    id INT AUTO_INCREMENT PRIMARY KEY, 
    migration VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   ) ENGINE=INNODB"
  );
 }

 public function getAppliedMigrations()
 {
  $statement = $this->pdo->prepare("SELECT migration FROM migrations");
  $statement->execute();

  return $statement->fetchAll(\PDO::FETCH_COLUMN);
 }
 protected function log($message)
 {
  $date = date('Y-m-d H:i:s');
  echo "[$date] - $message " . PHP_EOL;
 }
}