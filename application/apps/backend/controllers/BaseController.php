<?php

namespace Backend\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Translate\Adapter\NativeArray;

/**
 * Class CitiesController
 * @package Backend\Controllers
 */
class BaseController extends Controller
{
    /**
     * @return NativeArray
     */
    protected function getTranslation()
    {
        // Ask browser what is the best language
        $language = $this->request->getBestLanguage();

        /**
         * @var $messages
         */
        $translationFile = __DIR__ . "/../messages/" . $language . ".php";

        // Check if we have a translation file for that lang
        if (file_exists($translationFile)) {
            require $translationFile;

        } else {
            // Fallback to some default
            require __DIR__ . "/../messages/en.php";
        }

        // Return a translation object
        return new NativeArray(
            [
                "content" => $messages,
            ]
        );
    }
}