<?php
declare(strict_types=1);

namespace App\UI\Responder;

use Symfony\Component\HttpFoundation\Response;

class RemoveTrickResponder
{
    /**
     * @return Response
     */
    public function __invoke()
    {
        return new Response('', 200);
    }
}
