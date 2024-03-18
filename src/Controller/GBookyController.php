<?php

namespace App\Controller;

use App\Entity\GBooky;
use App\Form\GBookyType;
use App\Entity\Books;
use App\Entity\Member;
use App\Repository\GBookyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Http\Attribute\IsGranted;



#[Route('/g/booky')]
class GBookyController extends AbstractController
{
    #[Route('/', name: 'app_g_booky_index', methods: ['GET'])]
    public function index(GBookyRepository $gBookyRepository): Response
    {
        return $this->render('g_booky/index.html.twig', [
            'g_bookies' => $gBookyRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_g_booky_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, Member $member): Response
    {
      
        $gBooky = new GBooky();
        $form = $this->createForm(GBookyType::class, $gBooky);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
    
            $entityManager->persist($gBooky);
            $entityManager->flush();
            $this->addFlash('message', 'bien ajouté');
    

            return $this->redirectToRoute('app_g_booky_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('g_booky/new.html.twig', [
            'g_booky' => $gBooky,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_g_booky_show', methods: ['GET'])]
    public function show(GBooky $gBooky): Response
    {
        $hasAccess = false;
        if($this->isGranted('ROLE_ADMIN') || $gBooky->isPublished()) {
                $hasAccess = true;
        }
        else {
                $user = $this->getUser();
                if( $user ) {
                        $member = $user->getMyuser();
                        if ( $member &&  ($member == $gBooky->getMember()) ) {
                                $hasAccess = true;
                        }
                }
        }
        if(! $hasAccess) {
                throw $this->createAccessDeniedException("You cannot access the requested resource!");
        }
        return $this->render('g_booky/show.html.twig', [
            'g_booky' => $gBooky,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_g_booky_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function edit(Request $request, GBooky $gBooky, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GBookyType::class, $gBooky);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_g_booky_index', [], Response::HTTP_SEE_OTHER);
        }
      

        $hasAccess = $this->isGranted('ROLE_ADMIN') ||
        ($this->getUser()->getMyuser() == $gBooky->getMember());
        if(!$hasAccess) {
        throw $this->createAccessDeniedException("You cannot edit another member's Gallery!");
        }
        var_dump($hasAccess);

        return $this->render('g_booky/edit.html.twig', [
            'g_booky' => $gBooky,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_g_booky_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, GBooky $gBooky, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$gBooky->getId(), $request->request->get('_token'))) {
            $entityManager->remove($gBooky);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_g_booky_index', [], Response::HTTP_SEE_OTHER);
    }
   /**
 
 * @ParamConverter("gBooky", options={"id" = "gBooky_id"})
 * @ParamConverter("books", options={"id" = "books_id"})
 */
#[Route("/g/booky/{gBooky_id}/books/{books_id}", name: "app_gbooky_books_show", methods: ["GET"])]
public function BooksShow(GBooky $gBooky, Books $books, GBookyRepository $gBookyRepository): Response
{
    if(! $gBooky->getBooks()->contains($books) ){
        throw $this->createNotFoundException("Couldn't find such an object in this gallery!");
}


$hasAccess = false;
if($this->isGranted('ROLE_ADMIN') || $gBooky->isPublished()) {
        $hasAccess = true;
}
else {
        $privateGBooky = array();
        $user = $this->getUser();
          if( $user ) {
                  $member = $user->getMyuser();
                  $privateGBooky= $gBookyRepository->findBy(
                    [
                          'published' => false,
                          'creator' => $member
                    ]);
          }
}
if(! $hasAccess) {
        throw $this->createAccessDeniedException("You cannot access the requested ressource!");
}

    return $this->render('g_booky/books_show.html.twig', [
        'books' => $books, 
        'gBooky' => $gBooky,
    ]);
}


}
