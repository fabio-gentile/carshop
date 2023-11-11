<?php

namespace App\Controller;

use App\Repository\AdvertRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(AdvertRepository $advert): Response
    {
        $electricCars = $advert->findByCriteriaExpression('electrique', 'fuelType', 3);
        return $this->render('home/index.html.twig', [
            'lastAdverts' => $advert->findBy([], ['createdAt' => 'DESC'], 3),
            'electricCars' =>  $electricCars
        ]);
    }
}
