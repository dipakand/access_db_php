<?php
class WebClass
{
    public $db;
    function __construct($x)
    {
        $this->db = $x;
    }

    function getUsers()
    {
        return $this->db->getRecords("SELECT * FROM users ",array(),false,true);
    }
    function insertRecord($table, $params)
    {
        $this->db->insertRecord($table, $params);
        return $this->db->getRecordID();
    }

}
