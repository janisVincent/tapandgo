<?php

namespace Backend\Controllers;

use Backend\Models\Cities;
use Backend\Forms\CitiesForm;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Mvc\Model\Criteria as Criteria;

/**
 * Class CitiesController
 * @package Backend\Controllers
 */
class CitiesController extends BaseController
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
                "Backend\\Models\\Cities",
                $this->request->getPost()
            );

            $this->persistent->searchParams = $query->getParams();
            $cities = Cities::find($query->getParams());

        } else {
            $cities = Cities::find();
        }

        $this->view->setVar("cities", $cities);

        // Create a Model paginator, show 10 rows by page starting from $currentPage
        $paginator = new Paginator(
            [
                "data" => $cities,
                "limit" => $this->limit,
                "page" => intval($this->request->get('page') ?? 1),
            ]
        );

        // Get the paginated results
        $page = $paginator->getPaginate();
        $this->view->setVar("page", $page);

        // Search form
        $form = new CitiesForm(new Cities());
        $this->view->setVar('form', $form);
    }

    /**
     * Shows the view to create a "new" product
     */
    public function newAction()
    {
        $t = $this->getTranslation();
        $this->view->setVar("t", $t);

        $form = new CitiesForm(new Cities());
        $this->view->setVar('form', $form);
    }

    /**
     * Creates a city based on the data entered in the "new" action
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(
                [
                    "controller" => "cities",
                    "action" => "index",
                ]
            );
        }

        $t = $this->getTranslation();
        $this->view->setVar("t", $t);

        $city = new Cities();

        $form = new CitiesForm($city);
        $this->view->setVar("form", $form);

        // Validate the input
        $data = $this->request->getPost();

        if (!$form->isValid($data, $city)) {
            $messages = $form->getMessages();

            foreach ($messages as $message) {
                $this->flashSession->error($message);
            }

            return $this->dispatcher->forward(
                [
                    "controller" => "cities",
                    "action" => "new",
                ]
            );
        }

        if ($city->save() === false) {
            $messages = $city->getMessages();

            foreach ($messages as $message) {
                $this->flashSession->error($message);
            }

            return $this->dispatcher->forward(
                [
                    "controller" => "cities",
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
                "controller" => "cities",
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

        $city = Cities::findFirstById($id);

        if (!$city) {
            $this->flashSession->error(
                $t->_("item_not_found")
            );

            return $this->dispatcher->forward(
                [
                    "controller" => "cities",
                    "action" => "index",
                ]
            );
        }

        $form = new CitiesForm($city);
        $this->view->setVar('form', $form);
    }

    /**
     * Updates a city based on the data entered in the "edit" action
     */
    public function saveAction()
    {
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(
                [
                    "controller" => "cities",
                    "action" => "index",
                ]
            );
        }

        $t = $this->getTranslation();
        $this->view->setVar("t", $t);

        /**
         * @var Cities $city
         */
        $city = Cities::findFirstById($this->request->getPost("id"));

        if (!$city) {
            $this->flashSession->error(
                $t->_("item_not_found")
            );

            return $this->dispatcher->forward(
                [
                    "controller" => "cities",
                    "action" => "index",
                ]
            );
        }

        $form = new CitiesForm($city);
        $this->view->setVar("form", $form);

        // Validate the input
        $data = $this->request->getPost();

        if (!$form->isValid($data, $city)) {
            $messages = $form->getMessages();

            foreach ($messages as $message) {
                $this->flashSession->error($message);
            }

            return $this->dispatcher->forward(
                [
                    "controller" => "cities",
                    "action" => "new",
                ]
            );
        }

        if ($city->save() === false) {
            $messages = $city->getMessages();

            foreach ($messages as $message) {
                $this->flashSession->error($message);
            }

            return $this->dispatcher->forward(
                [
                    "controller" => "cities",
                    "action" => "new",
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
                "controller" => "cities",
            ]
        );
    }

    /**
     * Deletes an existing product
     */
    public function deleteAction($id)
    {
        // ...
    }
}