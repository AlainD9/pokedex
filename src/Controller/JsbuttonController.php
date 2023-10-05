<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JsbuttonController extends AbstractController
{
    #[Route('/jsbutton', name: 'app_jsbutton')]
    public function index(): Response
    {
        return $this->render('jsbutton/index.html.twig', [
            'controller_name' => 'JsbuttonController',
        ]);
    }
}
