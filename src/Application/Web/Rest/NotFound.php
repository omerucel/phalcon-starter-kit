<?php

namespace Application\Web;

class NotFound extends BaseRestController
{
    public function __call($name, $params)
    {
        // TODO : set an error code instead of zero
        throw new Error('Wrong request uri. Check API document for request uri.', 0, 404);
    }
}
