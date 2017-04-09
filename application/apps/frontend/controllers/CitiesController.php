<?php

namespace Frontend\Controllers;

use Phalcon\Mvc\Controller;
use Frontend\Models\Cities;
use Output\JSON as Output;

/**
 * Class CitiesController
 * @package Frontend\Controllers
 */
class CitiesController extends Controller
{
    /**
     * @var int
     */
    private $limit = 10;

    /**
     * @return string
     */
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

        $cities = Cities::find(
            [
                "active = 1",
                "offset" => $offset,
                "limit" => $limit,
            ]
        );

        foreach ($cities as $city) {
            $response[] = $this->_format($city);
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

        $city = Cities::findFirstById($id);

        if (false === $city) {
            http_response_code(204);
            throw new \Exception("Ressource does not exist.");
        }

        $response = $this->_format($city);

        return Output::render($response);
    }

    /**
     * @param Cities $city
     * @return array
     */
    private function _format(Cities $city): array
    {
        return
            [
                "id" => $city->getId(),
                "name" => $city->getName(),
                "latitude" => $city->getLatitude(),
                "longitude" => $city->getLongitude(),
            ];
    }
}