<?php
/**
 * Created by PhpStorm.
 * User: kasalirazaq
 * Date: 6/21/18
 * Time: 12:40 PM
 */

class EventObserver extends BaseEvent
{

    protected $_type;
    protected $_params;
    private function setEvtType($type)
    {
        $this->_type = $type;
    }

    public function getEvtType()
    {
        return $this->_type;
    }

    public function setParams($params)
    {
        $this->_params = $params;
    }

    public function getParams()
    {
        return $this->_params;
    }

    public function __construct($type, $class, $params)
    {
        parent::__construct();

        $this->setEvtType($type);
        $this->setParams($params);
        $this->attachEvent($class);
    }

    public function attachEvent($class)
    {
        $this->getEventsManager()->attach(
            $this->getEvtType(),
            new $class()
        );
    }

    public function fireEvent()
    {
        $this->getEventsManager()->fire($this->getEvtType(), $this, $this->getParams());
    }
}