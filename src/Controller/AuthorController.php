<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    private $authorRepository;
    private $Manager;

    function __construct(ManagerRegistry $doctrine)
    {
      $this->authorRepository = $doctrine->getRepository(Author::class);
      $this->Manager = $doctrine->getManager();
    }

    #[Route('/authors', name: 'authors')]
    public function index(): Response
    {
        $authors = $this->authorRepository->findAll();

        return $this->render('author/index.html.twig', [
            'authors' => $authors,
        ]);
    }

    #[Route('/author/create', name: 'create_author')]
    public function create(Request $request): Response
    {
      $author = new Author();

      $form = $this->createForm(AuthorType::class, $author);
      $form->handleRequest($request);


      if ($form->isSubmitted()) {
        if ($form->isValid()){
          $author = $form->getData();

          $this->Manager->persist($author);
          $this->Manager->flush();

          $this->addFlash(
            'success',
            'Author created succesfully',
          );
          return $this->redirect('/books');
          }
        $this->addFlash(
          'danger',
          'Error parameters not valid',
        );
      }

    return $this->renderForm('author/create.html.twig', [
      'form' => $form,
    ]);
  }
}
