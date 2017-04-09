<?php

namespace Output;

/**
 * Class JSON
 * @package Output
 */
class JSON implements \Output
{
    /**
     * @param $data
     * @return string
     */
    public static function render($data)
    {
        if (!headers_sent()) {
            header("Content-type: application/json; charset=utf-8");
        }

        if (!is_array($data)) {
            $data = [
                "response" => $data
            ];
        }

        $data_encoded = json_encode($data);

        if (JSON_ERROR_NONE == json_last_error()) {
            return $data_encoded;

        } else {
            http_response_code(520);

            return json_encode(array(
                "error" => "An error has occured.",
            ));
        }
    }
}