<?php

namespace Frontend\Models;

use Phalcon\Mvc\Model;

/**
 * Class Stations
 * @package Frontend\Models
 */
class Stations extends Model
{
    public $address;
    public $bikes_available;
    public $bikes_capacity;
    public $creation_date;
    public $description;
    public $id;
    public $last_update;
    public $latitude;
    public $longitude;
    public $name;

    /**
     * @return string
     */
    public function getAddress()
    {
        return trim($this->address);
    }

    /**
     * @return string
     */
    public function getBikesAvailable()
    {
        return (int)$this->bikes_available;
    }

    /**
     * @return string
     */
    public function getBikesCapacity()
    {
        return (int)$this->bikes_capacity;
    }

    /**
     * @return string
     */
    public function getCreationDate()
    {
        $creation_date = \DateTime::createFromFormat("Y-m-d H:i:s", $this->creation_date);
        return $creation_date->format(\DateTime::ISO8601);
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return trim($this->description);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return (int)$this->id;
    }

    /**
     * @return float
     */
    public function getLatitude()
    {
        return (double)$this->latitude;
    }

    /**
     * @return string
     */
    public function getLastUpdate()
    {
        $last_update = \DateTime::createFromFormat("Y-m-d H:i:s", $this->last_update);
        return $last_update->format(\DateTime::ISO8601);
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return (double)$this->longitude;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return trim($this->name);
    }
}