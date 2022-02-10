<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Genere;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;


class BooksController extends AbstractController
{
    private $bookRepository;
    private $authorRepository;
    private $genereRepository;
    private $Manager;

    function __construct(ManagerRegistry $doctrine)
    {
        $this->bookRepository = $doctrine->getRepository(Book::class);
        $this->Manager = $doctrine->getManager();
        $this->authorRepository = $doctrine->getRepository(Author::class);
        $this->genereRepository = $doctrine->getRepository(Genere::class);
    }

    #[Route('/books', name: 'books')]
    public function index(): Response
    {
        $books = $this->bookRepository->findAll();
        $authors = $this->authorRepository->findAll();
        $generes = $this->genereRepository->findAll();

        return $this->render('books/index.html.twig', [
            'books' => $books,
            'authors' => $authors,
            'generes' => $generes,
        ]);
    }

    #[Route('/books/new-book', name: 'create-book')]
    public function Createbook(Request $req): Response
    {

      $inputs = ['author', 'genere', 'title', 'date'];
      foreach ($inputs as $input){
        if (!$req->get($input)){
          $this->addFlash(
            'danger',
            'Error book not valid',
          );
          return $this->redirect('/books');
        }
      }
        $book = new Book();
        $author = $this->authorRepository->find($req->get('author'));
        $genere = $this->genereRepository->find($req->get('genere'));

        $book->setTitle($req->get('title'));
        $book->setAuthor($author);
        $book->setGenere($genere);
        $book->setDate(new DateTime($req->get('date')));


        $author->addBook($book);

        $this->Manager->persist($author);
        $this->Manager->persist($book);
        $this->Manager->flush();

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
        $author = $this->authorRepository->find($req->get('author'));
        $genere = $this->genereRepository->find($req->get('genere'));

        if ($book) {
            $book->setTitle($req->get('title'));
            $book->setAuthor($author);
            $book->setGenere($genere);
            $book->setDate(new DateTime($req->get('date')));
            $this->Manager->flush();
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
        $authors = $this->authorRepository->findAll();
        $generes = $this->genereRepository->findAll();

        if (!$book) {
            return $this->redirect('/books');
        }

        return $this->render("books/edit.html.twig", [
          "book" => $book,
          "authors" => $authors,
          "generes" => $generes,
        ]);
    }


    #[Route('/books/delete/{id}', name: 'delete-book')]
    public function deleteBook($id): Response
    {
        $book = $this->bookRepository->findOneBy(["id" => $id]);

        if ($book) {
            $this->Manager->remove($book);
            $this->Manager->flush();
            $this->addFlash(
              'danger',
              'Book deleted',
            );
        }
        return $this->redirect('/books');
    }
}
