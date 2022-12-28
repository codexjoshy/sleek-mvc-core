<?php

namespace app\core\exceptions;

class NotFoundException extends \Exception
{
 protected $message = 'Oops! Page not Found';
 protected $code = 404;
}