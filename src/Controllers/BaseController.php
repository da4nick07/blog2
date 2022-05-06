<?php
namespace Controllers;

use Services\Db;

abstract class BaseController
{
    protected Db $db;

    public function __construct()
    {
        $this->db = Db::getInstance();
    }
}