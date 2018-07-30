<?php

/**
 * Created by PhpStorm.
 * User: kasalirazaq
 * Date: 7/28/18
 * Time: 7:08 PM
 */

class VRedis extends Redis
{
    /**
     * @inheritdoc
     */
    public function set($key, $value, $timeout = 0, $opt = null)
    {
        $value = json_encode($value);
        return parent::set($key, $value, $timeout);
    }

    /**
     * @inheritdoc
     */
    public function setex( $key, $ttl, $value )
    {
        $value = json_encode($value);
        return parent::setex($key, $ttl, $value);
    }

    /**
     * @inheritdoc
     */
    public function get($key)
    {
        $value = parent::get($key);
        if ($value === false)
            return $value;
        return json_decode(parent::get($key), true);
    }

    /**
     * Push a item to left of set
     * Item is json encoded before adding to set
     * @param $key
     * @param $value
     * @return int
     */
    public function leftPush($key, $value)
    {
        $value = json_encode($value);
        return parent::lPush($key, $value);
    }

    /**
     * Push a item to right of set
     * Item is json encoded before adding to set
     * @param $key
     * @param $value
     * @return int
     */
    public function rightPush($key, $value)
    {
        $value = json_encode($value);
        return parent::rPush($key, $value);
    }
}
