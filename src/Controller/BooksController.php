<?php

namespace App\Controller;

use App\Entity\Book;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;


class BooksController extends AbstractController
{
    private $bookRepository;
    private $bookManager;

    function __construct(ManagerRegistry $doctrine)
    {
        $this->bookRepository = $doctrine->getRepository(Book::class);
        $this->bookManager = $doctrine->getManager();
    }

    #[Route('/books', name: 'books')]
    public function index(): Response
    {
        $books = $this->bookRepository->findAll();

        return $this->render('books/index.html.twig', [
            'books' => $books,
        ]);
    }

    #[Route('/books/new-book', name: 'create-book')]
    public function Createbook(Request $req): Response
    {
        $book = new Book();
        $book->setTitle($req->get('title'));
        $book->setAuthor($req->get('author'));
        $book->setDate(new DateTime($req->get('date')));

        if (!$book->getTitle() || !$book->getAuthor()){
          $this->addFlash(
            'danger',
            'Error book not valid',
          );
          return $this->redirect('/books');
        }

        $this->bookManager->persist($book);
        $this->bookManager->flush();

        $this->addFlash(
          'success',
          'Book created succesfuly',
        );


        return $this->redirect('/books');
    }


    #[Route('/books/update/{id}', name: 'update-book')]
    public function Updatebook($id, Request $req): Response
    {
        $book = $this->bookRepository->findOneBy(["id" => $id]);

        if ($book) {
            $book->setTitle($req->get('title'));
            $book->setAuthor($req->get('author'));
            $book->setDate(new DateTime($req->get('date')));
            $this->bookManager->flush();
        }

        $this->addFlash(
          'success',
          'Book updated succesfuly',
        );
        return $this->redirect('/books');
    }


    #[Route('/books/edit/{id}', name: 'edit-book')]
    public function editBook($id): Response
    {

        $book = $this->bookRepository->findOneBy(["id" => $id]);

        if (!$book) {
            return $this->redirect('/books');
        }

        return $this->render("books/edit.html.twig", ["book" => $book]);
    }


    #[Route('/books/delete/{id}', name: 'delete-book')]
    public function deleteBook($id): Response
    {
        $book = $this->bookRepository->findOneBy(["id" => $id]);

        if ($book) {
            $this->bookManager->remove($book);
            $this->bookManager->flush();
            $this->addFlash(
              'danger',
              'Book deleted',
            );
        }
        return $this->redirect('/books');
    }
}
