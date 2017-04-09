<?php

namespace Backend\Forms;

use Backend\Models\Stations;
use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Numeric;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Validation\Validator\Callback;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Numericality;

/**
 * Class StationsForm
 * @package Backend\Forms
 */
class StationsForm extends Form
{
    /**
     * This method returns the default value for field 'csrf'
     */
    public function getCsrf()
    {
        return $this->security->getToken();
    }

    /**
     * Forms initializer
     * @param Stations $station
     */
    public function initialize(Stations $station)
    {
        // Set the same form as entity
        $this->setEntity($station);

        // Add a text element to put a hidden CSRF
        $this->add(
            new Hidden(
                "csrf"
            )
        );

        $id = new Hidden("id");
        $this->add($id);

        $name = new Text("name");
        $name->setLabel("Name");
        $name->setFilters(
            [
                "striptags",
                "string",
            ]
        );
        $name->addValidators(
            [
                new PresenceOf(
                    [
                        "message" => "Name is required",
                    ]
                ),
            ]
        );

        $this->add($name);

        $latitude = new Text("latitude");
        $latitude->setLabel("Latitude");
        $latitude->setFilters(
            [
                "float",
            ]
        );
        $latitude->addValidators(
            [
                new Numericality(
                    [
                        "message" => "Latitude has to be valid number",
                    ]
                ),
            ]
        );

        $this->add($latitude);

        $longitude = new Text("longitude");
        $longitude->setLabel("Longitude");
        $longitude->setFilters(
            [
                "float",
            ]
        );
        $longitude->addValidators(
            [
                new Numericality(
                    [
                        "message" => "Longitude has to be valid number",
                    ]
                ),
            ]
        );

        $this->add($longitude);

        $description = new TextArea("description");
        $description->setLabel("Description");
        $description->setFilters(
            [
                "striptags",
                "string",
            ]
        );

        $this->add($description);

        $address = new TextArea("address");
        $address->setLabel("Address");
        $address->setFilters(
            [
                "striptags",
                "string",
            ]
        );

        $this->add($address);

        $bikes_capacity = new Numeric("bikes_capacity");
        $bikes_capacity->setLabel("Bikes capacity");
        $bikes_capacity->setAttribute("min", 0);
        $bikes_capacity->setFilters(
            [
                "int",
            ]
        );
        $bikes_capacity->addValidators(
            [
                new Numericality(
                    [
                        "message" => "Bikes capacity has to be a valid number",
                    ]
                ),
            ]
        );

        $this->add($bikes_capacity);

        $bikes_available = new Numeric("bikes_available");
        $bikes_available->setLabel("Bikes available");
        $bikes_available->setAttribute("min", 0);
        $bikes_available->setFilters(
            [
                "int",
            ]
        );
        $bikes_available->addValidators(
            [
                new Numericality(
                    [
                        "message" => "Bikes availability has to be a valid number",
                    ]
                ),
                /*
                 * @todo Class 'Phalcon\Validation\Validator\Callback' not found ?
                 *
                 new Callback(
                    [
                        "message" => "Bikes availabilty can not be greater than bikes capacity",
                        "callback" => function ($data) {
                            if ($data->getBikesAvailable() > $data->getBikesCapacity()) {
                                return false;
                            }

                            return true;
                        }
                    ]
                )*/
            ]
        );

        $this->add($bikes_available);

        $active = new Check("active");
        $active->setLabel("Active");
        $active->setFilters(
            [
                "int",
            ]
        );

        $this->add($active);
    }
}