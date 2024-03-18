<?php

namespace App\Controller;

use App\Entity\Member;
use App\Entity\MyBooks;
use App\Entity\Books;
use App\Entity\User;
use App\Repository\BooksRepository;
use App\Form\BooksType;
use App\Form\MyBooksType;
use App\Repository\MyBooksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Serializer\Context\Normalizer\GetSetMethodNormalizerContextBuilder;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class MyBooksController extends AbstractController
{
    #[Route('/home', name: 'app_home', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('my_books/home.html.twig', [
            'controller_name' => 'MyBooksController',
        ]);
    }
   
    #[Route('/my/books', name: 'app_my_books', methods: ['GET'])]
    public function indexAction()
    {
        $htmlpage = '<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Welcome!</title>
    </head>
    <body>
        <h1>Welcome</h1>
            
    <p>Bienvenue dans la liste des inventaires</p>
    </body>
</html>';
        
        return new Response(
            $htmlpage,
            Response::HTTP_OK,
            array('content-type' => 'text/html')
            );
    }
 
    #[Route('my/books/list', name: 'Inventory_list', methods: ['GET'])]
    public function listAction(ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();
        $mybooks = $entityManager->getRepository(MyBooks::class)->findAll();
    
        return $this->render('my_books/list.html.twig', [
            'mybooks' => $mybooks,
        ]);
    }

    #[Route('/new', name: 'mybooks_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]

    public function new(Request $request, EntityManagerInterface $entityManager, MyBooksRepository $mybooksRepository, Member $member): Response
    {
        $entityManager->persist($member);
        $mybooks = new MyBooks();
        $mybooks->setMember($member);
        $form = $this->createForm(MyBooksType::class, $mybooks);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($mybooks);
            $entityManager->flush();
            $this->addFlash('message', 'bien ajouté');

            return $this->redirectToRoute('Inventory_list', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('my_books/new.html.twig', [
            'mybooks' => $mybooks,
            'form' => $form,
            'member'=>$member,
        ]);
    }

    /**
 * Show a [inventaire]
 *
 * @param integer $id 
 */
#[IsGranted('ROLE_USER')]
#[Route('/{id}', name: 'MyBooks_show', requirements: ['id' => '\d+'])]
public function showAction(MyBooks $mybooks): Response
  
{
    $books = $mybooks->getBooks();
    $hasAccess = $this->isGranted('ROLE_ADMIN') ||
    ($this->getUser()->getMyuser() == $mybooks->getMember());
    if(!$hasAccess) {
    throw $this->createAccessDeniedException("You cannot access another member's inventory!");
    }
    return $this->render('my_books/show.html.twig', [
        'mybooks' => $mybooks,
    ]);
}
#[Route('/{id}/edit', name: 'mybooks_edit', methods: ['GET', 'POST'])]
#[IsGranted('ROLE_USER')]
    public function edit(Request $request, MyBooks $mybooks, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MyBooksType::class, $mybooks);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('Inventory_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('my_books/edit.html.twig', [
            'mybooks' => $mybooks,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'mybooks_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, MyBooks $mybooks, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mybooks->getId(), $request->request->get('_token'))) {
            $entityManager->remove($mybooks);
            $entityManager->flush();
        }
        $hasAccess = $this->isGranted('ROLE_ADMIN') ||
        ($this->getUser()->getMyuser() == $mybooks->getMember());
        if(! $hasAccess) {
        throw $this->createAccessDeniedException("You cannot delete another member's inventory!");
        }

        return $this->redirectToRoute('Inventory_list', [], Response::HTTP_SEE_OTHER);
    }
}

   


    
    
   

