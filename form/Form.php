<?php

namespace codexjoshy\sleekmvc\form;

use codexjoshy\sleekmvc\base\BaseModel;

class Form
{
 public static function begin(string $action, string $method)
 {
  echo sprintf('<form action="%s" method="%s" >', $action, $method);
  return new Form;
 }
 public static function end()
 {
  echo '</form>';
 }
 public  function field(BaseModel $model, $attribute, string $type = 'text')
 {
  return new InputField($model, $attribute, $type);
 }
}
