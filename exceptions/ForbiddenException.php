<?php

namespace app\core\exceptions;

class ForbiddenException extends \Exception
{
 protected $message = 'Unauthorized Access';
 protected $code = 403;
}