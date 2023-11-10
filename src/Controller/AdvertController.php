<?php

namespace App\Controller;

use App\Entity\Advert;
use App\Form\AdvertType;
use App\Repository\AdvertRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AdvertController extends AbstractController
{
    #[Route('/advert', name: 'advert_index')]
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

    /**
     * Permet d'ajouter une annonce a la db
     * @return Response
     */
    #[Route('/advert/new', name: 'advert_create')]
    #[IsGranted('ROLE_USER')]
    public function create(Request $request, EntityManagerInterface $manager): Response
    {
        $advert = new Advert();

        // appelle src/Form/AnnonceType.php
        $form = $this->createForm(AdvertType::class, $advert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // gestion des images
            foreach ($advert->getAdvertImages() as $image) {
                $image->setAdvert($advert);
                $manager->persist($image);
            }

            // img : author_id
            $advert->setSeller($this->getUser());

            // persist mon objet Ad
            $manager->persist($advert);
            // envoie les persists dans la db
            $manager->flush();

            // message flash pour l'utilisateur
            $this->addFlash(
                'success',
                "L'annonce de <strong>{$advert->getSeller()->getFullName()}</strong> a bien été enregistrée!"
            );
            return $this->redirectToRoute('advert_show', [
                'slug' => $advert->getSlug()
            ]);
        }

        return $this->render('advert/new.html.twig', [
            'myForm' => $form->createView()
        ]);
    }

    #[Route('/advert/{slug}', name: 'advert_show')]
    public function show(Advert $advert): Response
    {
        return $this->render('advert/show.html.twig', [
            'advert' => $advert,
        ]);
    }
}
