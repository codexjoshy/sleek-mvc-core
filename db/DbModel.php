<?php

namespace codexjoshy\sleekmvc\db;

use codexjoshy\sleekmvc\Application;
use codexjoshy\sleekmvc\base\BaseModel;

abstract class DbModel extends BaseModel
{
 abstract public function tableName(): string;
 abstract public function attributes(): array;
 abstract public function primaryKey(): string;

 public function save()
 {
  try {
   $tableName = $this->tableName();
   $attributes = $this->attributes();
   $params = array_map(fn ($attr) => ":$attr", $attributes);
   $attributeList = implode(", ", $attributes);
   $paramList = implode(", ", $params);

   $statement = self::prepare("INSERT INTO $tableName ($attributeList) VALUES ($paramList)");
   foreach ($attributes as $attribute) {
    $statement->bindValue(":$attribute", $this->{$attribute});
   }
   $statement->execute();
  } catch (\Throwable $th) {
   return false;
  }

  return true;

  // Application::$app->db
 }
 public function prepare($sql)
 {
  return Application::$app->db->pdo->prepare($sql);
 }

 public function exists()
 {
 }
 public static function findOne(array $where)
 {

  $class = static::class;
  $tableName = (new $class)->tableName();
  $attributes = array_keys($where);
  $whereClause = array_map(fn ($attr) => "$attr=:$attr", $attributes);
  $whereClause = implode("AND", $whereClause);
  $whereClause = rtrim($whereClause, "AND");
  $sql = "SELECT * FROM $tableName WHERE $whereClause LIMIT 1";
  $statement = (new $class)->prepare($sql);
  foreach ($where as $key => $value) {
   $statement->bindValue(":$key", $value);
  }
  $statement->execute();
  return $statement->fetchObject(static::class);
 }
}
