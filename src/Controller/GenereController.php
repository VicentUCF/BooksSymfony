<?php

namespace App\Controller;

use App\Entity\Genere;
use App\Form\GenereType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GenereController extends AbstractController
{
  private $genereRep;
  private $Manager;

  function __construct(ManagerRegistry $doctrine)
  {
    $this->genereRep = $doctrine->getRepository(Genere::class);
    $this->Manager = $doctrine->getManager();
  }
  #[Route('/genere', name: 'genere')]
  public function index(): Response
  {

      $generes = $this->genereRep->findAll();
      return $this->render('genere/index.html.twig', [
          'generes' => $generes,
      ]);
  }
  #[Route('/genere/create', name: 'create_genere')]
  public function create(Request $request): Response
  {
    $genere = new Genere();

    $form = $this->createForm(GenereType::class, $genere);
    $form->handleRequest($request);


    if ($form->isSubmitted()) {
      if ($form->isValid()) {
        $genere = $form->getData();

        $this->Manager->persist($genere);
        $this->Manager->flush();

        $this->addFlash(
          'success',
          'Genere created succesfully',
        );
        return $this->redirect('/books');
      }
      $this->addFlash(
        'danger',
        'Error parameters not valid',
      );
    }

    return $this->renderForm('genere/create.html.twig', [
      'form' => $form,
    ]);
  }
}
