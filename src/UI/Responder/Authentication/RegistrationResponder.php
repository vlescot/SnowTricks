<?php
declare(strict_types=1);

namespace App\UI\Responder\Authentication;

use Symfony\Component\HttpFoundation\RedirectResponse;

class RegistrationResponder
{
    /**
     * @param string $url
     *
     * @return RedirectResponse
     */
    public function __invoke(string $url)
    {
        return new RedirectResponse($url);
    }
}
