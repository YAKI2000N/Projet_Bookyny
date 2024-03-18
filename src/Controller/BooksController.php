<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\BooksRepository;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Books;
use App\Entity\MyBooks;
use App\Form\BooksType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class BooksController extends AbstractController
{
    #[Route('books/list', name: 'Books_list', methods: ['GET'])]
    public function listAction(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $books = $entityManager->getRepository(Books::class)->findAll();
        
        return $this->render('books/list.html.twig', [
            'books' => $books,
        ]);
    }
    #[Route('/new/{inventory_id}', name: 'books_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, BooksRepository $booksRepository,  $inventory_id): Response
    {
        $myBooks = $entityManager->getRepository(MyBooks::class)->find($inventory_id);
        $books = new Books();
        $books->setMyBooks($myBooks);
        $form = $this->createForm(BooksType::class, $books);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($books);
            $image = $form->get('image')->getData();
            dump($image);
            if ($image) {
                $books->setImage($image);
            }
            $entityManager->flush();
            $this->addFlash('message', 'bien ajouté');

            return $this->redirectToRoute('MyBooks_show', ['id' =>  $inventory_id], Response::HTTP_SEE_OTHER);
        }
        return $this->render('books/new.html.twig', [
            'books' => $books,
            'form' => $form,
            'myBooks'=> $myBooks,
        ]);
    }
}

   


    
    
   


