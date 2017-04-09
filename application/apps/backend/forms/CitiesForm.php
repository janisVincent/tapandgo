<?php

namespace Backend\Forms;

use Backend\Models\Cities;
use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Validation\Validator\PresenceOf;
use \Phalcon\Validation\Validator\Numericality;

/**
 * Class CitiesForm
 * @package Backend\Forms
 */
class CitiesForm extends Form
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
     * @param Cities $city
     */
    public function initialize(Cities $city)
    {
        // Set the same form as entity
        $this->setEntity($city);

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
                )
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