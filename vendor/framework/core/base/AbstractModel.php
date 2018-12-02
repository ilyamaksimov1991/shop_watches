<?php

namespace framework\base;

use framework\Db;
use Valitron\Validator;

/**
 * Class AbstractModel
 * @package framework\base
 */
abstract class AbstractModel
{
    /**
     * @var array $attributes
     */
    public $attributes = [];

    /**
     * @var array $errors
     */
    public $errors = [];

    /**
     * @var array $rules
     */
    public $rules = [];

    public function __construct()
    {
        Db::instance();
    }

    /**
     * Prepare the query columns
     *
     * @param $array
     * @return string
     */
    public function prepareQueryColumns($array)
    {
        $arg = func_get_args();
        $keys = array_keys($arg[0]);
        $sql = [];
        foreach ($keys as $val) {
            $sql[] = "$val = ?";
        }

        return (implode(' AND ', $sql));

    }

    /**
     * Get query values
     *
     * @param $array
     * @return array
     */
    public function getQueryValues($array)
    {
        $arg = func_get_args();
        return array_values($arg[0]);
    }

    /**
     * Load attributes model
     *
     * @param array $data
     */
    public function load($data)
    {
        foreach ($this->attributes as $name => $value) {
            if (isset($data[$name])) {
                $this->attributes[$name] = $data[$name];
            }
        }
    }


    /**
     * Validation of data from the user
     *
     * @param array $data
     * @return bool
     */
    public function validate($data)
    {
        Validator::langDir(WWW . '/validator/lang');
        Validator::lang('ru');
        $v = new Validator($data);
        $v->rules($this->rules);
        if ($v->validate()) {
            return true;
        }
        $this->errors = $v->errors();
        return false;
    }

    /**
     * Get errors
     */
    public function getErrors()
    {
        $errors = '<ul>';
        foreach ($this->errors as $error) {
            foreach ($error as $item) {
                $errors .= "<li>$item</li>";
            }
        }
        $errors .= '</ul>';
        $_SESSION['error'] = $errors;
    }

    /**
     * Update attributes to database
     * @param string $table
     * @param int $id
     * @return int|string
     */
    public function update($table, $id)
    {
        $bean = \R::load($table, $id);
        foreach ($this->attributes as $name => $value) {
            $bean->$name = $value;
        }
        return \R::store($bean);
    }

    /**
     * Save attributes to database
     *
     * @param string $table
     * @param bool $valid
     * @return int|string
     */
    public function save($table, $valid = true)
    {
        if ($valid) {
            $tbl = \R::dispense($table);
        } else {
            $tbl = \R::xdispense($table);
        }
        foreach ($this->attributes as $name => $value) {
            $tbl->$name = $value;
        }
        return \R::store($tbl);
    }

    /**
     * @param string $nameTable
     * @return array
     */
    public function getDataTable($nameTable)
    {
        return \R::getAssoc("SELECT * FROM {$nameTable}");
    }
}