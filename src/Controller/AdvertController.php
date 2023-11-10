<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Advert;
use App\Form\AdvertType;
use App\Repository\AdvertRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AdvertController extends AbstractController
{
    /**
     * Affiche les annonces avec une pagination
     * @param AdvertRepository $advert
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
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
        // appelle src/Form/AdvertType.php
        $form = $this->createForm(AdvertType::class, $advert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // gestion des images
            foreach ($advert->getAdvertImages() as $image) {
                $image->setAdvert($advert);
                $manager->persist($image);
            }
            $advert->setSeller($this->getUser());

            // persist mon objet Advert
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

    /** Editer une annonce
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param Advert $advert
     * @return Response
     */
    #[Route('/advert/{slug}/edit', name: 'advert_edit')]
    #[IsGranted(
        attribute: new Expression('(user === subject and is_granted("ROLE_USER")) or is_granted("ROLE_ADMIN")'),
        subject: new Expression('args["advert"].getSeller()'),
        message: "Cette annonce ne vous appartient pas, vous ne pouvez pas la modifier"
    )]
    public function edit(Request $request, EntityManagerInterface $manager, Advert $advert): Response
    {
        $form = $this->createForm(AdvertType::class, $advert);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // gestion des images
            foreach ($advert->getAdvertImages() as $image) {
                $image->setAdvert($advert);
                $manager->persist($image);
            }
            $manager->persist($advert);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce de <strong>" . $advert->getSeller()->getFullName() . "</strong> a bien été modifiée!"
            );
            return $this->redirectToRoute('advert_show', [
                'slug' => $advert->getSlug()
            ]);
        }
        return $this->render('advert/edit.html.twig', [
            'advert' => $advert,
            'myForm' => $form->createView()
        ]);
    }

    /**
     * Supprimer une annonce
     * @param Advert $advert
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route('/advert/{slug}/delete', name: 'advert_delete')]
    #[IsGranted(
        attribute: new Expression('(user === subject and is_granted("ROLE_USER")) or is_granted("ROLE_ADMIN")'),
        subject: new Expression('args["advert"].getSeller()'),
        message: "Cette annonce ne vous appartient pas, vous ne pouvez pas la supprimer"
    )]
    public function delete(Advert $advert, EntityManagerInterface $manager) : Response
    {
        $this->addFlash('success', "L'annonce de <strong>" . $advert->getSeller()->getFullName() . "</strong> a bien été supprimée");
        $manager->remove($advert);
        $manager->flush();

        return $this->redirectToRoute('advert_index');
    }

    /**
     * Affiche l'annonce
     * @param Advert $advert
     * @return Response
     */
    #[Route('/advert/{slug}', name: 'advert_show')]
    public function show(Advert $advert): Response
    {
        return $this->render('advert/show.html.twig', [
            'advert' => $advert,
        ]);
    }
}
