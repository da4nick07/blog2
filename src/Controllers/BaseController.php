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

    protected function getInputData()
    {
        return json_decode( file_get_contents('php://input'),true );
    }
}