<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\String\u;

class VinalController
{
    #[Route('/')]
    public function homepage(): Response
    {
        return new Response('abc');
    }

    #[Route('/letter/{slug}')]
    public function letter(string $slug = null): Response
    {
        if ($slug) {
            $title = u(str_replace('-', ' ', $slug))->title(true);
        } else {
            $title = 'All gene';
        }
        return new Response("OKE: " . $title);
    }
}
