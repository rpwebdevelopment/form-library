<?php
/**
 * Created by PhpStorm.
 * User: rporter
 * Date: 30/10/2020
 * Time: 09:27
 */

namespace FileLibrary\Src;


use FormLibrary\Src\Database;

class Model extends Database
{

    protected $connection;

    protected $table = '';

    public $schema = [];

    public $data = [];

    public $sanitised_data = [];

    /**
     * Model constructor - establish database connection
     */
    public function __construct()
    {
        $this->connection = parent::__construct();
    }

    /**
     * insert - build insert query
     *
     * @param array $values
     * @return bool
     */
    public function insert($values = [])
    {
        $this->data = $values;
        $this->sanitize();
        $query = "INSERT INTO %s SET %s";
        $params = implode(', ', $this->sanitised_data);
        $prepared_query = sprintf($query, $this->table, $params);
        if (!$this->connection->query($prepared_query)) {
            return false;
        }
        return true;
    }

    /**
     * sanitize - for each value within schema, if data exists sanitize it
     */
    private function sanitize()
    {
        foreach ($this->schema as $field => $type) {
            if (!isset($this->data[$field])) {
                continue;
            }
            if (strpos($type, ':') !== false) {
                $parts = explode(':', $type);
                $func = $parts[0];
                $this->$func($field, $parts[1]);
            } else {
                $this->$type($field);
            }
        }
    }

    /**
     * int - enforce int data type on value
     *
     * @param string $field
     */
    private function int($field = '')
    {
        $this->sanitised_data[] =
            "`$field` = " . (int) $this->data[$field];
    }

    /**
     * string - escape string values to prevent sql injection
     *
     * @param string $field
     */
    private function string($field = '')
    {
        $this->sanitised_data[$field] =
            "`$field` = '" . $this->connection->real_escape_string($this->data[$field]) . "'";
    }

    /**
     * enum - set int key for enum value
     *
     * @param string $field
     * @param string $extra
     */
    private function enum($field = '', $extra = '')
    {
        $parts = explode(',', $extra);
        foreach ($parts as $key => $value) {
            if ($this->data[$field] == $value) {
                $this->sanitised_data[] = "`$field` = '" . $value . "'";
                return;
            }
        }
    }

}
