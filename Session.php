<?php

namespace codexjoshy\sleekmvc;

class Session
{
 protected const FLASH_KEY = 'flash_messages';
 public function __construct()
 {
  session_start();
  // $flashMsgs = $_SESSION[self::FLASH_KEY] ?? [];
  // foreach ($flashMsgs as $key => &$flashMsg) {
  //  $flashMsg['remove'] = true;
  // }
  // $_SESSION[self::FLASH_KEY] = $flashMsgs;
 }
 public function setFlash(string $key, string $message)
 {
  $_SESSION[self::FLASH_KEY][$key] = ["remove" => false, "value" => $message];
 }
 public function getFlash($key)
 {
  $value =  $_SESSION[self::FLASH_KEY][$key]['value'] ?? '';
  if ($value) unset($_SESSION[self::FLASH_KEY][$key]);
  return $value;
 }

 /**
  * Undocumented function
  *
  * @param string $key
  * @param string $value
  * @return void
  */
 public function set(string $key, string $value)
 {
  $_SESSION[$key] = $value;
 }
 /**
  * Undocumented function
  *
  * @param string $key
  * @return void
  */
 public function get(string $key)
 {
  return $_SESSION[$key] ?? false;
 }
 /**
  * Undocumented function
  *
  * @param string $key
  * @return void
  */
 public function remove(string $key)
 {
  if ($this->get($key)) unset($_SESSION[$key]);
 }

 public function __destruct()
 {
  // $flashMsgs = $_SESSION[self::FLASH_KEY] ?? [];
  // foreach ($flashMsgs as $key => &$flashMsg) {
  //  if ($flashMsg['remove']) unset($flashMsg[$key]);
  // }
  // $_SESSION[self::FLASH_KEY] = $flashMsgs;
 }
}
