<?php

/**
 * Created by PhpStorm.
 * User: kasalirazaq
 * Date: 7/28/18
 * Time: 7:06 PM
 */


class BaseModel extends \Phalcon\Mvc\Model implements IModel
{

    /**
     * Cache variable that is used for the model
     * @var $redis
     */
    private static $redis;

    /**
     * Application configuration
     * @var $config
     */
    private $config;

    /**
     * Global events manager that is used to manage and fire events globally
     * @var $eventsManager
     */
    protected $eventsManager;


    /**
     * This will hold the models manager for building queries
     * @var $modelsManager
     */
    protected static $modelsManager;

    public function onConstruct()
    {
        $this->setConfig();
        $this->setRedis();

        $this->setEventManager();
        $this->setEventsManager($this->getEventManager());

        $this->setModelManager();

        $this->keepSnapshots(true);

        $this->setConnectionService('db');
    }


    private function setConfig()
    {
        $this->config = $this->getDI()->get('config');
    }

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return $this->config;
    }

    private function setRedis()
    {
        self::$redis = $this->getDI()->get('redis');
    }

    /**
     * @return \VRedis Object
     */
    public function getRedis()
    {
        return self::$redis;
    }

    private function setEventManager()
    {
        $this->eventsManager = \Phalcon\DI::getDefault()->get('eventsManager');
    }

    /**
     * @return mixed
     */
    public function getEventManager()
    {
        return $this->eventsManager;
    }

    private function setModelManager()
    {
        self::$modelsManager = \Phalcon\DI::getDefault()->get('modelsManager');
    }

    /**
     * @return mixed
     */
    public function getModelManager()
    {
        return self::$modelsManager;
    }

    /**
     * This is to assign the corresponding  array key value to model key
     * @param $data 'must be associative array'
     * @param array $filter 'filter field you do not want to set'
     * @return $this
     */
    public function setArrayValueToField(array $data, $filter = [])
    {
        foreach ($data as $key => $value) {
            if (!in_array($key, $filter)) {
                $this->$key = $value;
            }
        }

        return $this;
    }

    /**
     * @param $id
     * @return \Phalcon\Mvc\Model
     */
    public static function getById($id)
    {
        return self::findFirst([
            "id = :id:",
            "bind" => ["id" => trim($id)]
        ]);
    }

    /**
     * This is to get all value from table by fields
     *
     * Note:
     * $queryObj parameter is an associative array in which the key is the field and value is value. E.g ['email' => 'abc@voucher.com', 'status' => 'enabled']
     * $returnColumn parameter are columns to be returned.
     * $operator parameter (a string value): can either be AND or OR
     * @param $queryObj
     * @param $returnColumn
     * @param $operator
     * @param $returnFirstMatch
     *
     * @return mixed
     */
    public static function getByFields(
        array $queryObj,
        $operator,
        $returnColumn = null,
        $returnFirstMatch = false
    ) {
        $columns = is_null($returnColumn) ? "*" : $returnColumn;
        $condition = "";
        $bind = [];

        $count = 0;
        foreach ($queryObj as $key => $value) {
            $count += 1;
            $bind["$key"] = $value;
            if ($count >= sizeof($queryObj)) {
                $condition .= "$key = :$key:";
                break;
            }
            $condition .= "$key = :$key: $operator ";
        }
        if ($returnFirstMatch) {
            return self::findFirst([
                "columns" => $columns,
                $condition,
                "bind" => $bind
            ]);
        }
        return self::find([
            "columns" => $columns,
            $condition,
            "bind" => $bind
        ]);
    }

    /**
     * @param $value
     * @param $field
     * @return \Phalcon\Mvc\Model
     */
    public static function getByField($value, $field)
    {
        return self::findFirst([
            "$field = :value:",
            "bind" => ["value" => trim($value)]
        ]);
    }
}
