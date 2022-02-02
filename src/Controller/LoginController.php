<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;


class LoginController extends AbstractController
{

    private $userRepository;
    private $userManager;

    function __construct(ManagerRegistry $doctrine)
    {
        $this->userRepository = $doctrine->getRepository(User::class);
        $this->userManager = $doctrine->getManager();
    }

    #[Route('/login', name: 'index')]
    public function loginView(): Response
    {
        return $this->render('login/login.html.twig', [
            'controller_name' => 'LoginController',
        ]);
    }

    #[Route('/register', name: 'register')]
    public function registerView(): Response
    {
        return $this->render('login/register.html.twig', [
            'controller_name' => 'LoginController',
        ]);
    }


    #[Route('/login/new-login', name: 'new-login')]
    public function doLogin(Request $req): Response
    {

        $nickname = $req->get('nickname');
        $password = $req->get('password');

        if (!$nickname || !$password){
            $this->addFlash(
              'danger',
              'User or Password incorrect',
            );
            return $this->redirect('/login');
        }

        $user = $this->userRepository->findOneBy(['nickname' => $nickname, 'password' => $password]);

        if ($user) {
            $this->addFlash(
              'success',
              'Logged in',
            );
            return $this->redirect('/books');
        } else {
            return $this->redirect('/login');
        }
    }


    #[Route('/register/new-user', name: 'new-user')]
    public function doRegister(Request $req): Response
    {
        $user =  new User();
        $user->setName($req->get('name'));
        $user->setNickname($req->get('nickname'));
        $user->setPassword($req->get('password'));


        if ($this->userRepository->findOneBy(['nickname' => $user->getNickname()])) {
            return $this->redirectToRoute('register');
        }

        $this->userManager->persist($user);
        $this->userManager->flush();

        $this->addFlash(
            'success',
            'User registered succesfuly',
        );

        return $this->redirect('/books');
    }
}
