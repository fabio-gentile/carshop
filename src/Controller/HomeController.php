<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $fuelType = ['Essence', 'Diesel', 'Électrique', 'Hybride', 'GPL'];
        dd($fuelType[rand(0, count($fuelType) - 1)]);
        return $this->render('home/index.html.twig', [

        ]);
    }
}
