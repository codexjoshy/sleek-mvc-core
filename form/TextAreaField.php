<?php

namespace app\core\form;

use app\core\base\BaseField;
use app\core\base\BaseModel;

class TextAreaField extends BaseField
{

 public function __construct(BaseModel $model, string $attribute, string $type = self::TYPE_TEXT)
 {
  $this->type = $type;
  parent::__construct($model, $attribute, $type);
 }


 public function renderInput(): string
 {
  return sprintf(
   '<textarea type="%s" name="%s" class="form-control%s" >%s</textarea>',
   $this->type,
   $this->attribute,
   $this->model->hasError($this->attribute) ? ' is-invalid' : '',
   $this->model->{$this->attribute}
  );
 }
}