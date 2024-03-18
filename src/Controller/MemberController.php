<?php

namespace App\Controller;
use App\Entity\Member;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\MyBooks;
use App\Form\MyBooksType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class MemberController extends AbstractController
{
    #[Route('/member', name: 'app_member')]

    public function index(ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();
        $membres = $entityManager->getRepository(Member::class)->findAll();
        return $this->render('member/index.html.twig', [
            'membres' => $membres,
        ]);
    }
    
#[Route('/member/{id}', name: 'Member_show', requirements: ['id' => '\d+'])]
public function showAction(Member $membres): Response
{
    return $this->render('member/show.html.twig', [
        'membre' => $membres,
    ]);
}
}
