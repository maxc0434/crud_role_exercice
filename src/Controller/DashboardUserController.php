<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class DashboardUserController extends AbstractController
{
    #[Route('/dashboard/user/{id}', name: 'app_dashboard_user')]
    public function index($id, EntityManagerInterface $entityManager): Response
    {
        $datas = $entityManager->getRepository(User::class)->find($id);
        return $this->render('dashboard_user/index.html.twig', [
            'controller_name' => 'DashboardUserController',
            "datas"=>$datas
        ]);
    }

             #[Route('/update/{id}', name :'update')]
    public function update(Request $request, $id, UserRepository $UserRepo, EntityManagerInterface $entityManager): Response
    {
        $data = $entityManager->getRepository(User::class)->find($id);
        $form = $this->createForm(RegistrationFormType::class, $data);
        $form->handleRequest($request);
        if ( $form->isSubmitted()&& $form->isValid()){
            $entityManager->persist($data);
            $entityManager->flush();

            return $this->redirectToRoute('app_home_page');
            
        }
        $datas = $UserRepo->find($id);
        return $this->render('registration/updateRegister.html.twig', [
        'form' => $form->createView(),
        'datas'=>$datas,
        ]);
    }
}
