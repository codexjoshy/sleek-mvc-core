<?php

namespace app\core;

/**
 * A Validation class
 *
 */

class MyValidator
{

 /**
  * @var array $patterns
  */
 public $patterns = array(
  'uri'           => '[A-Za-z0-9-\/_?&=]+',
  'url'           => '[A-Za-z0-9-:.\/_?&=#]+',
  'alpha'         => '[\p{L}]+',
  'words'         => '[\p{L}\s]+',
  'alphanum'      => '[\p{L}0-9]+',
  'int'           => '[0-9]+',
  'float'         => '[0-9\.,]+',
  'tel'           => '[0-9+\s()-]+',
  'text'          => '[\p{L}0-9\s-.,;:!"%&()?+\'°#\/@]+',
  'file'          => '[\p{L}\s0-9-_!%&()=\[\]#@,.;+]+\.[A-Za-z0-9]{2,4}',
  'folder'        => '[\p{L}\s0-9-_!%&()=\[\]#@,.;+]+',
  'address'       => '[\p{L}0-9\s.,()°-]+',
  'date_dmy'      => '[0-9]{1,2}\-[0-9]{1,2}\-[0-9]{4}',
  'date_ymd'      => '[0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2}',
  'email'         => '[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+[.]+[a-z-A-Z]'
 );

 /**
  * @var array $errors
  */
 public $errors = array();

 /**
  * the key to be used
  * 
  * @param string $name
  * @return this
  */
 public function name($name)
 {

  $this->name = $name;
  return $this;
 }

 /**
  * the value to be assigned to the key
  * 
  * @param mixed $value
  * @return this
  */
 public function value($value)
 {

  $this->value = $value;
  return $this;
 }

 /**
  * File
  * 
  * @param mixed $value
  * @return this
  */
 public function file($value)
 {

  $this->file = $value;
  return $this;
 }

 /**
  * use a regex expression pattern 
  * 
  * @param string $name nome of the pattern
  * @return this
  */
 public function pattern($name)
 {

  if ($name == 'array') {

   if (!is_array($this->value)) {
    $this->errors[] = 'Format for ' . $this->name . ' not an array.';
   }
  } else {

   $regex = '/^(' . $this->patterns[$name] . ')$/u';
   if ($this->value != '' && !preg_match($regex, $this->value)) {
    $this->errors[] = 'Format for ' . $this->name . ' already exist.';
   }
  }
  return $this;
 }

 /**
  * customize your pattern to be used
  * 
  * @param string $pattern
  * @return this
  */
 public function customPattern($pattern)
 {

  $regex = '/^(' . $pattern . ')$/u';
  if ($this->value != '' && !preg_match($regex, $this->value)) {
   $this->errors[] = 'Format for ' . $this->name . ' not valid.';
  }
  return $this;
 }

 /**
  * required fields
  * 
  * @return this
  */
 public function required()
 {

  if ((isset($this->file) && $this->file['error'] == 4) || ($this->value == '' || $this->value == null)) {
   $this->errors[] = 'value for ' . $this->name . ' required.';
  }
  return $this;
 }

 /**
  *set minimum 
  * 
  * @param int $min
  * @return this
  */
 public function min($length)
 {

  if (is_string($this->value)) {

   if (strlen($this->value) < $length) {
    $this->errors[] = 'the length for ' . $this->name . " is less than $length";
   }
  } else {

   if ($this->value < $length) {
    $this->errors[] = 'Value for ' . $this->name . " is less than $length";
   }
  }
  return $this;
 }

 /**
  * set maximum
  * 
  * @param int $max
  * @return this
  */
 public function max($length)
 {

  if (is_string($this->value)) {

   if (strlen($this->value) > $length) {
    $this->errors[] = 'field ' . $this->name . " is more than the max character length $length";
   }
  } else {

   if ($this->value > $length) {
    $this->errors[] = 'field ' . $this->name . " is greater than the maximum value $length";
   }
  }
  return $this;
 }

 /**
  * check for equality on the value
  * @param mixed $value
  * @return this
  */
 public function equal($value)
 {

  if ($this->value != $value) {
   $this->errors[] = 'field  ' . $this->name . ' does not match.';
  }
  return $this;
 }

 /**
  * check file size
  *
  * @param int $size
  * @return this 
  */
 public function maxSize($size)
 {

  if ($this->file['error'] != 4 && $this->file['size'] > $size) {
   $this->errors[] = 'the file ' . $this->name . ' is greater than ' . number_format($size / 1048576, 2) . ' MB.';
  }
  return $this;
 }

 /**
  * check for a valid extension
  *
  * @param string $extension
  * @return this 
  */
 public function ext($extension)
 {

  if ($this->file['error'] != 4 && pathinfo($this->file['name'], PATHINFO_EXTENSION) != $extension && strtoupper(pathinfo($this->file['name'], PATHINFO_EXTENSION)) != $extension) {
   $this->errors[] = 'the file ' . $this->name . ' does not match ' . $extension . '.';
  }
  return $this;
 }

 /**
  * Prevent XSS attack
  *
  * @param string $string
  * @return $string
  */
 public function purify($string)
 {
  return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
 }

 /**
  * check if validation is a success
  * 
  * @return boolean
  */
 public function isSuccess()
 {
  if (empty($this->errors)) return true;
 }

 /**
  * get validation errors
  * 
  * @return array $this->errors
  */
 public function getErrors()
 {
  if (!$this->isSuccess()) return $this->errors;
 }

 /**
  * display errors as html list
  * 
  * @return string $html
  */
 public function displayErrors()
 {

  $html = '<ul>';
  foreach ($this->getErrors() as $error) {
   $html .= '<li>' . $error . '</li>';
  }
  $html .= '</ul>';

  return $html;
 }

 /**
  * visualize the result alone
  *
  * @return booelan|string
  */
 public function result()
 {

  if (!$this->isSuccess()) {

   foreach ($this->getErrors() as $error) {
    echo "$error\n";
   }
   exit;
  } else {
   return true;
  }
 }

 /**
  * verify its an integer
  *
  * @param mixed $value
  * @return boolean
  */
 public static function is_int($value)
 {
  if (filter_var($value, FILTER_VALIDATE_INT)) return true;
 }

 /**
  * verify its a float value
  *
  * @param mixed $value
  * @return boolean
  */
 public static function is_float($value)
 {
  if (filter_var($value, FILTER_VALIDATE_FLOAT)) return true;
 }

 /**
  * Verify its alphabets
  *
  * @param mixed $value
  * @return boolean
  */
 public static function is_alpha($value)
 {
  if (filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[a-zA-Z]+$/")))) return true;
 }

 /**
  * verify it contains alphabet and numerals
  *
  * @param mixed $value
  * @return boolean
  */
 public static function is_alphanum($value)
 {
  if (filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[a-zA-Z0-9]+$/")))) return true;
 }

 /**
  * Verify its a valid url
  *
  * @param mixed $value
  * @return boolean
  */
 public static function is_url($value)
 {
  if (filter_var($value, FILTER_VALIDATE_URL)) return true;
 }

 /**
  * verify its a valid link
  *
  * @param mixed $value
  * @return boolean
  */
 public static function is_uri($value)
 {
  if (filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => "/^[A-Za-z0-9-\/_]+$/")))) return true;
 }

 /**
  * Verify its a boolean
  *
  * @param mixed $value
  * @return boolean
  */
 public static function is_bool($value)
 {
  if (is_bool(filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE))) return true;
 }

 /**
  * Verify its a valid email
  * @param mixed $value
  * @return boolean
  */
 public static function is_email($value)
 {
  if (filter_var($value, FILTER_VALIDATE_EMAIL)) return true;
 }

 public function sanitize($value, $type = null)
 {
  $value =  trim(htmlspecialchars(strip_tags($value)));
  if ($type == 'email') {
   if ($this->is_email($value)) return $value;
   return false;
  }
  return $value;
 }
}