<?php

namespace app\core\base;

use app\core\Application;

abstract class BaseModel
{
 public const RULE_REQUIRED = 'required';
 public const RULE_EMAIL = 'email';
 public const RULE_MIN = 'min';
 public const RULE_MAX = 'max';
 public const RULE_MATCH = 'match';
 public const RULE_UNIQUE = 'unique';

 abstract function labels(): array;

 public function loadData($data)
 {
  foreach ($data as $key => $value) {
   if (property_exists($this, $key)) {
    $this->{$key} = $value;
   }
  }
 }

 abstract public function rules(): array;

 public array $errors = [];

 private function addErrorForRule(string $attribute, string $rule, array $params = [])
 {
  $message = $this->errorMessages()[$rule] ?? '';
  if ($params) {
   foreach ($params as $key => $value) {
    $message = str_replace("{{$key}}", $value, $message);
   }
  }
  $this->errors[$attribute][] = $message;
 }

 public function getLabel(string $attribute): string
 {
  return $this->labels()[$attribute] ?? $attribute;
 }


 public function validate(): bool | array
 {
  $data = [];
  foreach ($this->rules() as $attribute => $rules) {
   $value = $this->{$attribute};
   foreach ($rules as $rule) {
    $ruleName = $rule;
    if (is_array($ruleName)) $ruleName = $rule[0];
    if ($ruleName === self::RULE_REQUIRED && !$value) {
     $this->addErrorForRule($attribute, self::RULE_REQUIRED);
    }
    if ($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_SANITIZE_EMAIL)) {
     $this->addErrorForRule($attribute, self::RULE_REQUIRED);
    }
    if ($ruleName === self::RULE_MIN && strlen($value) < $rule[self::RULE_MIN]) {
     $this->addErrorForRule($attribute, self::RULE_MIN, $rule);
    }
    if ($ruleName === self::RULE_MAX && strlen($value) > $rule[self::RULE_MAX]) {
     $this->addErrorForRule($attribute, self::RULE_MAX, $rule);
    }
    if ($ruleName === self::RULE_MATCH && $value !== $this->{$rule[self::RULE_MATCH]}) {
     $rule[self::RULE_MATCH] = $this->getLabel($rule[self::RULE_MATCH]);
     $this->addErrorForRule($attribute, self::RULE_MATCH, $rule);
    }
    if ($ruleName === self::RULE_UNIQUE) {
     $className = $rule['class'];
     $uniqueAttr = $rule['attribute'] ?? $attribute;
     $tableName =  null;

     if (class_exists($className)) {
      $tableName =  $className::tableName();
      $statement = Application::$app->db->prepare("SELECT id FROM $tableName WHERE $uniqueAttr = :$uniqueAttr");
      $statement->bindValue(":$uniqueAttr", $value);
      $statement->execute();
      $record = $statement->fetchObject();
      if ($record) $this->addErrorForRule($attribute, self::RULE_UNIQUE, ['field' =>  $this->getLabel($attribute)]);
     }
    }
   }
   $data[$attribute] = $value;
  }
  return count($this->errors) ? false : $data;
 }

 public function addError(string $attribute, string $message)
 {
  $this->errors[$attribute][] = $message;
 }

 public function hasError($attribute)
 {
  return $this->errors[$attribute] ?? false;
 }
 public function getFirstError($attribute)
 {
  if ($this->hasError($attribute)) return $this->errors[$attribute][0];
  return false;
 }
 public function errorMessages()
 {
  return [
   self::RULE_REQUIRED => 'This field is required',
   self::RULE_EMAIL => 'This field must be a valid email address',
   self::RULE_MIN => 'Min length of this field is {min}',
   self::RULE_MAX => 'Max length of this field is {max}',
   self::RULE_MATCH => 'This field must be same as {match}',
   self::RULE_UNIQUE => 'Record with this {field} already exists'
  ];
 }
}