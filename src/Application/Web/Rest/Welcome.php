<?php

namespace Application\Web\Rest;

use Application\Web\BaseRestController;

class Welcome extends BaseRestController
{
    public function get()
    {
        return $this->toJson(
            array(
                'meta' => array(
                    'requestId' => $this->getDi()->getConfigs()->req_id,
                    'httpStatusCode' => 200
                ),
                'name' => 'Application REST API'
            )
        );
    }
}
