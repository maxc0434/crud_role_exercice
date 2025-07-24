<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class DashboardAdminController extends AbstractController
{
    #[Route('/dashboard/admin', name: 'app_dashboard_admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(UserRepository $UserRepo): Response
    {
        $datas = $UserRepo->findAll();
        return $this->render('dashboard_admin/index.html.twig', [
            'datas'=>$datas,
        ]);
    }
         #[Route('/update/{id}', name :'update')]
    public function update(Request $request, $id, UserRepository $UserRepo, EntityManagerInterface $entityManager): Response
    {
        $data = $entityManager->getRepository(User::class)->find($id);
        $form = $this->createForm(RegistrationFormType::class, $data, [//option pour enlever les options donc password et termes
            'is_registration' => false,
        ]);
        $form->handleRequest($request);
        if ( $form->isSubmitted()&& $form->isValid()){
            $entityManager->persist($data);
            $entityManager->flush();

            return $this->redirectToRoute('app_dashboard_admin');
            
        }
        $datas = $UserRepo->findAll();
        return $this->render('registration/updateRegister.html.twig', [
        'form' => $form->createView(),
        'datas'=>$datas,
        ]);
    }
        
    #[Route('/delete/{id}', name :'delete')]
    public function delete($id, EntityManagerInterface $entityManager): Response
    {
        $crud = $entityManager->getRepository(User::class)->find($id);
        $entityManager->remove($crud);
        $entityManager->flush();

        return $this->redirectToRoute('app_dashboard_admin');

    }
}


    