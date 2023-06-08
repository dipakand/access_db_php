<?php
class Database
{
    private static $the_only_connection = null;

    private static $model_object_count = 0;

    private $db;

    private $stmt;

    private $config;

    /**
     * establishes connection with mysql
     */
    function __construct()
    {
        if (null === self::$the_only_connection) {
            $db = $this->config['database settings'];
            $host = hOST;
            $user = dBuSER;
            $pword = dBpWD;
            $db_name = dBnAME;
            $mdbFilename = mdbFilename;
            try {
                // for mysql db
                /* self::$the_only_connection = new PDO("mysql:host=$host;dbname=$db_name", $user, $pword, array(
                    PDO::MYSQL_ATTR_FOUND_ROWS => true,
                    PDO::ATTR_PERSISTENT => true
                )); */
                // for access db
                self::$the_only_connection = new PDO(
                    "odbc:DRIVER={Microsoft Access Driver (*.mdb, *.accdb)}; charset=UTF-8; DBQ=$db_name; Uid=''; Pwd='';"
                );
                self::$the_only_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$the_only_connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                // echo 'connect';
            } catch (PDOException $e) {
                // echo 'not connect';
                throw $e;
            }
        }

        $this->db = self::$the_only_connection;
    }

    function executeQuery($query, $params = array())
    {
        $this->stmt = $this->db->prepare($query);
        if (!($this->stmt)) {
            throw new Exception('Query failed while preparing');
        }
        try {
            $this->stmt->execute($params);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    function getOne($query, $params = array())
    {
        $this->executeQuery($query, $params);
        $column = $this->stmt->fetchColumn();
        unset($this->stmt);
        return $column;
    }

    function getRecord($query, $params = array(), $array = false)
    {

        $this->executeQuery($query, $params);

        $record = array();
        // if ($this->totalRecords() > 0) {
        if ($array) {
            $this->stmt->setFetchMode(PDO::FETCH_ASSOC);
        } else {
            $this->stmt->setFetchMode(PDO::FETCH_OBJ);
        }
        $record = $this->stmt->fetch();
        // }
        unset($this->stmt);
        return $record;
    }

    function getRecords($query, $params = array(), $array = false, $all = true)
    {
        $this->executeQuery($query, $params);

        $records = array();
        // if ($this->totalRecords() > 0) {
        if ($array) {
            $this->stmt->setFetchMode(PDO::FETCH_ASSOC);
        } else {
            $this->stmt->setFetchMode(PDO::FETCH_OBJ);
        }
        if ($all) {
            $records = $this->stmt->fetchAll();
        } else {
            while (($record = $this->stmt->fetch()) !== false) {
                $records[] = $record;
            }
        }
        // }
        unset($this->stmt);
        return $records;
    }

    function insertRecord($table, $params)
    {
        $query = '';
        $fields = $place_holders = array();
        $values = array_values($params);
        foreach ($params as $field => $val) {
            array_push($fields, $field);
            array_push($place_holders, '?');
        }
        $query = "INSERT INTO $table(" . implode(", ", $fields) . ") VALUES(" . implode(", ", $place_holders) . ")";
        $this->executeQuery($query, $values);

    }

    function updateRecord($table, $params, $where, $multiple = array(), $comment = '')
    {
        $query = '';
        $fields = array();
        $values = array_values($params);
        foreach ($params as $field => $val) {
            array_push($fields, $field . ' = ?');
        }

        $where_clause = array();
        $where_values = array();
        foreach ($where as $col => $val) {
            if (is_array($val)) {
                if (isset($val['op'])) {
                    array_push($where_clause, $col . ' ' . $val['op'] . ' ? ');
                }
                array_push($values, $val['value']);
                array_push($where_values, $val['value']);
            } else {
                array_push($where_clause, $col . ' = ?');
                array_push($values, $val);
                array_push($where_values, $val);
            }
        }

        if (count($multiple)) {
            foreach ($multiple as $column => $mvalues) {
                if (count($mvalues)) {
                    $placeholders = implode(", ", array_fill(0, count($mvalues), '?'));
                    array_push($where_clause, $column . ' IN (' . $placeholders . ')');
                    $values = array_merge($values, $mvalues);
                }
            }
        }

        $query = "UPDATE $table SET " . implode(", ", $fields) . " WHERE " . implode(" AND ", $where_clause);
        $this->executeQuery($query, $values);
    }

    function deleteRecord($table, $params, $comment = '')
    {
        $where_clause = array();
        $values = array_values($params);
        foreach ($params as $col => $val) {
            array_push($where_clause, $col . ' = ?');
        }

        $query = "DELETE FROM $table  WHERE " . implode(" AND ", $where_clause);
        $this->executeQuery($query, $values);
    }

    function getRecordID()
    {
        // return $this->db->lastInsertId();
        return $this->ID;
    }

    function totalRecords()
    {
        return $this->stmt->rowCount();
    }
    function getRowCount($query, $params = array())
    {
        $this->executeQuery($query, $params);
        return $this->totalRecords();
    }

    function showQuery()
    {
        return $this->stmt->queryString;
    }

    function dbClose()
    {
        $this->db = null;
    }

    function start()
    {
        $this->db->beginTransaction();
    }

    function save()
    {
        $this->db->commit();
    }

    function undo()
    {
        $this->db->rollBack();
    }

    private function getPrimaryKey($table)
    {
        $record = $this->getRecord("SHOW KEYS FROM $table WHERE Key_name = 'PRIMARY'");
        return $record->Column_name;
    }

    public function __destruct()
    {
        self::$model_object_count--;
        if (0 == self::$model_object_count) {
            $this->dbClose();
            self::$the_only_connection = null;
        }
    }
}