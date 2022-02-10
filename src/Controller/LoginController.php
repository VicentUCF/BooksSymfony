<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;


class LoginController extends AbstractController
{

  private $userRepository;
  private $Manager;

  function __construct(ManagerRegistry $doctrine)
  {
    $this->userRepository = $doctrine->getRepository(User::class);
    $this->Manager = $doctrine->getManager();
  }

  #[Route('/login', name: 'login')]
  public function login(Request $request): Response
  {
    $user = new User();

    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $user = $form->getData();
      $user = $this->userRepository->findOneBy(['nickname' => $user->getNickname(), 'password' => $user->getPassword()]);

      if ($user) {
        $this->addFlash(
          'success',
          'Logged in',
        );
        return $this->redirect('/books');
      }else{
        $this->addFlash(
          'danger',
          'User or Password incorrect',
        );
        return $this->redirect('/login');
      }
    }

    return $this->renderForm('login/login.html.twig', [
      'form' => $form,
    ]);
  }

  #[Route('/register', name: 'register')]
  public function register(Request $request): Response
  {
    $user = new User();

    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $user = $form->getData();
      if ($this->userRepository->findOneBy(['nickname' => $user->getNickname()])) {
        return $this->redirectToRoute('register');
      }
      $this->Manager->persist($user);
      $this->Manager->flush();
      $this->addFlash(
        'success',
        'User registered succesfuly',
      );

      return $this->redirect('/books');
    }

    return $this->renderForm('login/register.html.twig', [
      'form' => $form,
    ]);
  }

}
