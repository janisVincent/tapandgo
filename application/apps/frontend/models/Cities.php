<?php

namespace Frontend\Models;

use Phalcon\Mvc\Model;

/**
 * Class Cities
 * @package Frontend\Models
 */
class Cities extends Model
{
    public $id;
    public $latitude;
    public $longitude;
    public $name;

    /**
     * @return int
     */
    public function getId()
    {
        return (int)$this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return trim($this->name);
    }

    /**
     * @return float
     */
    public function getLatitude()
    {
        return (double)$this->latitude;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return (double)$this->longitude;
    }
}