<?php

namespace app\core\base;


abstract class BaseField
{
 public const TYPE_TEXT = "text";
 public const TYPE_PASSWORD = "password";
 public const TYPE_NUMBER = "number";

 public string $type;
 public string $attribute;
 public BaseModel $model;

 abstract public function renderInput(): string;
 public function __construct(BaseModel $model, string $attribute, string $type = self::TYPE_TEXT)
 {
  $this->model = $model;
  $this->attribute = $attribute;
  $this->type = $type;
 }

 public function __toString()
 {
  return sprintf(
   '<div class="form-group">
     <label>%s</label>
     %s
    <div class="invalid-feedback">%s</div>
   </div>',
   $this->model->getLabel($this->attribute),
   $this->renderInput(),
   $this->model->getFirstError($this->attribute)
  );
 }
}