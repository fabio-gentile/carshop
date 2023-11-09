<?php

namespace App\Controller;

use App\Entity\Advert;
use App\Repository\AdvertRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdvertController extends AbstractController
{
    #[Route('/annonce', name: 'advert_index')]
    public function index(AdvertRepository $advert, Request $request, PaginatorInterface $paginator): Response
    {
        $ADS_PER_PAGE = 9;
        $adverts = $paginator->paginate(
            $advert->findAll(),
            $request->query->getInt('page', 1),
            $ADS_PER_PAGE
        );
        return $this->render('advert/index.html.twig', [
            'adverts' => $adverts
        ]);
    }

    #[Route('/annonce/{slug}', name: 'advert_show')]
    public function show(Advert $advert): Response
    {
        return $this->render('advert/show.html.twig', [
            'advert' => $advert,
        ]);
    }
}
