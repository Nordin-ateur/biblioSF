<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\LivreRepository as LR;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(LR $lr): Response
    {
        return $this->render('home/index.html.twig', [
            'livres' => $lr->findAll(),
        ]);
    }
}
