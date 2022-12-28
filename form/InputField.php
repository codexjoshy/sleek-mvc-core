<?php

namespace app\core\form;

use app\core\base\BaseField;
use app\core\base\BaseModel;

class InputField extends BaseField
{

 public function __construct(BaseModel $model, string $attribute, string $type = self::TYPE_TEXT)
 {
  $this->type = $type;
  parent::__construct($model, $attribute, $type);
 }


 public function renderInput(): string
 {
  return sprintf(
   '<input type="%s" name="%s" value="%s"  class="form-control%s" />',
   $this->type,
   $this->attribute,
   $this->model->{$this->attribute},
   $this->model->hasError($this->attribute) ? ' is-invalid' : '',
  );
 }

 public function passwordField()
 {
  $this->type = self::TYPE_PASSWORD;
  return $this;
 }
 public function textAreaField()
 {
  $this->field =  '
  <label>%s</label>
  <textarea type="%s" name="%s" class="form-control%s">%s</textarea>';
  return $this;
 }
}