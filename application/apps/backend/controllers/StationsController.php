<?php

namespace Backend\Controllers;

use Backend\Models\Stations;
use Backend\Forms\StationsForm;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Mvc\Model\Criteria as Criteria;

/**
 * Class StationsController
 * @package Backend\Controllers
 */
class StationsController extends BaseController
{
    /**
     * @var int
     */
    private $limit = 10;

    public function indexAction()
    {
        $this->persistent->searchParams = null;

        $t = $this->getTranslation();
        $this->view->setVar("t", $t);

        if ($this->request->isPost() && !$this->dispatcher->wasForwarded()) {
            // Create the query conditions
            $query = Criteria::fromInput(
                $this->di,
                "Backend\\Models\\Stations",
                $this->request->getPost()
            );

            $this->persistent->searchParams = $query->getParams();
            $stations = Stations::find($query->getParams());

        } else {
            $stations = Stations::find();
        }

        $this->view->setVar("stations", $stations);

        // Create a Model paginator, show 10 rows by page starting from $currentPage
        $paginator = new Paginator(
            [
                "data" => $stations,
                "limit" => $this->limit,
                "page" => intval($this->request->get('page') ?? 1),
            ]
        );

        // Get the paginated results
        $page = $paginator->getPaginate();
        $this->view->setVar("page", $page);

        // Search form
        $form = new StationsForm(new Stations());
        $this->view->setVar('form', $form);
    }

    /**
     * Shows the view to create a "new" product
     */
    public function newAction()
    {
        $t = $this->getTranslation();
        $this->view->setVar("t", $t);

        $form = new StationsForm(new Stations());
        $this->view->setVar('form', $form);
    }

    /**
     * Creates a station based on the data entered in the "new" action
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(
                [
                    "controller" => "stations",
                    "action" => "index",
                ]
            );
        }

        $t = $this->getTranslation();
        $this->view->setVar("t", $t);

        $station = new Stations();

        $form = new StationsForm($station);
        $this->view->setVar("form", $form);

        // Validate the input
        $data = $this->request->getPost();

        if (!$form->isValid($data, $station)) {
            $messages = $form->getMessages();

            foreach ($messages as $message) {
                $this->flashSession->error($message);
            }

            return $this->dispatcher->forward(
                [
                    "controller" => "stations",
                    "action" => "new",
                ]
            );
        }

        if ($station->save() === false) {
            $messages = $station->getMessages();

            foreach ($messages as $message) {
                $this->flashSession->error($message);
            }

            return $this->dispatcher->forward(
                [
                    "controller" => "stations",
                    "action" => "new",
                ]
            );
        }

        $form->clear();

        $this->flashSession->success(
            $t->_("item_created")
        );

        return $this->dispatcher->forward(
            [
                "controller" => "stations",
                "action" => "index",
            ]
        );
    }

    /**
     * Shows the view to "edit" an existing product
     * @param int $id
     */
    public function editAction(int $id)
    {
        $t = $this->getTranslation();
        $this->view->setVar("t", $t);

        $station = Stations::findFirstById($id);

        if (!$station) {
            $this->flashSession->error(
                $t->_("item_not_found")
            );

            return $this->dispatcher->forward(
                [
                    "controller" => "stations",
                    "action" => "index",
                ]
            );
        }

        $form = new StationsForm($station);
        $this->view->setVar('form', $form);
    }

    /**
     * Updates a station based on the data entered in the "edit" action
     */
    public function saveAction()
    {
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(
                [
                    "controller" => "stations",
                    "action" => "index",
                ]
            );
        }

        $t = $this->getTranslation();
        $this->view->setVar("t", $t);

        /**
         * @var Stations $station
         */
        $station = Stations::findFirstById($this->request->getPost("id"));

        if (!$station) {
            $this->flashSession->error(
                $t->_("item_not_found")
            );

            return $this->dispatcher->forward(
                [
                    "controller" => "stations",
                    "action" => "index",
                ]
            );
        }

        $form = new StationsForm($station);
        $this->view->setVar("form", $form);

        // Validate the input
        $data = $this->request->getPost();

        if (!$form->isValid($data, $station)) {
            $messages = $form->getMessages();

            foreach ($messages as $message) {
                $this->flashSession->error($message);
            }

            return $this->dispatcher->forward(
                [
                    "controller" => "stations",
                    "action" => "edit",
                    "params" =>
                        [
                            "id" => $station->id,
                        ],
                ]
            );
        }

        if ($station->save() === false) {
            $messages = $station->getMessages();

            foreach ($messages as $message) {
                $this->flashSession->error($message);
            }

            return $this->dispatcher->forward(
                [
                    "controller" => "stations",
                    "action" => "edit",
                    "params" =>
                        [
                            "id" => $station->id,
                        ],
                ]
            );
        }

        $form->clear();

        $this->flashSession->success(
            $t->_("item_updated")
        );

        return $this->response->redirect(
            [
                "for" => "admin_controller",
                "controller" => "stations",
            ]
        );
    }

    /**
     * Deletes an existing product
     * @param $id
     */
    public function deleteAction($id)
    {
        // ...
    }
}