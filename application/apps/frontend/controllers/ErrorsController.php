<?php

namespace Frontend\Controllers;

use Phalcon\Mvc\Controller;

/**
 * Class ErrorsController
 * @package Frontend\Controllers
 */
class ErrorsController extends Controller
{
    public function notFound404Action()
    {
        http_response_code(404);
    }

    public function notContent204Action()
    {
        http_response_code(204);
        exit;
    }
}