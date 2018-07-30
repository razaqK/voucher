<?php
/**
 * Created by PhpStorm.
 * User: kasalirazaq
 * Date: 6/21/18
 * Time: 12:17 PM
 */

use Phalcon\Events\EventsAwareInterface;
use Phalcon\Events\ManagerInterface;
use Phalcon\Events\Manager as EventsManager;

abstract class BaseEvent implements EventsAwareInterface
{

    protected $_eventsManager;

    public function __construct()
    {
        $eventsManager = new EventsManager();

        $this->setEventsManager($eventsManager);
    }

    public function getEventsManager()
    {
        return $this->_eventsManager;
    }

    public function setEventsManager(ManagerInterface $eventsManager)
    {
        $this->_eventsManager = $eventsManager;
    }

    abstract public function attachEvent($class);

    abstract public function fireEvent();
}