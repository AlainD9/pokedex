<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PokemonController extends AbstractController
{
    #[Route('/pokemon/{name<[a-z]+>}', name: 'app_pokemon')]
    public function index(
        string $name = 'MissingNo.'
    ): Response
    {
        return $this->render('pokemon/index.html.twig', [
            'name' => $name,
        ]);
    }
}
