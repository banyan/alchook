<?php

class Application_Model_Abstract
{
    CONST DB_NAME = 'alchook';

    protected $_db;

    public function __construct()
    {
        $mongo     = new Mongo();
        $this->_db = $mongo->selectDB(self::DB_NAME);
    }
}

