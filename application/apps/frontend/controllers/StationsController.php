<?php

namespace Frontend\Controllers;

use Phalcon\Mvc\Controller;
use Frontend\Models\Stations;
use Output\JSON as Output;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

/**
 * Class StationsController
 * @package Frontend\Controllers
 */
class StationsController extends Controller
{
    /**
     * @var int
     */
    private $limit = 10;

    /**
     * @var int Radius (kilometers)
     */
    private $radius = 10;

    public function indexAction()
    {
        $this->view->disable();

        $response = [];

        $offset = 0;
        $page = intval($this->request->get('page') ?? 1);
        $limit = intval($this->request->get('limit') ?? $this->limit);

        if ($page > 0) {
            $offset = ($limit * --$page);
        }

        $stations = Stations::find(
            [
                "offset" => $offset,
                "limit" => $limit,
            ]
        );

        foreach ($stations as $station) {
            $response[] = $this->_format($station);
        }

        return Output::render($response);
    }

    /**
     * @param $id
     * @return string
     * @throws \Exception
     */
    public function showAction($id)
    {
        $this->view->disable();

        $station = Stations::findFirstById($id);

        if (false === $station) {
            http_response_code(204);
            throw new \Exception("Ressource does not exist.");
        }

        $response = $this->_format($station);

        return Output::render($response);

    }

    /**
     * @return string
     */
    public function nearAction()
    {
        $this->view->disable();

        $response = [];

        $offset = 0;
        $page = intval($this->request->get('page') ?? 1);
        $limit = intval($this->request->get('limit') ?? $this->limit);

        if ($page > 0) {
            $offset = ($limit * --$page);
        }

        $lat = floatval($this->request->get('lat'));
        $lng = floatval($this->request->get('lng'));
        $radius = intval($this->request->get('radius') ?? $this->radius);

        /**
         * @see http://www.plumislandmedia.net/mysql/haversine-mysql-nearest-loc/
         */
        $sql = "
SELECT
    d.*
FROM 
(
    SELECT 
        s.*,
        p.radius,
        p.distance_unit * DEGREES(ACOS(
            COS(RADIANS(p.latpoint))
            * COS(RADIANS(s.latitude))
            * COS(RADIANS(p.longpoint - s.longitude))
            + SIN(RADIANS(p.latpoint))
            * SIN(RADIANS(s.latitude))
        )) AS distance
    FROM
        stations s
    JOIN 
    (
        SELECT
            $lat AS latpoint, $lng AS longpoint,
            $radius AS radius, 111.045 AS distance_unit
    ) AS p ON 1=1
    WHERE
        s.latitude BETWEEN p.latpoint  - (p.radius / p.distance_unit)
    AND
        p.latpoint  + (p.radius / p.distance_unit)
    AND
        s.longitude BETWEEN p.longpoint - (p.radius / (p.distance_unit * COS(RADIANS(p.latpoint))))
    AND
        p.longpoint + (p.radius / (p.distance_unit * COS(RADIANS(p.latpoint))))
) as d
WHERE
    distance <= radius
ORDER BY
    distance
LIMIT 
    $offset, $limit";

        $stations = new Stations();
        $items = new Resultset(
            null,
            $stations,
            $stations->getReadConnection()->query($sql)
        );

        foreach ($items as $item) {
            $row = $this->_format($item);
            $row['distance'] = (float)$item->distance;
            $response[] = $row;
        }

        return Output::render($response);
    }

    /**
     * @param $id
     * @return string
     * @throws \Exception
     */
    public function takeAction($id)
    {
        $this->view->disable();

        /**
         * @var Stations $station
         */
        $station = Stations::findFirst("id = $id");

        if (false === $station) {
            http_response_code(204);
            throw new \Exception("Ressource does not exist.");
        }

        if (--$station->bikes_available >= 0) {
            $response = $station->update();
        }

        return Output::render(!empty($response));
    }

    /**
     * @param $id
     * @return string
     * @throws \Exception
     */
    public function dropAction($id)
    {
        $this->view->disable();

        /**
         * @var Stations $station
         */
        $station = Stations::findFirstById($id);

        if (false === $station) {
            http_response_code(204);
            throw new \Exception("Ressource does not exist.");
        }

        if (++$station->bikes_available <= $station->bikes_capacity) {
            $response = $station->update();
        }

        return Output::render(!empty($response));
    }

    /**
     * @param Stations $station
     * @return array
     */
    private function _format(Stations $station)
    {
        return
            [
                "id" => $station->getId(),
                "creationDate" => $station->getCreationDate(),
                "lastUpdate" => $station->getLastUpdate(),
                "name" => $station->getName(),
                "address" => $station->getAddress(),
                "description" => $station->getDescription(),
                "latitude" => $station->getLatitude(),
                "longitude" => $station->getLongitude(),
                "bikesCapacity" => $station->getBikesCapacity(),
                "bikesAvailable" => $station->getBikesAvailable(),
            ];
    }
}
