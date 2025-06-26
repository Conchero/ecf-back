<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SigninForm;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;

class SigninController extends AbstractController
{
    #[Route('/signin', name: 'app_signin')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $userPasswordHasher
    ): Response {
        $user = new User();
        $form = $this->createForm(SigninForm::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
    /** @var string $plainPassword */
    $plainPassword = $form->get('plainPassword')->getData();

    // encode the plain password and set it on the User entity
    $hashedPassword = $userPasswordHasher->hashPassword($user, $plainPassword);
    $user->setPassword($hashedPassword);

    $entityManager->persist($user);
    $entityManager->flush();

    return $this->redirectToRoute('app_login');
}

        return $this->render('signin/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
