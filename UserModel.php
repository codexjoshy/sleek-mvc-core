<?php

namespace codexjoshy\sleekmvc;

use codexjoshy\sleekmvc\db\DbModel;

abstract class UserModel extends DbModel
{
 abstract public function getDisplayName(): string;
}
