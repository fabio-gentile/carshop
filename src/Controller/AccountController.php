<?php

namespace App\Controller;

use App\Entity\PasswordUpdate;
use App\Entity\User;
use App\Entity\UserImageModify;
use App\Form\AccountType;
use App\Form\PasswordUpdateType;
use App\Form\RegistrationType;
use App\Form\UserImageModifyType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\TooManyLoginAttemptsAuthenticationException;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    /**
     * Permet à l'utilisateur de se connecter
     * @param AuthenticationUtils $utils
     * @return Response
     */
    #[Route('/login', name: 'account_login')]
    public function index(AuthenticationUtils $utils): Response
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();
        $loginError = null;

        if ($error instanceof TooManyLoginAttemptsAuthenticationException) {
            // limitation de tentatives de connexion
            $loginError = 'Trop de tentatives de connexion. Réssayez plus tard';
        }
        return $this->render('account/index.html.twig', [
            'hasError' => $error !== null,
            'username' => $username,
            'loginError' => $loginError,
        ]);
    }

    /**
     * Permet de se déconnecter
     * @return void
     */
    #[Route('/logout', name: 'account_logout')]
    public function logout(): void
    {

    }

  /**
     * Permet d'afficher le formulaire d'inscription ainsi la gestion de l'inscription de l'utilisateur
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param UserPasswordHasherInterface $hasher
     * @return Response
     */
    #[Route("/register", name: "account_register")]
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        // partie traitement du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            // gestion de l'image
            $file = $form->get('picture')->getData();
            if (!empty($file)) {
                $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFileName);
                $newFilename = $safeFileName . '-' . uniqid() . '.' . $file->guessExtension();

                // Move the file to the directory where pictures are stored
                try {
                    $file->move($this->getParameter('uploads_directory'), $newFilename);
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                    return $e->getMessage();
                }

                $user->setPicture($newFilename);
            }

            // gestion de l'inscription dans la bdd
            $hash = $hasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hash);

            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success', 'Inscription réalisée avec succès');
            return $this->redirectToRoute('account_login');
        }
        return $this->render("account/registration.html.twig", [
            'myForm' => $form->createView()
        ]);
    }

    /**
     * Permet de modifier le mot de passe
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param UserPasswordHasherInterface $hasher
     * @return Response
     */
    #[Route("/account/password-update", name: "account_password")]
    #[IsGranted('ROLE_USER')]
    public function updatePassword(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): Response
    {
        $passwordUpdate = new PasswordUpdate();
        $user = $this->getUser();
        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // vérifier si le mot de passe correspond à l'ancien
            if (!password_verify($passwordUpdate->getOldPassword(), $user->getPassword())) {
                // gestion de l'erreur
                $form->get('oldPassword')->addError(new FormError("Le mot de passe que vous avez tapé n'est pas votre mot de passe actuel"));
            } else {
                $newPassword = $passwordUpdate->getNewPassword();
                $hash = $hasher->hashPassword($user, $newPassword);

                $user->setPassword($hash);
                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    'Votre mot de passe a bien été modifié'
                );
                return $this->redirectToRoute('account_profile');
            }
        }
        return $this->render("account/passwordChange.html.twig", [
            'myForm' => $form->createView()
        ]);
    }

    /**
     * Modifier son profil
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route("/account/profile", name: "account_profile")]
    #[IsGranted('ROLE_USER')]
    public function profile(Request $request, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
//        dd($user);
        // validation des images
        $picture = $user->getPicture();
        if (!empty($picture)) {
            $user->setPicture(new File($this->getParameter('uploads_directory') . '/' . $picture));
        }

        $form = $this->createForm(AccountType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPicture($picture);
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', 'Les données ont été enregistrées avec succès');
            return $this->redirectToRoute('account_index');
        }

        return $this->render('account/profile.html.twig', [
            'myForm' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * Modification de l'avatar de l'utilisateur
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     */
    #[Route("/account/image-modify", name: "account_image_modify")]
    #[IsGranted('ROLE_USER')]
    public function imgModify(EntityManagerInterface $manager, Request $request): Response
    {
        $imageModify = new UserImageModify();
        $user = $this->getUser();
        $form = $this->createForm(UserImageModifyType::class, $imageModify);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!empty($user->getPicture())) {
                unlink($this->getParameter('uploads_directory') . '/' . $user->getPicture());
            }

            // gestion de l'image
            $file = $form->get('newPicture')->getData();
            if (!empty($file)) {
                $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFileName = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFileName);
                $newFilename = $safeFileName . '-' . uniqid() . '.' . $file->guessExtension();

                // Move the file to the directory where pictures are stored
                try {
                    $file->move($this->getParameter('uploads_directory'), $newFilename);
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                    return $e->getMessage();
                }

                $user->setPicture($newFilename);
            }

            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success', 'Votre avatar a bien été modifié');
            return $this->redirectToRoute('account_index');
        }
        return $this->render('account/imageModify.html.twig', [
            'myForm' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer l'image de l'utilisateur
     * @param EntityManagerInterface $manager
     * @return Response
     */
    #[Route("/account/image-delete", name: "account_image_delete")]
    #[IsGranted('ROLE_USER')]
    public function removeImg(EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        if (!empty($user->getPicture())) {
            unlink($this->getParameter('uploads_directory') . '/' . $user->getPicture());
            $user->setPicture(null);

            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success', 'Votre avatar a bien été supprimé');
        }
        return $this->redirectToRoute('account_index');
    }
}
